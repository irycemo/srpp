<?php

namespace App\Traits\MovimientoRegistral;

use App\Models\FolioReal;
use App\Models\MovimientoRegistral;
use App\Models\Propiedadold;
use Illuminate\Support\Facades\Log;

trait CambiarAntecedenteTrait{

    public $modal_cambiar_antecedente = false;

    public $folio_real_cambiar_atecendente;
    public $tomo_cambiar_atecendente;
    public $registro_cambiar_atecendente;
    public $numero_propiedad_cambiar_atecendente;
    public $distrito_cambiar_atecendente;

    public function updated($field, $value){

        if($field == 'folio_real_cambiar_atecendente'){

            $this->reset(['tomo_cambiar_atecendente', 'registro_cambiar_atecendente', 'numero_propiedad_cambiar_atecendente', 'distrito_cambiar_atecendente']);

        }elseif(in_array($field, ['tomo_cambiar_atecendente', 'registro_cambiar_atecendente', 'numero_propiedad_cambiar_atecendente', 'distrito_cambiar_atecendente'])){

            $this->reset(['folio_real_cambiar_atecendente']);

        }

    }

    public function abrirModalCambiarAntecedente(MovimientoRegistral $modelo){

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modal_cambiar_antecedente = true;

    }

    public function cambiarAntecedente(){

        try {

            $folio_real = FolioReal::when(! empty($this->folio_real_cambiar_atecendente), function($q){
                                        $q->where('folio', $this->folio_real_cambiar_atecendente);
                                    })
                                    ->when(! empty($this->tomo_cambiar_atecendente), function($q){
                                        $q->where('tomo_antecedente', $this->tomo_cambiar_atecendente);
                                    })
                                    ->when(! empty($this->registro_cambiar_atecendente), function($q){
                                        $q->where('registro_antecedente', $this->registro_cambiar_atecendente);
                                    })
                                    ->when(! empty($this->numero_propiedad_cambiar_atecendente), function($q){
                                        $q->where('numero_propiedad_antecedente', $this->numero_propiedad_cambiar_atecendente);
                                    })
                                    ->when(! empty($this->distrito_cambiar_atecendente), function($q){
                                        $q->where('distrito_antecedente', $this->distrito_cambiar_atecendente);
                                    })
                                    ->first();

            if($folio_real && $this->modelo_editar->folio_real == $folio_real->id){

                $this->dispatch('mostrarMensaje', ['warning', "El folio real es el mismo al que pertenece el movimiento."]);

                return;

            }

            if($this->folio_real_cambiar_atecendente && ! $folio_real){

                $this->dispatch('mostrarMensaje', ['warning', "El folio real no existe."]);

                return;

            }

            if($folio_real){

                $this->modelo_editar->update([
                    'folio_real' => $folio_real->id,
                    'tomo' => $folio_real->tomo_antecedente,
                    'registro' => $folio_real->registro_antecedente,
                    'numero_propiedad' => $folio_real->numero_propiedad_antecedente,
                    'distrito' => $folio_real->distrito_antecedente,
                    'actualizado_por' => auth()->id(),
                    'folio' => $folio_real->movimientosRegistrales()->max('folio') + 1,
                    'estado' => 'nuevo'
                ]);

                $this->modal_cambiar_antecedente = false;

                return;

            }

            $propiedad = Propiedadold::where('distrito', $this->distrito_cambiar_atecendente)
                                    ->where('tomo', $this->tomo_cambiar_atecendente)
                                    ->where('registro', $this->registro_cambiar_atecendente)
                                    ->where('noprop', $this->numero_propiedad_cambiar_atecendente)
                                    ->first();

            if($propiedad?->status == 'V'){

                $this->dispatch('mostrarMensaje', ['warning', "La propiedad ya esta vendida."]);

                $this->modal_cambiar_antecedente = false;

                return;

            }

            $movimientos = MovimientoRegistral::where('tomo', $this->tomo_cambiar_atecendente)
                                                ->where('registro', $this->registro_cambiar_atecendente)
                                                ->where('numero_propiedad', $this->numero_propiedad_cambiar_atecendente)
                                                ->where('distrito', $this->distrito_cambiar_atecendente)
                                                ->orderBy('folio', 'desc')
                                                ->get();

            if($movimientos->count()){

                $movimiento = $movimientos->first();

                if($movimiento->id == $this->modelo_editar->id){
                    $this->modal_cambiar_antecedente = false;

                    return;

                }

                $this->modelo_editar->update([
                    'folio_real' => null,
                    'tomo' => $this->tomo_cambiar_atecendente,
                    'registro' => $this->registro_cambiar_atecendente,
                    'numero_propiedad' => $this->numero_propiedad_cambiar_atecendente,
                    'distrito' => $this->distrito_cambiar_atecendente,
                    'actualizado_por' => auth()->id(),
                    'folio' => $movimientos->max('folio') + 1,
                    'estado' => 'precalificacion'
                ]);

                $this->modal_cambiar_antecedente = false;

                return;

            }

            $this->modelo_editar->update([
                'folio_real' => null,
                'tomo' => $this->tomo_cambiar_atecendente,
                'registro' => $this->registro_cambiar_atecendente,
                'numero_propiedad' => $this->numero_propiedad_cambiar_atecendente,
                'distrito' => $this->distrito_cambiar_atecendente,
                'actualizado_por' => auth()->id(),
                'folio' => 1,
                'pase_a_folio' => 1,
                'estado' => 'nuevo'
            ]);

            $this->dispatch('mostrarMensaje', ['success', "El movimiento se actualizó con éxito."]);

            $this->modal_cambiar_antecedente = false;

        } catch (\Throwable $th) {

            $this->dispatch('mostrarMensaje', ['error', "Hubo un error."]);
            Log::error("Error al cambiar antecedente de movimiento registral por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

        }

    }

}
