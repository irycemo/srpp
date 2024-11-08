<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Carátula de pase a folio</title>
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
                    <p style="margin:0"><strong>DISTRITO:</strong> {{ $distrito }}</p>
                </div>

                <div>

                    <p class="titulo">CARATULA DE ASIGNACION DE FOLIO REAL</p>

                </div>

                <p class="parrafo">
                    EL DIRECTOR DEL REGISTRO PÚBLICO DE LA PROPIEDAD <strong>{{ $director }}</strong>, AUTORIZA EL PRESENTE FOLIO REAL PARA LOS ASIENTOS RELATIVOS A EL INMUEBLE QUE A CONTINUACIÓN SE DESCRIBE:
                </p>

                <p style="text-align: center" class="titulo"><strong>FOLIO REAL:</strong> {{ $folioReal->folio }}</p>

                @if(count($folioReal->antecedentes))

                    <p class="separador">Antecedente(s)</p>

                    <table>

                        <thead>

                            <tr>
                                <th style="padding-right: 10px;">Folio real</th>
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
                                        {{ $antecedente->folio_real ?? 'N/A' }}
                                    </td>
                                    <td style="padding-right: 40px;">
                                        {{ $antecedente->tomo_antecedente ?? 'N/A' }}
                                    </td>
                                    <td style="padding-right: 40px;">
                                        {{ $antecedente->registro_antecedente ?? 'N/A' }}
                                    </td>
                                    <td style="padding-right: 40px;">
                                        {{ $antecedente->numero_propiedad_antecedente ?? 'N/A' }}
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
                    <p class="separador">Antecedente</p>
                    <p style="text-align: center"><strong>SECCIÓN:</strong> {{ $folioReal->seccion_antecedente }}; <strong>DISTRITO:</strong> {{ $distrito}}; <strong>TOMO:</strong> {{ $folioReal->tomo_antecedente }}, <strong>REGISTRO:</strong> {{ $folioReal->registro_antecedente }}, <strong>NÚMERO DE PROPIEDAD:</strong> {{ $folioReal->numero_propiedad_antecedente }}</p>

                @endif

                <p class="separador">Documento de entrada</p>

                @if(in_array($folioReal->tipo_documento, ['OFICIO', 'TÍTULO DE PROPIEDAD','ESCRITURA INSTITUCIONAL', 'RESOLUCIÓN JUDICIAL']))

                    <p class="parrafo">
                        <strong>Tipo de documento: </strong> {{ $folioReal->tipo_documento }}; <strong>Número de documento: </strong> {{ $folioReal->numero_documento }}; <strong>Cargo de la autoridad: </strong> {{ $folioReal->autoridad_cargo }}; <strong>Nombre de la autoridad: </strong> {{ $folioReal->autoridad_nombre }}; <strong>Número de la autoridad: </strong> {{ $folioReal->autoridad_numero }}; <strong>Fecha de emisión: </strong> {{ $folioReal->fecha_emision }}; <strong>Fecha de inscripción: </strong> {{$folioReal->fecha_inscripcion }}; <strong>Dependencia: </strong>{{ $folioReal->procedencia }}
                    </p>

                    <p class="parrafo"><strong>Acto contenido:</strong> {{ $folioReal->acto_contenido_antecedente }}</p>

                    @if($folioReal->observaciones_antecedente)

                        <p class="parrafo"><strong>Observaciones en el antecedente:</strong> {{ $folioReal->observaciones_antecedente }}</p>

                    @endif

                @else

                    <p class="parrafo">
                        <strong>Tipo de documento: </strong> {{ $folioReal->tipo_documento }}; <strong>Número de escritura: </strong> {{ $folioReal->escritura->numero }}; <strong>Número de notaria: </strong> {{ $folioReal->escritura->notaria }}; <strong>Nombre del notario: </strong> {{ $folioReal->escritura->nombre_notario }}; <strong>Estado del notario: </strong> {{ $folioReal->escritura->estado_notario }}; <strong>Fecha de inscripción: </strong> {{ $folioReal->escritura->fecha_inscripcion }}; <strong>Fecha de la escritura: </strong> {{ $folioReal->escritura->fecha_escritura }}; <strong>Número de hojas: </strong>{{ $folioReal->escritura->numero_hojas }}; <strong>Número de paginas: </strong>{{ $folioReal->escritura->numero_paginas }}
                    </p>

                    <p class="parrafo"><strong>Acto contenido en el antecedente:</strong> {{ $folioReal->escritura->acto_contenido_antecedente }}</p>

                    @if($folioReal->escritura->comentario)

                        <p class="parrafo"><strong>Observaciones en el antecedente:</strong> {{ $folioReal->escritura->comentario }}</p>

                    @endif

                @endif

                @include('comun.caratulas.ubicacion_inmueble')

                @if(count($predio->colindancias))

                    @include('comun.caratulas.colindancias')

                @endif

                @include('comun.caratulas.descripcion_inmueble')

                @include('comun.caratulas.propietarios')

                @if($folioReal->movimientosRegistrales > 1)

                    <p class="separador" style="text-align: center">Movimientos registrales</p>

                    <div style="margin-left: 10px; margin-right: 10px;">

                        @if(count($folioReal->gravamenes) >= 1)

                            <p class="separador" style="text-align: center">Gravamenes</p>

                            @foreach ($folioReal->gravamenes as $gravamen)

                                @if($gravamen->movimiento_folio == 1) @continue @endif

                                <p class="parrafo">

                                    <p class="separador">gravamen ({{ $folioReal->folio }}-{{ $gravamen->movimiento_folio }})</p>

                                    <p class="parrafo">
                                        <strong>Fecha de inscripción: </strong> {{ $gravamen->fecha_inscripcion }}. <strong>Valor del gravamen:</strong> ${{ number_format($gravamen->valor_gravamen, 2) }} {{ $gravamen->divisa }}.
                                    </p>

                                    <p class="parrafo">
                                        <strong>Acto contenido: </strong> {{ $gravamen->acto_contenido }}
                                    </p>

                                    <p class="parrafo">
                                        <strong>Tipo / Número de documento: </strong> {{ $gravamen->tipo_documento }}/{{ $gravamen->numero_documento }}
                                    </p>

                                    <p class="parrafo">
                                        <strong>Procedencia: </strong> {{ $gravamen->procedencia }}
                                    </p>

                                    <p class="parrafo">
                                        <strong>Tipo: </strong> {{ $gravamen->tipo }}
                                    </p>

                                    <p class="parrafo">
                                        <strong>Descripción: </strong> {{ $gravamen->observaciones }}
                                    </p>

                                    <p class="parrafo">
                                        <strong>deudores: </strong>
                                        @foreach ($gravamen->deudores as $deudor)

                                            {{ $deudor->nombre }} {{ $deudor->ap_paterno }} {{ $deudor->ap_materno }} {{ $deudor->razon_social }}@if(!$loop->last), @endif

                                        @endforeach
                                    </p>

                                    <p class="parrafo">
                                        <strong>acreedores: </strong>
                                        @foreach ($gravamen->acreedores as $acreedor)

                                            {{ $acreedor->nombre }} {{ $acreedor->ap_paterno }} {{ $acreedor->ap_materno }} {{ $acreedor->razon_social }}@if(!$loop->last), @endif

                                        @endforeach
                                    </p>

                                </p>

                            @endforeach

                        @endif

                        @if(count($folioReal->sentencias) >= 1)

                            <p class="separador" style="text-align: center">Sentencias</p>

                            @foreach ($folioReal->sentencias as $sentencia)

                                @if($sentencia->movimiento_folio == 1) @continue @endif

                                <p class="parrafo">

                                    <p class="separador">Sentencia ({{ $folioReal->folio }}-{{ $sentencia->movimiento_folio }})</p>

                                    <p class="parrafo">
                                        <strong>Fecha de inscripción: </strong> {{ $sentencia->fecha_inscripcion }}. <strong>Tomo:</strong> {{ $sentencia->tomo }}. <strong>Registro:</strong> {{ $sentencia->registro }}.  @if(isset($sentencia->hojas))<strong>Hojas: </strong> {{ $sentencia->hojas }}.@endif  @if(isset($sentencia->expediente))<strong>Expediente: </strong> {{ $sentencia->expediente }}.@endif
                                    </p>

                                    <p class="parrafo">
                                        <strong>Acto contenido:</strong> {{ $sentencia->acto_contenido }}
                                    </p>

                                    <p class="parrafo">
                                        <strong>Descripción del acto:</strong> {{ $sentencia->descripcion }}
                                    </p>

                                </p>

                            @endforeach

                        @endif

                        {{-- @if($folioReal->varios) >= 1)

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

                        @endif --}}

                        @if(count($folioReal->cancelaciones) >= 1)

                            <p class="separador" style="text-align: center">Cancelaciones</p>

                            @foreach ($folioReal->cancelaciones as $cancelacion)

                                @if($cancelacion->movimiento_folio == 1) @continue @endif

                                <p class="parrafo">

                                    <p class="separador">Cancelación ({{ $folioReal->folio }}-{{ $cancelacion->movimiento_folio }})</p>

                                    <p class="parrafo">
                                        <strong>Gravamen cancelado:</strong> {{ $folioReal->folio }}-{{ $cancelacion->movimiento_folio }}
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

                    @if(!$firma_electronica)

                        @if($distrito == '02 Uruapan' )
                            <p style="margin-top: 80px;"></p>
                            <p class="borde">Lic. SANDRO MEDINA MORALES </p>
                            <p style="margin:0;">COORDINADOR REGIONAL 4 PURHÉPECHA (URUAPAN)</p>
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
                                <p><strong>número de control:</strong> {{ $datos_control->numero_control }}.</p>

                            </td>
                        </tr>
                    </tbody>

                </table>

            </div>

        </div>

    </main>

</body>
</html>
