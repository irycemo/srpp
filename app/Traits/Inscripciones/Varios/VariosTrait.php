<?php

namespace App\Traits\Inscripciones\Varios;

use App\Models\Vario;

trait VariosTrait{

    public $modalContraseña = false;
    public $modalPersona = false;
    public $link;
    public $contraseña;

    public Vario $vario;
    public $movimientoRegistral;

    public $modal = false;
    public $crear = false;
    public $editar = false;

    public $actor;

    public function finalizar(){

        $this->validate();

        if(!$this->vario->movimientoRegistral->documentoEntrada()){

            $this->dispatch('mostrarMensaje', ['error', "Debe subir el documento de entrada."]);

            return;

        }

        $this->modalContraseña = true;

    }

}
