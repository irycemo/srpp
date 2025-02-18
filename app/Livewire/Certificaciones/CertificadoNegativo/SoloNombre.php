<?php

namespace App\Livewire\Certificaciones\CertificadoNegativo;

use Livewire\Component;
use App\Traits\Inscripciones\ColindanciasTrait;
use App\Traits\Certificaciones\CertificadoPropiedadTrait;

class SoloNombre extends Component
{

    use CertificadoPropiedadTrait;
    use ColindanciasTrait;

    public $certificacion;

    public $predios = [];
    public $prediosOld = [];

    public $flagGenerar = false;

    public function render()
    {
        return view('livewire.certificaciones.certificado-negativo.solo-nombre');
    }
}
