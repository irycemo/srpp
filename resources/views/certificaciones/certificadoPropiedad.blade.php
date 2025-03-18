<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Certificado de propiedad</title>
</head>
<style>
    header{
        position: fixed;
        top: 0cm;
        left: 0cm;
        right: 0cm;
        height: 100px;
        text-align: center;
    }

    header img{
        height: 100px;
        display: block;
        margin-left: auto;
        margin-right: auto;
    }


    body{
        margin-top: 120px;
        margin-bottom: 40px;
        counter-reset: page;
        height: 100%;
        background-image: url("storage/img/escudo_fondo.png");
        background-size: cover;
        background-position: 0 -50px !important;
        font-family: sans-serif;
        font-weight: normal;
        line-height: 1.5;
        text-transform: uppercase;
        font-size: 9px;
    }

    .center{
        display: block;
        margin-left: auto;
        margin-right: auto;
        width: 50%;
    }

    .container{
        display: flex;
        align-content: space-around;
    }

    .parrafo{
        text-align: justify;
    }

    .firma{
        text-align: center;
    }

    .control{
        text-align: center;
    }

    .atte{
        margin-bottom: 10px;
    }

    .borde{
        display: inline;
        border-top: 1px solid;
    }

    .tabla{
        width: 100%;
        font-size: 10px;
        margin-bottom: 30px;;
        margin-left: auto;
        margin-right: auto;
    }

    footer{
        position: fixed;
        bottom: 0cm;
        left: 0cm;
        right: 0cm;
        background: #5E1D45;
        color: white;
        font-size: 12px;
        text-align: right;
        padding-right: 10px;
        text-transform: lowercase;
    }

    .fot{
        display: flex;
        padding: 2px;
        text-align: center;
    }

    .fot p{
        display: inline-block;
        width: 33%;
        margin: 0;
    }

    .qr{
        display: block;
    }

    .no-break{
        page-break-inside: avoid;
    }

    table{
        margin-bottom: 5px;
        margin-left: auto;
        margin-right: auto;
    }

    .separador{
        text-align: justify;
        border-bottom: 1px solid black;
        padding: 0 20px 0 20px;
        border-radius: 25px;
        border-color: gray;
        letter-spacing: 5px;
        margin: 0 0 5px 0;
    }

    .titulo{
        text-align: center;
        font-size: 13px;
        font-weight: bold;
        margin: 0;
    }

