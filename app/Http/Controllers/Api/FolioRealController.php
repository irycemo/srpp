<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\FolioRealRequest;
use App\Http\Resources\FolioRealResource;
use App\Models\FolioReal;

class FolioRealController extends Controller
{

    public function consultarFolioReal(FolioRealRequest $request){

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


        if($folio_real){

            if($folio_real->estado != 'activo'){

                return response()->json([
                    'folio_real' => null,
                ], 401);

            }

            return (new FolioRealResource($folio_real))->response()->setStatusCode(200);

        }elseif(isset($validated['folio_real']) && !$folio_real){

            return response()->json([
                'folio_real' => null,
            ], 404);

        }elseif(!$folio_real){

            return response()->json([
                'folio_real' => null,
            ], 204);

        }

    }


}
