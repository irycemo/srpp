<?php

namespace App\Traits\Inscripciones;

use App\Models\Gravamen;
use Illuminate\Support\Str;
use App\Models\MovimientoRegistral;
use App\Exceptions\GeneralException;
use App\Http\Services\FolioRealService;
use Illuminate\Support\Facades\Storage;
use App\Traits\Inscripciones\RecuperarPredioTrait;

trait EnviarMovimientoCorreccion{

    use RecuperarPredioTrait;

    public function enviarCorreccion(MovimientoRegistral $movimientoRegistral){

        $movimiento = $movimientoRegistral->folioReal->movimientosRegistrales()
                                                        ->whereIn('estado', ['finalizado', 'concluido', 'elaborado'])
                                                        ->where('folio', '>', $movimientoRegistral->folio)
                                                        ->first();

        if($movimiento) $this->validaciones($movimiento);

        $folios = $movimientoRegistral->movimientosHijos()->pluck('folio_real') ;

        foreach($folios as $folio){

            if($folio == $movimientoRegistral->folioReal->id) continue;

            (new FolioRealService())->borrarFolioReal($folio, true);

        }

        if($movimientoRegistral->inscripcionPropiedad){

            $this->obtenerMovimientoConFirmaElectronica($movimientoRegistral);

            foreach ($movimientoRegistral->inscripcionPropiedad->actores as $actor) {

                $actor->delete();

            }

        }

        if($movimientoRegistral->fideicomiso){

            $this->obtenerMovimientoConFirmaElectronica($movimientoRegistral);

            foreach ($movimientoRegistral->fideicomiso->actores as $actor) {

                $actor->delete();

            }

        }

        if($movimientoRegistral->gravamen){

            $this->obtenerMovimientoConFirmaElectronica($movimientoRegistral);

            foreach ($movimientoRegistral->gravamen->actores as $actor) {

                $actor->delete();

            }

        }

        if($movimientoRegistral->vario){

            if($movimientoRegistral->vario->acto_contenido == 'PRIMER AVISO PREVENTIVO'){

                $this->revertirPrimerAvisoPreventivo($movimientoRegistral);

            }elseif(in_array($movimientoRegistral->vario->acto_contenido, ['CANCELACIÓN DE PRIMER AVISO PREVENTIVO', 'CANCELACIÓN DE SEGUNDO AVISO PREVENTIVO'])){

                $this->revertirCancelacionAvisoPreventivo($movimientoRegistral);

            }

            $this->obtenerMovimientoConFirmaElectronica($movimientoRegistral);

            foreach ($movimientoRegistral->vario->actores as $actor) {

                $actor->delete();

            }

        }

        if($movimientoRegistral->cancelacion){

            $this->reactivarGravamen($movimientoRegistral, $movimientoRegistral->cancelacion->gravamenCancelado->gravamen);

            $this->obtenerMovimientoConFirmaElectronica($movimientoRegistral);

            foreach ($movimientoRegistral->cancelacion->actores as $actor) {

                $actor->delete();

            }

        }

        if($movimientoRegistral->sentencia){

            if($movimientoRegistral->sentencia->acto_contenido == 'CANCELACIÓN DE SENTENCIA'){

                $cancelado = MovimientoRegistral::where('movimiento_padre', $this->modelo_editar->id)->first();

                $cancelado->update(['movimiento_padre' => null]);

                $cancelado->sentencia->update(['estado' => 'activo']);

            }elseif(in_array($movimientoRegistral->sentencia->acto_contenido, ['RESOLUCIÓN', 'DEMANDA', 'PROVIDENCIA PRECAUTORIA'])){

                $movimientoRegistral->folioReal->update(['estado' => 'activo']);

                $movimientoRegistral->folioReal->bloqueos()->where('estado', 'activo')->first()->update([
                    'estado' => 'inactivo',
                    'observaciones_desbloqueo' => 'Se desbloquea folio por corrección en la sentencia con folio: ' . $movimientoRegistral->folio,
                    'actualizado_por' => auth()->id()
                ]);

            }

            $this->obtenerMovimientoConFirmaElectronica($movimientoRegistral);

            foreach ($movimientoRegistral->sentencia->actores as $actor) {

                $actor->delete();

            }

        }

        if($movimientoRegistral->reformaMoral){

            $this->obtenerMovimientoConFirmaElectronica($movimientoRegistral);

            foreach ($movimientoRegistral->reformaMoral->actores as $actor) {

                $actor->delete();

            }

        }

        $movimientoRegistral->update([
            'estado' => 'correccion',
            'actualizado_por' => auth()->id()
        ]);

        $movimientoRegistral->audits()->latest()->first()->update(['tags' => 'Envió movimiento registral a corrección']);

    }

