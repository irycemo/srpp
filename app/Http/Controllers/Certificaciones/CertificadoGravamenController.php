<?php

namespace App\Http\Controllers\Certificaciones;

use Carbon\Carbon;
use App\Models\File;
use App\Models\User;
use App\Models\Predio;
use App\Models\Gravamen;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\MovimientoRegistral;
use App\Traits\NombreServicioTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Luecano\NumeroALetras\NumeroALetras;

class CertificadoGravamenController extends Controller
{

    use NombreServicioTrait;

    public function certificadoGravamen(MovimientoRegistral $movimientoRegistral){

        /* $this->authorize('update', $movimientoRegistral); */

        $formatter = new NumeroALetras();

        $predio = Predio::where('folio_real', $movimientoRegistral->folio_real)->first();

        $gravamenes = Gravamen::with('deudores', 'acreedores', 'movimientoRegistral.folioReal')
                                ->WhereHas('movimientoRegistral', function($q) use($movimientoRegistral){
                                    $q->where('folio_real', $movimientoRegistral->folio_real);
                                })
                                ->where('estado', 'activo')
                                ->get();

        Carbon::setLocale(config('app.locale'));
        setlocale(LC_ALL, 'es_MX', 'es', 'ES', 'es_MX.utf8');

        $fecha = Carbon::parse($predio->folioReal->fecha_inscripcion);

        $año = $fecha->format('Y');

        $fecha = now()->formatLocalized('%d DE %B DE ') . $formatter->toWords($año);

        $director = User::where('status', 'activo')
                            ->whereHas('roles', function($q){
                                $q->where('name', 'Director');
                            })->first()->name;

        $registro_numero = $formatter->toWords($predio->folioReal->registro_antecedente);

        $tomo_numero = $formatter->toWords($predio->folioReal->tomo_antecedente);

        $aviso = $movimientoRegistral->FolioReal->avisoPreventivo();

        $servicio = $this->nombreServicio($movimientoRegistral->certificacion->servicio);

        $pdf = Pdf::loadView('certificaciones.certificadoGravamen', compact('predio', 'director', 'movimientoRegistral', 'gravamenes', 'fecha', 'registro_numero', 'tomo_numero', 'formatter', 'aviso', 'servicio'));

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 745, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(1,1,1));

        $canvas->page_text(35, 745, $movimientoRegistral->folioReal->folio  .'-' . $movimientoRegistral->folio, null, 9, array(1, 1, 1));

        if(!$movimientoRegistral->caratula())
            $this->pdfSinFirma($pdf, $movimientoRegistral);

        return $pdf->stream('documento.pdf');

    }

    public function pdfSinFirma($pdf, $movimientoRegistral){

        $nombreS3 = Str::random(40) . '.pdf';

        $nombreLocal = Str::random(40) . '.pdf';

        if(env('LOCAL') == "1"){

            Storage::disk('s3')->put($nombreS3, $pdf->output());

            File::create([
                'fileable_id' => $movimientoRegistral->id,
                'fileable_type' => 'App\Models\MovimientoRegistral',
                'descripcion' => 'caratula',
                'url' => $nombreS3
            ]);

        }elseif(env('LOCAL') == "0"){

            Storage::disk('caratulas')->put($nombreLocal, $pdf->output());

            File::create([
                'fileable_id' => $movimientoRegistral->id,
                'fileable_type' => 'App\Models\MovimientoRegistral',
                'descripcion' => 'caratula_s3',
                'url' => $nombreLocal
            ]);

        }elseif(env('LOCAL') == "2"){

            Storage::disk('s3')->put($nombreS3, $pdf->output());

            File::create([
                'fileable_id' => $movimientoRegistral->id,
                'fileable_type' => 'App\Models\MovimientoRegistral',
                'descripcion' => 'caratula_s3',
                'url' => $nombreS3
            ]);

            Storage::disk('caratulas')->put($nombreLocal, $pdf->output());

            File::create([
                'fileable_id' => $movimientoRegistral->id,
                'fileable_type' => 'App\Models\MovimientoRegistral',
                'descripcion' => 'caratula',
                'url' => $nombreLocal
            ]);

        }

    }

}
