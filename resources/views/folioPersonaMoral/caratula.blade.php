<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Carátula de pase a folio de persona moral</title>
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
        margin-top: 100px;
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
                    <p style="margin:0"><strong>DISTRITO:</strong> {{ $datos_control->distrito }}</p>
                </div>

                <div>

                    <p class="titulo">CARATULA DE ASIGNACION DE FOLIO REAL DE PERSONA MORAL</p>

                </div>

                <p class="parrafo">
                    EL DIRECTOR DEL REGISTRO PÚBLICO DE LA PROPIEDAD <strong> {{ $datos_control->director }}</strong>, AUTORIZA EL PRESENTE FOLIO REAL DE PERSONA MORAL:
                </p>

                <p style="text-align: center" class="titulo"><strong>FOLIO REAL: PM-</strong>{{ $folioReal->folio }}</p>

                <p class="separador">Documento de entrada</p>

                <p class="parrafo">
                    <strong>Tipo de documento: </strong> escritura pública; <strong>Número de escritura: </strong> {{ $folioReal->escritura->numero }}; <strong>Número de notaria: </strong> {{ $folioReal->escritura->notaria }}; <strong>Nombre del notario: </strong> {{ $folioReal->escritura->nombre_notario }}; <strong>Estado del notario: </strong> {{ $folioReal->escritura->estado_notario }}; <strong>Fecha de inscripción: </strong> {{ $folioReal->escritura->fecha_inscripcion }}; <strong>Fecha de la escritura: </strong> {{ $folioReal->escritura->fecha_escritura }}; <strong>Número de hojas: </strong>{{ $folioReal->escritura->numero_hojas }}; <strong>Número de paginas: </strong>{{ $folioReal->escritura->numero_paginas }}
                </p>

                @include('comun.caratulas.folio_persona_moral')

                <div class="firma no-break">

                    <p class="atte">
                        <strong>A T E N T A M E N T E</strong>
                    </p>

                    @if(!$firma_electronica)

                        @if($datos_control->distrito == '02 Uruapan' )
                            <p style="margin-top: 80px;"></p>
                            <p class="borde">Lic. SANDRO MEDINA MORALES </p>
                            <p style="margin:0;">COORDINADOR REGIONAL 4 PURHÉPECHA (URUAPAN)</p>
                        @else
                            <p style="margin-top: 80px;"></p>
                            <p class="borde" style="margin:0;">{{ $datos_control->director }}</p>
                            <p style="margin:0;">Director del registro público de la propiedad</p>
                        @endif

                    @else

                        <p style="margin:0;">{{ $datos_control->director }}</p>
                        <p style="margin:0;">Director del registro público de la propiedad</p>
                        <p style="text-align: center">Firma Electrónica:</p>
                        <p class="parrafo" style="overflow-wrap: break-word;">{{ $firma_electronica }}</p>

                    @endif

                </div>

                <p class="separador">datos de control</p>

                <table style="margin-top: 10px">

                    <tbody>
                        <tr>
                            <td style="padding-right: 40px;">

                                <img class="qr" src="{{ $qr }}" alt="QR">
                            </td>
                            <td style="padding-right: 40px;">

                                <p><strong>folio Asignado por:</strong> {{ $folioReal->asignado_por }}.</p>
                                <p><strong>Fecha de asignación de folio:</strong> {{ $datos_control->fecha_asignacion }}.</p>
                                @if(isset($datos_control->numero_control))
                                    <p><strong>número de control:</strong> {{ $datos_control->numero_control }}.</p>
                                @else

                                @endif

                            </td>
                        </tr>
                    </tbody>

                </table>

            </div>

        </div>

    </main>

</body>
</html>
