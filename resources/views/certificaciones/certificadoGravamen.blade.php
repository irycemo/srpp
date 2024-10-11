<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Certificado de gravamen</title>
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
<body >

    <header>

            <img class="encabezado" src="{{ public_path('storage/img/encabezado.png') }}" alt="encabezado">

    </header>

    <footer>

        <div class="fot">
            <p>www.irycem.michoacan.gob.mx</p>
        </div>

    </footer>

    <main>

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

        <div>

            <p class="titulo">CERTIFICADO DE GRAVAMEN O DE LIBERTAD DE GRAVAMEN</p>

        </div>

        <p class="parrafo informacion">
            EL CIUDADANO LICENCIADO EN DERECHO <strong>{{ $director }}</strong>, DIRECTOR DEL REGISTRO PÚBLICO DE LA PROPIEDAD
            CERTIFICA QUE DE ACUERDO A LA BUSQUEDA EN EL INMUEBLE:
        </p>

        <div class="informacion">

            <p style="text-align: center; margin:0;"><strong>FOLIO REAL:</strong> {{ $folioReal->folio }}</p>

            <p class="separador">Antecedente(s)</p>

            <p style="text-align: center; margin:0;"><strong>SECCIÓN:</strong> {{ $folioReal->seccion }}; <strong>DISTRITO:</strong> {{ $folioReal->distrito}}; <strong>TOMO:</strong> {{ $folioReal->tomo }}, <strong>REGISTRO:</strong> {{ $folioReal->registro }}, <strong>NÚMERO DE PROPIEDAD:</strong> {{ $folioReal->numero_propiedad }}.</p>

            <br>

            @include('comun.caratulas.ubicacion_inmueble')

            @if(count($predio->colindancias))

                @include('comun.caratulas.colindancias')

            @endif

            @include('comun.caratulas.descripcion_inmueble')

            @include('comun.caratulas.propietarios')

            <br>

            @if(count($gravamenes))

                <p><strong>REPORTA EL(LOS) SIGUIENTE(S) GRAVAMEN(ES):</strong></p>

                @foreach ($gravamenes as $gravamen)

                    <p class="parrafo">
                        <strong>movimiento registral: </strong>{{ $folioReal->folio }}-{{ $gravamen->movimiento_folio }}
                        <strong>Tomo: </strong>{{ $gravamen->tomo }}
                        <strong>Registro: </strong>{{ $gravamen->registro }}
                        <strong>Distrito: </strong>{{ $gravamen->distrito }};
                        CON <strong>FECHA DE INSCRIPCIÓN: </strong> {{ $gravamen->fecha_inscripcion }};
                        <strong>RELATIVO A: </strong> {{ $gravamen->acto_contenido }};
                        <strong>Tipo / Número de documento: </strong>{{ $gravamen->tipo }}/{{ $gravamen->numero_documento }}
                        <strong>Procedencia: </strong>{{ $gravamen->procedencia }}
                        <strong>Tipo: </strong>{{ $gravamen->tipo }};
                        <strong>CELEBRADO POR EL(LOS) ACREEDOR(ES): </strong>
                        @foreach ($gravamen->acreedores as $acreedor)

                            {{ $acreedor->nombre }} {{ $acreedor->ap_paterno }} {{ $acreedor->ap_materno }}{{ $acreedor->razon_social }}@if(!$loop->last), @endif

                        @endforeach
                        <strong> Y COMO DEUDOR(ES): </strong>
                        @foreach ($gravamen->deudores as $deudor)

                            {{ $deudor->nombre }} {{ $deudor->ap_paterno }} {{ $deudor->ap_materno }}{{ $deudor->razon_social }}@if(!$loop->last), @else; @endif

                        @endforeach
                        <strong>POR LA CANTIDAD DE: </strong> ${{ number_format($gravamen->valor_gravamen, 2) }} {{ $gravamen->valor_gravamen_letras }} {{ $gravamen->divisa }}.
                    </p>

                    <br>

                @endforeach

            @else

                <p class="parrafo">
                    <strong style="text-decoration: underline">no se encontro constancia de que reporte gravamen alguno.</strong>
                </p>

                <br>

            @endif

            @if($aviso)

                <p><strong>REPORTA un aviso preventivo:</strong></p>

                <p class="parrafo">
                    <strong>acto: </strong>{{ $aviso->acto_contenido }}
                </p>

                <p class="parrafo">
                    <strong>movimiento registral: </strong>{{ $folioReal->folio }}-{{ $aviso->movimiento_folio }}
                </p>

                <p class="parrafo">
                    <strong>descripción: </strong>{{ $aviso->descripcion }}
                </p>

                <br>

            @endif

            <p class="parrafo">
                A SOLICITUD DE: <strong>{{ $datos_control->solicitante }} </strong> se expide EL PRESENTE CERTIFICADO EN LA CIUDAD DE @if($folioReal->distrito== '02 Uruapan' ) URUAPAN, @else MORELIA, @endif MICHOACÁN, A LAS {{ $datos_control->elaborado_en }}.
            </p>

        </div>

        <div class="firma no-break">

            <p class="atte">
                <strong>A T E N T A M E N T E</strong>
            </p>

            @if(!$firma_electronica)

                @if($folioReal->distrito== '02 Uruapan' )
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

        <div class="informacion no-break">

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

    </main>

</body>
</html>
