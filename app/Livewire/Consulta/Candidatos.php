<?php

namespace App\Livewire\Consulta;

use App\Constantes\Constantes;
use Illuminate\Support\Facades\Log;
use App\Models\Candidato;
use Livewire\Component;

class Candidatos extends Component
{

    public $distritos;
    public $distrito;
    public $tomo;
    public $tomo_bis;
    public $registro;
    public $registro_bis;
    public $numero_propiedad;
    public $numero_propiedad_inicial;
    public $numero_propiedad_final;

    public $candidato;

    public $modal_candidatos = false;

    public function agregarCandidatos(){

        $this->validate([
            'tomo' => 'required',
            'registro' => 'required',
            'distrito' => 'required',
            'numero_propiedad_inicial' => 'required',
            'numero_propiedad_final' => 'required',
        ]);

        try {

            for($i = $this->numero_propiedad_inicial; $i <= $this->numero_propiedad_final; $i++){

                Candidato::firstOrCreate(
                    [
                        'tomo' => $this->tomo,
                        'tomo_bis' => $this->tomo_bis,
                        'registro' => $this->registro,
                        'registro_bis' => $this->registro_bis,
                        'distrito' => $this->distrito,
                        'numero_propiedad' => $i,
                    ],
                    [
                        'tomo' => $this->tomo,
                        'tomo_bis' => $this->tomo_bis,
                        'registro' => $this->registro,
                        'registro_bis' => $this->registro_bis,
                        'distrito' => $this->distrito,
                        'numero_propiedad' => $i,
                    ]
                );

            }

            $this->dispatch('mostrarMensaje', ['success', "La información de guardo con éxito."]);

            $this->modal_candidatos = false;

        } catch (\Throwable $th) {

            Log::error(
                "Error al agregar candidatos",
                [
                    'message' => $th->getMessage(),
                    'usuario' => auth()->user()->name,
                    'trace' => $th
                ]
            );

            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
        }

    }

    public function buscar(){

        $this->validate([
            'distrito' => 'required',
            'tomo' => 'required',
            'registro' => 'required',
            'numero_propiedad' => 'required',
        ]);

        $this->candidato = Candidato::where('distrito', $this->distrito)
                                ->where('tomo', $this->tomo)
                                ->where('registro', $this->registro)
                                ->where('numero_propiedad', $this->numero_propiedad)
                                ->when($this->tomo_bis, function($q){
                                    $q->whereNotNull('tomo_bis')
                                        ->where('tomo_bis', '<>', '');
                                })
                                ->when($this->registro_bis, function($q){
                                    $q->whereNotNull('registro_bis')
                                        ->where('registro_bis', '<>', '');
                                })
                                ->first();

        if($this->candidato){

            $this->dispatch('mostrarMensaje', ['success', "El antecedente es candidato a folio real simplificado."]);

        }else{

            $this->dispatch('mostrarMensaje', ['warning', "El antecedente no es candidato a folio real simplificado."]);

        }

    }

    public function mount(){

        $this->distritos = Constantes::DISTRITOS;

    }

    public function render()
    {
        return view('livewire.consulta.candidatos')->extends('layouts.admin');
    }

}
