<?php

namespace App\Livewire\PersonaMoral;

use Livewire\Component;
use App\Models\ReformaMoral;
use App\Constantes\Constantes;

class Reformas extends Component
{

    public ReformaMoral $reformaMoral;

    public $actores;

    public $denominacion;
    public $capital;
    public $duracion;
    public $observaciones;
    public $tipo;
    public $domicilio;

    public $objeto;

    public $modalContraseña = false;
    public $contraseña;

    protected $listeners = ['refresh' => 'refreshActores'];

    protected function rules(){
        return [
            'denominacion' => 'required',
            'capital' => 'required|numeric|min:0',
            'duracion' => 'required|numeric|min:0',
            'observaciones' => 'nullable',
            'tipo' => 'required',
            'observaciones_escritura' => 'nullable',
            'domicilio' => 'required',
         ];
    }

    protected $validationAttributes  = [
        'denominacion' => 'denominación',
        'duracion' => 'duración',
    ];

    public function refreshActores(){

        $this->reformaMoral->movimientoRegistral->folioRealPersona->load('actores.persona');

    }

    public function mount(){

        $this->actores = Constantes::ACTORES_FOLIO_REAL_PERSONA_MORAL;

    }

    public function render()
    {
        return view('livewire.persona-moral.reformas')->extends('layouts.admin');
    }
}
