<?php

namespace App\Livewire\Consulta;

use Livewire\Component;
use App\Models\Old\VariosOld;
use App\Constantes\Constantes;

class IndicesVarios extends Component
{

    public $distritos;
    public $distrito;
    public $tomo;
    public $registro;

    public $varios = [];

    public $modal = false;

    public VariosOld $vario;

    public function buscar(){

        $this->validate([
            'distrito' => 'required',
            'tomo' => 'required',
            'registro' => 'required',
        ]);

        $this->varios = VariosOld::where('distrito', $this->distrito)
                                            ->where('tomovar', $this->tomo)
                                            ->where('registrovar', $this->registro)
                                            ->get();

    }

    public function abrirModalVer(VariosOld $VariosOld){

        $this->vario = $VariosOld;

        $this->modal = true;

    }

    public function mount(){

        $this->vario = VariosOld::make();

        $this->distritos = Constantes::DISTRITOS;

    }

    public function render()
    {
        return view('livewire.consulta.indices-varios')->extends('layouts.admin');
    }
}
