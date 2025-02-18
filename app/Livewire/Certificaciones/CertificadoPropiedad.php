<?php

namespace App\Livewire\Certificaciones;

use App\Models\Actor;
use App\Models\Predio;
use App\Models\Persona;
use Livewire\Component;
use App\Models\FolioReal;
use App\Models\Personaold;
use App\Models\Colindancia;
use App\Models\Propiedadold;
use App\Models\Certificacion;
use App\Constantes\Constantes;
use App\Models\CertificadoPersona;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Services\SistemaTramitesService;

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
