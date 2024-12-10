<?php

namespace App\Livewire\Inscripciones\Propiedad;

use Livewire\Component;
use App\Models\Propiedad;
use App\Constantes\Constantes;

class PropiedadInscripcion extends Component
{

    public $actos;
    public $acto;

    public $inscripcion;
    public $propiedad;

    protected function rules(){
        return [
            'inscripcion.acto_contenido' => 'required'
        ];
    }

    public function mount(){

        $this->inscripcion = Propiedad::find($this->propiedad);

        $this->actos = Constantes::ACTOS_INSCRIPCION_PROPIEDAD;

    }

    public function render()
    {
        return view('livewire.inscripciones.propiedad.propiedad-inscripcion')->extends('layouts.admin');
    }

}
