<?php

namespace App\Http\Controllers\Api;

use App\Models\MovimientoRegistral;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConsultaGravamenRequest;

class GravamenController extends Controller
{

    public function consultarGravamen(ConsultaGravamenRequest $request){

        $data = $request->validated();

        $movimientoRegistral = MovimientoRegistral::when(isset($data['folio']), function($q) use($data){
                                                            $q->where('folio', $data['folio']);
                                                        })
                                                        ->when(isset($data['tomo_gravamen']), function($q) use($data){
                                                            $q->where('tomo_gravamen', $data['tomo_gravamen']);
                                                        })
                                                        ->when(isset($data['registro_gravamen']), function($q) use($data){
                                                            $q->where('registro_gravamen', $data['registro_gravamen']);
                                                        })
                                                        ->where('distrito', $data['distrito'])
                                                        ->whereHas('folioReal', function($q) use($data){
                                                            $q->where('folio', $data['folio_real']);
                                                        })
                                                        ->first();

                                                        info($data);

        if(!$movimientoRegistral){

            return response()->json([
                'error' => "El movimiento registral no existe.",
            ], 404);

        }

        if(!$movimientoRegistral->gravamen){

            return response()->json([
                'error' => "El gravamen no existe.",
            ], 404);

        }

        if($movimientoRegistral->gravamen->estado != 'activo'){

            return response()->json([
                'error' => "El gravamen no esa activo.",
            ], 401);

        }

        return response()->json([
            'data' => [
                'folio' => $movimientoRegistral->folio,
                'tomo_gravamen' => $movimientoRegistral->tomo_gravamen,
                'registro_gravamen' => $movimientoRegistral->registro_gravamen,
            ],
        ], 200);

    }
}
