<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\CertificacionServiceException;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\CopiasUpdateRequest;
use App\Http\Services\CertificacionesService;

class CertificacionController extends Controller
{

    public function __construct(public CertificacionesService $certificacionesService){}

    public function actualizarPaginas(CopiasUpdateRequest $request)
    {

        try {

            $movimientoRegistral = $this->certificacionesService->actualizarPaginas($request);

            return response()->json([
                'result' => 'success',
                'data' => $movimientoRegistral
            ], 200);

        } catch (CertificacionServiceException $th) {

            return response()->json([
                'result' => 'error',
                'data' => $th->getMessage(),
            ], 500);

        } catch (\Throwable $th) {

            Log::error('Error al actualizar el trámite: ' . $request->año . '-' . $request->tramite . ' desde Sistema Trámites. ' . $th);

            return response()->json([
                'result' => 'error',
                'data' => $th->getMessage(),
            ], 500);

        }

    }

}
