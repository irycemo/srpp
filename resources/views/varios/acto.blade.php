<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Varios</title>
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

    .atte{
        margin-bottom: 40px;
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

            <div style="text-align: center; font-weight: bold; font-size: 11px;">
                <p style="margin: 0">GOBIERNO DEL ESTADO DE MICHOACÁN DE OCAMPO</p>
                <P style="margin: 0">SECRETARÍA DE FINANZAS Y ADMINISTRACIÓN</P>
                <P style="margin: 0">INSTITUTO REGISTRAL Y CATASTRAL DEL ESTADO DE MICHOACÁN</P>
                <P style="margin: 0">DIRECCIÓN DEL REGISTRO PÚBLICO  DE LA PROPIEDAD</P>
            </div>

            <div class="informacion">

                <div style="text-align: right">
                    <p style="margin:0;"><strong>movimiento registral:</strong>{{ $predio->folioReal->folio }}-{{ $vario->movimientoRegistral->folio }}</p>
                    <p style="margin:0;"><strong>DISTRITO:</strong> {{ $vario->movimientoRegistral->distrito}}</p>
                </div>

                <div style="text-align: center">
                    <p><strong>{{ $vario->acto_contenido }}</strong></p>
                </div>

                @if($vario->acto_contenido == 'PERSONAS MORALES')

                    <p class="parrafo">

                        <p><strong>FOLIO REAL de persona moral:</strong>{{ $vario->movimientoRegistral->folioRealPersona->folio }}; <strong>Denominación:</strong> {{ $vario->movimientoRegistral->folioRealPersona->denominacion }}; <strong>Fecha de celebarción:</strong>{{ $vario->movimientoRegistral->folioRealPersona->fecha_celebracion }}; <strong>Fecha de inscripción:</strong>{{ $vario->movimientoRegistral->folioRealPersona->fecha_inscripcion }}.

                    </p>

                    <p class="parrafo">

                        <strong>Fecha de celebarción:</strong>{{ $vario->movimientoRegistral->folioRealPersona->fecha_celebracion }}; <strong>Fecha de inscripción:</strong>{{ $vario->movimientoRegistral->folioRealPersona->fecha_inscripcion }}.

                    </p>

                    <p class="parrafo">

                        <strong>Notaria:</strong> {{ $vario->movimientoRegistral->folioRealPersona->notaria }}; <strong>Nombre del notario:</strong>{{ $vario->movimientoRegistral->folioRealPersona->nombre_notario }}; <strong>Número de hojas:</strong>{{ $vario->movimientoRegistral->folioRealPersona->numero_hojas }}.

                    </p>

                    <p class="parrafo">

                        <strong>Descripción:</strong> {{ $vario->movimientoRegistral->folioRealPersona->descripcion }}

                    </p>

                    <p class="parrafo">

                        <strong>Observaciones:</strong> {{ $vario->movimientoRegistral->folioRealPersona->observaciones }}

                    </p>

                    <p class="separador">Participantes</p>

                    <table>

                        <thead>

                            <tr>
                                <th >Razón social</th>
                                <th >RFC</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach ($vario->movimientoRegistral->folioRealPersona->actores as $propietario)

                                <tr>
                                    <td style="padding-right: 40px;">
                                        {{ $propietario->persona->razon_social }}
                                    </td>
                                    <td style="padding-right: 40px;">
                                        {{ $propietario->persona->rfc }}
                                    </td>
                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                @else

                    @include('comun.caratulas.ubicacion_inmueble')

                    @if($predio->colindancias->count())

                        @include('comun.caratulas.colindancias')

                    @endif

                    @include('comun.caratulas.descripcion_inmueble')

                    @include('comun.caratulas.propietarios')

                    <p class="parrafo">
                        <strong>descripción del acto:</strong> {{ $vario->descripcion }}
                    </p>

                @endif


                <p class="parrafo">
                    A SOLICITUD DE: <strong>{{ $vario->movimientoRegistral->solicitante }}</strong> se expide EL PRESENTE EN LA CIUDAD DE @if($predio->folioReal->distrito== '02 Uruapan' ) uruapan @else MORELIA @endif MICHOACÁN, A LAS
                    {{ Carbon\Carbon::now()->locale('es')->translatedFormat('H:i:s \d\e\l l d \d\e F \d\e\l Y'); }}.
                </p>

            </div>

            <div class="firma no-break">

                <p class="atte">
                    <strong>A T E N T A M E N T E</strong>
                </p>

                @if($vario->movimientoRegistral->distrito == '02 Uruapan' )
                    <p class="borde">Lic. SANDRO MEDINA MORALES </p>
                    <p style="margin:0;">COORDINADOR REGIONAL 4 PURHÉPECHA (URUAPAN)</p>
                @else
                    <p class="borde" style="margin:0;">{{ $director }}</p>
                    <p style="margin:0;">Director del registro público de la propiedad</p>
                @endif

                <div style="margin-top: 50px;">

                    <table class="tabla" >
                        <tbody sty>
                            <tr>
                                <td style="padding-right: 40px; text-align:center; width: 50%; vertical-align: bottom; white-space: nowrap;">

                                    <p class="borde">{{ $vario->movimientoRegistral->asignadoA->name }}</p>
                                    <p style="margin: 0">REGISTRADOR</p>

                                </td>

                                @if($vario->movimientoRegistral->distrito != '02 Uruapan' )

                                    <td style="padding-right: 40px; text-align:center; width: 50%; vertical-align: bottom; white-space: nowrap;">

                                        <p class="borde">{{ $jefe_departamento }}</p>
                                        <p style="margin: 0">JEFE DE Departamento de Registro de Inscripciones	</p>
                                    </td>

                                @endif

                            </tr>
                        </tbody>
                    </table>

                </div>

            </div>

            <div class="control no-break">

                <p class="separador">DATOS DE CONTROL</p>

                <table style="font-size: 9px">
                    <tbody>
                        <tr>
                            <td style="padding-right: 40px; text-align:left; ; vertical-align: bottom; white-space: nowrap;">

                                <p style="margin: 0"><strong>NÚMERO DE CONTROL: </strong>{{ $vario->movimientoRegistral->año }}-{{ $vario->movimientoRegistral->tramite }}-{{ $vario->movimientoRegistral->usuario }}</p>
                                <p style="margin: 0"><strong>DERECHOS: </strong>${{ number_format($vario->movimientoRegistral->monto, 2) }}</p>
                                <p style="margin: 0"><strong>Tipo de servicio: </strong>{{ $vario->movimientoRegistral->tipo_servicio }}</p>
                                <p style="margin: 0"><strong>Servicio: </strong>{{ $servicio }}</p>

                            </td>

                            <td style="padding-right: 40px; text-align:left; ; vertical-align: tom; white-space: nowrap;">

                                <p style="margin: 0"><strong>Fecha de impresión: </strong>{{ now()->format('d/m/Y H:i:s') }}</p>
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
