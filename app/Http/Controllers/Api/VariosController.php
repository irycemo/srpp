<?php

namespace App\Http\Controllers\Api;

use App\Models\MovimientoRegistral;
use App\Http\Controllers\Controller;
use App\Http\Requests\AvisoPreventivoRequest;

class VariosController extends Controller
{

    public function consultarPrimerAvisoPreventivo(AvisoPreventivoRequest $request){

        $data = $request->validated();

        $movimientoRegistral = MovimientoRegistral::whereHas('vario', function($q){
                                                            $q->where('acto_contenido', 'PRIMER AVISO PREVENTIVO');
                                                        })
                                                        ->when(isset($data['folio']), function($q) use($data){
                                                            $q->where('folio', $data['folio']);
                                                        })
                                                        ->whereHas('folioReal', function($q) use($data){
                                                            $q->where('folio', $data['folio_real']);
                                                        })
                                                        ->first();

        if(!$movimientoRegistral){

            return response()->json([
                'error' => "El aviso no existe.",
            ], 404);

        }

        if(!$movimientoRegistral->vario){

            return response()->json([
                'error' => "El aviso no existe.",
            ], 404);

        }

        if($movimientoRegistral->vario->estado != 'activo'){

            return response()->json([
                'error' => "El aviso no esa activo.",
            ], 401);

        }

        return response()->json([], 200);

    }

    public function consultarSegundoAvisoPreventivo(AvisoPreventivoRequest $request){

        $data = $request->validated();

        $movimientoRegistral = MovimientoRegistral::whereHas('vario', function($q){
                                                            $q->where('acto_contenido', 'SEGUNDO AVISO PREVENTIVO');
                                                        })
                                                        ->when(isset($data['folio']), function($q) use($data){
                                                            $q->where('folio', $data['folio']);
                                                        })
                                                        ->whereHas('folioReal', function($q) use($data){
                                                            $q->where('folio', $data['folio_real']);
                                                        })
                                                        ->first();

        if(!$movimientoRegistral){

            return response()->json([
                'error' => "El movimiento registral no existe.",
            ], 404);

        }

        if(!$movimientoRegistral->vario){

            return response()->json([
                'error' => "El aviso no existe.",
            ], 404);

        }

        if($movimientoRegistral->vario->estado != 'activo'){

            return response()->json([
                'error' => "El aviso no esa activo.",
            ], 401);

        }

        return response()->json([], 200);

    }

}
