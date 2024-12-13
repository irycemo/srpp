<?php

namespace App\Http\Services;

use App\Models\FolioReal;

class FolioRealService{

    public function borrarFolioReal($folioRealId){

        $folioReal = FolioReal::Find($folioRealId);

        $folioReal->predio->colindancias->each->delete();

        $folioReal->predio->actores->each->delete();

        $folioReal->predio->escritura?->delete();

        $folioReal->predio->delete();

        foreach ($folioReal->movimientosRegistrales as $movimiento) {

            $movimiento->load('firmasElectronicas','certificacion','inscripcionPropiedad.actores', 'cancelacion', 'gravamen.actores', 'sentencia', 'vario.actores', 'reformaMoral.actores', 'archivos');

            $movimiento->firmasElectronicas?->each->delete();

            $movimiento->certificacion?->delete();

            $movimiento->inscripcionPropiedad?->actores?->each->delete();

            $movimiento->inscripcionPropiedad?->delete();

            $movimiento->cancelacion?->delete();

            $movimiento->gravamen?->actores?->each->delete();

            $movimiento->gravamen?->delete();

            $movimiento->sentencia?->actores?->each->delete();

            $movimiento->sentencia?->delete();

            $movimiento->vario?->actores?->each->delete();

            $movimiento->vario?->delete();

            $movimiento->reformaMoral?->actores?->each->delete();

            $movimiento->reformaMoral?->delete();

            foreach($movimiento->archivos as $archivo){

                if($archivo->descripcion == 'caratula'){

                    unlink('caratulas/' . $archivo->url);

                }elseif($archivo->descripcion == 'documento_entrada'){

                    unlink('documento_entrada/' . $archivo->url);

                }

                $archivo->delete();

            }

            $movimiento->delete();

        }

        foreach($folioReal->archivos as $archivo){

            if($archivo->descripcion == 'caratula'){

                if(file_exists('caratulas/' . $archivo->url))
                    unlink('caratulas/' . $archivo->url);

            }elseif($archivo->descripcion == 'documento_entrada'){

                if(file_exists('documento_entrada/' . $archivo->url))
                    unlink('documento_entrada/' . $archivo->url);

            }

            $archivo->delete();

        }

        $folioReal->firmasElectronicas?->each->delete();

        $folioReal->antecedentes?->each->delete();

        $folioReal->delete();

    }

}
