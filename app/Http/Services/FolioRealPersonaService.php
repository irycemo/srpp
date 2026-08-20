<?php

namespace App\Http\Services;

use App\Exceptions\GeneralException;
use App\Models\FolioRealPersona;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Storage;

class FolioRealPersonaService{

    public function borrarFolioRealPersona($folioRealId, $revisar_movimientos_registrales = null){

        $folioReal = FolioRealPersona::Find($folioRealId);

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

        $folioReal->objetos?->each->delete();

        $folioReal->delete();

    }

    public function borrarMovimientoRegistral(MovimientoRegistral $movimiento, $revisar_movimientos_registrales){

        if($revisar_movimientos_registrales){

            $movimiento->load('folioRealPersona');

            if(!in_array($movimiento->estado, ['nuevo', 'correccion', 'pase_folio', 'no recibido', 'recahzado', 'precalificacion'])){

                throw new GeneralException("El folio real: " . $movimiento->folioRealPersona->folio . " tiene movimientos registrales elaborados no es posible borrarlo.");

            }

        }

        $movimiento->load('firmasElectronicas', 'reformaMoral.actores', 'archivos');

        $movimiento->firmasElectronicas?->each->delete();

        $movimiento->reformaMoral?->actores?->each->delete();

        $movimiento->reformaMoral?->delete();

        foreach($movimiento->archivos as $archivo){

            if(app()->isProduction()){

                if (Storage::disk('s3')->exists(config('services.ses.ruta_documento_entrada') . '/' . $archivo->url)) {

                    Storage::disk('s3')->delete(config('services.ses.ruta_documento_entrada') . '/' . $archivo->url);

                }

            }else{

                if($archivo->descripcion == 'caratula'){

                    unlink('caratulas/' . $archivo->url);

                }elseif($archivo->descripcion == 'documento_entrada'){

                    unlink('documento_entrada/' . $archivo->url);

                }

            }

            $archivo->delete();

        }

        $movimientos_padres = MovimientoRegistral::where('movimiento_padre', $movimiento->id)->get();

        if($movimientos_padres->count()){

            foreach ($movimientos_padres as $mov_padre) {

                $mov_padre->update(['movimiento_padre' => null]);

            }

        }

        $movimiento->delete();

    }

}
