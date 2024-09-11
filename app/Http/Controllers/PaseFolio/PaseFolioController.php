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

        $folioReal->load(
            'predio',
            'antecedentes',
            'gravamenes.deudores',
            'gravamenes.acreedores',
            'gravamenes.movimientoRegistral',
            'sentencias.movimientoRegistral',
            'varios.movimientoRegistral',
            'cancelaciones.movimientoRegistral'
        );

        $formatter = new NumeroALetras();

        $director = Str::upper(User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Director');
        })->first()->name);

        $distrito = Constantes::DISTRITOS[$folioReal->distrito_antecedente];

        $registro_letras = $formatter->toWords($folioReal->registro_antecedente);

        $tomo_letras = $formatter->toWords($folioReal->tomo_antecedente);

        $predio = $folioReal->predio;

        $pdf = Pdf::loadView('pasefolio.caratula', compact(
            'folioReal',
            'distrito',
            'registro_letras',
            'tomo_letras',
            'director',
            'predio'
        ));

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 745, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(1, 1, 1));

        $canvas->page_text(35, 745, "Folio real: " . $folioReal->folio , null, 10, array(1, 1, 1));

        return $pdf->stream('documento.pdf');

    }

}
