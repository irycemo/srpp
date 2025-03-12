<?php

namespace App\Livewire\Consulta;

use Livewire\Component;
use App\Constantes\Constantes;
use App\Models\Old\SentenciaOld;

class IndicesSentencia extends Component
{

    public $distritos;
    public $distrito;
    public $tomo;
    public $registro;

    public $sentencias = [];

    public $modal = false;

    public SentenciaOld $sentencia;

    public function buscar(){

        $this->validate([
            'distrito' => 'required',
            'tomo' => 'required',
            'registro' => 'required',
        ]);

        $this->sentencias = SentenciaOld::where('distrito', $this->distrito)
                                            ->where('tomosen', $this->tomo)
                                            ->where('registrosen', $this->registro)
                                            ->get();

    }

    public function abrirModalVer(SentenciaOld $SentenciaOld){

        $this->sentencia = $SentenciaOld;

        $this->modal = true;

    }

    public function mount(){

        $this->sentencia = SentenciaOld::make();

        $this->distritos = Constantes::DISTRITOS;

    }

    public function render()
    {
        return view('livewire.consulta.indices-sentencia')->extends('layouts.admin');
    }
}
