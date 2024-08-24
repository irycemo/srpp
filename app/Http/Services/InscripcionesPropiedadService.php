<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Log;
use App\Exceptions\CertificacionServiceException;
use App\Models\Propiedad;

class InscripcionesPropiedadService{

    public function store(array $request){

        try {

            Propiedad::create($this->requestCrear($request));

        } catch (\Throwable $th) {

            Log::error('Error al ingresar el trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites. ' . $th);

            throw new CertificacionServiceException('Error al ingresar inscripción de propiedad con el trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites.');

        }

    }

    public function requestCrear(array $request):array
    {

        $array = [];

        $fields = [
            'valor_propiedad',
            'numero_inmuebles',
        ];

        foreach($fields as $field){

            if(array_key_exists($field, $request)){

                $array[$field] = $request[$field];

            }

        }

        return $array +  [
            'servicio' => $request['servicio'],
            'movimiento_registral_id' => $request['movimiento_registral'],
        ];

    }

}
