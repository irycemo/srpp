<?php

namespace App\Livewire\Admin;

use App\Constantes\Constantes;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Component;

class MovimientosRegistralesOrdenar extends Component
{

    public $folio_real;
    public $distrito;
    public $tomo;
    public $registro;
    public $numero_propiedad;
    public $distritos;

    public $movimientos;
    public $datos = [];

    protected function rules(){
        return [
            'folio_real' => Rule::requiredIf($this->distrito === null && $this->tomo == null && $this->registro === null && $this->numero_propiedad === null),
            'distrito' => Rule::requiredIf($this->folio_real === null ),
            'tomo' => Rule::requiredIf($this->folio_real === null),
            'registro' => Rule::requiredIf($this->folio_real === null),
            'numero_propiedad' => Rule::requiredIf($this->folio_real === null),
         ];
    }

    public function updated($field, $value){

        if($field == 'folio_real'){

            $this->reset(['distrito', 'tomo','registro', 'numero_propiedad', 'movimientos']);

        }elseif(in_array($field, ['distrito', 'tomo','registro', 'numero_propiedad'])){

            $this->reset(['folio_real', 'movimientos']);

        }

    }

    public function reaordenarMovimientos($id_1, $id_2){

        $movimiento_registral_1 = MovimientoRegistral::find($id_1);

        $folio_1 = $movimiento_registral_1->folio;

        $movimiento_registral_2 = MovimientoRegistral::find($id_2);

        $folio_2 = $movimiento_registral_2->folio;

        if($folio_2 == 1){

            if(in_array($movimiento_registral_1->estado, ['precalificacion', 'no recibido'])){

                $movimiento_registral_1->update([
                    'pase_a_folio' => true,
                    'folio' => $folio_2,
                    'estado' => 'nuevo',
                    'actualizado_por' => auth()->id()
                ]);

            }else{

                $movimiento_registral_1->update([
                    'pase_a_folio' => true,
                    'folio' => $folio_2,
                    'actualizado_por' => auth()->id()
                ]);

            }

        }else{

            $movimiento_registral_1->update(['folio' => $folio_2, 'actualizado_por' => auth()->id()]);

        }

        $movimiento_registral_2->update(['folio' => $folio_1, 'actualizado_por' => auth()->id(), 'pase_a_folio' => false]);

        $movimiento_registral_2->audits()->latest()->first()->update(['tags' => 'Cambió el orden de folio']);

        $movimiento_registral_2->audits()->latest()->first()->update(['tags' => 'Cambió el orden de folio']);

        $this->movimientos = MovimientoRegistral::when($this->folio_real, function($q){
                                                    $q->whereHas('folioReal', function($q){
                                                        $q->where('folio', $this->folio_real);
                                                    });
                                                })
                                                ->when($this->folio_real === null, function($q){
                                                    $q->where('distrito', $this->distrito)
                                                        ->where('tomo', $this->tomo)
                                                        ->where('registro', $this->registro)
                                                        ->where('numero_propiedad', $this->numero_propiedad);
                                                })
                                                ->get();

    }

    public function buscar(){

        $this->validate();

        $this->movimientos = MovimientoRegistral::when($this->folio_real, function($q){
                                                        $q->whereHas('folioReal', function($q){
                                                            $q->where('folio', $this->folio_real);
                                                        });
                                                    })
                                                    ->when($this->folio_real === null, function($q){
                                                        $q->where('distrito', $this->distrito)
                                                            ->where('tomo', $this->tomo)
                                                            ->where('registro', $this->registro)
                                                            ->where('numero_propiedad', $this->numero_propiedad);
                                                    })
                                                    ->orderBy('folio')
                                                    ->get();

        $this->reset('datos')                                                    ;

        foreach($this->movimientos as $movimiento){

            $this->datos[$movimiento->id]['estado'] = $movimiento->estado;

            $this->datos[$movimiento->id]['folio'] = $movimiento->folio;

        }

        /* $this->dispatch('cargar_ordenamiento'); */

    }

    public function guardar(MovimientoRegistral $movimiento){

        $this->validate([
            'datos' => 'required|array',
            'datos.*.estado' => 'required|string|min:2',
            'datos.*.folio' => 'required|int|min:1',
        ]);

        try {

            $movimiento->update([
                'estado' => $this->datos[$movimiento->id]['estado'],
                'folio' => $this->datos[$movimiento->id]['folio'],
                'actualizado_por' => auth()->id()
            ]);

            $movimiento->audits()->latest()->first()->update(['tags' => 'Cambió el orden de folio']);

            $this->buscar();

        } catch (\Throwable $th) {

            Log::error("Error al reordenar movimientos por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function mount(){

        $this->distritos = Constantes::DISTRITOS;

    }

    public function render()
    {
        return view('livewire.admin.movimientos-registrales-ordenar')->extends('layouts.admin');
    }

}
