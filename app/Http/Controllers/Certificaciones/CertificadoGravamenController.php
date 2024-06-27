<?php

namespace App\Http\Controllers\Certificaciones;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Predio;
use App\Models\Gravamen;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\MovimientoRegistral;
use Luecano\NumeroALetras\NumeroALetras;

class CertificadoGravamenController extends Controller
{

    public function certificadoGravamen(MovimientoRegistral $movimientoRegistral){

        /* $this->authorize('update', $movimientoRegistral); */

        $formatter = new NumeroALetras();

        $predio = Predio::where('folio_real', $movimientoRegistral->folio_real)->first();

        $gravamenes = Gravamen::with('deudores.persona', 'deudores.actor.persona',  'acreedores.persona', 'movimientoRegistral.folioReal')
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

        $pdf = Pdf::loadView('certificaciones.certificadoGravamen', compact('predio', 'director', 'movimientoRegistral', 'gravamenes', 'fecha', 'registro_numero', 'tomo_numero', 'formatter'));

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 794, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(1, 1, 1));



        return $pdf->stream('documento.pdf');

    }

}
