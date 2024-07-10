<?php

namespace App\Http\Controllers\PaseFolio;

use App\Models\User;
use App\Models\FolioReal;
use Illuminate\Support\Str;
use App\Constantes\Constantes;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Luecano\NumeroALetras\NumeroALetras;

class PaseFolioController extends Controller
{

    public function caratula(FolioReal $folioReal){

        $folioReal->load('predio', 'antecedentes', 'gravamenes.deudores.persona', 'gravamenes.deudores.actor.persona', 'gravamenes.acreedores.persona', 'gravamenes.movimientoRegistral', 'sentencias.movimientoRegistral', 'varios.movimientoRegistral', 'cancelaciones.movimientoRegistral');

        $formatter = new NumeroALetras();

        $director = Str::upper(User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Director');
        })->first()->name);

        $distrito = Constantes::DISTRITOS[$folioReal->distrito_antecedente];

        $registro_letras = $formatter->toWords($folioReal->registro_antecedente);

        $tomo_letras = $formatter->toWords($folioReal->tomo_antecedente);

        $superficie_terreno = $formatter->toWords($folioReal->predio->superficie_terreno);

        $superficie_construccion = $formatter->toWords($folioReal->predio->superficie_construccion);

        $monto_transaccion = $formatter->toWords($folioReal->predio->monto_transaccion);

        $pdf = Pdf::loadView('pasefolio.caratula', compact(
            'folioReal',
            'distrito',
            'registro_letras',
            'tomo_letras',
            'director',
            'superficie_terreno',
            'superficie_construccion',
            'monto_transaccion',
        ));

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 794, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(1, 1, 1));

        $canvas->page_text(35, 794, "Folio real: " . $folioReal->folio , null, 10, array(1, 1, 1));

        return $pdf->stream('documento.pdf');

    }

}
