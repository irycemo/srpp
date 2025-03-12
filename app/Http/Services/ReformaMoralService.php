<?php

namespace App\Http\Services;

use App\Models\ReformaMoral;
use Illuminate\Support\Facades\Log;
use App\Exceptions\CertificacionServiceException;

class ReformaMoralService{

    public function store(array $request){

        try {

            ReformaMoral::create([
                'movimiento_registral_id' => $request['movimiento_registral'],
            ]);

        } catch (\Throwable $th) {

            Log::error('Error al ingresar el trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites. ' . $th);

            throw new CertificacionServiceException('Error al ingresar reforma moral con trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites.');

        }

    }

}
