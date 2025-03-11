<?php

namespace App\Livewire\Consulta;

use App\Constantes\Constantes;
use App\Models\Propiedadold;
use Livewire\Component;

class IndicesPropiedad extends Component
{

    public $distritos;
    public $distrito;
    public $tomo;
    public $registro;
    public $numero_propiedad;

    public $propiedades = [];

    public $modal = false;

    public Propiedadold $propiedad;

    public function buscar(){

        $this->validate([
            'distrito' => 'required',
            'tomo' => 'required',
            'registro' => 'required',
        ]);

        $this->propiedades = Propiedadold::where('distrito', $this->distrito)
                                            ->where('tomo', $this->tomo)
                                            ->where('registro', $this->registro)
                                            ->when($this->numero_propiedad, function($q){
                                                $q->where('noprop', $this->numero_propiedad);
                                            })
                                            ->get();

    }

    public function abrirModalVer(Propiedadold $propiedadold){

        $this->propiedad = $propiedadold;

        $this->modal = true;

    }

    public function mount(){

        $this->propiedad = Propiedadold::make();

        $this->distritos = Constantes::DISTRITOS;

    }

    public function render()
    {
        return view('livewire.consulta.indices-propiedad')->extends('layouts.admin');
    }
}
