<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Log;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\Http;

class SistemaTramitesLineaService{

    public function actualizarAvisoPreventivo(int $movimiento_registral_id){

        $response = Http::withToken(config('services.sistema_tramites_linea.token'))
                            ->accept('application/json')
                            ->asForm()
                            ->post(
                                config('services.sistema_tramites_linea.actualizar_estado'),
                                [
                                    'movimiento_registral_id' => $movimiento_registral_id,
                                ]
                            );

        if($response->status() !== 200){

            Log::error(
                "Error al actualizar aviso preventivo en Sistema Trámites en Línea.",
                [
                    'response' => $response
                ]
            );

            $data = json_decode($response, true);

            if(isset($data['error'])){

                throw new GeneralException($data['error']);

            }

            throw new GeneralException("Error al actualizar aviso preventivo en Sistema Trámites en Línea.");

        }

    }

}
