<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait NombreServicioTrait{

    public function nombreServicio($año, $tramite, $usuario){

        $response = Http::acceptJson()
                        ->withToken(env('SISTEMA_TRAMITES_TOKEN'))
                        ->withQueryParameters([
                            'ano' => $año,
                            'numero_control' => $tramite,
                            'usuario' => $usuario
                        ])
                        ->get(env('SISTEMA_TRAMITES_CONSULTAR_SERVICIO'));

        $data = json_decode($response, true);

        if($response->status() === 200){

            return $data['nombre'];

        }

        return null;

    }

}