</style>
<body>

    <header>

        <img src="{{ public_path('storage/img/encabezado.png') }}" alt="encabezado">

    </header>

    <footer>

        <div class="fot">
            <p>www.irycem.michoacan.gob.mx</p>
        </div>

    </footer>

    <main>

        <div class="container">

            <div>

                <div style="text-align: center; font-weight: bold; font-size: 11px;">
                    <p style="margin: 0">GOBIERNO DEL ESTADO DE MICHOACÁN DE OCAMPO</p>
                    <P style="margin: 0">SECRETARÍA DE FINANZAS Y ADMINISTRACIÓN</P>
                    <P style="margin: 0">INSTITUTO REGISTRAL Y CATASTRAL DEL ESTADO DE MICHOACÁN</P>
                    <P style="margin: 0">DIRECCIÓN DEL REGISTRO PÚBLICO  DE LA PROPIEDAD</P>
                </div>

                <div style="text-align: right">
                    <p style="margin:0;"><strong>Movimiento registral:</strong> {{ $folioReal->folio }}-{{ $datos_control->movimiento_folio }}</p>
                    <p style="margin:0;"><strong>DISTRITO:</strong> {{ $folioReal->distrito}}</p>
                </div>

                <p class="titulo">
                    certificado de propiedad
                </p>

                <p class="parrafo">
                    EL DIRECTOR DEL REGISTRO PÚBLICO DE LA PROPIEDAD @if($folioReal->distrito == '02 Uruapan' ) <strong>L.A. SANDRO MEDINA MORALES</strong> @else <strong>{{ $director }}</strong>, @endif certifica que habiendose examinado el acervo registral correspondiente al distrito de {{ $folioReal->distrito}}, en el periodo de 1977 un mil novecientos setenta y siete a la fecha, se encontro registro de la siguiente propiedad:
                </p>

                <p style="text-align: center"><strong>FOLIO REAL - I:</strong> {{ $folioReal->folio }}</p>

                <p style="text-align: center"><strong>SECCIÓN:</strong> {{ $folioReal->seccion }}; <strong>DISTRITO:</strong> {{ $folioReal->distrito}}; <strong>TOMO:</strong> {{ $folioReal->tomo }}, <strong>REGISTRO:</strong> {{ $folioReal->registro }}, <strong>NÚMERO DE PROPIEDAD:</strong> {{ $folioReal->numero_propiedad }}</p>

                @include('comun.caratulas.ubicacion_inmueble')

                @include('comun.caratulas.descripcion_inmueble')

                @include('comun.caratulas.colindancias')

                @include('comun.caratulas.propietarios')

                @if(isset($datos_control->observaciones_certificado))

                    <p class="parrafo">
                        <strong>Observaciones del certificado:</strong> {{ $datos_control->observaciones_certificado }}
                    </p>

                @endif

                <p class="parrafo">
                    A SOLICITUD DE: <strong>{{ $datos_control->solicitante }}</strong> se expide EL PRESENTE CERTIFICADO EN LA CIUDAD DE @if($folioReal->distrito== '02 Uruapan' ) URUAPAN, @else MORELIA, @endif MICHOACÁN, A LAS {{ $datos_control->elaborado_en }}.
                </p>

                <div class="firma no-break">

                    <p class="atte">
                        <strong>A T E N T A M E N T E</strong>
                    </p>

                    @if(!$firma_electronica)

                        @if($folioReal->distrito == '02 Uruapan' )
                            <p style="margin-top: 80px;"></p>
                            <p class="borde">Lic. SANDRO MEDINA MORALES </p>
                            <p style="margin:0;">COORDINADOR REGIONAL 4 PURHÉPECHA (URUAPAN)</p>
                        @else
                            <p style="margin-top: 80px;"></p>
                            <p class="borde" style="margin:0;">{{ $director }}</p>
                            <p style="margin:0;">Director del registro público de la propiedad</p>
                        @endif

                    @else

                        <p style="margin:0;">{{ $director }}</p>
                        <p style="margin:0;">Director del registro público de la propiedad</p>
                        <p style="text-align: center">Firma Electrónica:</p>
                        <p class="parrafo" style="overflow-wrap: break-word;">{{ $firma_electronica }}</p>

                    @endif

                </div>

                <div class="informacion">

                    <div class="control no-break">

                        <p class="separador">DATOS DE CONTROL</p>

                        <table style="margin-top: 10px">

                            <tbody>
                                <tr>
                                    <td style="padding-right: 40px;">

                                        <img class="qr" src="{{ $qr }}" alt="QR">

                                    </td>
                                    <td style="padding-right: 40px;">

                                        @if($datos_control->numero_control)

                                            <p style="margin: 0"><strong>NÚMERO DE CONTROL: </strong>{{ $datos_control->numero_control }}</p>

                                        @else

                                            <p style="margin: 0"><strong>NÚMERO DE CONTROL: </strong>{{ $movimientoRegistral->certificacion->observaciones }}</p>

                                        @endif
                                        <p style="margin: 0"><strong>DERECHOS: </strong>${{ number_format($datos_control->monto, 2) }}</p>
                                        <p style="margin: 0"><strong>Tipo de servicio: </strong>{{ $datos_control->tipo_servicio }}</p>
                                        <p style="margin: 0"><strong>Servicio: </strong>{{ $datos_control->servicio }}</p>
                                        <p style="margin: 0"><strong>Elaborado en: </strong>{{ $datos_control->elaborado_en }}</p>
                                        <p style="margin: 0"><strong>Verificado POR: </strong>{{  $datos_control->verificado_por }}</p>
                                        <p style="margin: 0"><strong>Movimiento registral:</strong> {{ $folioReal->folio }}-{{ $datos_control->movimiento_folio }}</p>
                                        <p style="margin: 0"><strong>Folio real asignado por:</strong> {{ $datos_control->asigno_folio }}</p>

                                    </td>
                                </tr>
                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </main>

</body>
</html>
