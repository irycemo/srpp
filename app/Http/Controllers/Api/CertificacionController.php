<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\CopiasUpdateRequest;
use App\Http\Services\CertificacionesService;

class CertificacionController extends Controller
{

    public function __construct(public CertificacionesService $certificacionesService){}

    public function actualizarPaginas(CopiasUpdateRequest $request)
    {

        $validated = $request->validated();

        try {

            $this->certificacionesService->actualizarPaginas($validated);

            return response()->json([
                'data' => []
            ], 200);

        } catch (\Throwable $th) {

            Log::error('Error al actualizar el trámite: ' . $request->año . '-' . $request->tramite . ' desde Sistema Trámites. ' . $th);

            return response()->json([
                'error' => 'Error al actualizar el trámite: ' . $request->año . '-' . $request->tramite . '-' . $request->usuario . ' en Sistema RPP.',
            ], 500);

        }

    }

}
