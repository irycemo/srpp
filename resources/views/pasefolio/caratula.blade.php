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
        margin-top: 100px;
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

                <p class="parrafo">
                    EL DIRECTOR DEL REGISTRO PÚBLICO DE LA PROPIEDAD <strong>{{ $director }}</strong>, AUTORIZA EL PRESENTE FOLIO REAL PARA LOS ASIENTOS RELATIVOS AL INMUEBLE QUE ACONTINUACIÓN SE DESCRIBE:
                </p>

                <p style="text-align: center"><strong>FOLIO REAL:</strong> {{ $folioReal->folio }}</p>

                <p style="text-align: center"><strong>SECCIÓN:</strong> {{ $folioReal->seccion_antecedente }}; <strong>DISTRITO:</strong> {{ $distrito}}; <strong>TOMO:</strong> {{ $folioReal->tomo_antecedente }}, <strong>REGISTRO:</strong> {{ $folioReal->registro_antecedente }}, <strong>NÚMERO DE PROPIEDAD:</strong> {{ $folioReal->numero_propiedad_antecedente }}</p>

                <p style="text-align: center"><strong>DATOS DE IDENTIFICACIÓN</strong></p>

                <p><strong>UBICACIÓN DEL INMUEBLE:</strong></p>

                <p class="parrafo">

                    @if ($folioReal->predio->codigo_postal)
                        <strong>CÓDIGO POSTAL:</strong> {{ $folioReal->predio->codigo_postal }};
                    @endif

                    @if ($folioReal->predio->tipo_asentamiento)
                        <strong>TIPO DE ASENTAMIENTO:</strong> {{ $folioReal->predio->tipo_asentamiento }};
                    @endif

                    @if ($folioReal->predio->nombre_asentamiento)
                        <strong>NOMBRE DEL ASENTAMIENTO:</strong> {{ $folioReal->predio->nombre_asentamiento }};
                    @endif

                    @if ($folioReal->predio->municipio)
                        <strong>MUNICIPIO:</strong> {{ $folioReal->predio->municipio }};
                    @endif

                    @if ($folioReal->predio->ciudad)
                        <strong>CIUDAD:</strong> {{ $folioReal->predio->ciudad }};
                    @endif

                    @if ($folioReal->predio->localidad)
                        <strong>LOCALIDAD:</strong> {{ $folioReal->predio->localidad }};
                    @endif

                    @if ($folioReal->predio->tipo_vialidad)
                        <strong>TIPO DE VIALIDAD:</strong> {{ $folioReal->predio->tipo_vialidad }};
                    @endif

                    @if ($folioReal->predio->nombre_vialidad)
                        <strong>NOMBRE DE LA VIALIDAD:</strong> {{ $folioReal->predio->nombre_vialidad }};
                    @endif

                    @if ($folioReal->predio->numero_exterior)
                        <strong>NÚMERO EXTERIOR:</strong> {{ $folioReal->predio->numero_exterior ?? 'SN' }};
                    @endif

                    @if ($folioReal->predio->numero_interior)
                        <strong>NÚMERO INTERIOR:</strong> {{ $folioReal->predio->numero_interior ?? 'SN' }};
                    @endif

                    @if ($folioReal->predio->nombre_edificio)
                        <strong>EDIFICIO:</strong> {{ $folioReal->predio->nombre_edificio }};
                    @endif

                    @if ($folioReal->predio->clave_edificio)
                        <strong>clave del edificio:</strong> {{ $folioReal->predio->clave_edificio }};
                    @endif

                    @if ($folioReal->predio->departamento_edificio)
                        <strong>DEPARTAMENTO:</strong> {{ $folioReal->predio->departamento_edificio }};
                    @endif

                    @if ($folioReal->predio->lote)
                        <strong>LOTE:</strong> {{ $folioReal->predio->lote }};
                    @endif

                    @if ($folioReal->predio->manzana)
                        <strong>MANZANA:</strong> {{ $folioReal->predio->manzana }};
                    @endif

                    @if ($folioReal->predio->ejido)
                        <strong>ejido:</strong> {{ $folioReal->predio->ejido }};
                    @endif

                    @if ($folioReal->predio->parcela)
                        <strong>parcela:</strong> {{ $folioReal->predio->parcela }};
                    @endif

                    @if ($folioReal->predio->solar)
                        <strong>solar:</strong> {{ $folioReal->predio->solar }};
                    @endif

                    @if ($folioReal->predio->poblado)
                        <strong>poblado:</strong> {{ $folioReal->predio->poblado }};
                    @endif

                    @if ($folioReal->predio->numero_exterior)
                        <strong>número exterior:</strong> {{ $folioReal->predio->numero_exterior }};
                    @endif

                    @if ($folioReal->predio->numero_exterior_2)
                        <strong>número exterior 2:</strong> {{ $folioReal->predio->numero_exterior_2 }};
                    @endif

                    @if ($folioReal->predio->numero_adicional)
                        <strong>número adicional:</strong> {{ $folioReal->predio->numero_adicional }};
                    @endif

                    @if ($folioReal->predio->numero_adicional_2)
                        <strong>número adicional 2:</strong> {{ $folioReal->predio->numero_adicional_2 }};
                    @endif

                    @if ($folioReal->predio->lote_fraccionador)
                        <strong>lote del fraccionador:</strong> {{ $folioReal->predio->lote_fraccionador }};
                    @endif

                    @if ($folioReal->predio->manzana_fraccionador)
                        <strong>manzana del fraccionador:</strong> {{ $folioReal->predio->manzana_fraccionador }};
                    @endif

                    @if ($folioReal->predio->etapa_fraccionador)
                        <strong>etapa del fraccionador:</strong> {{ $folioReal->predio->etapa_fraccionador }};
                    @endif

                    @if ($folioReal->predio->observaciones)
                        <strong>OBSERVACIONES:</strong> {{ $folioReal->predio->observaciones }}.
                    @endif

                </p>

                <p><strong>colindancias:</strong></p>

                <table>

                    <thead>

                        <tr>
                            <th>Viento</th>
                            <th>Longitud</th>
                            <th>Descripción</th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($folioReal->predio->colindancias as $colindancia)

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

                <p class="parrafo">

                    <ul>

                        @foreach ($folioReal->predio->colindancias as $colindancia)

                            <li><strong>viento:</strong> {{ $colindancia->viento }}; <strong>longitud:</strong> {{ $colindancia->longitud }} metros; <strong>descripción:</strong> {{ $colindancia->descripcion }}.</li>

                        @endforeach

                    </ul>

                </p>

                <p><strong>DESCRIPCIÓN DEL INMUEBLE:</strong></p>

                <p class="parrafo">
                    @if($folioReal->predio->cp_localidad)
                        <strong>Cuenta predial:</strong> {{ $folioReal->predio->cp_localidad }}-{{ $folioReal->predio->cp_oficina }}-{{ $folioReal->predio->cp_tipo_predio }}-{{ $folioReal->predio->cp_registro }};
                    @endif

                    @if($folioReal->predio->cc_region_catastral)
                        <strong>Clave catastral:</strong> {{ $folioReal->predio->cc_estado }}-{{ $folioReal->predio->cc_region_catastral }}-{{ $folioReal->predio->cc_municipio }}-{{ $folioReal->predio->cc_zona_catastral }}-{{ $folioReal->predio->cc_sector }}-{{ $folioReal->predio->cc_manzana }}-{{ $folioReal->predio->cc_predio }}-{{ $folioReal->predio->cc_edificio }}-{{ $folioReal->predio->cc_departamento }};
                    @endif

                    <strong>Superficie de terreno:</strong> {{ $superficie_terreno }} {{ $folioReal->predio->unidad_area }} <strong>Superficie de construcción:</strong> {{ $superficie_construccion }} {{ $folioReal->predio->unidad_area }} <strong>monto de la transacción:</strong> {{ $monto_transaccion }} {{ $folioReal->predio->divisa }};

                    @if ($folioReal->predio->curt)
                        <strong>curt:</strong> {{ $folioReal->predio->curt }};
                    @endif

                    @if ($folioReal->predio->superficie_judicial)
                        <strong>superficie judicial:</strong> {{ $folioReal->predio->superficie_judicial }} {{ $folioReal->predio->unidad_area }};
                    @endif

                    @if ($folioReal->predio->superficie_notarial)
                        <strong>superficie notarial:</strong> {{ $folioReal->predio->superficie_notarial }} {{ $folioReal->predio->unidad_area }};
                    @endif

                    @if ($folioReal->predio->area_comun_terreno)
                        <strong>área de terreno común:</strong> {{ $folioReal->predio->area_comun_terreno }} {{ $folioReal->predio->unidad_area }};
                    @endif

                    @if ($folioReal->predio->area_comun_construccion)
                        <strong>área de construcción común:</strong> {{ $folioReal->predio->area_comun_construccion }} {{ $folioReal->predio->unidad_area }};
                    @endif

                    @if ($folioReal->predio->valor_terreno_comun)
                        <strong>valor de terreno común:</strong> {{ $folioReal->predio->valor_terreno_comun }} {{ $folioReal->predio->divisa }};
                    @endif

                    @if ($folioReal->predio->valor_construccion_comun)
                        <strong>valor de construcción común:</strong> {{ $folioReal->predio->valor_construccion_comun }} {{ $folioReal->predio->divisa }};
                    @endif

                    @if ($folioReal->predio->valor_catastral)
                        <strong>valor de construcción común:</strong> {{ $folioReal->predio->valor_catastral }} {{ $folioReal->predio->divisa }};
                    @endif

                    <strong>Descripción:</strong> {{ $folioReal->predio->descripcion }}.

                </p>

                <p><strong>propietarios:</strong></p>

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

                        @foreach ($folioReal->predio->propietarios() as $propietario)

                            <tr>
                                <td style="padding-right: 40px;">
                                    {{ $propietario->persona->nombre }} {{ $propietario->persona->ap_paterno }} {{ $propietario->persona->ap_materno }} {{ $propietario->persona->razon_social }}
                                </td>
                                <td style="padding-right: 40px;">
                                    {{ $propietario->porcentaje_propiedad ?? '0.00' }} %
                                </td>
                                <td style="padding-right: 40px;">
                                    {{ $propietario->porcentaje_nuda ?? '0.00' }} %;
                                </td>
                                <td style="padding-right: 40px;">
                                    {{ $propietario->porcentaje_usufructo ?? '0.00' }} %;
                                </td>
                            </tr>

                        @endforeach

                    </tbody>

                </table>

                <div class="firma no-break">

                    <p class="atte">
                        <strong>A T E N T A M E N T E</strong>
                    </p>

                    @if($distrito == '02 URUAPAN' )
                        <p class="borde">L.A. SANDRO MEDINA MORALES </p>
                    @else
                        <p class="borde" style="margin:0;">{{ $director }}</p>
                        <p style="margin:0;">Director del registro público de la propiedad</p>
                    @endif

                </div>

                <div class="parrafo">

                    <p><strong>Fecha de pase a folio:</strong> {{ Carbon\Carbon::now()->locale('es')->translatedFormat('H:i:s \d\e\l l d \d\e F \d\e\l Y') }} <strong>Registrador:</strong> {{ auth()->user()->name }}</p>

                </div>

            </div>

        </div>

    </main>

</body>
</html>
