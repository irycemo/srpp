<?php

namespace App\Livewire\Consulta;

use Livewire\Component;
use App\Models\FolioReal;
use App\Models\Personaold;
use App\Models\Propiedadold;
use App\Constantes\Constantes;

class IndicesPropiedad extends Component
{

    public $distritos;
    public $distrito;
    public $tomo;
    public $registro;
    public $numero_propiedad;

    public $nombre;
    public $ap_paterno;
    public $ap_materno;

    public $propiedades = [];

    public $modal = false;

    public Propiedadold $propiedad;

    public $folioReal;

    public function buscarPorPropietario(){

        $this->validate([
            'nombre' => 'required',
        ]);

        $ids = Personaold::select('idPropiedad')
                            ->distinct()
                            ->where(function($q){
                                $q->where('nombre2', 'LIKE', '%' . $this->nombre . '%')
                                    ->orWhere('nombre1', 'LIKE', '%' . $this->nombre . '%');
                            })
                            ->when($this->ap_paterno, function($q){
                                $q->where('paterno', 'LIKE', '%'. $this->ap_paterno . '%');
                            })
                            ->when($this->ap_paterno, function($q){
                                $q->where('materno', 'LIKE', '%'. $this->ap_materno . '%');
                            })
                            ->get()
                            ->toArray();

        $this->propiedades = Propiedadold::whereKey($ids)->get();

    }

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

        $this->folioReal = FolioReal::where('tomo_antecedente' , $propiedadold->tomo)
                                        ->where('registro_antecedente' , $propiedadold->registro)
                                        ->where('distrito_antecedente' , $propiedadold->distrito)
                                        ->where('numero_propiedad_antecedente' , $propiedadold->noprop)
                                        ->first();

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
