<?php

namespace App\Livewire\Consulta;

use App\Constantes\Constantes;
use App\Models\FolioRealPersona;
use Livewire\Component;

class ConsultaFRPM extends Component
{

    public $distritos;
    public $distrito;
    public $folio;
    public $tomo;
    public $registro;
    public $denominacion;

    public $folios_reales;
    public $folioReal;

    public function limpiar(){

        $this->reset([
            'distrito',
            'folio',
            'tomo',
            'registro',
            'denominacion',
            'folioReal',
            'folios_reales'
        ]);

    }

    public function buscar(){

        $this->reset('folioReal');

        if(!$this->distrito && !$this->tomo && !$this->folio && !$this->registro && !$this->denominacion){

            $this->dispatch('mostrarMensaje', ['warning', "Debe ingresar información."]);

            return;

        }

        $this->folios_reales = FolioRealPersona::when($this->folio, function($q){
                                                    $q->where('folio', $this->folio);
                                                })
                                                ->when($this->tomo, function($q){
                                                    $q->where('tomo_antecedente', $this->tomo);
                                                })
                                                ->when($this->registro, function($q){
                                                    $q->where('registro_antecedente', $this->registro);
                                                })
                                                ->when($this->distrito, function($q){
                                                    $q->where('distrito', $this->distrito);
                                                })
                                                ->when($this->denominacion, function($q){
                                                    $q->where('denominacion', 'LIKE', '%' .$this->denominacion . '%');
                                                })
                                                ->get();

        if($this->folios_reales->count() === 0){

            $this->dispatch('mostrarMensaje', ['error', "No hay resultado con los parametros ingresados."]);

        }

    }

    public function ver(FolioRealPersona $folio){

        $this->limpiar();

        $this->folioReal = $folio;

        $this->folioReal->load('reformas.actores.persona','reformas.movimientoRegistral', 'objetoActual', 'actores.persona');

    }

    public function mount(){

        $this->distritos = Constantes::DISTRITOS;

    }

    public function render()
    {
        return view('livewire.consulta.consulta-f-r-p-m')->extends('layouts.admin');
    }
}
