<?php

namespace App\Http\Services;

use App\Models\MovimientoRegistral;

interface MovimientoServiceInterface
{

    public function crear(array $request):void;

    public function obtenerUsuarioAsignado(array $request):int | null;

    public function obtenerSupervisorAsignado(array $request):int;

    public function corregir(MovimientoRegistral $movimientoRegistral):void;

}