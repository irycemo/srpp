<?php

namespace App\Http\Services;

use App\Models\FolioReal;
use App\Models\MovimientoRegistral;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\Storage;

class FolioRealService{

    public function borrarFolioReal($folioRealId, $revisar_movimientos_registrales = null){

        $folioReal = FolioReal::Find($folioRealId);

        $folioReal->predio->colindancias->each->delete();

        $folioReal->predio->actores->each->delete();

        $folioReal->predio->update(['escritura_id' => null]);

        $folioReal->predio->delete();

        foreach ($folioReal->movimientosRegistrales as $movimiento) {

            $this->borrarMovimientoRegistral($movimiento, $revisar_movimientos_registrales);

        }

        foreach($folioReal->archivos as $archivo){

            if($archivo->descripcion == 'caratula'){

            if(app()->isProduction()){

                Storage::disk('s3')->delete(config('services.ses.ruta_caratulas') . $archivo->url);

            }else{

                if(file_exists('caratulas/' . $archivo->url)){

                    unlink('caratulas/' . $archivo->url);

                }

            }

            }elseif($archivo->descripcion == 'documento_entrada'){

                if(app()->isProduction()){

                    Storage::disk('s3')->delete(config('services.ses.ruta_documento_entrada') . $archivo->url);

                }else{

                    if(file_exists('documento_entrada/' . $archivo->url)){

                        unlink('documento_entrada/' . $archivo->url);

                    }

                }

            }

            $archivo->delete();

        }

        $folioReal->firmasElectronicas?->each->delete();

        $folioReal->antecedentes?->each->delete();

        $folioReal->delete();

    }

    public function borrarMovimientoRegistral(MovimientoRegistral $movimiento, $revisar_movimientos_registrales){

        if($revisar_movimientos_registrales){

            $movimiento->load('folioReal');

            if(!in_array($movimiento->estado, ['nuevo', 'correccion', 'pase_folio', 'no recibido', 'recahzado', 'precalificacion'])){

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