    public function validaciones(MovimientoRegistral $movimientoRegistral){

        if(in_array($movimientoRegistral->folioReal->estado, ['bloqueado', 'centinela'])){

            throw new GeneralException("El folio esta bloqueado.");

        }

        if($movimientoRegistral){

            throw new GeneralException("El folio real ya tiene movimientos registrales posteriores concluidos ó finalizados.");

            /* if($movimiento->fecha_entrega->addDays(30) < now()){

                throw new GeneralException("Han pasado 30 dias desde su fecha de entrega no es posible enviar a corrección.");

            } */

        }

    }

    public function revertirPrimerAvisoPreventivo(MovimientoRegistral $movimientoRegistral){

        $movimientoCertificadoGravamen = MovimientoRegistral::where('movimiento_padre', $movimientoRegistral->id)->first();

        if(! $movimientoCertificadoGravamen) return;

        $movimientoCertificadoGravamen->certificacion?->delete();

        $movimientoCertificadoGravamen->firmasElectronicas?->each->delete();

        foreach($movimientoCertificadoGravamen->archivos as $archivo){

            if($archivo->descripcion == 'caratula'){

                if(app()->isProduction()){

                    if (Storage::disk('s3')->exists(config('services.ses.ruta_caratulas') . $archivo->url)) {

                        Storage::disk('s3')->delete(config('services.ses.ruta_caratulas') . $archivo->url);

                    }

                }else{

                    unlink('caratulas/' . $archivo->url);

                }

            }elseif($archivo->descripcion == 'documento_entrada'){

                if(app()->isProduction()){

                    if(Str::contains($archivo->url, config('services.ses.ruta_documento_entrada'))){

                        if (Storage::disk('s3')->exists($archivo->url)) {

                            Storage::disk('s3')->delete($archivo->url);

                        }


                    }else{

                        if (Storage::disk('s3')->exists(config('services.ses.ruta_documento_entrada') . $archivo->url)) {

                            Storage::disk('s3')->delete(config('services.ses.ruta_documento_entrada') . $archivo->url);

                        }

                    }

                }else{

                    unlink('documento_entrada/' . $archivo->url);

                }

            }

            $archivo->delete();

        }

        $movimientoCertificadoGravamen->delete();

    }

    public function revertirCancelacionAvisoPreventivo(MovimientoRegistral $movimientoRegistral){

        $movimientoAvisoCancelado = MovimientoRegistral::where('movimiento_padre', $movimientoRegistral->id)->first();

        $descripcion = str_replace(' AVISO CANCELADO MEDIANTE MOVIMIENTO REGISTRAL ' . $movimientoAvisoCancelado->folio, '', $movimientoAvisoCancelado->vario->descripcion);

        $movimientoAvisoCancelado->vario->update(['estado' => 'activo', 'descripcion' => $descripcion]);

    }

    public function reactivarGravamen(MovimientoRegistral $movimientoRegistral, Gravamen $gravamen){

        $observaciones = str_replace('Cancelado parcialmente mediante movimiento registral: ' . $movimientoRegistral->folioReal->folio . '-' . $movimientoRegistral->folio, '', $gravamen->observaciones);

        $observaciones = str_replace('Cancelado mediante movimiento registral: ' . $movimientoRegistral->folioReal->folio . '-' . $movimientoRegistral->folio, '', $observaciones);

        if($gravamen->movimientoRegistral->estado == 'pase_folio'){

            $monto = $gravamen->valor_gravamen;

        }else{

            $monto = json_decode($gravamen->movimientoRegistral->firmaElectronica->cadena_original)->gravamen->valor_gravamen;

        }

        $gravamen->update([
            'observaciones' => $observaciones,
            'valor_gravamen' => $monto,
            'estado' => 'activo'
        ]);

    }

}