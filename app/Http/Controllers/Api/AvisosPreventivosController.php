<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Varios\VariosController;
use App\Models\MovimientoRegistral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AvisosPreventivosController extends Controller
{

    public function generarAvisoPreventivoPdf(Request $request){

        $validated = $request->validate(['movimiento_registral_id' => 'required|numeric|min:1']);

        $movimientoRegistral = MovimientoRegistral::find($validated['movimiento_registral_id']);

        if(! $movimientoRegistral){

            return response()->json([
                'error' => 'No see ncontro el certificado de gravamen.',
            ], 404);

        }

        if(! $movimientoRegistral->pdf){

            (new VariosController)->reimprimirFirmado($movimientoRegistral->firmaElectronica);

            $movimientoRegistral->refresh();

            if(app()->isProduction()){

                return response()->json([
                    'data' => [
                        'pdf' => Storage::disk('s3')->temporaryUrl(config('services.ses.ruta_pdfs') . $movimientoRegistral->pdf->url, now()->addMinutes(10))
                    ]
                ], 200);

            }else{

                return response()->json([
                    'data' => [
                        'pdf' => Storage::disk('pdfs')->Url($movimientoRegistral->pdf->url)
                    ]
                ], 200);

            }

        }else{

            if(app()->isProduction()){

                return response()->json([
                    'data' => [
                        'pdf' => Storage::disk('s3')->temporaryUrl(config('services.ses.ruta_pdfs') . $movimientoRegistral->pdf->url, now()->addMinutes(10))
                    ]
                ], 200);

            }else{

                return response()->json([
                    'data' => [
                        'pdf' => Storage::disk('pdfs')->Url($movimientoRegistral->pdf->url)
                    ]
                ], 200);

            }

        }

    }

}
