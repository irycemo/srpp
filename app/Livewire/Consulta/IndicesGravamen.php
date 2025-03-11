<?php

namespace App\Livewire\Consulta;

use Livewire\Component;
use App\Constantes\Constantes;
use App\Models\Old\GravamenOld;

class IndicesGravamen extends Component
{

    public $distritos;
    public $distrito;
    public $tomo;
    public $registro;

    public $gravamenes = [];

    public $modal = false;

    public GravamenOld $gravamen;

    public function buscar(){

        $this->validate([
            'distrito' => 'required',
            'tomo' => 'required',
            'registro' => 'required',
        ]);

        $this->gravamenes = GravamenOld::where('Distrito', $this->distrito)
                                            ->where('tomog', $this->tomo)
                                            ->where('registrog', $this->registro)
                                            ->get();

    }

    public function abrirModalVer(GravamenOld $gravamenold){

        $this->gravamen = $gravamenold;

        $this->modal = true;

    }

    public function mount(){

        $this->gravamen = GravamenOld::make();

        $this->distritos = Constantes::DISTRITOS;

    }

    public function render()
    {
        return view('livewire.consulta.indices-gravamen')->extends('layouts.admin');
    }
}
