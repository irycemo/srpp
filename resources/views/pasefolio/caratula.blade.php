<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Carátula de pase a folio</title>
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
        margin-bottom: 40px;
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
                    <p style="margin:0"><strong>DISTRITO:</strong> {{ $distrito}}</p>
                </div>

                <div>

                    <p style="text-align: center; font-weight: bold; font-size: 11px;">CARATULA DE ASIGNACION DE FOLIO REAL</p>

                </div>

                <p class="parrafo">
                    EL DIRECTOR DEL REGISTRO PÚBLICO DE LA PROPIEDAD <strong>{{ $director }}</strong>, AUTORIZA EL PRESENTE FOLIO REAL PARA LOS ASIENTOS RELATIVOS A EL INMUEBLE QUE A CONTINUACIÓN SE DESCRIBE:
                </p>

                <p style="text-align: center"><strong>FOLIO REAL:</strong> {{ $folioReal->folio }}</p>

                @if($folioReal->antecedentes->count())

                    <p class="separador">Antecedentes fusionados</p>

                    <table>

                        <thead>

                            <tr>
                                <th style="padding-right: 10px;">Tomo</th>
                                <th style="padding-right: 10px;">Registro</th>
                                <th style="padding-right: 10px;">Numero de propiedad</th>
                                <th style="padding-right: 10px;">Distrito</th>
                                <th style="padding-right: 10px;">Sección</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach ($folioReal->antecedentes as $antecedente)

                                <tr>
                                    <td style="padding-right: 40px;">
                                        {{ $antecedente->tomo_antecedente }}
                                    </td>
                                    <td style="padding-right: 40px;">
                                        {{ $antecedente->registro_antecedente }}
                                    </td>
                                    <td style="padding-right: 40px;">
                                        {{ $antecedente->numero_propiedad_antecedente }}
                                    </td>
                                    <td style="padding-right: 40px;">
                                        {{ $antecedente->distrito_antecedente }}
                                    </td>
                                    <td style="padding-right: 40px;">
                                        {{ $antecedente->seccion_antecedente }}
                                    </td>
                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                @else

                    <p style="text-align: center"><strong>SECCIÓN:</strong> {{ $folioReal->seccion_antecedente }}; <strong>DISTRITO:</strong> {{ $distrito}}; <strong>TOMO:</strong> {{ $folioReal->tomo_antecedente }}, <strong>REGISTRO:</strong> {{ $folioReal->registro_antecedente }}, <strong>NÚMERO DE PROPIEDAD:</strong> {{ $folioReal->numero_propiedad_antecedente }}</p>

                @endif

                <p class="separador">Documento de entrada</p>

                @if(in_array($folioReal->tipo_documento, ['OFICIO', 'TÍTULO DE PROPIEDAD']))

                    <p class="parrafo">
                        <strong>Tipo de documento: </strong> {{ $folioReal->tipo_documento }}; <strong>Número de documento: </strong> {{ $folioReal->numero_documento }}; <strong>Cargo de la autoridad: </strong> {{ $folioReal->autoridad_cargo }}; <strong>Nombre de la autoridad: </strong> {{ $folioReal->autoridad_nombre }}; <strong>Número de la autoridad: </strong> {{ $folioReal->autoridad_numero }}; <strong>Fecha de emisión: </strong> {{ Carbon\Carbon::parse($folioReal->fecha_emision)->format('d-m-Y') }}; <strong>Fecha de inscripción: </strong> {{ Carbon\Carbon::parse($folioReal->fecha_inscripcion)->format('d-m-Y') }}; <strong>Dependencia: </strong>{{ $folioReal->procedencia }}
                    </p>

                    <p class="parrafo"><strong>Acto contenido en el antecedente:</strong> {{ $folioReal->acto_contenido_antecedente }}</p>

                    @if($folioReal->observaciones_antecedente)

                        <p class="parrafo"><strong>Observaciones en el antecedente:</strong> {{ $folioReal->observaciones_antecedente }}</p>

                    @endif

                @else

                    <p class="parrafo">
                        <strong>Tipo de documento: </strong> {{ $folioReal->tipo_documento }}; <strong>Número de escritura: </strong> {{ $folioReal->predio->escritura->numero }}; <strong>Número de notaria: </strong> {{ $folioReal->predio->escritura->notaria }}; <strong>Nombre del notario: </strong> {{ $folioReal->predio->escritura->nombre_notario }}; <strong>Estado del notario: </strong> {{ $folioReal->predio->escritura->estado_notario }}; <strong>Fecha de inscripción: </strong> {{ Carbon\Carbon::parse($folioReal->predio->escritura->fecha_inscripcion)->format('d-m-Y') }}; <strong>Fecha de la escritura: </strong> {{ Carbon\Carbon::parse($folioReal->predio->escritura->fecha_escritura)->format('d-m-Y') }}; <strong>Número de hojas: </strong>{{ $folioReal->predio->escritura->numero_hojas }}; <strong>Número de paginas: </strong>{{ $folioReal->predio->escritura->numero_paginas }}
                    </p>

                    <p class="parrafo"><strong>Acto contenido en el antecedente:</strong> {{ $folioReal->predio->escritura->acto_contenido_antecedente }}</p>

                    @if($folioReal->predio->escritura->comentario)

                        <p class="parrafo"><strong>Observaciones en el antecedente:</strong> {{ $folioReal->predio->escritura->comentario }}</p>

                    @endif

                @endif

                @include('comun.caratulas.ubicacion_inmueble')

                @if($predio->colindancias->count())

                    @include('comun.caratulas.colindancias')

                @endif

                @include('comun.caratulas.descripcion_inmueble')

                @include('comun.caratulas.propietarios')

                @if($folioReal->movimientosRegistrales->count() > 1)

                    <p class="separador" style="text-align: center">Movimientos registrales</p>

                    <div style="margin-left: 10px; margin-right: 10px;">

                        @if($folioReal->gravamenes->count() >= 1)

                            <p class="separador" style="text-align: center">Gravamenes</p>

                            @foreach ($folioReal->gravamenes as $gravamen)

                                @if($gravamen->movimientoRegistral->folio == 1) @continue @endif

                                <p class="parrafo">

                                    <p class="separador">gravamen ({{ $folioReal->folio }}-{{ $folioReal->movimientosRegistrales()->where('id', $gravamen->movimiento_registral_id)->first()->folio }})</p>

                                    <p class="parrafo">
                                        <strong>Fecha de inscripción:</strong> {{ Carbon\Carbon::parse($gravamen->fecha_inscripcion)->format('d/m/Y') }}. <strong>Valor del gravamen:</strong> ${{ number_format($gravamen->valor_gravamen, 2) }} {{ $gravamen->divisa }}.
                                    </p>

                                    <p class="parrafo">
                                        <strong>Acto contenido:</strong> {{ $gravamen->acto_contenido }}
                                    </p>

                                    <p class="parrafo">
                                        <strong>Tipo / Número de documento:</strong> {{ $gravamen->movimientoRegistral->tipo_documento }}/{{ $gravamen->movimientoRegistral->numero_documento }}
                                    </p>

                                    <p class="parrafo">
                                        <strong>Procedencia:</strong> {{ $gravamen->movimientoRegistral->procedencia }}
                                    </p>

                                    <p class="parrafo">
                                        <strong>Tipo:</strong> {{ $gravamen->tipo }}
                                    </p>

                                    <p class="parrafo">
                                        <strong>Descripción:</strong> {{ $gravamen->observaciones }}
                                    </p>

                                    <p class="parrafo">
                                        <strong>deudores:</strong>
                                        @foreach ($gravamen->deudores as $deudor)

                                            {{ $deudor->persona->nombre }} {{ $deudor->persona->ap_paterno }} {{ $deudor->persona->ap_materno }} {{ $deudor->persona->razon_social }}@if(!$loop->last), @endif

                                        @endforeach
                                    </p>

                                    <p class="parrafo">
                                        <strong>acreedores:</strong>
                                        @foreach ($gravamen->acreedores as $acreedor)

                                            {{ $acreedor->persona->nombre }} {{ $acreedor->persona->ap_paterno }} {{ $acreedor->persona->ap_materno }} {{ $acreedor->persona->razon_social }}@if(!$loop->last), @endif

                                        @endforeach
                                    </p>

                                </p>

                            @endforeach

                        @endif

                        @if($folioReal->sentencias->count() >= 1)

                            <p class="separador" style="text-align: center">Sentencias</p>

                            @foreach ($folioReal->sentencias as $sentencia)

                                @if($sentencia->movimientoRegistral->folio == 1) @continue @endif

                                <p class="parrafo">

                                    <p class="separador">Sentencia ({{ $folioReal->folio }}-{{ $folioReal->movimientosRegistrales()->where('id', $sentencia->movimiento_registral_id)->first()->folio }})</p>

                                    <p class="parrafo">
                                        <strong>Acto contenido:</strong> {{ $sentencia->acto_contenido }}
                                    </p>

                                    <p class="parrafo">
                                        <strong>Descripción del acto:</strong> {{ $sentencia->descripcion }}
                                    </p>

                                </p>

                            @endforeach

                        @endif

                        @if($folioReal->varios->count() >= 1)

                            <p class="separador" style="text-align: center">Varios</p>

                            @foreach ($folioReal->varios as $vario)

                                @if($vario->movimientoRegistral->folio == 1) @continue @endif

                                <p class="parrafo">

                                    <p class="separador">Varios ({{ $folioReal->folio }}-{{ $folioReal->movimientosRegistrales()->where('id', $vario->movimiento_registral_id)->first()->folio }})</p>

                                    <p class="parrafo">
                                        <strong>Acto contenido:</strong> {{ $vario->acto_contenido }}
                                    </p>

                                    <p class="parrafo">
                                        <strong>Descripción del acto:</strong> {{ $vario->descripcion }}
                                    </p>

                                </p>

                            @endforeach

                        @endif

                        @if($folioReal->cancelaciones->count() >= 1)

                            <p class="separador" style="text-align: center">Cancelaciones</p>

                            @foreach ($folioReal->cancelaciones as $cancelacion)

                                @if($cancelacion->movimientoRegistral->folio == 1) @continue @endif

                                <p class="parrafo">

                                    <p class="separador">Cancelación ({{ $folioReal->folio }}-{{ $folioReal->movimientosRegistrales()->where('id', $cancelacion->movimiento_registral_id)->first()->folio }})</p>

                                    <p class="parrafo">
                                        <strong>Gravamen cancelado:</strong> {{ $folioReal->folio }}-{{ $folioReal->movimientosRegistrales()->where('id', $cancelacion->gravamen)->first()->folio }}
                                    </p>

                                    <p class="parrafo">
                                        <strong>Acto contenido:</strong> {{ $cancelacion->acto_contenido }}
                                    </p>

                                    <p class="parrafo">
                                        <strong>Descripción del acto:</strong> {{ $cancelacion->observaciones }}
                                    </p>

                                </p>

                            @endforeach

                        @endif

                    </div>

                @endif

                <div class="firma no-break">

                    <p class="atte">
                        <strong>A T E N T A M E N T E</strong>
                    </p>

                    @if($distrito == '02 Uruapan' )
                        <p class="borde">Lic. SANDRO MEDINA MORALES </p>
                        <p style="margin:0;">COORDINADOR REGIONAL 4 PURHÉPECHA (URUAPAN)</p>
                    @else
                        <p class="borde" style="margin:0;">{{ $director }}</p>
                        <p style="margin:0;">Director del registro público de la propiedad</p>
                    @endif

                </div>

                <p class="separador">datos de control</p>

                <div class="parrafo">

                    <p><strong>Fecha de asignación de folio:</strong> {{ Carbon\Carbon::now()->locale('es')->translatedFormat('H:i:s \d\e\l l d \d\e F \d\e\l Y') }}.</p>
                    <p><strong>Registrador:</strong> {{ auth()->user()->name }}.</p>
                    <p><strong>número de control:</strong> {{ $folioReal->movimientosRegistrales->where('folio', 1)->first()->año }}-{{ $folioReal->movimientosRegistrales->where('folio', 1)->first()->tramite }}-{{ $folioReal->movimientosRegistrales->where('folio', 1)->first()->usuario }}.</p>

                </div>

            </div>

        </div>

    </main>

</body>
</html>
