<?php

namespace App\Http\Services;

use App\Models\FolioReal;
use App\Models\Cancelacion;
use Illuminate\Support\Facades\Log;
use App\Exceptions\CertificacionServiceException;

class InscripcionesCancelacionService{

    public function store(array $request){

        try {

            if(isset($request['asiento_registral'])){

                $gravamen = FolioReal::where('folio', $request['folio_real'])
                                        ->first()
                                        ->movimientosRegistrales()
                                        ->where('folio', $request['asiento_registral'])
                                        ->first()
                                        ->id;

            }else{

                $gravamen = null;

            }

            Cancelacion::create([
                'gravamen' => $gravamen,
                'servicio' => $request['servicio'],
                'movimiento_registral_id' => $request['movimiento_registral'],
            ]);

        } catch (\Throwable $th) {

            Log::error('Error al ingresar el trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites. ' . $th);

            throw new CertificacionServiceException('Error al ingresar cancelación de gravamen con trámite: ' . $request['año'] . '-' . $request['tramite'] . '-' . $request['usuario'] . ' desde Sistema Trámites.');

        }

    }

}
