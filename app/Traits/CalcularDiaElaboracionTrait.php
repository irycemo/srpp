<?php

namespace App\Traits;

trait CalcularDiaElaboracionTrait{

    public function calcularDiaElaboracion($modelo){

        if($modelo->tipo_servicio == 'ordinario'){

            $diaElaboracion = $modelo->fecha_pago;

            for ($i=0; $i < 2; $i++) {

                $diaElaboracion->addDays(1);

                while($diaElaboracion->isWeekend()){

                    $diaElaboracion->addDay();

                }

            }

            if($diaElaboracion <= now()){

                return false;

            }else{

                $this->dispatch('mostrarMensaje', ['warning', "El trámite puede finalizarse apartir del " . $diaElaboracion->format('d-m-Y')]);

                return true;

            }

        }elseif($modelo->tipo_servicio == 'urgente'){

            $diaElaboracion = $modelo->fecha_pago;

            $diaElaboracion->addDays(1);

            while($diaElaboracion->isWeekend()){

                $diaElaboracion->addDay();

            }

            if($diaElaboracion <= now()){

                return false;

            }else{

                $this->dispatch('mostrarMensaje', ['warning', "El trámite puede finalizarse apartir del " . $diaElaboracion->format('d-m-Y')]);

                return true;

            }

        }else{

            return false;

        }

    }

}
