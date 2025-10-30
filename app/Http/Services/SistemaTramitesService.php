<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Exceptions\SistemaTramitesServiceException;


class SistemaTramitesService{

    public $token;

    public function __construct()
    {
        $this->token = env('SISTEMA_TRAMITES_TOKEN');
    }

    public function finaliarTramite($año, $tramite, $usuario, $estado){

        $url = env('SISTEMA_TRAMITES_FINALIZAR');

        $response = Http::withToken($this->token)->acceptJson()->asForm()->post($url, [
            'año' => $año,
            'tramite' => $tramite,
            'usuario' => $usuario,
            'estado' => $estado,
        ]);

        if($response->status() != 200){

            throw new SistemaTramitesServiceException('Error al enviar trámite actualizado al sistema trámites.' . $response);

        }

    }

    public function rechazarTramite($año, $tramite, $usuario, $observaciones){

        $url = env('SISTEMA_TRAMITES_RECHAZAR');

        $response = Http::withToken($this->token)->acceptJson()->asForm()->post($url, [
            'año' => $año,
            'tramite' => $tramite,
            'usuario' => $usuario,
            'observaciones' => $observaciones,
            'estado' => 'rechazado'
        ]);

        if($response->status() != 200){

            Log::error('Error al enviar tramite rechazado al sistema trámites.' . $response);

            throw new SistemaTramitesServiceException('Error al enviar tramite rechazado al sistema trámites.' . $response);

        }

    }

}
