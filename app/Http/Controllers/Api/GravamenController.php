<?php

namespace App\Http\Controllers\Api;

use App\Models\MovimientoRegistral;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConsultaGravamenRequest;
use App\Http\Requests\ConsultarGravamenReestructuraRequest;

class GravamenController extends Controller
{

    public function consultarGravamen(ConsultaGravamenRequest $request){

        $validated = $request->validated();

        $movimientoRegistral = MovimientoRegistral::when(isset($validated['folio']), function($q) use($validated){
                                                            $q->where('folio', $validated['folio']);
                                                        })
                                                        ->when(isset($validated['tomo_gravamen']), function($q) use($validated){
                                                            $q->where('tomo_gravamen', $validated['tomo_gravamen']);
                                                        })
                                                        ->when(isset($validated['registro_gravamen']), function($q) use($validated){
                                                            $q->where('registro_gravamen', $validated['registro_gravamen']);
                                                        })
                                                        ->where('distrito', $validated['distrito'])
                                                        ->when(isset($validated['folio_real']), function($q) use($validated){
                                                            $q->whereHas('folioReal', function($q) use($validated){
                                                                $q->where('folio', $validated['folio_real']);
                                                            });
                                                        })
                                                        ->first();

        info($movimientoRegistral);

        if(!$movimientoRegistral){

            return response()->json([
                'error' => "El gravamen no existe.",
            ], 404);

        }

        if(!$movimientoRegistral->gravamen){

            return response()->json([
                'error' => "El gravamen no existe.",
            ], 404);

        }

        if($movimientoRegistral->gravamen->estado != 'activo'){

            return response()->json([
                'error' => "El gravamen no esta activo.",
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

    public function consultarGravamenReestructura(ConsultarGravamenReestructuraRequest $request){

        $validated = $request->validated();

        $movimientoRegistral = MovimientoRegistral::whereHas('folioReal', function($q) use($validated){
                                                            $q->where('folio', $validated['folio_real']);
                                                    })
                                                    ->where('folio', $validated['movimiento_registral'])
                                                    ->first();

        if(!$movimientoRegistral){

            return response()->json([
                'error' => "El gravamen no existe.",
            ], 404);

        }

        if(!$movimientoRegistral->gravamen){

            return response()->json([
                'error' => "El gravamen no existe.",
            ], 404);

        }

        if($movimientoRegistral->gravamen->estado != 'activo'){

            return response()->json([
                'error' => "El gravamen no esta activo.",
            ], 401);

        }

        return response()->json([
            'data' => [
                'movimiento_registral_id' => $movimientoRegistral->id,
            ],
        ], 200);

    }

}
