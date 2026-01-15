<?php

namespace App\Http\Services;

use App\Models\Gravamen;
use App\Models\MovimientoRegistral;
use App\Http\Services\MovimientoServiceInterface;
use App\Traits\Inscripciones\RevisarFolioMatrizTrait;

class InscripcionesGravamenService implements MovimientoServiceInterface{

    use RevisarFolioMatrizTrait;

    public function crear(array $request):void
    {

        Gravamen::create([
            'estado' => 'nuevo',
            'servicio' => $request['servicio'],
            'movimiento_registral_id' => $request['movimiento_registral_id'],
        ]);

    }

    public function obtenerUsuarioAsignado(array $request):int | null
    {
        return (new AsignacionService())->obtenerUsuarioGravamen(isset($request['folio_real']), $request['distrito'], $request['estado']);
    }

    public function obtenerSupervisorAsignado(array $request):int
    {
        return (new AsignacionService())->obtenerSupervisorInscripciones($request['distrito']);
    }

    public function corregir(MovimientoRegistral $movimientoRegistral):void
    {}

}
