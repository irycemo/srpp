<?php

namespace App\Http\Controllers\Api;

use App\Models\FolioReal;
use App\Models\Antecedente;
use App\Models\Propiedadold;
use Illuminate\Http\Request;
use App\Models\FolioRealPersona;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\FolioRealRequest;
use App\Http\Resources\FolioRealResource;
use App\Http\Resources\FolioRealPersonaMoral;

class FolioRealController extends Controller
{

    public function consultarFolioReal(FolioRealRequest $request){

        try {

            $folio_real = null;

            $validated = $request->validated();

            $antecedente = Antecedente::when(isset($validated['tomo']), function($q) use($validated){
                                                $q->where('tomo_antecedente', $validated['tomo']);
                                            })
                                        ->when(isset($validated['registro']), function($q) use($validated){
                                            $q->where('registro_antecedente', $validated['registro']);
                                        })
                                        ->when(isset($validated['numero_propiedad']), function($q) use($validated){
                                            $q->where('numero_propiedad_antecedente', $validated['numero_propiedad']);
                                        })
                                        ->when(isset($validated['folio_real']), function($q)use($validated){
                                            $q->whereHas('folioRealAntecedente', function($q) use($validated){
                                                $q->where('folio', $validated['folio_real']);
                                            });
                                        })->first();
            if($antecedente){

                $folio_real = FolioReal::whereKey($antecedente->folio_real)->first();

            }

            if(!$folio_real){

                $folio_real = FolioReal::when(isset($validated['folio_real']), function($q) use($validated){
                                            $q->where('folio', $validated['folio_real']);
                                        })
                                        ->when(isset($validated['tomo']), function($q) use($validated){
                                            $q->where('tomo_antecedente', $validated['tomo']);
                                        })
                                        ->when(isset($validated['registro']), function($q) use($validated){
                                            $q->where('registro_antecedente', $validated['registro']);
                                        })
                                        ->when(isset($validated['distrito']), function($q) use($validated){
                                            $q->where('distrito_antecedente', $validated['distrito']);
                                        })
                                        ->when(isset($validated['numero_propiedad']), function($q) use($validated){
                                            $q->where('numero_propiedad_antecedente', $validated['numero_propiedad']);
                                        })
                                        ->first();

            }

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
                                                ->whereIn('status', ['V', 'C'])
                                                ->first();

            }else{

                $propiedad = null;
            }

            if($folio_real){

                if(in_array($folio_real->estado, ['bloqueado', 'centinela',' inactivo'])){

                    return response()->json([
                        'error' => 'El folio real esta bloqueado o en centinela',
                    ], 401);

                }else{

                    return (new FolioRealResource($folio_real))->response()->setStatusCode(200);

                }

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

    public function consultarFolioMovimiento(Request $request){

        $validated = $request->validate(['folio_real' => 'required', 'asiento_registral' => 'nullable']);

        $folio_real = FolioReal::where('folio', $validated['folio_real'])->first();

        if(!$folio_real){

            return response()->json([
                'error' => "El folio real no existe.",
            ], 404);

        }else{

            if(in_array($folio_real->estado, ['bloqueado', 'centinela'])){

                return response()->json([
                    'error' => 'El folio real esta bloqueado',
                ], 401);

            }elseif($folio_real->estado != 'activo'){

                return response()->json([
                    'error' => 'El folio real no esta activo',
                ], 401);

            }else{

                if(isset($validated['asiento_registral'])){

                    $movimiento = MovimientoRegistral::where('folio_real', $folio_real->id)
                                                        ->where('folio', $validated['asiento_registral'])
                                                        ->first();

                    if(!$movimiento){

                        return response()->json([
                            'error' => "El movimiento registral real no existe.",
                        ], 404);

                    }else{

                        return (new FolioRealResource($folio_real))->response()->setStatusCode(200);

                    }

                }else{

                    return (new FolioRealResource($folio_real))->response()->setStatusCode(200);

                }

            }

        }

    }

    public function consultarFolioRealPersonaMoral(FolioRealRequest $request){

        try {

            $folio_real = null;

            $validated = $request->validated();

            $folio_real = FolioRealPersona::when(isset($validated['tomo']), function($q) use($validated){
                                                    $q->where('tomo_antecedente', $validated['tomo']);
                                                })
                                            ->when(isset($validated['registro']), function($q) use($validated){
                                                $q->where('registro_antecedente', $validated['registro']);
                                            })
                                            ->when(isset($validated['distrito']), function($q) use($validated){
                                                $q->where('distrito', $validated['distrito']);
                                            })
                                            ->when(isset($validated['folio_real']), function($q) use($validated){
                                                $q->where('folio', $validated['folio_real']);
                                            })
                                            ->first();

            if(!$folio_real && isset($validated['folio_real'])){

                return response()->json([
                    'error' => "El folio real de persona moral no existe.",
                ], 404);

            }elseif(!$folio_real && !isset($validated['folio_real'])){

                return response()->json([
                    'error' => "El folio real de persona moral no existe.",
                ], 204);

            }else{

                if(in_array($folio_real->estado, ['bloqueado', 'centinela'])){

                    return response()->json([
                        'error' => 'El folio real esta bloqueado',
                    ], 401);

                }elseif($folio_real->estado == 'activo'){

                    return (new FolioRealPersonaMoral($folio_real))->response()->setStatusCode(200);

                }else{

                    return response()->json([
                        'error' => 'El folio real no esta activo',
                    ], 401);

                }

            }

        }catch (\Throwable $th) {

            Log::error("Error al consultar folio real mediante api: " . $th);

            return response()->json([
                'error' => $th->getMessage(),
            ], 500);

        }

    }

}
