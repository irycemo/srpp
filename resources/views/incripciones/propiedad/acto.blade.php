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
                <p><strong>por</strong> {{ $inscripcion->movimientoRegistral->tipo_documento }} <strong>n°</strong> {{ $inscripcion->movimientoRegistral->numero_documento }} <strong>de fecha</strong> {{ $inscripcion->movimientoRegistral->fecha_emision }} <strong>otorgado por</strong> {{ $inscripcion->movimientoRegistral->autoridad_cargo }} {{ $inscripcion->movimientoRegistral->autoridad_nombre }}
                    <strong>consta que </strong>
                    @foreach ($inscripcion->transmitentes() as $transmitente)

                        {{ $transmitente->persona->nombre }} {{ $transmitente->persona->ap_paterno }} {{ $transmitente->persona->ap_materno }} {{ $transmitente->persona->razon_social }}

                        @if($transmitente->representadoPor)

                            <strong>representado(a) por: </strong>{{ $transmitente->representadoPor->persona->nombre }} {{ $transmitente->representadoPor->persona->ap_paterno }} {{ $transmitente->representadoPor->persona->ap_materno }} {{ $transmitente->persona->razon_social }}

                        @endif

                    @endforeach
                    , <strong>comparecio arealizar el acto de </strong> {{ $inscripcion->acto_contenido }}.
                </p>
            </div>

            <div>

                <p><strong>propietarios:</strong></p>

                <p class="parrafo">

                    <ul>
                        @foreach ($inscripcion->propietarios() as $transmitente)

                            <li>
                                {{ $transmitente->persona->nombre }} {{ $transmitente->persona->ap_paterno }} {{ $transmitente->persona->ap_materno }} {{ $transmitente->persona->razon_social }},
                                @if($transmitente->persona->curp)<strong>estado civil:</strong> {{ $transmitente->persona->estado_civil }},@endif
                                @if($transmitente->persona->curp)<strong>curp:</strong> {{ $transmitente->persona->curp }},@endif
                                <strong>rfc:</strong> {{ $transmitente->persona->rfc }},
                                <strong>nacionalidad:</strong> {{ $transmitente->persona->nacionalidad }},
                                <strong>porcentaje de nuda:</strong> {{ $transmitente->porcentaje_nuda }},
                                <strong>porcentaje de usufructo:</strong> {{ $transmitente->porcentaje_usufructo }}.
                            </li>

                        @endforeach

                    </ul>

                </p>

            </div>

            <div class="firma">

                <p class="atte">
                    <strong>A T E N T A M E N T E</strong>
                </p>

                @if($distrito == '02 URUAPAN' )

                    <p class="borde">
                        L.A. SANDRO MEDINA MORALES
                    </p>

                @else

                    <p class="borde">{{ $director }}</p>
                    <p style="margin: 0">DIRECTOR DEL REGISTRO PÚBLICO  DE LA PROPIEDAD</p>

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

                            <td style="padding-right: 40px; text-align:center; width: 50%; vertical-align: bottom; white-space: nowrap;">

                                <p class="borde">{{ $jefe_departamento }}</p>
                                <p style="margin: 0">JEFE DE Departamento de Registro de Inscripciones	</p>
                            </td>

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

                                <p style="margin: 0"><strong>NÚMERO DE CONTROL: </strong>{{ $inscripcion->movimientoRegistral->año }}-{{ $inscripcion->movimientoRegistral->tramite }}</p>
                                <p style="margin: 0"><strong>DERECHOS: </strong>{{ $inscripcion->movimientoRegistral->monto }}</p>
                                <p style="margin: 0"><strong>Tipo de servicio: </strong>{{ $inscripcion->movimientoRegistral->tipo_servicio }}</p>

                            </td>

                            <td style="padding-right: 40px; text-align:left; ; vertical-align: bottom; white-space: nowrap;">

                                {{-- <p><strong>FECHA DE ENTRADA:</strong>{{ $inscripcion->movimientoRegistral->created_at->format('d-m-Y') }}</p> --}}
                                <p style="margin: 0"><strong>Impreso en: </strong>{{ now()->format('d-m-Y H:i:s') }}</p>
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
