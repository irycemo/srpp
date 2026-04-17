<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Log;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\Http;

class SistemaTramitesService{

    public function finaliarTramite($año, $tramite, $usuario, $estado){

        $response = Http::withToken(config('services.sistema_tramites.token'))
                            ->accept('application/json')
                            ->asForm()
                            ->post(
                                config('services.sistema_tramites.finaliar_tramite'),
                                [
                                    'año' => $año,
                                    'tramite' => $tramite,
                                    'usuario' => $usuario,
                                    'estado' => $estado,
                                ]
                            );

        if($response->status() !== 200){

            Log::error("Error al finalizar trámite en Sistema Trámites, tramite: " . $tramite->año . '-' . $tramite->numero_control . '-' . $tramite->usuario . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $response);

            $data = json_decode($response, true);

            if(isset($data['error'])){

                throw new GeneralException($data['error']);

            }

            throw new GeneralException("Error al finalizar trámite en Sistema Trámites.");

        }

    }

    public function rechazarTramite($año, $tramite, $usuario, $folio_real, $tomo, $registro, $numero_propiedad, $observaciones){

        $response = Http::withToken(config('services.sistema_tramites.token'))
                            ->accept('application/json')
                            ->asForm()
                            ->post(
                                config('services.sistema_tramites.rechazar_tramite'),
                                [
                                    'año' => $año,
                                    'tramite' => $tramite,
                                    'usuario' => $usuario,
                                    'observaciones' => $observaciones,
                                    'estado' => 'rechazado',
                                    'folio_real' => $folio_real,
                                    'tomo' => $tomo,
                                    'registro' => $registro,
                                    'numero_propiedad' => $numero_propiedad,
                                ]
                            );

        if($response->status() !== 200){

            Log::error("Error al rechazar trámite en Sistema Trámites, tramite: " . $tramite->año . '-' . $tramite->numero_control . '-' . $tramite->usuario . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $response);

            $data = json_decode($response, true);

            if(isset($data['error'])){

                throw new GeneralException($data['error']);

            }

            throw new GeneralException("Error al rechazar trámite en Sistema Trámites.");

        }

    }

}
