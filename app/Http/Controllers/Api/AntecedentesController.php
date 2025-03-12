<?php

namespace App\Http\Controllers\Api;

use App\Models\Propiedadold;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\AntecedenteRequest;
use App\Http\Resources\AntecedenteResource;

class AntecedentesController extends Controller
{

    public function consultarAntecedentes(AntecedenteRequest $request){

        try {

            $validated = $request->validated();

            $antecedentes = Propiedadold::select('noprop', 'ubicacion')
                                            ->where('distrito', $validated['distrito'])
                                            ->where('tomo', $validated['tomo'])
                                            ->where('registro', $validated['registro'])
                                            ->where('status', '!=', 'V')
                                            ->get();

            if(!$antecedentes->count()){

                return response()->json([
                    'error' => 'No se encontraron antecedentes con la información ingresada.',
                ], 404);

            }else{

                return response()->json([
                    'antecedentes' => $antecedentes,
                ], 200);

                return AntecedenteResource::collection($antecedentes)->response()->setStatusCode(200);

            }

        }catch (\Throwable $th) {

            Log::error("Error al consultar antecedentes mediante api: " . $th);

            return response()->json([
                'error' => $th->getMessage(),
            ], 500);

        }

    }

}
