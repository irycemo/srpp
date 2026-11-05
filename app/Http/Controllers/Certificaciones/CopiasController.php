<?php

namespace App\Http\Controllers\Certificaciones;

use App\Models\User;
use App\Models\FirmaElectronica;
use Illuminate\Support\Facades\Storage;
use PhpCfdi\Credentials\Credential;
use Illuminate\Support\Str;
use App\Models\Certificacion;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use App\Http\Controllers\Controller;
use App\Traits\NombreServicioTrait;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Luecano\NumeroALetras\NumeroALetras;

class CopiasController extends Controller
{

    use NombreServicioTrait;

    public function copiaCertificada(Certificacion $certificacion){

        $certificacion->load('movimientoRegistral');

        $formatter = new NumeroALetras();

        $director = Str::upper(User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Director');
        })->first()->name);

        if($certificacion->folio_real){

            $folio_real = $certificacion->folio_real;

            $folio_real_letra = $formatter->toWords($folio_real);

        }else{

            $folio_real = null;

            $folio_real_letra = null;

        }

        if($certificacion->movimiento_registral){

            $movimiento_registral = $certificacion->movimiento_registral;

            $movimiento_registral_letra = $formatter->toWords($movimiento_registral);

        }else{

            $movimiento_registral = null;

            $movimiento_registral_letra = null;

        }

        $distrito = Str::upper($certificacion->movimientoRegistral->distrito);

        $registro = $certificacion->movimientoRegistral->registro;

        $registro_bis = $certificacion->movimientoRegistral->registro_bis;

        $registro_letras = $formatter->toWords($registro);

        $tomo = $certificacion->movimientoRegistral->tomo;

        $tomo_bis = $certificacion->movimientoRegistral->tomo_bis;

        $tomo_letras = $formatter->toWords($tomo);

        $paginas = $certificacion->numero_paginas;

        $paginas_letras = $formatter->toWords($paginas);

        $solicitante = Str::upper($certificacion->movimientoRegistral->solicitante);

        $now = now()->locale('es');

        $hora = $now->format('H');

        $hora_letras = $formatter->toWords($hora);

        $minutos = $now->format('i');

        $minutos_letras = $formatter->toWords($minutos);

        $dia = $now->format('d');

        $dia_letras = $formatter->toWords($dia);

        $mes = Str::upper($now->monthName);

        $año = $now->format('Y');

        $año_letras = $formatter->toWords($año);

        $numero_control = $certificacion->movimientoRegistral->año . '-' .$certificacion->movimientoRegistral->tramite . '-' .$certificacion->movimientoRegistral->usuario;

        $superviso = Str::upper($certificacion->movimientoRegistral->supervisor->name);

        $elaboro = Str::upper($certificacion->movimientoRegistral->asignadoA->name);

        $folio_carpeta = $certificacion->folio_carpeta_copias;

        $derechos = $certificacion->movimientoRegistral->monto;

        $fecha_entrega = $certificacion->movimientoRegistral->fecha_entrega;

        $tipo_servicio = Str::upper($certificacion->movimientoRegistral->tipo_servicio);

        $seccion = Str::upper($certificacion->movimientoRegistral->seccion);

        $qr = $this->generadorQr();

        $numero_oficio = $certificacion->movimientoRegistral->numero_oficio;

        $servicio = $this->nombreServicio($certificacion->movimientoRegistral->año, $certificacion->movimientoRegistral->tramite, $certificacion->movimientoRegistral->usuario);

        if(auth()->user()->hasRole(['Certificador Oficialia', 'Certificador Juridico'])){

            $pdf = Pdf::loadView('certificaciones.copiaCertificadaOficialia', compact(
                'distrito',
                'director',
                'registro_letras',
                'registro',
                'registro_bis',
                'tomo',
                'tomo_bis',
                'tomo_letras',
                'paginas',
                'paginas_letras',
                'solicitante',
                'hora',
                'hora_letras',
                'minutos',
                'minutos_letras',
                'dia',
                'dia_letras',
                'año',
                'año_letras',
                'mes',
                'numero_control',
                'numero_oficio',
                'superviso',
                'elaboro',
                'folio_carpeta',
                'derechos',
                'fecha_entrega',
                'tipo_servicio',
                'seccion',
                'qr',
                'servicio',
                'folio_real',
                'folio_real_letra',
                'movimiento_registral',
                'movimiento_registral_letra',
            ));

        }else{

            $pdf = Pdf::loadView('certificaciones.copiaCertificada', compact(
                'distrito',
                'director',
                'registro_letras',
                'registro',
                'registro_bis',
                'tomo',
                'tomo_bis',
                'tomo_letras',
                'paginas',
                'paginas_letras',
                'solicitante',
                'hora',
                'hora_letras',
                'minutos',
                'minutos_letras',
                'dia',
                'dia_letras',
                'año',
                'año_letras',
                'mes',
                'numero_control',
                'superviso',
                'elaboro',
                'folio_carpeta',
                'derechos',
                'fecha_entrega',
                'tipo_servicio',
                'seccion',
                'qr',
                'servicio',
                'folio_real',
                'folio_real_letra',
                'movimiento_registral',
                'movimiento_registral_letra',
            ));

        }

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 745, "Página: {PAGE_NUM} de {PAGE_COUNT}", $dom_pdf->getFontMetrics()->getFont('helvetica'), 9, array(1,1,1));

        return $pdf->stream('documento.pdf');

    }

    public function copiaSimple(Certificacion $certificacion){

        $certificacion->load('movimientoRegistral');

        $formatter = new NumeroALetras();

        $director = Str::upper(User::where('status', 'activo')->whereHas('roles', function($q){
            $q->where('name', 'Director');
        })->first()->name);

        $distrito = Str::upper($certificacion->movimientoRegistral->distrito);

        $registro = $certificacion->movimientoRegistral->registro;

        $registro_bis = $certificacion->movimientoRegistral->registro_bis;

        $registro_letras = $formatter->toWords($registro);

        $tomo = $certificacion->movimientoRegistral->tomo;

        $tomo_bis = $certificacion->movimientoRegistral->tomo_bis;

        $tomo_letras = $formatter->toWords($tomo);

        $paginas = $certificacion->numero_paginas;

        $paginas_letras = $formatter->toWords($paginas);

        $solicitante = Str::upper($certificacion->movimientoRegistral->solicitante);

        $now = now()->locale('es');

        $hora = $now->format('H');

        $hora_letras = $formatter->toWords($hora);

        $minutos = $now->format('i');

        $minutos_letras = $formatter->toWords($minutos);

        $dia = $now->format('d');

        $dia_letras = $formatter->toWords($dia);

        $mes = Str::upper($now->monthName);

        $año = $now->format('Y');

        $año_letras = $formatter->toWords($año);

        if($certificacion->folio_real){

            $folio_real = $certificacion->folio_real;

            $folio_real_letra = $formatter->toWords($folio_real);

        }else{

            $folio_real = null;

            $folio_real_letra = null;

        }

        if($certificacion->movimiento_registral){

            $movimiento_registral = $certificacion->movimiento_registral;

            $movimiento_registral_letra = $formatter->toWords($movimiento_registral);

        }else{

            $movimiento_registral = null;

            $movimiento_registral_letra = null;

        }

        $numero_control = $certificacion->movimientoRegistral->año . '-' .$certificacion->movimientoRegistral->tramite . '-' .$certificacion->movimientoRegistral->usuario;

        $superviso = Str::upper($certificacion->movimientoRegistral->supervisor->name);

        $elaboro = Str::upper($certificacion->movimientoRegistral->asignadoA->name);

        $folio_carpeta = $certificacion->folio_carpeta_copias;

        $derechos = $certificacion->movimientoRegistral->monto;

        $fecha_entrega = $certificacion->movimientoRegistral->fecha_entrega;

        $tipo_servicio = Str::upper($certificacion->movimientoRegistral->tipo_servicio);


        $seccion = Str::upper($certificacion->movimientoRegistral->seccion);

        $qr = $this->generadorQr();

        $servicio = $this->nombreServicio($certificacion->movimientoRegistral->año, $certificacion->movimientoRegistral->tramite, $certificacion->movimientoRegistral->usuario);

        $pdf = Pdf::loadView('certificaciones.copiaSimple', compact(
            'distrito',
            'director',
            'registro_letras',
            'registro',
            'registro_bis',
            'tomo',
            'tomo_bis',
            'tomo_letras',
            'paginas',
            'paginas_letras',
            'solicitante',
            'hora',
            'hora_letras',
            'minutos',
            'minutos_letras',
            'dia',
            'dia_letras',
            'año',
            'año_letras',
            'mes',
            'numero_control',
            'superviso',
            'elaboro',
            'folio_carpeta',
            'derechos',
            'fecha_entrega',
            'tipo_servicio',
            'seccion',
            'qr',
            'servicio',
            'folio_real',
            'folio_real_letra',
            'movimiento_registral',
            'movimiento_registral_letra',
        ));

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 745, "Página: {PAGE_NUM} de {PAGE_COUNT}", $dom_pdf->getFontMetrics()->getFont('helvetica'), 9, array(1,1,1));

        return $pdf->stream('documento.pdf');

    }

    public function copiaCertificadaFirmada(Certificacion $certificacion){

        $certificacion->load('movimientoRegistral');

        $formatter = new NumeroALetras();

        $object = (object)[];

        $director = User::where('status', 'activo')->whereHas('roles', function($q){
                                    $q->where('name', 'Director');
                                })->first();

        $object->director = $director->name;

        if($certificacion->folio_real){

            $object->folio_real = $certificacion->folio_real;

            $object->folio_real_letra = $formatter->toWords($object->folio_real);

        }else{

            $object->folio_real = null;

            $object->folio_real_letra = null;

        }

        if($certificacion->movimiento_registral){

            $object->movimiento_registral = $certificacion->movimiento_registral;

            $object->movimiento_registral_letra = $formatter->toWords($object->movimiento_registral);

        }else{

            $object->movimiento_registral = null;

            $object->movimiento_registral_letra = null;

        }

        $object->distrito = Str::upper($certificacion->movimientoRegistral->distrito);

        $object->registro = $certificacion->movimientoRegistral->registro;

        $object->registro_bis = $certificacion->movimientoRegistral->registro_bis;

        $object->registro_letras = $formatter->toWords($object->registro);

        $object->tomo = $certificacion->movimientoRegistral->tomo;

        $object->tomo_bis = $certificacion->movimientoRegistral->tomo_bis;

        $object->tomo_letras = $formatter->toWords($object->tomo);

        $object->paginas = $certificacion->numero_paginas;

        $object->paginas_letras = $formatter->toWords($object->paginas);

        $object->solicitante = Str::upper($certificacion->movimientoRegistral->solicitante);

        $now = now()->locale('es');

        $object->hora = $now->format('H');

        $object->hora_letras = $formatter->toWords($object->hora);

        $object->minutos = $now->format('i');

        $object->minutos_letras = $formatter->toWords($object->minutos);

        $object->dia = $now->format('d');

        $object->dia_letras = $formatter->toWords($object->dia);

        $object->mes = Str::upper($now->monthName);

        $object->año = $now->format('Y');

        $object->año_letras = $formatter->toWords($object->año);

        $object->numero_control = $certificacion->movimientoRegistral->año . '-' .$certificacion->movimientoRegistral->tramite . '-' .$certificacion->movimientoRegistral->usuario;

        $object->superviso = Str::upper($certificacion->movimientoRegistral->supervisor->name);

        $object->elaboro = Str::upper($certificacion->movimientoRegistral->asignadoA->name);

        $object->folio_carpeta = $certificacion->folio_carpeta_copias;

        $object->derechos = $certificacion->movimientoRegistral->monto;

        $object->fecha_entrega = $certificacion->movimientoRegistral->fecha_entrega;

        $object->tipo_servicio = Str::upper($certificacion->movimientoRegistral->tipo_servicio);

        $object->seccion = Str::upper($certificacion->movimientoRegistral->seccion);

        $qr = $this->generadorQr();

        $object->numero_oficio = $certificacion->movimientoRegistral->numero_oficio;

        $object->servicio = $this->nombreServicio($certificacion->movimientoRegistral->año, $certificacion->movimientoRegistral->tramite, $certificacion->movimientoRegistral->usuario);

        $fielDirector = Credential::openFiles(Storage::disk('efirmas')->path($director->efirma->cer),
                                                Storage::disk('efirmas')->path($director->efirma->key),
                                                $director->efirma->contraseña
                                            );

        $firmaDirector = $fielDirector->sign(json_encode($object));

        FirmaElectronica::where('movimiento_registral_id', $certificacion->movimientoRegistral)->first()?->delete();

        FirmaElectronica::create([
            'movimiento_registral_id' => $certificacion->movimiento_registral_id,
            'cadena_original' => json_encode($object),
            'cadena_encriptada' => base64_encode($firmaDirector),
            'estado' => 'activo'
        ]);

        $pdf = Pdf::loadView('certificaciones.copiaCertificadaFirmada', [
            'datos_control' => $object,
            'firma_electronica' => base64_encode($firmaDirector),
            'qr' => $qr
        ]);

        $pdf->render();

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(480, 745, "Página: {PAGE_NUM} de {PAGE_COUNT}", $dom_pdf->getFontMetrics()->getFont('helvetica'), 9, array(1,1,1));

        return $pdf->stream('documento.pdf');

    }

    public function generadorQr(){

        $result = Builder::create()
                            ->writer(new PngWriter())
                            ->writerOptions([])
                            ->data('https://irycem.michoacan.gob.mx/')
                            ->encoding(new Encoding('UTF-8'))
                            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
                            ->size(100)
                            ->margin(0)
                            ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
                            ->labelText('Escanea para verificar')
                            ->labelFont(new NotoSans(7))
                            ->labelAlignment(LabelAlignment::Center)
                            ->validateResult(false)
                            ->build();

        return $result->getDataUri();
    }

}
