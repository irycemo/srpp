<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoRegistral;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\CertificadoListaRequest;
use App\Http\Resources\CertificadoListaResource;
use App\Http\Controllers\Certificaciones\CertificadoGravamenController;

class CertificadosController extends Controller
{

    public function consultarCertificadosGravamen(CertificadoListaRequest $request){

        $validated = $request->validated();

        if(isset($validated['estado'])){

            $estados = explode(',', $validated['estado']);

        }

        $certificaciones = MovimientoRegistral::with('folioReal')
                                ->whereHas('certificacion')
                                ->where('usuario_tramites_linea_id', $validated['entidad'])
                                ->when(isset($validated['folio_real']), function($q) use ($validated){
                                    $q->WhereHas('folioReal', function($q) use ($validated){
                                        $q->where('folio', $validated['folio_real']);
                                    });
                                })
                                ->when(isset($validated['año']), function($q) use ($validated){
                                    $q->where('año', $validated['año']);
                                })
                                ->when(isset($validated['folio']), function($q) use ($validated){
                                    $q->where('tramite', $validated['folio']);
                                })
                                ->when(isset($validated['estado']), fn($q) => $q->whereIn('estado', $estados))
                                ->orderBy('id', 'desc')
                                ->paginate($validated['pagination'], ['*'], 'page', $validated['pagina']);

        return CertificadoListaResource::collection($certificaciones)->response()->setStatusCode(200);

    }

    public function generarCertificadoGravamenPdf(Request $request){

        $validated = $request->validate(['id' => 'required|numeric|min:1']);

        $movimientoRegistral = MovimientoRegistral::find($validated['id']);

        if(! $movimientoRegistral){

            return response()->json([
                'error' => 'No see ncontro el certificado de gravamen.',
            ], 404);

        }

        if(! $movimientoRegistral->pdf){

            (new CertificadoGravamenController)->reimprimirFirmado($movimientoRegistral->firmaElectronica);

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

    public function consultarEstadisticas(Request $request){

        $validated = $request->validate(['entidad_id' => 'required|numeric|min:1']);

        $array = cache()->get('estadisticas_tramites_en_linea_' . $validated['entidad_id']);

        if(!$array){

            $array = Cache::rememberForever('estadisticas_tramites_en_linea_' . $validated['entidad_id'], function() use ($validated){

                $array = [];

                $movimientos = MovimientoRegistral::select('estado', DB::raw('count(*) as total'))
                                                        ->where('usuario_tramites_linea_id', $validated['entidad_id'])
                                                        ->groupBy('estado')
                                                        ->whereMonth('created_at', Carbon::now()->month)
                                                        ->get();

                foreach ($movimientos as $movimiento) {

                    $array [] = ['estado' => $movimiento->estado, 'total' => $movimiento->total, 'color' => str_replace('-400', '', $movimiento->getEstadoColorAttribute())];

                }

                return $array;

            });

        }

        return response()->json([
            'data' => $array
        ], 200);

    }

}
