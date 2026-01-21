<?php

namespace App\Traits\Inscripciones\Sentencias;

use App\Models\Sentencia;

trait SentenciaTrait{

    public $modalContraseña = false;
    public $modalPersona = false;
    public $link;
    public $contraseña;
    public $actos;

    public Sentencia $sentencia;

    public $modal = false;
    public $crear = false;
    public $editar = false;

    public $actor;

    public function updatedSentenciaActoContenido(){

        $this->dispatch('cambiarActo', $this->sentencia->acto_contenido);

    }

    public function finalizar(){

        $this->validate();

        if(!$this->sentencia->movimientoRegistral->documentoEntrada()){

            $this->dispatch('mostrarMensaje', ['error', "Debe subir el documento de entrada."]);

            return;

        }

        $this->modalContraseña = true;

    }

    public function borrarPredio(){

        if($this->sentencia->predio){

            $this->sentencia->predio->colindancias()->delete();

            foreach ($this->sentencia->predio->propietarios() as $propietario) {
                $propietario->delete();
            }

            $this->sentencia->predio->delete();

            $this->sentencia->predio_id = null;

        }

    }

}
