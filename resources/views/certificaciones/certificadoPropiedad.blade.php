<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Certificado de propiedad</title>
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
        margin-bottom: 20px;
        counter-reset: page;
        height: 100%;
        background-image: url("storage/img/escudo_fondo.png");
        background-size: cover;
        font-family: sans-serif;
        font-weight: normal;
        line-height: 1.5;
        text-transform: uppercase;
        font-size: 10px;
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

    .titulo{
        text-align: center;
        font-size: 11px;
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
                    <p style="margin:0;"><strong>DISTRITO:</strong> {{ $movimientoRegistral->distrito}}</p>
                </div>

                <p class="titulo">
                    certificado de propiedad
                </p>

                <p class="parrafo">
                    EL DIRECTOR DEL REGISTRO PÚBLICO DE LA PROPIEDAD @if($distrito == '02 Uruapan' ) <strong>L.A. SANDRO MEDINA MORALES</strong> @else <strong>{{ $director }}</strong>, @endif certifica que habiendose examinado acervo registral correspondiente al distrito de {{ $distrito}} se encontro registro de la siguiente propiedad:
                </p>

                <p style="text-align: center"><strong>FOLIO REAL:</strong> {{ $movimientoRegistral->folioReal->folio }}</p>

                <p style="text-align: center"><strong>SECCIÓN:</strong> {{ $movimientoRegistral->folioReal->seccion_antecedente }}; <strong>DISTRITO:</strong> {{ $distrito}}; <strong>TOMO:</strong> {{ $movimientoRegistral->folioReal->tomo_antecedente }}, <strong>REGISTRO:</strong> {{ $movimientoRegistral->folioReal->registro_antecedente }}, <strong>NÚMERO DE PROPIEDAD:</strong> {{ $movimientoRegistral->folioReal->numero_propiedad_antecedente }}</p>

                <p class="separador">UBICACIÓN DEL INMUEBLE</p>

                <p class="parrafo">

                    @if ($predio->codigo_postal)
                        <strong>CÓDIGO POSTAL:</strong> {{ $predio->codigo_postal }};
                    @endif

                    @if ($predio->tipo_asentamiento)
                        <strong>TIPO DE ASENTAMIENTO:</strong> {{ $predio->tipo_asentamiento }};
                    @endif

                    @if ($predio->nombre_asentamiento)
                        <strong>NOMBRE DEL ASENTAMIENTO:</strong> {{ $predio->nombre_asentamiento }};
                    @endif

                    @if ($predio->municipio)
                        <strong>MUNICIPIO:</strong> {{ $predio->municipio }};
                    @endif

                    @if ($predio->ciudad)
                        <strong>CIUDAD:</strong> {{ $predio->ciudad }};
                    @endif

                    @if ($predio->localidad)
                        <strong>LOCALIDAD:</strong> {{ $predio->localidad }};
                    @endif

                    @if ($predio->tipo_vialidad)
                        <strong>TIPO DE VIALIDAD:</strong> {{ $predio->tipo_vialidad }};
                    @endif

                    @if ($predio->nombre_vialidad)
                        <strong>NOMBRE DE LA VIALIDAD:</strong> {{ $predio->nombre_vialidad }};
                    @endif

                    @if ($predio->numero_exterior)
                        <strong>NÚMERO EXTERIOR:</strong> {{ $predio->numero_exterior ?? 'SN' }};
                    @endif

                    @if ($predio->numero_interior)
                        <strong>NÚMERO INTERIOR:</strong> {{ $predio->numero_interior ?? 'SN' }};
                    @endif

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

                {{-- <p class="separador">colindancias</p>

                <table>

                    <thead>

                        <tr>
                            <th>Viento</th>
                            <th>Longitud</th>
                            <th>Descripción</th>
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

                </table> --}}

                <p class="separador">DESCRIPCIÓN DEL INMUEBLE</p>

                <p class="parrafo">
                    @if($predio->cp_localidad)
                        <strong>Cuenta predial:</strong> {{ $predio->cp_localidad }}-{{ $predio->cp_oficina }}-{{ $predio->cp_tipo_predio }}-{{ $predio->cp_registro }};
                    @endif

                    @if($predio->cc_region_catastral)
                        <strong>Clave catastral:</strong> {{ $predio->cc_estado }}-{{ $predio->cc_region_catastral }}-{{ $predio->cc_municipio }}-{{ $predio->cc_zona_catastral }}-{{ $predio->cc_sector }}-{{ $predio->cc_manzana }}-{{ $predio->cc_predio }}-{{ $predio->cc_edificio }}-{{ $predio->cc_departamento }};
                    @endif

                    <strong>Superficie de terreno:</strong> {{ $predio->superficie_terreno }} {{ $predio->unidad_area }} <strong>Superficie de construcción:</strong> {{ $predio->superficie_construccion }} {{ $predio->unidad_area }} <strong>monto de la transacción:</strong> {{ $predio->monto_transaccion }} {{ $predio->divisa }};

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

                    <strong>Descripción:</strong> {{ $predio->descripcion }}.

                </p>

                <p class="separador">propietarios</p>

                <table>

                    <thead>

                        <tr>
                            <th style="padding-right: 10px;">Nombre / Razón social</th>
                            <th style="padding-right: 10px;">% de propiedad</th>
                            <th style="padding-right: 10px;">% de nuda</th>
                            <th style="padding-right: 10px;">% de usufructo</th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($predio->propietarios() as $propietario)

                            <tr>
                                <td style="padding-right: 40px;">
                                    {{ $propietario->persona->nombre }} {{ $propietario->persona->ap_paterno }} {{ $propietario->persona->ap_materno }} {{ $propietario->persona->razon_social }}
                                </td>
                                <td style="padding-right: 40px;">
                                    {{ $propietario->porcentaje_propiedad ?? '0.00' }} %
                                </td>
                                <td style="padding-right: 40px;">
                                    {{ $propietario->porcentaje_nuda ?? '0.00' }} %
                                </td>
                                <td style="padding-right: 40px;">
                                    {{ $propietario->porcentaje_usufructo ?? '0.00' }} %
                                </td>
                            </tr>

                        @endforeach

                    </tbody>

                </table>

                <p class="parrafo">
                    A SOLICITUD DE: <strong>{{ $movimientoRegistral->solicitante }}</strong> se expide EL PRESENTE CERTIFICADO EN LA CIUDAD DE @if($predio->folioReal->distrito== '02 Uruapan' ) URUAPAN, @else MORELIA, @endif MICHOACÁN, A LAS
                    {{ Carbon\Carbon::now()->locale('es')->translatedFormat('H:i:s \d\e\l l d \d\e F \d\e\l Y'); }}.
                </p>

                <div class="firma no-break">

                    <p class="atte">
                        <strong>A T E N T A M E N T E</strong>
                    </p>

                    @if($distrito == '02 Uruapan' )
                        <p class="borde">Lic. SANDRO MEDINA MORALES </p>
                        <p style="margin:0;">COORDINADOR REGIONAL 4 PURHÉPECHA (URUAPAN)</p>
                    @else
                        <p class="borde" style="margin:0;">{{ $director }}</p>
                        <p style="margin:0;">Director del registro público de la propiedad</p>
                    @endif

                </div>

                <div class="informacion">

                    <div class="control no-break">

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

            </div>

        </div>

    </main>

</body>
</html>
