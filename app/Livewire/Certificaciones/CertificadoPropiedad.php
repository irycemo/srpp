<?php

namespace App\Livewire\Certificaciones;

use Livewire\Component;
use App\Models\Certificacion;
use App\Constantes\Constantes;

class CertificadoPropiedad extends Component
{

    public Certificacion $certificacion;

    public $radio;
    public $propiedad_radio;
    public $negativo_radio;

    public $vientos;

    public function mount(){

        $this->vientos = Constantes::VIENTOS;

    }

    public function render()
    {
        return view('livewire.certificaciones.certificado-propiedad')->extends('layouts.admin');
    }

}
