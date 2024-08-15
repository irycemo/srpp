<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait NombreServicioTrait{

    public function nombreServicio($clave){

        $response = Http::acceptJson()
                        ->withToken(env('SISTEMA_TRAMITES_TOKEN'))
                        ->withQueryParameters([
                            'clave_ingreso' => $clave,
                        ])
                        ->get(env('SISTEMA_TRAMITES_CONSULTAR_SERVICIO'));


        $data = json_decode($response, true);

        if($response->status() === 200){

            return $data['nombre'];

        }

        return null;

    }

}
