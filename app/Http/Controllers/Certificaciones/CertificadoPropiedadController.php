<?php

namespace App\Http\Controllers\Certificaciones;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Predio;
use Illuminate\Http\Request;
use App\Constantes\Constantes;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\CertificadoPersona;
use App\Models\MovimientoRegistral;
use App\Http\Controllers\Controller;
use Luecano\NumeroALetras\NumeroALetras;

class CertificadoPropiedadController extends Controller
{

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

        $pdf = Pdf::loadView('certificaciones.certificadoNegativoPropiedad', compact('predio', 'distrito', 'director', 'movimientoRegistral', 'fecha', 'registro_numero', 'tomo_numero', 'formatter'));

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 794, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(1, 1, 1));

        $canvas->page_text(35, 794, $movimientoRegistral->folioReal->folio  .'-' . $movimientoRegistral->folio, null, 9, array(1, 1, 1));

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

        $pdf = Pdf::loadView('certificaciones.certificadoPropiedad', compact('predio', 'distrito', 'director', 'movimientoRegistral', 'fecha', 'registro_numero', 'tomo_numero', 'formatter', 'personas'));

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 794, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(1, 1, 1));

        $canvas->page_text(35, 794, $movimientoRegistral->folioReal->folio  .'-' . $movimientoRegistral->folio, null, 9, array(1, 1, 1));

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

        $pdf = Pdf::loadView('certificaciones.certificadoUnicoPropiedad', compact('predio', 'distrito', 'director', 'movimientoRegistral', 'fecha', 'registro_numero', 'tomo_numero', 'formatter'));

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 794, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(1, 1, 1));

        $canvas->page_text(35, 794, $movimientoRegistral->folioReal->folio  .'-' . $movimientoRegistral->folio, null, 9, array(1, 1, 1));

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

        $pdf = Pdf::loadView('certificaciones.certificadoPropiedadColindancias', compact('predio', 'distrito', 'director', 'movimientoRegistral', 'fecha', 'registro_numero', 'tomo_numero', 'formatter'));

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 794, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(1, 1, 1));

        $canvas->page_text(35, 794, $movimientoRegistral->folioReal->folio  .'-' . $movimientoRegistral->folio, null, 9, array(1, 1, 1));

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

        $pdf = Pdf::loadView('certificaciones.certificadoNegativo', compact('distrito', 'director', 'movimientoRegistral', 'persona'));

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 794, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(1, 1, 1));

        return $pdf->stream('documento.pdf');

    }

}
