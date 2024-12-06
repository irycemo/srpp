<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cancelación</title>
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

            <div style="text-align: center; font-weight: bold; font-size: 11px;">
                <p style="margin: 0">GOBIERNO DEL ESTADO DE MICHOACÁN DE OCAMPO</p>
                <P style="margin: 0">SECRETARÍA DE FINANZAS Y ADMINISTRACIÓN</P>
                <P style="margin: 0">INSTITUTO REGISTRAL Y CATASTRAL DEL ESTADO DE MICHOACÁN</P>
                <P style="margin: 0">DIRECCIÓN DEL REGISTRO PÚBLICO  DE LA PROPIEDAD</P>
            </div>

            <div class="informacion">

                <div style="text-align: right">
                    <p style="margin:0;"><strong>Movimiento registral:</strong>{{ $datos_control->folioReal }}-{{ $datos_control->movimiento_folio }}</p>
                    <p style="margin:0;"><strong>DISTRITO:</strong> {{ $datos_control->distrito}}</p>
                </div>

                <div class="titulo">
                    <p><strong>{{ $cancelacion->acto_contenido }}</strong></p>
                </div>

                <p class="separador">Descripción del acto</p>

                <p class="parrafo">
                    {{ $cancelacion->observaciones }}
                </p>

                <p class="separador">Documento de entrada</p>

                <p class="parrafo">
                    <strong>Tipo de documento: </strong> {{ $cancelacion->tipo_documento }}; @if(isset($cancelacion->numero_documento))<strong>Número de documento: </strong> {{ $cancelacion->numero_documento }};@endif <strong>Cargo de la autoridad: </strong> {{ $cancelacion->autoridad_cargo }}; <strong>Nombre de la autoridad: </strong> {{ $cancelacion->autoridad_nombre }}; <strong>Número de la autoridad: </strong> {{ $cancelacion->autoridad_numero }}; <strong>Fecha de emisión: </strong> {{ $cancelacion->fecha_emision }}; @if(isset($cancelacion->fecha_inscripcion))<strong>Fecha de inscripción: </strong> {{$cancelacion->fecha_inscripcion }};@endif @if(isset($cancelacion->procedencia))<strong>Dependencia: </strong>{{ $cancelacion->procedencia }} @endif
                </p>

                @include('comun.caratulas.ubicacion_inmueble')

                @if(count($predio->colindancias))

                    @include('comun.caratulas.colindancias')

                @endif

                @include('comun.caratulas.descripcion_inmueble')

                @include('comun.caratulas.propietarios')

                <p class="separador">datos del gravamen afectado</p>

                <p class="parrafo">
                    <strong>Folio:</strong> {{ $datos_control->folioReal . '-' . $cancelacion->gravamen->movimiento_folio }}
                    <strong>Fecha de inscripción:</strong> {{ $cancelacion->gravamen->fecha_inscripcion }}
                    <strong>Valor del gravamen:</strong> ${{ number_format($cancelacion->gravamen->valor_gravamen, 2) }} {{ $cancelacion->gravamen->divisa }}
                </p>

                <p class="parrafo">
                    <strong>Acto contenido:</strong>{{ $cancelacion->gravamen->acto_contenido }}.
                    <strong>Tipo:</strong>{{ $cancelacion->gravamen->tipo }}
                </p>

                <p class="parrafo">
                    <strong>Descripción del gravamen: </strong>{{ $cancelacion->gravamen->observaciones }}
                </p>

            </div>

            <p class="parrafo">
                A SOLICITUD DE: <strong>{{ $datos_control->solicitante }}</strong> Ese EXPiDe EL PRESENTE EN LA CIUDAD DE @if($datos_control->distrito == '02 Uruapan' ) uruapan @else MORELIA @endif, MICHOACÁN, A LAS {{ $datos_control->elaborado_en }}.
            </p>

            <div class="firma no-break">

                <p class="atte">
                    <strong>A T E N T A M E N T E</strong>
                </p>

                @if(!$firma_electronica)

                    @if($datos_control->distrito== '02 Uruapan' )
                        <p class="borde">Lic. SANDRO MEDINA MORALES </p>
                        <p style="margin:0;">coordinador regional 4 purepecha</p>
                    @else
                        <p class="borde" style="margin:0;">{{ $datos_control->director }}</p>
                        <p style="margin:0;">Director del registro público de la propiedad</p>
                    @endif

                    <div style="margin-top: 50px;">

                        <table class="tabla" >
                            <tbody sty>
                                <tr>
                                    <td style="padding-right: 40px; text-align:center; width: 50%; vertical-align: bottom; white-space: nowrap;">

                                        <p class="borde">{{ $datos_control->registrado_por }}</p>
                                        <p style="margin: 0">REGISTRADOR</p>

                                    </td>

                                    @if($datos_control->distrito != '02 Uruapan')

                                        <td style="padding-right: 40px; text-align:center; width: 50%; vertical-align: bottom; white-space: nowrap;">

                                            <p class="borde">{{ $datos_control->jefe_departamento }}</p>
                                            <p style="margin: 0">JEFE DE Departamento de Registro de Inscripciones	</p>
                                        </td>

                                    @endif

                                </tr>
                            </tbody>
                        </table>

                    </div>

                @else

                    <p style="margin:0;">{{ $datos_control->director }}</p>
                    <p style="margin:0;">Director del registro público de la propiedad</p>
                    <p style="text-align: center">Firma Electrónica:</p>
                    <p class="parrafo" style="overflow-wrap: break-word;">{{ $firma_electronica }}</p>

                    <p >{{ $datos_control->registrado_por }}</p>
                    <p class="borde" style="margin: 0">REGISTRADOR</p>

                    <p >{{ $datos_control->jefe_departamento }}</p>
                    <p class="borde" style="margin: 0">JEFE DE Departamento de Registro de Inscripciones</p>

                @endif

            </div>

            <div class="control no-break">

                <p class="separador">DATOS DE CONTROL</p>

                <table style="margin-top: 10px">

                    <tbody>
                        <tr>
                            <td style="padding-right: 40px;">

                                <img class="qr" src="{{ $qr }}" alt="QR">

                            </td>
                            <td style="padding-right: 40px;">

                                <p style="margin: 0"><strong>NÚMERO DE CONTROL: </strong>{{ $datos_control->numero_control }}</p>
                                <p style="margin: 0"><strong>Movimiento registral:</strong> {{ $datos_control->folioReal }}-{{ $datos_control->movimiento_folio }}</p>
                                <p style="margin: 0"><strong>DERECHOS: </strong>${{ number_format($datos_control->monto, 2) }}</p>
                                <p style="margin: 0"><strong>Tipo de servicio: </strong>{{ $datos_control->tipo_servicio }}</p>
                                <p style="margin: 0"><strong>Servicio: </strong>{{ $datos_control->servicio }}</p>
                                <p style="margin: 0"><strong>Elaborado en: </strong>{{ $datos_control->elaborado_en }}</p>
                                <p style="margin: 0"><strong>Fecha de prelación: </strong>{{ $cancelacion->fecha_prelacion }}</p>
                                <p style="margin: 0"><strong>Registrado POR: </strong>{{  $datos_control->registrado_por }}</p>
                                <p style="margin: 0"><strong>Folio real asignado por:</strong> {{ $datos_control->asigno_folio }}</p>

                            </td>
                        </tr>
                    </tbody>

                </table>

            </div>

        </div>

    </main>
</body>
</html>
