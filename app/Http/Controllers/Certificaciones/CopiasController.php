<?php

namespace App\Http\Controllers\Certificaciones;

use App\Models\User;
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

        if($certificacion->movimientoRegistral->FolioReal){

            $folio_real = $certificacion->movimientoRegistral->FolioReal->folio;

            $folio_real_numero = $formatter->toWords($folio_real);

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
                'servicio'
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
                'servicio'
            ));

        }

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
            'servicio'
        ));

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
