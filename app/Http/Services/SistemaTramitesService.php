<?php

namespace App\Http\Services;

use App\Exceptions\SistemaTramitesServiceException;
use Illuminate\Support\Facades\Http;


class SistemaTramitesService{

    public function finaliarTramite($año, $tramite, $estado){

        $url = env('SISTEMA_TRAMITES_FINALIZAR');

        $response = Http::acceptJson()->asForm()->post($url, [
            'año' => $año,
            'tramite' => $tramite,
            'estado' => $estado,
        ]);

        if($response->status() != 200){

            throw new SistemaTramitesServiceException('Error al enviar trámite actualizado al sistema trámites.' . $response);

        }

    }

    public function rechazarTramite($año, $tramite, $observaciones){

        $url = env('SISTEMA_TRAMITES_RECHAZAR');

        $response = Http::acceptJson()->asForm()->post($url, [
            'año' => $año,
            'tramite' => $tramite,
            'observaciones' => $observaciones,
            'estado' => 'rechazado'
        ]);

        if($response->status() != 200){

            throw new SistemaTramitesServiceException('Error al enviar tramite rechazado al sistema trámites.' . $response);

        }

    }

}
