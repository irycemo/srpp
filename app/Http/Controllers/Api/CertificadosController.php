<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Certificacion;
use App\Models\MovimientoRegistral;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
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
                                ->where('usuario', 67)
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

        if(!$movimientoRegistral->firmaElectronica){

            return response()->json([
                'error' => 'No see ncontro el certificado de gravamen.',
            ], 404);

        }

        try {

            $pdf = (new CertificadoGravamenController())->reimprimirFirmado($movimientoRegistral->firmaElectronica);

            return response()->json([
                'data' => [
                    'pdf' => base64_encode($pdf->stream())
                ]
            ], 200);

        } catch (\Throwable $th) {

            Log::error("Error al generar pdf desde Sistema Trámites en Lína." . $th);

            return response()->json([
                'error' => 'Hubo un error al generar el pdf.',
            ], 500);

        }

    }

}
