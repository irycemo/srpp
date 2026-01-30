<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\FolioReal;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Exceptions\GeneralException;
use App\Http\Services\VariosService;

class VerFolioReal extends Component
{

    public FolioReal $folioReal;
    public $movimiento_registral;

    public function verMovimientoRegistral(MovimientoRegistral $movimiento_registral){

        $this->movimiento_registral = $movimiento_registral;

    }

    public function reaordenarMovimientos($id_1, $id_2){

        $movimiento_registral_1 = MovimientoRegistral::find($id_1);

        $folio_1 = $movimiento_registral_1->folio;

        $movimiento_registral_2 = MovimientoRegistral::find($id_2);

        $folio_2 = $movimiento_registral_2->folio;

        $movimiento_registral_1->update(['folio' => $folio_2, 'actualizado_por' => auth()->id(), 'pase_a_folio' => false]);

        $movimiento_registral_1->audits()->latest()->first()->update(['tags' => 'Cambió el orden de folio']);

        if($folio_1 == 1){

            $movimiento_registral_2->update([
                'pase_a_folio' => true,
                'folio' => $folio_1,
                'actualizado_por' => auth()->id()
            ]);

        }else{

            $movimiento_registral_2->update(['folio' => $folio_1, 'actualizado_por' => auth()->id()]);

        }

        $movimiento_registral_2->audits()->latest()->first()->update(['tags' => 'Cambió el orden de folio']);

        $this->folioReal->refresh();

    }

    public function agregarAclaracionAdministrativa(){

        try {

            DB::transaction(function () {

                $movimiento_registral = MovimientoRegistral::create([
                    'folio_real' => $this->folioReal->id,
                    'estado' => 'nuevo',
                    'folio' => $this->folioReal->ultimoFolio() + 1,
                    'servicio_nombre' => 'Aclaraciones administrativas de inscripciones',
                    'fecha_prelacion' => now(),
                    'fecha_entrega' => now(),
                    'tipo_servicio' => 'extra_urgente',
                    'solicitante' => 'Administración',
                    'seccion' => 'propiedad',
                    'distrito' => $this->folioReal->getOriginal('distrito_antecedente')
                ]);

                (new VariosService())->crear([
                    'servicio_nombre' => 'Aclaraciones administrativas de inscripciones',
                    'servicio' => 'D112',
                    'movimiento_registral_id' => $movimiento_registral->id,
                    'año' => null,
                    'tramite' => null,
                    'usuario' => null,
                ]);

            });

            $this->folioReal->refresh();

            $this->dispatch('mostrarMensaje', ['success', 'El movimiento registral de registró con éxito.']);

        } catch (GeneralException $ex) {

            $this->dispatch('mostrarMensaje', ['warning', $ex->getMessage()]);

        } catch (\Throwable $th) {

            $this->dispatch('mostrarMensaje', ['error', "Hubo un error."]);
            Log::error("Error al generar aclaración administrativa a folio real por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

        }

    }

    public function quitarPartesIguales(){

        $this->folioReal->predio->update(['partes_iguales' => 0]);

        $this->dispatch('mostrarMensaje', ['success', 'La información de actualizó con éxito.']);

    }

    public function render()
    {
        return view('livewire.admin.ver-folio-real')->extends('layouts.admin');
    }
}
