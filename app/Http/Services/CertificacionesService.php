<?php

namespace App\Http\Services;

use App\Models\Certificacion;
use App\Models\MovimientoRegistral;
use App\Http\Services\MovimientoServiceInterface;

class CertificacionesService implements MovimientoServiceInterface{

    public function crear(array $request):void
    {

        Certificacion::create($this->requestCrear($request));

        if($request['servicio_nombre'] == 'Certificado negativo de vivienda bienestar' && $request['solicitante'] == 'Vivienda Bienestar'){

            $movimientoRegistral = MovimientoRegistral::find($request['movimiento_registral_id']);

            $movimientoRegistral->update([
                'tipo_servicio' => 'extra_urgente',
            ]);

        }

    }

    public function obtenerUsuarioAsignado(array $request):int |null
    {

        $asignacion_service = (new AsignacionService());

        /* Certificaciones: Copias simples, Copias certificadas */
        if(in_array($request['servicio'], ['DL13', 'DL14'])){

            if(isset($request['folio_real'])){

                return $asignacion_service->obtenerCertificador($request['distrito'], $request['solicitante'], $request['tipo_servicio'], false);

            }else{

                return $asignacion_service->obtenerCopiador($request['distrito'], false);

            }

        }

        /* Certificaciones: Consultas */
        if(in_array($request['servicio'], ['DC90', 'DC91', 'DC92', 'DC93'])){

            return $asignacion_service->obtenerUsuarioConsulta($request['distrito']);

        }

        /* Certificaciones: Gravamen */
        if($request['servicio'] == 'DL07'){

            return $asignacion_service->obtenerCertificadorGravamen($request['distrito'], $request['solicitante'], $request['tipo_servicio'], false, isset($request['folio_real']));

        }

        /* Certificaciones: Propiedad */
        if(in_array($request['servicio'], ['DL10'])){

            return $asignacion_service->obtenerCertificadorPropiedad($request['distrito'], false);

        }

        return null;

    }

    public function obtenerSupervisorAsignado(array $request):int
    {

        return (new AsignacionService())->obtenerSupervisorCertificaciones($request['distrito']);

    }

    public function corregir(MovimientoRegistral $movimientoRegistral):void
    {}

    public function actualizarPaginas(array $request):void
    {

        $movimientoRegistral = MovimientoRegistral::find($request['movimiento_registral']);

        $movimientoRegistral->update([
            'estado' => 'nuevo',
            'monto' => $movimientoRegistral->monto + (float)$request['monto'],
            'fecha_entrega' => $this->recalcularFechaEntrega($request['tipo_servicio']),
            'tipo_servicio' => $request['tipo_servicio'],
        ]);

        $movimientoRegistral->certificacion->update(['numero_paginas' => $movimientoRegistral->certificacion->numero_paginas + (int)$request['numero_paginas']]);

    }

    public function requestCrear(array $request):array
    {

        if(in_array($request['servicio'], ['DL13', 'DL14']) && isset($request['folio_real'])){

            MovimientoRegistral::find($request['movimiento_registral_id'])->update(['estado' => 'elaborado']);

        }

        return [
            'servicio' => $request['servicio'],
            'numero_paginas' => $request['numero_paginas'],
            'observaciones' => $request['observaciones'] ?? null,
            'movimiento_registral_id' => $request['movimiento_registral_id'],
            'folio_real' => $request['folio_real'] ?? null,
            'movimiento_registral' => $request['asiento_registral'] ?? null,
        ];

    }

    public function recalcularFechaEntrega($tipo_servicio):string
    {

        if($tipo_servicio == 'ordinario'){

            $actual = now();

            for ($i=0; $i < 5; $i++) {

                $actual->addDays(1);

                while($actual->isWeekend()){

                    $actual->addDay();

                }

            }

            return $actual->toDateString();

        }elseif($tipo_servicio == 'urgente'){

            $actual = now()->addDays(1);

            while($actual->isWeekend()){

                $actual->addDay();

            }

            return $actual->toDateString();

        }else{

            return now()->toDateString();

        }

    }

}
