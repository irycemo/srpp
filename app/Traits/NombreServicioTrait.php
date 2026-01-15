<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait NombreServicioTrait{

    public function nombreServicio($año, $tramite, $usuario){

        $response = Http::acceptJson()
                        ->withToken(config('services.sistema_tramites.token'))
                        ->withQueryParameters([
                            'ano' => $año,
                            'numero_control' => $tramite,
                            'usuario' => $usuario
                        ])
                        ->get(config('services.sistema_tramites.consultar_servicio'));

        $data = json_decode($response, true);

        if($response->status() === 200){

            return $data['nombre'] ?? '';

        }

        return null;

    }

}
