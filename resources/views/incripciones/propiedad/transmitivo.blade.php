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
                <p style="margin: 0"><strong>FOLIO REAL:</strong>{{ $inscripcion->movimientoRegistral->folioReal->folio }}</p>
                <p style="margin: 0"><strong>DISTRITO:</strong>{{ $inscripcion->movimientoRegistral->distrito }}</p>
            </div>

            <div style="text-align: center">
                <p><strong>BOLETA DE PRESENTACIÓN</strong></p>
            </div>

            <div>

                <table >
                    <tbody>
                        <tr>
                            <td style="padding-right: 40px; text-align:left; ; vertical-align: bottom; white-space: nowrap;">

                                <p style="margin: 0"><strong>NÚMERO DE ENTRADA: </strong>{{ $inscripcion->movimientoRegistral->folio }}</p>
                                <p style="margin: 0"><strong>FECHA DE RECEPCIÓN: </strong>{{ $inscripcion->movimientoRegistral->fecha_pago }}</p>
                                <p style="margin: 0"><strong>ESTADO: </strong>{{ $inscripcion->movimientoRegistral->estado }}</p>
                                <p style="margin: 0"><strong>FECHA DE DOCUMENTO: </strong>{{ $inscripcion->movimientoRegistral->fecha_emision }}</p>

                            </td>

                            <td style="padding-right: 40px; text-align:left; ; vertical-align: bottom; white-space: nowrap;">

                                {{-- <p><strong>FECHA DE ENTRADA:</strong>{{ $inscripcion->movimientoRegistral->created_at->format('d-m-Y') }}</p> --}}
                                <p style="margin: 0"><strong>FEDATARIO CARGO: </strong>{{ $inscripcion->movimientoRegistral->autoridad_cargo }}</p>
                                <p style="margin: 0"><strong>FEDATARIO NOMBRE: </strong>{{ $inscripcion->movimientoRegistral->autoridad_nombre }}</p>
                                <p style="margin: 0"><strong>SOLICITANTE: </strong>{{ $inscripcion->movimientoRegistral->solicitante }}</p>
                                <p style="margin: 0"><strong>INSTRUMENTO: </strong>{{ $inscripcion->movimientoRegistral->tipo_documento }}: {{ $inscripcion->movimientoRegistral->numero_documento }}</p>

                            </td>

                        </tr>
                    </tbody>
                </table>

            </div>

            <div style="text-align: center">
                <p><strong>RESUMEN DE ACTOS</strong></p>
            </div>

            <div class="parrafo">
                <p>{{ $inscripcion->descripcion_acto }}</p>
            </div>

            <div>

                <p><strong>UBICACIÓN DEL INMUEBLE:</strong></p>

                <p class="parrafo">

                    <strong>CÓDIGO POSTAL:</strong> {{ $inscripcion->movimientoRegistral->folioReal->predio->codigo_postal }}; <strong>TIPO DE ASENTAMIENTO:</strong> {{ $inscripcion->movimientoRegistral->folioReal->predio->tipo_asentamiento }}; <strong>NOMBRE DEL ASENTAMIENTO:</strong> {{ $inscripcion->movimientoRegistral->folioReal->predio->nombre_asentamiento }}; <strong>MUNICIPIO:</strong> {{ $inscripcion->movimientoRegistral->folioReal->predio->municipio }};

                    <strong>CIUDAD:</strong> {{ $inscripcion->movimientoRegistral->folioReal->predio->ciudad }}; <strong>LOCALIDAD:</strong> {{ $inscripcion->movimientoRegistral->folioReal->predio->localidad }}; <strong>TIPO DE VIALIDAD:</strong> {{ $inscripcion->movimientoRegistral->folioReal->predio->tipo_vialidad }}; <strong>NOMBRE DE LA VIALIDAD:</strong> {{ $inscripcion->movimientoRegistral->folioReal->predio->nombre_vialidad }};

                    <strong>NÚMERO EXTERIOR:</strong> {{ $inscripcion->movimientoRegistral->folioReal->predio->numero_exterior ?? 'SN' }}; <strong>NÚMERO INTERIOR:</strong> {{ $inscripcion->movimientoRegistral->folioReal->predio->numero_interior ?? 'SN' }};

                    @if ($inscripcion->movimientoRegistral->folioReal->predio->nombre_edificio)
                        <strong>EDIFICIO:</strong> {{ $inscripcion->movimientoRegistral->folioReal->predio->nombre_edificio }};
                    @endif

                    @if ($inscripcion->movimientoRegistral->folioReal->predio->clave_edificio)
                        <strong>clave del edificio:</strong> {{ $inscripcion->movimientoRegistral->folioReal->predio->clave_edificio }};
                    @endif

                    @if ($inscripcion->movimientoRegistral->folioReal->predio->departamento_edificio)
                        <strong>DEPARTAMENTO:</strong> {{ $inscripcion->movimientoRegistral->folioReal->predio->departamento_edificio }};
                    @endif

                    @if ($inscripcion->movimientoRegistral->folioReal->predio->lote)
                        <strong>LOTE:</strong> {{ $inscripcion->movimientoRegistral->folioReal->predio->lote }};
                    @endif

                    @if ($inscripcion->movimientoRegistral->folioReal->predio->manzana)
                        <strong>MANZANA:</strong> {{ $inscripcion->movimientoRegistral->folioReal->predio->manzana }};
                    @endif

                    @if ($inscripcion->movimientoRegistral->folioReal->predio->ejido)
                        <strong>ejido:</strong> {{ $inscripcion->movimientoRegistral->folioReal->predio->ejido }};
                    @endif

                    @if ($inscripcion->movimientoRegistral->folioReal->predio->parcela)
                        <strong>parcela:</strong> {{ $inscripcion->movimientoRegistral->folioReal->predio->parcela }};
                    @endif

                    @if ($inscripcion->movimientoRegistral->folioReal->predio->solar)
                        <strong>solar:</strong> {{ $inscripcion->movimientoRegistral->folioReal->predio->solar }};
                    @endif

                    @if ($inscripcion->movimientoRegistral->folioReal->predio->poblado)
                        <strong>poblado:</strong> {{ $inscripcion->movimientoRegistral->folioReal->predio->poblado }};
                    @endif

                    @if ($inscripcion->movimientoRegistral->folioReal->predio->numero_exterior)
                        <strong>número exterior:</strong> {{ $inscripcion->movimientoRegistral->folioReal->predio->numero_exterior }};
                    @endif

                    @if ($inscripcion->movimientoRegistral->folioReal->predio->numero_exterior_2)
                        <strong>número exterior 2:</strong> {{ $inscripcion->movimientoRegistral->folioReal->predio->numero_exterior_2 }};
                    @endif

                    @if ($inscripcion->movimientoRegistral->folioReal->predio->numero_adicional)
                        <strong>número adicional:</strong> {{ $inscripcion->movimientoRegistral->folioReal->predio->numero_adicional }};
                    @endif

                    @if ($inscripcion->movimientoRegistral->folioReal->predio->numero_adicional_2)
                        <strong>número adicional 2:</strong> {{ $inscripcion->movimientoRegistral->folioReal->predio->numero_adicional_2 }};
                    @endif

                    @if ($inscripcion->movimientoRegistral->folioReal->predio->lote_fraccionador)
                        <strong>lote del fraccionador:</strong> {{ $inscripcion->movimientoRegistral->folioReal->predio->lote_fraccionador }};
                    @endif

                    @if ($inscripcion->movimientoRegistral->folioReal->predio->manzana_fraccionador)
                        <strong>manzana del fraccionador:</strong> {{ $inscripcion->movimientoRegistral->folioReal->predio->manzana_fraccionador }};
                    @endif

                    @if ($inscripcion->movimientoRegistral->folioReal->predio->etapa_fraccionador)
                        <strong>etapa del fraccionador:</strong> {{ $inscripcion->movimientoRegistral->folioReal->predio->etapa_fraccionador }};
                    @endif

                    @if ($inscripcion->movimientoRegistral->folioReal->predio->observaciones)
                        <strong>OBSERVACIONES:</strong> {{ $inscripcion->movimientoRegistral->folioReal->predio->observaciones }}.
                    @endif

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
