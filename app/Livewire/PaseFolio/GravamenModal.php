<?php

namespace App\Livewire\PaseFolio;

use Livewire\Component;

class GravamenModal extends Component
{

    public $folioReal;

    public function agregarGravamen(){

        if(!$this->movimientoRegistral->folioReal){

            $this->dispatch('mostrarMensaje', ['error', "Primero debe ingresar los datos de propiedad."]);

            return;

        }

        if($this->propiedad->propietarios()->count() == 0){

            $this->dispatch('mostrarMensaje', ['error', "Primero debe ingresar los propietarios."]);

            return;

        }

        $this->dispatch("openModal", 'pase-folio.modal-gravamen', ['movimientoRegistral' => $this->movimientoRegistral->id, 'crear' => false]);

    }

    public function render()
    {
        return view('livewire.pase-folio.gravamen-modal');
    }
}
