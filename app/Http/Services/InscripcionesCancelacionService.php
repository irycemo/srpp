<?php

namespace App\Http\Services;

use App\Models\FolioReal;
use App\Models\Cancelacion;
use App\Models\MovimientoRegistral;
use App\Http\Services\MovimientoServiceInterface;
use App\Traits\Inscripciones\RevisarFolioMatrizTrait;

class InscripcionesCancelacionService implements MovimientoServiceInterface{

    use RevisarFolioMatrizTrait;

    public function crear(array $request):void
    {

        if(isset($request['asiento_registral'])){

            $gravamen = FolioReal::where('folio', $request['folio_real'])
                                    ->first()
                                    ->movimientosRegistrales()
                                    ->where('folio', $request['asiento_registral'])
                                    ->first()
                                    ->id;

        }else{

            $gravamen = null;

        }

        Cancelacion::create([
            'gravamen' => $gravamen,
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
