<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CandidatoRequest;
use App\Models\Candidato;
use App\Models\FolioReal;

class CandidatoController extends Controller
{

    public function consultarCandidato(CandidatoRequest $request){

        $validated = $request->validated();

        $folio_real = FolioReal::where('tomo_antecedente', $validated['tomo'])
                                    ->where('registro_antecedente', $validated['registro'])
                                    ->where('distrito_antecedente', $validated['distrito'])
                                    ->where('numero_propiedad_antecedente', $validated['numero_propiedad'])
                                    ->first();

        if($folio_real){

            return response()->json([
                'error' => "Ya existe un folio real con ese antecedente.",
            ], 401);

        }

        $candidato = Candidato::where('tomo', $validated['tomo'])
                                    ->where('registro', $validated['registro'])
                                    ->where('distrito', $validated['distrito'])
                                    ->where('numero_propiedad', $validated['numero_propiedad'])
                                    ->first();

        if(!$candidato){

            return response()->json([
                'error' => "El antecedente no es candidato a folio simplificado.",
            ], 404);

        }

        return response()->json([], 200);

    }

}
