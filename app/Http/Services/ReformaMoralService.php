<?php

namespace App\Http\Services;

use App\Models\ReformaMoral;
use App\Models\MovimientoRegistral;
use App\Http\Services\MovimientoServiceInterface;

class ReformaMoralService implements MovimientoServiceInterface{

    public function crear(array $request):void
    {

        ReformaMoral::create([
            'movimiento_registral_id' => $request['movimiento_registral_id'],
        ]);

    }

    public function obtenerUsuarioAsignado(array $request):int
    {
        return (new AsignacionService())->obtenerUsuarioFolioRealMoral($request['distrito']);
    }

    public function obtenerSupervisorAsignado(array $request):int
    {
        return (new AsignacionService())->obtenerSupervisorInscripciones($request['distrito']);
    }

    public function corregir(MovimientoRegistral $movimiento):void
    {}

}
