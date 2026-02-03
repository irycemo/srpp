<?php

namespace App\Http\Services;

use App\Models\FolioReal;
use App\Models\Cancelacion;
use App\Models\MovimientoRegistral;
use App\Http\Services\MovimientoServiceInterface;
use App\Models\Gravamen;
use App\Traits\Inscripciones\RevisarFolioMatrizTrait;

class InscripcionesCancelacionService implements MovimientoServiceInterface{

    use RevisarFolioMatrizTrait;

    public function crear(array $request):void
    {

        if($request['servicio_nombre'] == 'Cancelación de reserva de dominio' && !isset($request['asiento_registral'])){

            $movimiento_cancelacion = MovimientoRegistral::find($request['movimiento_registral_id']);

            $movimiento_gravamen = $movimiento_cancelacion->replicate();

            $movimiento_gravamen->servicio_nombre = 'Reserva de dominio';
            $movimiento_gravamen->estado = 'pase_folio';
            $movimiento_gravamen->monto = null;
            $movimiento_gravamen->tipo_documento = null;
            $movimiento_gravamen->numero_documento = null;
            $movimiento_gravamen->autoridad_cargo = null;
            $movimiento_gravamen->autoridad_nombre = null;
            $movimiento_gravamen->autoridad_numero = null;
            $movimiento_gravamen->fecha_emision = null;
            $movimiento_gravamen->fecha_inscripcion = null;
            $movimiento_gravamen->procedencia = null;
            $movimiento_gravamen->numero_oficio = null;
            $movimiento_gravamen->save();

            $gravamen = Gravamen::create([
                'acto_contenido' => 'RESERVA DE DOMINIO',
                'estado' => 'activo',
                'servicio' => 'D156',
                'movimiento_registral_id' => $movimiento_gravamen->id
            ]);

        }

        if(isset($request['asiento_registral'])){

            $gravamen = FolioReal::where('folio', $request['folio_real'])
                                    ->first()
                                    ->movimientosRegistrales()
                                    ->where('folio', $request['asiento_registral'])
                                    ->first();

        }else{

            $gravamen = null;

        }

        Cancelacion::create([
            'gravamen' => $gravamen?->id,
            'servicio' => $request['servicio'],
            'movimiento_registral_id' => $request['movimiento_registral_id'],
        ]);

    }

    public function obtenerUsuarioAsignado(array $request):int | null
    {
        return (new AsignacionService())->obtenerUsuarioCancelacion(isset($request['folio_real']), $request['distrito'], $request['estado']);
    }

    public function obtenerSupervisorAsignado(array $request):int
    {
        return (new AsignacionService())->obtenerSupervisorInscripciones($request['distrito']);
    }

    public function corregir(MovimientoRegistral $movimientoRegistral):void
    {}

}