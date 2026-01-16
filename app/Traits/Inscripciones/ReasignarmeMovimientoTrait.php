<?php

namespace App\Traits\Inscripciones;

use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;

trait ReasignarmeMovimientoTrait{

    public $modal_reasignarme_movimiento_registral = false;
    public $año;
    public $tramite;
    public $usuario;

    public function asignarmeMovimientoRegistral(){

        $this->validate([
            'año' => 'required',
            'tramite' => 'required',
            'usuario' => 'required',
        ]);

        try {

            $movimientoRegistral = MovimientoRegistral::where('año', $this->año)
                                                        ->where('tramite', $this->tramite)
                                                        ->where('usuario', $this->usuario)
                                                        ->where('folio', 1)
                                                        ->whereIn('estado', ['nuevo', 'no recibido'])
                                                        ->first();

            if(!$movimientoRegistral){

                $this->dispatch('mostrarMensaje', ['warning', "No se encontro el movimiento registral."]);

                return;

            }

            DB::transaction(function () use($movimientoRegistral) {

                $movimientoRegistral->update([
                    'usuario_asignado' => auth()->id(),
                    'actualizado_por' => auth()->id()
                ]);

                $movimientoRegistral->audits()->latest()->first()->update(['tags' => 'Reasignó usuario']);

            });

            $this->dispatch('mostrarMensaje', ['success', "Se reasigno correctamente."]);

            $this->reset(['tramite', 'usuario', 'modal_reasignarme_movimiento_registral']);

        } catch (\Throwable $th) {

            Log::error("Error al reasignar movimiento registral por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

}
