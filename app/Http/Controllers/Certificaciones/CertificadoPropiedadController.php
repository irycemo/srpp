<?php

namespace App\Http\Controllers\Certificaciones;

use Carbon\Carbon;
use App\Models\File;
use App\Models\User;
use App\Models\Predio;
use Illuminate\Support\Str;
use App\Constantes\Constantes;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\CertificadoPersona;
use App\Models\MovimientoRegistral;
use App\Traits\NombreServicioTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Luecano\NumeroALetras\NumeroALetras;

class CertificadoPropiedadController extends Controller
{

    use NombreServicioTrait;

    public function certificadoNegativoPropiedad(MovimientoRegistral $movimientoRegistral){

        /* $this->authorize('update', $movimientoRegistral); */

        $formatter = new NumeroALetras();

        $predio = Predio::where('folio_real', $movimientoRegistral->folio_real)->first();

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

        $distrito = Constantes::DISTRITOS[$movimientoRegistral->folioReal->distrito_antecedente];

        $servicio = $this->nombreServicio($movimientoRegistral->certificacion->servicio);

        $personas = $movimientoRegistral->certificacion->personas;

        $pdf = Pdf::loadView('certificaciones.certificadoNegativoPropiedad', compact('predio', 'distrito', 'director', 'movimientoRegistral', 'fecha', 'registro_numero', 'tomo_numero', 'formatter', 'servicio', 'personas'));

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 745, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(1, 1, 1));

        $canvas->page_text(35, 745, $movimientoRegistral->folioReal->folio  .'-' . $movimientoRegistral->folio, null, 9, array(1, 1, 1));

        return $pdf->stream('documento.pdf');

    }

    public function certificadoPropiedad(MovimientoRegistral $movimientoRegistral){

        /* $this->authorize('update', $movimientoRegistral); */

        $formatter = new NumeroALetras();

        $predio = Predio::where('folio_real', $movimientoRegistral->folio_real)->first();

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

        $distrito = Constantes::DISTRITOS[$movimientoRegistral->folioReal->distrito_antecedente];

        $personas = CertificadoPersona::with('persona')->where('certificacion_id', $movimientoRegistral->certificacion->id)->get();

        $servicio = $this->nombreServicio($movimientoRegistral->certificacion->servicio);

        $pdf = Pdf::loadView('certificaciones.certificadoPropiedad', compact('predio', 'distrito', 'director', 'movimientoRegistral', 'fecha', 'registro_numero', 'tomo_numero', 'formatter', 'personas', 'servicio'));

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 745, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(1, 1, 1));

        $canvas->page_text(35, 745, $movimientoRegistral->folioReal->folio  .'-' . $movimientoRegistral->folio, null, 9, array(1, 1, 1));

        if(!$movimientoRegistral->caratula())
            $this->pdfSinFirma($pdf, $movimientoRegistral);

        return $pdf->stream('documento.pdf');

    }

    public function certificadoUnicoPropiedad(MovimientoRegistral $movimientoRegistral){

        /* $this->authorize('update', $movimientoRegistral); */

        $formatter = new NumeroALetras();

        $predio = Predio::where('folio_real', $movimientoRegistral->folio_real)->first();

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

        $distrito = Constantes::DISTRITOS[$movimientoRegistral->folioReal->distrito_antecedente];

        $servicio = $this->nombreServicio($movimientoRegistral->certificacion->servicio);

        $persona = $movimientoRegistral->certificacion->personas()->first()->persona->nombre . ' ' .
                    $movimientoRegistral->certificacion->personas()->first()->persona->ap_paterno . ' ' .
                    $movimientoRegistral->certificacion->personas()->first()->persona->ap_materno;

        $pdf = Pdf::loadView('certificaciones.certificadoUnicoPropiedad', compact('predio', 'distrito', 'director', 'movimientoRegistral', 'fecha', 'registro_numero', 'tomo_numero', 'formatter', 'servicio', 'persona'));

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 745, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(1, 1, 1));

        $canvas->page_text(35, 745, $movimientoRegistral->folioReal->folio  .'-' . $movimientoRegistral->folio, null, 9, array(1, 1, 1));

        if(!$movimientoRegistral->caratula())
            $this->pdfSinFirma($pdf, $movimientoRegistral);

        return $pdf->stream('documento.pdf');

    }

    public function certificadoPropiedadColindancias(MovimientoRegistral $movimientoRegistral){

        /* $this->authorize('update', $movimientoRegistral); */

        $formatter = new NumeroALetras();

        $predio = Predio::where('folio_real', $movimientoRegistral->folio_real)->first();

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

        $distrito = Constantes::DISTRITOS[$movimientoRegistral->folioReal->distrito_antecedente];

        $servicio = $this->nombreServicio($movimientoRegistral->certificacion->servicio);

        $pdf = Pdf::loadView('certificaciones.certificadoPropiedadColindancias', compact('predio', 'distrito', 'director', 'movimientoRegistral', 'fecha', 'registro_numero', 'tomo_numero', 'formatter', 'servicio'));

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 745, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(1, 1, 1));

        $canvas->page_text(35, 745, $movimientoRegistral->folioReal->folio  .'-' . $movimientoRegistral->folio, null, 9, array(1, 1, 1));

        if(!$movimientoRegistral->caratula())
            $this->pdfSinFirma($pdf, $movimientoRegistral);

        return $pdf->stream('documento.pdf');

    }

    public function certificadoNegativo(MovimientoRegistral $movimientoRegistral){

        /* $this->authorize('update', $movimientoRegistral); */

        $formatter = new NumeroALetras();

        Carbon::setLocale(config('app.locale'));
        setlocale(LC_ALL, 'es_MX', 'es', 'ES', 'es_MX.utf8');

        $director = User::where('status', 'activo')
                            ->whereHas('roles', function($q){
                                $q->where('name', 'Director');
                            })->first()->name;

        $distrito = $movimientoRegistral->distrito;

        $persona = $movimientoRegistral->certificacion->personas()->first()->persona->nombre . ' ' .
                    $movimientoRegistral->certificacion->personas()->first()->persona->ap_paterno . ' ' .
                    $movimientoRegistral->certificacion->personas()->first()->persona->ap_materno;

        $servicio = $this->nombreServicio($movimientoRegistral->certificacion->servicio);

        $predio = $movimientoRegistral->folioReal->predio;

        $pdf = Pdf::loadView('certificaciones.certificadoNegativo', compact('distrito', 'director', 'movimientoRegistral', 'persona', 'servicio', 'predio'));

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 745, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(1, 1, 1));

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
