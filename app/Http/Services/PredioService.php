<?php

namespace App\Http\Services;

use App\Exceptions\PredioException;

class PredioService{

    public function revisarPorcentajesFinal($propietarios){

        $pn = 0;

        $pu = 0;

        $pp = 0;

        foreach($propietarios as $propietario){

            $pn = $pn + $propietario->porcentaje_nuda;

            $pu = $pu + $propietario->porcentaje_usufructo;

            $pp = $pp + $propietario->porcentaje_propiedad;

        }

        if($pp == 0){

            if($pn < 99.999) throw new PredioException("El porcentaje de nuda propiedad no es el 100%.");

            if($pu < 99.999) throw new PredioException("El porcentaje de usufructo no es el 100%.");

        }else{

            if(($pn + $pp) < 99.999) throw new PredioException("El porcentaje de nuda propiedad no es el 100%.");

            if(($pu + $pp) < 99.999) throw new PredioException("El porcentaje de usufructo no es el 100%.");

        }

    }

}
