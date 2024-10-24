<?php

namespace App\Http\Controllers\Api;

use App\Models\FolioReal;
use App\Models\Propiedadold;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\FolioRealRequest;
use App\Http\Resources\FolioRealResource;

class FolioRealController extends Controller
{

    public function consultarFolioReal(FolioRealRequest $request){

        try {

            $validated = $request->validated();

            $folio_real = FolioReal::when(isset($validated['tomo']), function($q) use($validated){
                                            $q->where('tomo_antecedente', $validated['tomo']);
                                        })
                                        ->when(isset($validated['registro']), function($q) use($validated){
                                            $q->where('registro_antecedente', $validated['registro']);
                                        })
                                        ->when(isset($validated['distrito']), function($q) use($validated){
                                            $q->where('distrito_antecedente', $validated['distrito']);
                                        })
                                        ->when(isset($validated['seccion']), function($q) use($validated){
                                            $q->where('seccion_antecedente', $validated['seccion']);
                                        })
                                        ->when(isset($validated['numero_propiedad']), function($q) use($validated){
                                            $q->where('numero_propiedad_antecedente', $validated['numero_propiedad']);
                                        })
                                        ->when(isset($validated['folio_real']), function($q) use($validated){
                                            $q->where('folio', $validated['folio_real']);
                                        })
                                        ->first();

            if(
                isset($validated['tomo']) &&
                isset($validated['registro']) &&
                isset($validated['distrito']) &&
                isset($validated['seccion']) &&
                isset($validated['numero_propiedad'])
            ){

                    $propiedad = Propiedadold::where('distrito', $validated['distrito'])
                                                ->where('tomo', $validated['tomo'])
                                                ->where('registro', $validated['registro'])
                                                ->where('noprop', $validated['numero_propiedad'])
                                                ->where('status', 'V')
                                                ->first();

                }else{

                    $propiedad = null;
                }


            if($folio_real){

                if($folio_real->estado == 'rechazado'){

                    return response()->json([
                        'error' => 'El folio real esta rechazado',
                    ], 401);

                }

                return (new FolioRealResource($folio_real))->response()->setStatusCode(200);

            }elseif(isset($validated['folio_real']) && !$folio_real){

                return response()->json([
                    'folio_real' => null,
                ], 404);

            }elseif(!$folio_real){

                if($propiedad){

                    return response()->json([
                        'error' => 'La propiedad ya ha sido vendida',
                    ], 401);

                }

                return response()->json([
                    'folio_real' => null,
                ], 204);

            }

        }catch (\Throwable $th) {

            Log::error("Error al consultar folio real mediante api: " . $th);

            return response()->json([
                'error' => $th->getMessage(),
            ], 500);

        }

    }


}
