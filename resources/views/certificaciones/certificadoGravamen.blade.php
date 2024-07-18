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
        margin-bottom: 20px;
        counter-reset: page;
        height: 100%;
        background-image: url("storage/img/escudo_fondo.png");
        background-size: cover;
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

        <div>

            <p class="titulo">DIRECCIÓN DE REGISTRO PÚBLICO DE LA PROPIEDAD</p>
            <p class="titulo">CERTIFICADO DE GRAVAMEN O DE LIBERTAD DE GRAVAMEN</p>

        </div>

        <p style="text-align: right; margin:0;"><strong>Movimiento registral:</strong> {{ $movimientoRegistral->folioReal->folio }}-{{ $movimientoRegistral->folio }}</p>

        <p class="parrafo informacion">
            EL CIUDADANO LICENCIADO EN DERECHO <strong>{{ $director }}</strong>, DIRECTOR DEL REGISTRO PÚBLICO DE LA PROPIEDAD
            CERTIFICA QUE DE ACUERDO A LA BUSQUEDA EN EL INMUEBLE:
        </p>

        <div class="informacion">

            <p style="text-align: center; margin:0;"><strong>FOLIO REAL:</strong> {{ $movimientoRegistral->folioReal->folio }}</p>

            <p style="text-align: center; margin:0;"><strong>SECCIÓN:</strong> {{ $predio->folioReal->seccion_antecedente }}; <strong>DISTRITO:</strong> {{ $predio->folioReal->distrito}}; <strong>TOMO:</strong> {{ $predio->folioReal->tomo_antecedente }}, <strong>REGISTRO:</strong> {{ $predio->folioReal->registro_antecedente }}, <strong>NÚMERO DE PROPIEDAD:</strong> {{ $predio->folioReal->numero_propiedad_antecedente }}.</p>

            <br>

            <p class="parrafo">

                <p class="separador">UBICACIÓN DEL INMUEBLE</p>

                <p class="parrafo">

                    <strong>CÓDIGO POSTAL:</strong> {{ $predio->codigo_postal }}; <strong>TIPO DE ASENTAMIENTO:</strong> {{ $predio->tipo_asentamiento }}; <strong>NOMBRE DEL ASENTAMIENTO:</strong> {{ $predio->nombre_asentamiento }}; <strong>MUNICIPIO:</strong> {{ $predio->municipio }};

                    <strong>CIUDAD:</strong> {{ $predio->ciudad }};

                    @if ($predio->localidad)
                        <strong>LOCALIDAD:</strong> {{ $predio->localidad }};
                    @endif

                    @if ($predio->tipo_vialidad)
                        <strong>TIPO DE VIALIDAD:</strong> {{ $predio->tipo_vialidad }};
                    @endif

                    @if ($predio->nombre_vialidad)
                        <strong>NOMBRE DE LA VIALIDAD:</strong> {{ $predio->nombre_vialidad }};
                    @endif

                    <strong>NÚMERO EXTERIOR:</strong> {{ $predio->numero_exterior ?? 'SN' }}; <strong>NÚMERO INTERIOR:</strong> {{ $predio->numero_interior ?? 'SN' }};

                    @if ($predio->nombre_edificio)
                        <strong>EDIFICIO:</strong> {{ $predio->nombre_edificio }};
                    @endif

                    @if ($predio->clave_edificio)
                        <strong>clave del edificio:</strong> {{ $predio->clave_edificio }};
                    @endif

                    @if ($predio->departamento_edificio)
                        <strong>DEPARTAMENTO:</strong> {{ $predio->departamento_edificio }};
                    @endif

                    @if ($predio->lote)
                        <strong>LOTE:</strong> {{ $predio->lote }};
                    @endif

                    @if ($predio->manzana)
                        <strong>MANZANA:</strong> {{ $predio->manzana }};
                    @endif

                    @if ($predio->ejido)
                        <strong>ejido:</strong> {{ $predio->ejido }};
                    @endif

                    @if ($predio->parcela)
                        <strong>parcela:</strong> {{ $predio->parcela }};
                    @endif

                    @if ($predio->solar)
                        <strong>solar:</strong> {{ $predio->solar }};
                    @endif

                    @if ($predio->poblado)
                        <strong>poblado:</strong> {{ $predio->poblado }};
                    @endif

                    @if ($predio->numero_exterior)
                        <strong>número exterior:</strong> {{ $predio->numero_exterior }};
                    @endif

                    @if ($predio->numero_exterior_2)
                        <strong>número exterior 2:</strong> {{ $predio->numero_exterior_2 }};
                    @endif

                    @if ($predio->numero_adicional)
                        <strong>número adicional:</strong> {{ $predio->numero_adicional }};
                    @endif

                    @if ($predio->numero_adicional_2)
                        <strong>número adicional 2:</strong> {{ $predio->numero_adicional_2 }};
                    @endif

                    @if ($predio->lote_fraccionador)
                        <strong>lote del fraccionador:</strong> {{ $predio->lote_fraccionador }};
                    @endif

                    @if ($predio->manzana_fraccionador)
                        <strong>manzana del fraccionador:</strong> {{ $predio->manzana_fraccionador }};
                    @endif

                    @if ($predio->etapa_fraccionador)
                        <strong>etapa del fraccionador:</strong> {{ $predio->etapa_fraccionador }};
                    @endif

                    @if ($predio->observaciones)
                        <strong>OBSERVACIONES:</strong> {{ $predio->observaciones }}.
                    @endif

                </p>

                <br>

                <p class="separador">colindancias</p>

                <table>

                    <thead>

                        <tr>
                            <th style="padding-right: 40px;">Viento</th>
                            <th style="padding-right: 40px;">Longitud (mts.)</th>
                            <th style="padding-right: 40px;">Descripción</th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($predio->colindancias as $colindancia)

                            <tr>
                                <td style="padding-right: 40px;">
                                    {{ $colindancia->viento }}
                                </td>
                                <td style="padding-right: 40px;">
                                    {{ number_format($colindancia->longitud, 2) }}
                                </td>
                                <td style="padding-right: 40px;">
                                    {{ $colindancia->descripcion }}
                                </td>
                            </tr>

                        @endforeach

                    </tbody>

                </table>

                <p class="separador">DESCRIPCIÓN DEL INMUEBLE</p>

                <p class="parrafo">
                    @if($predio->cp_localidad)
                        <strong>Cuenta predial:</strong> {{ $predio->cp_localidad }}-{{ $predio->cp_oficina }}-{{ $predio->cp_tipo_predio }}-{{ $predio->cp_registro }};
                    @endif

                    @if($predio->cc_region_catastral)
                        <strong>Clave catastral:</strong> {{ $predio->cc_estado }}-{{ $predio->cc_region_catastral }}-{{ $predio->cc_municipio }}-{{ $predio->cc_zona_catastral }}-{{ $predio->cc_sector }}-{{ $predio->cc_manzana }}-{{ $predio->cc_predio }}-{{ $predio->cc_edificio }}-{{ $predio->cc_departamento }};
                    @endif

                    <strong>Superficie de terreno:</strong> {{ $predio->superficie_terreno }} {{ $predio->unidad_area }};

                    <strong>Superficie de construcción:</strong> {{ $predio->superficie_construccion }} {{ $predio->unidad_area }};

                    <strong>valor catastral:</strong> {{ $predio->monto_transaccion }} {{ $predio->divisa }};

                    @if ($predio->curt)
                        <strong>curt:</strong> {{ $predio->curt }};
                    @endif

                    @if ($predio->superficie_judicial)
                        <strong>superficie judicial:</strong> {{ $predio->superficie_judicial }} {{ $predio->unidad_area }};
                    @endif

                    @if ($predio->superficie_notarial)
                        <strong>superficie notarial:</strong> {{ $predio->superficie_notarial }} {{ $predio->unidad_area }};
                    @endif

                    @if ($predio->area_comun_terreno)
                        <strong>área de terreno común:</strong> {{ $predio->area_comun_terreno }} {{ $predio->unidad_area }};
                    @endif

                    @if ($predio->area_comun_construccion)
                        <strong>área de construcción común:</strong> {{ $predio->area_comun_construccion }} {{ $predio->unidad_area }};
                    @endif

                    @if ($predio->valor_terreno_comun)
                        <strong>valor de terreno común:</strong> {{ $predio->valor_terreno_comun }} {{ $predio->divisa }};
                    @endif

                    @if ($predio->valor_construccion_comun)
                        <strong>valor de construcción común:</strong> {{ $predio->valor_construccion_comun }} {{ $predio->divisa }};
                    @endif

                    @if ($predio->valor_catastral)
                        <strong>valor de construcción común:</strong> {{ $predio->valor_catastral }} {{ $predio->divisa }};
                    @endif

                    @if($predio->descripcion)
                        <strong>Descripción:</strong> {{ $predio->descripcion }}.
                    @endif

                </p>

                <br>

                <p class="separador">propietarios</p>

                <table>

                    <thead>

                        <tr>
                            <th >Nombre / Razón social</th>
                            <th >% de propiedad</th>
                            <th >% de nuda</th>
                            <th >% de usufructo</th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($predio->propietarios() as $propietario)

                            <tr>
                                <td style="padding-right: 40px;">
                                    {{ $propietario->persona->nombre }} {{ $propietario->persona->ap_paterno }} {{ $propietario->persona->ap_materno }} {{ $propietario->persona->razon_social }}
                                </td>
                                <td style="padding-right: 40px;">
                                    {{ $propietario->porcentaje_propiedad ?? '0.00' }}%
                                </td>
                                <td style="padding-right: 40px;">
                                    {{ $propietario->porcentaje_nuda ?? '0.00' }}%
                                </td>
                                <td style="padding-right: 40px;">
                                    {{ $propietario->porcentaje_usufructo ?? '0.00' }}%
                                </td>
                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </p>

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
                        <strong>Tipo: </strong>{{ $gravamen->tipo }};
                        <strong>CELEBRADO POR EL(LOS) ACREDOR(ES):</strong>
                        @foreach ($gravamen->acreedores as $acreedor)

                            {{ $acreedor->persona->nombre }} {{ $acreedor->persona->ap_paterno }} {{ $acreedor->persona->ap_materno }}{{ $acreedor->persona->razon_social }}@if(!$loop->last), @endif

                        @endforeach
                        <strong> Y COMO DEUDOR(ES):</strong>
                        @foreach ($gravamen->deudores as $deudor)

                            @if($deudor->actor)

                                {{ $deudor->actor->persona->nombre }} {{ $deudor->actor->persona->ap_paterno }} {{ $deudor->actor->persona->ap_materno }}{{ $deudor->actor->persona->razon_social }}@if(!$loop->last), @else; @endif

                            @else

                                {{ $deudor->persona->nombre }} {{ $deudor->persona->ap_paterno }} {{ $deudor->persona->ap_materno }}{{ $deudor->persona->razon_social }}@if(!$loop->last), @else; @endif

                            @endif

                        @endforeach
                        <strong>POR LA CANTIDAD DE: </strong> ${{ number_format($gravamen->valor_gravamen, 2) }} {{ $formatter->toWords($gravamen->valor_gravamen) }} {{ $gravamen->divisa }}.
                    </p>

                    <br>

                @endforeach

            @else

                <p class="parrafo">
                    <strong style="text-decoration: underline">no se encontro constancia de que reporte grvamen alguno.</strong>
                </p>

                <br>

            @endif

            <p class="parrafo">
                A SOLICITUD DE: <strong>{{ $movimientoRegistral->solicitante }}</strong> se expide EL PRESENTE CERTIFICADO EN LA CIUDAD DE @if($predio->folioReal->distrito== '02 Uruapan' ) URUAPAN, @else MORELIA, @endif MICHOACÁN, A LAS
                {{ Carbon\Carbon::now()->locale('es')->translatedFormat('H:i:s \d\e\l l d \d\e F \d\e\l Y'); }}.
            </p>

        </div>

        <div class="firma no-break">

            <p class="atte">
                <strong>A T E N T A M E N T E</strong>
            </p>

            @if($predio->folioReal->distrito== '02 Uruapan' )
                <p class="borde">L.A. SANDRO MEDINA MORALES </p>
                <p style="margin:0;">coordinador regional 4 purepecha</p>
            @else
                <p class="borde" style="margin:0;">{{ $director }}</p>
                <p style="margin:0;">Director del registro público de la propiedad</p>
            @endif

        </div>

        <div class="informacion">

            <div class="control">

                <p class="separador">DATOS DE CONTROL</p>

                <table style="font-size: 9px">
                    <tbody>
                        <tr>
                            <td style="padding-right: 40px; text-align:left; vertical-align: bottom;">

                                @if($movimientoRegistral->año)

                                    <p style="margin: 0"><strong>NÚMERO DE CONTROL: </strong>{{ $movimientoRegistral->año }}-{{ $movimientoRegistral->tramite }}-{{ $movimientoRegistral->usuario }}</p>

                                @else

                                    <p style="margin: 0"><strong>NÚMERO DE CONTROL: </strong>{{ $movimientoRegistral->certificacion->observaciones }}</p>

                                @endif

                                <p style="margin: 0"><strong>DERECHOS: </strong>${{ number_format($movimientoRegistral->monto, 2) }}</p>
                                <p style="margin: 0"><strong>Tipo de servicio: </strong>{{ $movimientoRegistral->tipo_servicio }}</p>

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
