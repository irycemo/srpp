<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Log;
use App\Exceptions\CertificacionServiceException;
use App\Models\Vario;

class VariosService{

    public function store(array $request){

        try {

            Vario::create([
                'servicio' => $request['servicio'],
                'movimiento_registral_id' => $request['movimiento_registral'],
            ]);

        } catch (\Throwable $th) {

            Log::error('Error al ingresar el trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites. ' . $th);

            throw new CertificacionServiceException('Error al ingresar inscripción de propiedad con el trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites.');

        }

    }

}
