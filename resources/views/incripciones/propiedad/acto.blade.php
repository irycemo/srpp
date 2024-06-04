<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inscripción de propiedad</title>
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
        text-transform: uppercase
    }

    .center{
        display: block;
        margin-left: auto;
        margin-right: auto;
        width: 50%;
    }

    .container{
        font-size: 10px;
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
        margin-top: 20px;
        text-align: center;
    }

    .atte{
        margin-bottom: 40px;
    }

    .borde{
        display: inline;
        border-top: 1px solid;
        margin: 0;
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

    .no-break{
        page-break-inside: avoid;
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

            <div style="text-align: center; font-weight: bold; font-size: 11px;">
                <p style="margin: 0">GOBIERNO DEL ESTADO DE MICHOACÁN DE OCAMPO</p>
                <P style="margin: 0">SECRETARÍA DE FINANZAS Y ADMINISTRACIÓN</P>
                <P style="margin: 0">INSTITUTO REGISTRAL Y CATASTRAL DEL ESTADO DE MICHOACÁN</P>
                <P style="margin: 0">DIRECCIÓN DEL REGISTRO PÚBLICO  DE LA PROPIEDAD</P>
            </div>

            <div style="text-align: right">
                <p><strong>FOLIO REAL:</strong>{{ $inscripcion->movimientoRegistral->folioReal->folio }}-{{ $inscripcion->movimientoRegistral->folio }} <strong>DISTRITO:</strong> {{ $distrito}}</p>
            </div>

            <div style="text-align: center">
                <p><strong>{{ $inscripcion->acto_contenido }}</strong></p>
            </div>

            <div class="parrafo">

                <p><strong>por</strong> {{ $inscripcion->movimientoRegistral->tipo_documento }} <strong>n°</strong> {{ $inscripcion->movimientoRegistral->numero_documento }} <strong>de fecha</strong> {{ Carbon\Carbon::parse($inscripcion->movimientoRegistral->fecha_emision)->format('d-m-Y') }} <strong>otorgado por</strong> {{ $inscripcion->movimientoRegistral->autoridad_cargo }} {{ $inscripcion->movimientoRegistral->autoridad_nombre }}
                    <strong>consta que </strong>
                    @foreach ($inscripcion->transmitentes() as $transmitente)

                        {{ $transmitente->persona->nombre }} {{ $transmitente->persona->ap_paterno }} {{ $transmitente->persona->ap_materno }} {{ $transmitente->persona->razon_social }}

                        @if($transmitente->representadoPor)

                            <strong>representado(a) por: </strong>{{ $transmitente->representadoPor->persona->nombre }} {{ $transmitente->representadoPor->persona->ap_paterno }} {{ $transmitente->representadoPor->persona->ap_materno }} {{ $transmitente->persona->razon_social }}

                        @endif

                    @endforeach
                    , <strong>comparecio a realizar el acto de </strong> {{ $inscripcion->acto_contenido }}.
                </p>

                <p class="separador">Descripción del acto</p>

                <p>{{ $inscripcion->descripcion }}.</p>

                <p class="separador">DESCRIPCIÓN DEL INMUEBLE</p>

                <p>
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
                    @endif.
                </p>

                <p class="separador">colindancias</p>

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

                </table>

            </div>

            <div>

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

            </div>

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

            <div style="margin-top: 50px;">

                <table class="tabla" >
                    <tbody sty>
                        <tr>
                            <td style="padding-right: 40px; text-align:center; width: 50%; vertical-align: bottom; white-space: nowrap;">

                                <p class="borde">{{ $inscripcion->movimientoRegistral->asignadoA->name }}</p>
                                <p style="margin: 0">REGISTRADOR</p>

                            </td>

                            @if($distrito != '02 URUAPAN' )

                                <td style="padding-right: 40px; text-align:center; width: 50%; vertical-align: bottom; white-space: nowrap;">

                                    <p class="borde">{{ $jefe_departamento }}</p>
                                    <p style="margin: 0">JEFE DE Departamento de Registro de Inscripciones	</p>
                                </td>

                            @endif

                        </tr>
                    </tbody>
                </table>

            </div>

            <div class="control no-break">

                <p class="separador">DATOS DE CONTROL</p>

                <table style="font-size: 9px">
                    <tbody>
                        <tr>
                            <td style="padding-right: 40px; text-align:left; ; vertical-align: bottom; white-space: nowrap;">

                                <p style="margin: 0"><strong>NÚMERO DE CONTROL: </strong>{{ $inscripcion->movimientoRegistral->año }}-{{ $inscripcion->movimientoRegistral->tramite }}-{{ $inscripcion->movimientoRegistral->usuario }}</p>
                                <p style="margin: 0"><strong>DERECHOS: </strong>${{ number_format($inscripcion->movimientoRegistral->monto, 2) }}</p>
                                <p style="margin: 0"><strong>Tipo de servicio: </strong>{{ $inscripcion->movimientoRegistral->tipo_servicio }}</p>

                            </td>

                            <td style="padding-right: 40px; text-align:left; ; vertical-align: bottom; white-space: nowrap;">

                                {{-- <p><strong>FECHA DE ENTRADA:</strong>{{ $inscripcion->movimientoRegistral->created_at->format('d-m-Y') }}</p> --}}
                                <p style="margin: 0"><strong>Fecha de impresión: </strong>{{ now()->format('d-m-Y H:i:s') }}</p>
                                <p style="margin: 0"><strong>IMPRESO POR: </strong>{{  auth()->user()->name }}</p>

                            </td>

                        </tr>
                    </tbody>
                </table>

            </div>

        </div>

    </main>
</body>
</html>
