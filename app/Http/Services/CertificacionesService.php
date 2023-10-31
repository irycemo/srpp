<?php

namespace App\Http\Services;

use App\Models\Certificacion;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\CopiasUpdateRequest;
use App\Exceptions\CertificacionServiceException;

class CertificacionesService{

    public function store(array $request){

        try {

            Certificacion::create($this->requestCrear($request));

        } catch (\Throwable $th) {

            Log::error('Error al ingresar el trámite: ' . $request['año'] . '-' . $request['tramite'] . ' desde Sistema Trámites. ' . $th);

            throw new CertificacionServiceException('Error al ingresar el trámite: ' . $request['año'] . '-' . $request['tramite'] . ' desde Sistema Trámites.');

        }

    }

    public function actualizarPaginas(CopiasUpdateRequest $request)
    {

        try {

            $data = $request->validated();

            $movimientoRegistral = MovimientoRegistral::find($data['movimiento_registral']);

            $movimientoRegistral->update(['estado' => 'nuevo']);

            $movimientoRegistral->certificacion->update(['numero_paginas' => $movimientoRegistral->certificacion->numero_paginas + (int)$data['numero_paginas']]);

        } catch (\Throwable $th) {

            Log::error('Error al ingresar el trámite: ' . $request['año'] . '-' . $request['tramite'] . ' desde Sistema Trámites. ' . $th);

            throw new CertificacionServiceException('Error al ingresar el trámite: ' . $request['año'] . '-' . $request['tramite'] . ' desde Sistema Trámites.');

        }

    }

    public function requestCrear(array $request):array
    {

        return [
            'servicio' => $request['servicio'],
            'numero_paginas' => $request['numero_paginas'],
            'observaciones' => $request['observaciones'],
            'movimiento_registral_id' => $request['movimiento_registral'],
        ];

    }

}
