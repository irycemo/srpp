<?php

namespace App\Traits\Inscripciones;

use App\Models\MovimientoRegistral;

trait FinalizarInscripcionTrait{

    public $modal_finalizar = false;

    public function abrirModalFinalizar(MovimientoRegistral $modelo){

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modal_finalizar = true;

    }

}