<?php

namespace App\Http\Services;

use App\Models\FolioReal;
use App\Models\MovimientoRegistral;
use App\Exceptions\GeneralException;

class FolioRealService{

    public function borrarFolioReal($folioRealId, $revisar_movimientos_registrales = null){

        $folioReal = FolioReal::Find($folioRealId);

        $folioReal->predio->colindancias->each->delete();

        $folioReal->predio->actores->each->delete();

        $es = $folioReal->predio->escritura;

        $folioReal->predio->delete();

        foreach ($folioReal->movimientosRegistrales as $movimiento) {

            $this->borrarMovimientoRegistral($movimiento, $revisar_movimientos_registrales);

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

        if($es){

            $es->delete();

        }

    }

    public function borrarMovimientoRegistral(MovimientoRegistral $movimiento, $revisar_movimientos_registrales){

        if($revisar_movimientos_registrales){

            $movimiento->load('folioReal');

            if(!in_array($movimiento->estado, ['nuevo', 'correccion', 'pase_folio', 'no recibido', 'recahzado'])){

                throw new GeneralException("El folio real: " . $movimiento->folioReal->folio . " tiene movimientos registrales elaborados no es posible borrarlo.");

            }

        }

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

}
