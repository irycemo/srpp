<?php

namespace App\Livewire\PaseFolio;

use App\Constantes\Constantes;
use Livewire\Component;

class GravamenModal extends Component
{

    public $folioReal;

    public $modal = false;
    public $editar = false;
    public $crear = false;

    public $actores;

    public function agregarGravamen(){

        $this->modal = true;

    }

    public function mount(){

        $this->actores = Constantes::ACTORES_GRAVAMEN;

    }

    public function render()
    {
        return view('livewire.pase-folio.gravamen-modal');
    }
}
