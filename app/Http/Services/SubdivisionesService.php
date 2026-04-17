<?php

namespace App\Http\Services;

use App\Models\MovimientoRegistral;
use App\Http\Services\AsignacionService;
use App\Http\Services\MovimientoServiceInterface;

class SubdivisionesService implements MovimientoServiceInterface
{

    public function crear(array $request):void{}

    public function obtenerUsuarioAsignado(array $request):int
    {

        $asignacion_service = (new AsignacionService());

        return $asignacion_service->obtenerUsuarioSubdivisiones($request['distrito']);

    }

    public function obtenerSupervisorAsignado(array $request):int
    {

        $asignacion_service = (new AsignacionService());

        return $asignacion_service->obtenerSupervisorInscripciones($request['distrito']);

    }

    public function corregir(MovimientoRegistral $movimientoRegistral):void{}

    public function regresarMovimientoId(MovimientoRegistral $movimiento):MovimientoRegistral
    {

        $movimiento_hijo = MovimientoRegistral::where('movimiento_padre', $movimiento->id)->first();

        if($movimiento_hijo){

            return $movimiento_hijo;

        }else{

            return $movimiento;

        }

    }

}