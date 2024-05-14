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
                <p style="margin: 0"><strong>FOLIO REAL:</strong>{{ $inscripcion->movimientoRegistral->folioReal->folio }}-{{ $inscripcion->movimientoRegistral->folio }}</p>
                <p style="margin: 0"><strong>DISTRITO:</strong>{{ $inscripcion->movimientoRegistral->distrito }}</p>
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
                    , <strong>comparecio arealizar el acto de </strong> {{ $inscripcion->acto_contenido }}.
                </p>

                <p>
                    <strong>Descripción del predio:</strong> {{ $inscripcion->descripcion }}.

                    <strong>CÓDIGO POSTAL:</strong> {{ $inscripcion->codigo_postal }}; <strong>TIPO DE ASENTAMIENTO:</strong> {{ $inscripcion->tipo_asentamiento }}; <strong>NOMBRE DEL ASENTAMIENTO:</strong> {{ $inscripcion->nombre_asentamiento }}; <strong>MUNICIPIO:</strong> {{ $inscripcion->municipio }};

                    <strong>CIUDAD:</strong> {{ $inscripcion->ciudad }}; <strong>LOCALIDAD:</strong> {{ $inscripcion->localidad }}; <strong>TIPO DE VIALIDAD:</strong> {{ $inscripcion->tipo_vialidad }}; <strong>NOMBRE DE LA VIALIDAD:</strong> {{ $inscripcion->nombre_vialidad }};

                    <strong>NÚMERO EXTERIOR:</strong> {{ $inscripcion->numero_exterior ?? 'SN' }}; <strong>NÚMERO INTERIOR:</strong> {{ $inscripcion->numero_interior ?? 'SN' }};
                </p>

                <table>

                    <thead>

                        <tr>
                            <th style="text-align: left;">Viento</th>
                            <th style="text-align: left;">Longitud</th>
                            <th style="text-align: left;">Descripción</th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($inscripcion->movimientoRegistral->folioReal->predio->colindancias as $colindancia)

                            <tr>
                                <td style="padding-right: 40px;">
                                    <p>{{ $colindancia->viento }}</p>
                                </td>
                                <td style="padding-right: 40px;">
                                    <p>{{ number_format($colindancia->longitud, 2) }}</p>
                                </td>
                                <td>
                                    <p>{{ $colindancia->descripcion }}</p>
                                </td>
                            </tr>

                        @endforeach

                    </tbody>

                </table>

                <p><strong>Valor: {{ $inscripcion->monto_transaccion }}</strong></p>

            </div>

            <div>

                <p><strong>propietarios:</strong></p>

                <p class="parrafo">

                    <table class="tabla" >
                        <tbody sty>

                            @foreach ($inscripcion->propietarios() as $transmitente)

                                <tr>
                                    <td style="padding-right: 40px; text-align:center; width: 50%; vertical-align: bottom; white-space: nowrap;">

                                        {{ $transmitente->persona->nombre }} {{ $transmitente->persona->ap_paterno }} {{ $transmitente->persona->ap_materno }} {{ $transmitente->persona->razon_social }}

                                    </td>

                                    <td style="padding-right: 40px; text-align:center; width: 50%; vertical-align: bottom; white-space: nowrap;">

                                        {{ $transmitente->porcentaje_nuda }}%
                                    </td>

                                    <td style="padding-right: 40px; text-align:center; width: 50%; vertical-align: bottom; white-space: nowrap;">

                                        {{ $transmitente->porcentaje_usufructo }}%
                                    </td>

                                </tr>

                            @endif
                        </tbody>
                    </table>

                </p>

            </div>

            <div class="firma">

                <p class="atte">
                    <strong>El DIRECTOR DEL REGISTRO PÚBLICO  DE LA PROPIEDAD</strong>
                </p>

                <p class="borde">{{ $director }}</p>

            </div>

            <div style="margin-top: 50px;">

                <table class="tabla" >
                    <tbody sty>
                        <tr>
                            <td style="padding-right: 40px; text-align:center; width: 50%; vertical-align: bottom; white-space: nowrap;">

                                <p class="borde">{{ $inscripcion->movimientoRegistral->asignadoA->name }}</p>
                                <p style="margin: 0">REGISTRADOR</p>

                            </td>

                            @if($distrito == '02 URUAPAN' )

                                <td style="padding-right: 40px; text-align:center; width: 50%; vertical-align: bottom; white-space: nowrap;">

                                    <p class="borde">{{ $jefe_departamento }}</p>
                                    <p style="margin: 0">JEFE DE Departamento de Registro de Inscripciones	</p>
                                </td>

                            @endif

                        </tr>
                    </tbody>
                </table>

            </div>

            <div class="control">

                <strong>DATOS DE CONTROL</strong>

                <table style="font-size: 9px">
                    <tbody>
                        <tr>
                            <td style="padding-right: 40px; text-align:left; ; vertical-align: bottom; white-space: nowrap;">

                                <p style="margin: 0"><strong>NÚMERO DE CONTROL: </strong>{{ $inscripcion->movimientoRegistral->año }}-{{ $inscripcion->movimientoRegistral->tramite }}-{{ $inscripcion->movimientoRegistral->usuario }}</p>
                                <p style="margin: 0"><strong>DERECHOS: </strong>{{ $inscripcion->movimientoRegistral->monto }}</p>
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
