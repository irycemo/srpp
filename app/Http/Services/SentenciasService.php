<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Log;
use App\Exceptions\InscripcionesServiceException;
use App\Models\Sentencia;

class SentenciasService{

    public function store(array $request){

        try {

            Sentencia::create([
                'servicio' => $request['servicio'],
                'movimiento_registral_id' => $request['movimiento_registral'],
            ]);

        } catch (\Throwable $th) {

            Log::error('Error al ingresar el trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites. ' . $th);

            throw new InscripcionesServiceException('Error al ingresar cancelación de gravamen con trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites.');

        }

    }

    public function corregir(Sentencia $sentencia){

        if($sentencia->acto_contenido == 'SENTENCIA RECTIFICACTORIA'){

        }elseif($sentencia->acto_contenido == 'CANCELACIÓN DE SENTENCIA'){

        }elseif(in_array($sentencia->acto_contenido, ['RESOLUCIÓN', 'DEMANDA', 'PROVIDENCIA PRECAUTORIA'])){

        }else{

            throw new InscripcionesServiceException('El acto contenido no esta registrado para corrección.');

        }

    }

}
