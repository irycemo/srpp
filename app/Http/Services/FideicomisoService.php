<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Log;
use App\Exceptions\InscripcionesServiceException;
use App\Models\Fideicomiso;

class FideicomisoService{

    public function store(array $request)
    {

        try {

            Fideicomiso::create([
                'estado' => 'nuevo',
                'movimiento_registral_id' => $request['movimiento_registral'],
            ]);

        } catch (\Throwable $th) {

            Log::error('Error al ingresar inscripción de propiedad con el trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites. ' . $th);

            throw new InscripcionesServiceException('Error al ingresar inscripción de propiedad con el trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites.');

        }

    }

}
