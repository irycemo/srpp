<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Log;
use App\Exceptions\CertificacionServiceException;
use App\Models\Cancelacion;

class InscripcionesCancelacionService{

    public function store(array $request){

        try {

            Cancelacion::create([
                'servicio' => $request['servicio'],
                'movimiento_registral_id' => $request['movimiento_registral'],
            ]);

        } catch (\Throwable $th) {

            Log::error('Error al ingresar el trámite: ' . $request['año'] . '-' . $request['tramite'] . ' desde Sistema Trámites. ' . $th);

            throw new CertificacionServiceException('Error al ingresar cancelación de gravamen con trámite: ' . $request['año'] . '-' . $request['tramite'] . ' desde Sistema Trámites.');

        }

    }

}
