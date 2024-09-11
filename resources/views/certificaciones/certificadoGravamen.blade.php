<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Certificado de gravamen</title>
</head>
<style>

    /* @page {
        margin: 0cm 0cm;
    } */


    header{
        position: fixed;
        top: 0cm;
        left: 0cm;
        right: 0cm;
        height: 100px;
        text-align: center;
    }

    .encabezado{
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

    .separador{
        text-align: justify;
        border-bottom: 1px solid black;
        padding: 0 20px 0 20px;
        border-radius: 25px;
        border-color: gray;
        letter-spacing: 5px;
        margin: 0 0 5px 0;
    }

    .parrafo{
        text-align: justify;
    }

    .titulo{
        text-align: center;
        font-size: 11px;
        font-weight: bold;
        margin: 0;
    }

    .fundamento{
        font-weight: bold;
        text-align: center;
        font-size: 9px;
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

    .informacion{
        padding: 0 20px 0 20px;
        margin-bottom: 10px;
    }

    .informacion p{
        margin: 0;
    }

    table{
        margin-bottom: 5px;
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
        text-transform: lowercase;
    }

    .fot{
        padding: 2px;
    }

    .fot p{
        text-align: center;
        margin: 0;
        margin-left: 10px;
    }

    .firma{
        text-align: center;
    }

    .control{
        margin-top: 20px;
        text-align: center;
    }

    .qr{
        display: block;
    }

    .caracteristicas-tabla{
        page-break-inside: avoid;
    }

    .totales{
        flex: auto;
    }

    .imagenes{
        width: 200px;
    }

    .borde{
        display: inline;
        border-top: 1px solid;
    }

    .atte{
        margin-bottom: 40px;
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
            <p style="margin:0;"><strong>Movimiento registral:</strong> {{ $predio->folioReal->folio }}-{{ $movimientoRegistral->folio }}</p>
            <p style="margin:0;"><strong>DISTRITO:</strong> {{ $movimientoRegistral->distrito}}</p>
        </div>

        <div>
            <p class="titulo">CERTIFICADO DE GRAVAMEN O DE LIBERTAD DE GRAVAMEN</p>

        </div>

        <p class="parrafo informacion">
            EL CIUDADANO LICENCIADO EN DERECHO <strong>{{ $director }}</strong>, DIRECTOR DEL REGISTRO PÚBLICO DE LA PROPIEDAD
            CERTIFICA QUE DE ACUERDO A LA BUSQUEDA EN EL INMUEBLE:
        </p>

        <div class="informacion">

            <p style="text-align: center; margin:0;"><strong>FOLIO REAL:</strong> {{ $movimientoRegistral->folioReal->folio }}</p>

            <p style="text-align: center; margin:0;"><strong>SECCIÓN:</strong> {{ $predio->folioReal->seccion_antecedente }}; <strong>DISTRITO:</strong> {{ $predio->folioReal->distrito}}; <strong>TOMO:</strong> {{ $predio->folioReal->tomo_antecedente }}, <strong>REGISTRO:</strong> {{ $predio->folioReal->registro_antecedente }}, <strong>NÚMERO DE PROPIEDAD:</strong> {{ $predio->folioReal->numero_propiedad_antecedente }}.</p>

            <br>

            @include('comun.caratulas.ubicacion_inmueble')

            @if($predio->colindancias->count())

                @include('comun.caratulas.colindancias')

            @endif

            @include('comun.caratulas.descripcion_inmueble')

            @include('comun.caratulas.propietarios')

            <br>

            @if($gravamenes->count())

                <p><strong>REPORTA EL(LOS) SIGUIENTE(S) GRAVAMEN(ES):</strong></p>

                <br>

                @foreach ($gravamenes as $gravamen)

                    <p class="parrafo">
                        <strong>movimiento registral: </strong>{{ $gravamen->movimientoRegistral->folioReal->folio }}-{{ $gravamen->movimientoRegistral->folio }}
                        <strong>Tomo: </strong>{{ $gravamen->movimientoRegistral->tomo_gravamen }}
                        <strong>Registro: </strong>{{ $gravamen->movimientoRegistral->registro_gravamen }}
                        <strong>Distrito: </strong>{{ $gravamen->movimientoRegistral->distrito }};
                        CON <strong>FECHA DE INSCRIPCIÓN: </strong> {{ Carbon\Carbon::parse($gravamen->fecha_inscripcion)->format('d-m-Y') }};
                        <strong>RELATIVO A: </strong> {{ $gravamen->acto_contenido }};
                        <strong>Tipo / Número de documento:</strong>{{ $gravamen->movimientoRegistral->tipo_documento }}/{{ $gravamen->movimientoRegistral->numero_documento }}
                        <strong>Procedencia:</strong>{{ $gravamen->movimientoRegistral->procedencia }}
                        <strong>Tipo: </strong>{{ $gravamen->tipo }};
                        <strong>CELEBRADO POR EL(LOS) ACREDOR(ES):</strong>
                        @foreach ($gravamen->acreedores as $acreedor)

                            {{ $acreedor->persona->nombre }} {{ $acreedor->persona->ap_paterno }} {{ $acreedor->persona->ap_materno }}{{ $acreedor->persona->razon_social }}@if(!$loop->last), @endif

                        @endforeach
                        <strong> Y COMO DEUDOR(ES):</strong>
                        @foreach ($gravamen->deudores as $deudor)

                            {{ $deudor->persona->nombre }} {{ $deudor->persona->ap_paterno }} {{ $deudor->persona->ap_materno }}{{ $deudor->persona->razon_social }}@if(!$loop->last), @else; @endif

                        @endforeach
                        <strong>POR LA CANTIDAD DE: </strong> ${{ number_format($gravamen->valor_gravamen, 2) }} {{ $formatter->toWords($gravamen->valor_gravamen) }} {{ $gravamen->divisa }}.
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

                <br>

                <p class="parrafo">
                    <strong>acto: </strong>{{ $aviso->acto_contenido }}
                </p>

                <p class="parrafo">
                    <strong>movimiento registral: </strong>{{ $aviso->movimientoRegistral->folioReal->folio }}-{{ $aviso->movimientoRegistral->folio }}
                </p>

                <p class="parrafo">
                    <strong>descripción: </strong>{{ $aviso->descripcion }}
                </p>

                <br>

            @endif

            <p class="parrafo">
                A SOLICITUD DE: <strong>{{ $movimientoRegistral->solicitante }} </strong> se expide EL PRESENTE CERTIFICADO EN LA CIUDAD DE @if($predio->folioReal->distrito== '02 Uruapan' ) URUAPAN, @else MORELIA, @endif MICHOACÁN, A LAS
                {{ Carbon\Carbon::now()->locale('es')->translatedFormat('H:i:s \d\e\l l d \d\e F \d\e\l Y'); }}.
            </p>

        </div>

        <div class="firma no-break">

            <p class="atte">
                <strong>A T E N T A M E N T E</strong>
            </p>

            @if($predio->folioReal->distrito== '02 Uruapan' )
                <p class="borde">Lic. SANDRO MEDINA MORALES </p>
                <p style="margin:0;">COORDINADOR REGIONAL 4 PURHÉPECHA (URUAPAN)</p>
            @else
                <p class="borde" style="margin:0;">{{ $director }}</p>
                <p style="margin:0;">Director del registro público de la propiedad</p>
            @endif

        </div>

        <div class="informacion no-break">

            <div class="control no-break">

                <p class="separador">DATOS DE CONTROL</p>

                <table style="font-size: 9px">
                    <tbody>
                        <tr>
                            <td style="padding-right: 40px; text-align:left; vertical-align: bottom;">

                                <p style="margin: 0"><strong>NÚMERO DE CONTROL: </strong>{{ $movimientoRegistral->año }}-{{ $movimientoRegistral->tramite }}-{{ $movimientoRegistral->usuario }}</p>
                                <p style="margin: 0"><strong>DERECHOS: </strong>${{ number_format($movimientoRegistral->monto, 2) }}</p>
                                <p style="margin: 0"><strong>Tipo de servicio: </strong>{{ $movimientoRegistral->tipo_servicio }}</p>
                                <p style="margin: 0"><strong>Servicio: </strong>{{ $servicio }}</p>

                            </td>

                            <td style="padding-right: 40px; text-align:left; ; vertical-align: top; white-space: nowrap;">

                                <p style="margin: 0"><strong>Impreso en: </strong>{{ now()->format('d/m/Y H:i:s') }}</p>
                                <p style="margin: 0"><strong>IMPRESO POR: </strong>{{  auth()->user()->name }}</p>
                                <p style="margin: 0"><strong>Movimiento registral:</strong> {{ $movimientoRegistral->folioReal->folio }}-{{ $movimientoRegistral->folio }}</p>
                            </td>

                        </tr>
                    </tbody>
                </table>

            </div>

        </div>

    </main>

</body>
</html>
