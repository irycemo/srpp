<?php

namespace App\Livewire\Varios;

use App\Traits\Inscripciones\Varios\VariosTrait;
use Livewire\Component;

class AclaracionAdministrativa extends Component
{

    use VariosTrait;

    public function mount(){

        $this->vario->acto_contenido = 'ACLARACIÓN ADMINISTRATIVA';

    }

    public function render()
    {
        return view('livewire.varios.aclaracion-administrativa');
    }
}
