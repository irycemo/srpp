<?php

namespace App\Traits\Inscripciones;

use App\Models\Regional;
use App\Constantes\Constantes;

trait RevisarUsuarioRegionalTrait{

    public function revisarUsuarioRegional($usuario){

        if(isset(Constantes::USUARIOS_REGIONALES[$usuario])){

            return Regional::where('numero', Constantes::USUARIOS_REGIONALES[$usuario])->first();

        }

        return null;

    }

}