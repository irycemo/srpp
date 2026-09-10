<?php

namespace App\Livewire\Consulta;

use App\Constantes\Constantes;
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

    public $candidato;

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
