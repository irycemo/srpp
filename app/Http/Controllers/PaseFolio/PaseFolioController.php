<?php

namespace App\Http\Controllers\PaseFolio;

use App\Models\File;
use App\Models\User;
use App\Models\FolioReal;
use Illuminate\Support\Str;
use App\Constantes\Constantes;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
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

        $firma = false;

        $pdf = Pdf::loadView('pasefolio.caratula', compact(
            'folioReal',
            'distrito',
            'registro_letras',
            'tomo_letras',
            'director',
            'predio',
            'firma'
        ));

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 745, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(1, 1, 1));

        $canvas->page_text(35, 745, "Folio real: " . $folioReal->folio , null, 10, array(1, 1, 1));

        $firma = true;

        $pdfFirmado = Pdf::loadView('pasefolio.caratula', compact(
            'folioReal',
            'distrito',
            'registro_letras',
            'tomo_letras',
            'director',
            'predio',
            'firma'
        ));

        $this->pdfFirmado($pdfFirmado, $folioReal);

        return $pdf->stream('documento.pdf');

    }

    public function pdfFirmado($pdf, FolioReal $folioReal){

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 745, "Página: {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(1, 1, 1));

        $canvas->page_text(35, 745, "Folio real: " . $folioReal->folio , null, 10, array(1, 1, 1));

        $nombreS3 = Str::random(40) . '.pdf';

        $nombreLocal = Str::random(40) . '.pdf';

        if(env('LOCAL') == "0"){

            Storage::disk('s3')->put($nombreS3, $pdf->output());

            File::create([
                'fileable_id' => $folioReal->id,
                'fileable_type' => 'App\Models\FolioReal',
                'descripcion' => 'caratula_s3',
                'url' => $nombreS3
            ]);

        }elseif(env('LOCAL') == "1"){

            Storage::disk('caratulas')->put($nombreLocal, $pdf->output());

            File::create([
                'fileable_id' => $folioReal->id,
                'fileable_type' => 'App\Models\FolioReal',
                'descripcion' => 'caratula',
                'url' => $nombreLocal
            ]);

        }elseif(env('LOCAL') == "2"){

            Storage::disk('s3')->put($nombreS3, $pdf->output());

            File::create([
                'fileable_id' => $folioReal->id,
                'fileable_type' => 'App\Models\FolioReal',
                'descripcion' => 'caratula_s3',
                'url' => $nombreS3
            ]);

            Storage::disk('caratulas')->put($nombreLocal, $pdf->output());

            File::create([
                'fileable_id' => $folioReal->id,
                'fileable_type' => 'App\Models\FolioReal',
                'descripcion' => 'caratula',
                'url' => $nombreLocal
            ]);

        }

    }

}
