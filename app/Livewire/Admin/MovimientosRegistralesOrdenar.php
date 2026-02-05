<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Constantes\Constantes;
use App\Models\MovimientoRegistral;
use Illuminate\Validation\Rule;

class MovimientosRegistralesOrdenar extends Component
{

    public $folio_real;
    public $distrito;
    public $tomo;
    public $registro;
    public $numero_propiedad;
    public $distritos;

    public $movimientos;

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

            $this->reset(['distrito', 'tomo','registro', 'numero_propiedad']);

        }else{

            $this->reset('folio_real');

        }

    }

    public function reaordenarMovimientos($id_1, $id_2){

        $movimiento_registral_1 = MovimientoRegistral::find($id_1);

        $folio_1 = $movimiento_registral_1->folio;

        $movimiento_registral_2 = MovimientoRegistral::find($id_2);

        $folio_2 = $movimiento_registral_2->folio;

        $movimiento_registral_2->update(['folio' => $folio_1, 'actualizado_por' => auth()->id(), 'pase_a_folio' => false]);

        $movimiento_registral_2->audits()->latest()->first()->update(['tags' => 'Cambió el orden de folio']);

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

            $movimiento_registral_2->update(['folio' => $folio_1, 'actualizado_por' => auth()->id()]);

        }

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
                                                    ->get();

        $this->dispatch('cargar_ordenamiento');

    }

    public function mount(){

        $this->distritos = Constantes::DISTRITOS;

    }

    public function render()
    {
        return view('livewire.admin.movimientos-registrales-ordenar')->extends('layouts.admin');
    }

}
