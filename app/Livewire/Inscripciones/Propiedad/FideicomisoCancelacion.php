<?php

namespace App\Livewire\Inscripciones\Propiedad;

use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class FideicomisoCancelacion extends Component
{

    use WithFilePond;

    public $inscripcion;

    public function mount(){

        $this->inscripcioin->acto_contenido = 'REVERCIÓN O CANCELACIÓN DE FIDEICOMISO';

    }

    public function render()
    {
        return view('livewire.inscripciones.propiedad.fideicomiso-cancelacion');
    }
}
