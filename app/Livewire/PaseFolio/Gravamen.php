<?php

namespace App\Livewire\PaseFolio;

use App\Constantes\Constantes;
use App\Models\MovimientoRegistral;
use Livewire\Component;

class Gravamen extends Component
{

    public MovimientoRegistral $movimientoRegistral;

    public $gravamenes = [];
    public $distritos = [];
    public $actos = [];
    public $modal = false;
    public $crear = false;
    public $editar = false;

    public function agregarGravamen(){

        if(!$this->movimientoRegistral->folioReal){

            $this->dispatch('mostrarMensaje', ['error', "Primero debe ingresar los datos de propiedad."]);

            return;

        }

        $this->modal = true;

        $this->crear = true;

    }

    public function mount(){

        $this->distritos = Constantes::DISTRITOS;

    }

    public function render()
    {
        return view('livewire.pase-folio.gravamen');
    }
}
