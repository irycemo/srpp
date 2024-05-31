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
        margin-top: 100px;
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

            <p class="fundamento">
                EL CIUDADANO LICENCIADO EN DERECHO {{ $director }} DIRECTOR DEL REGISTRO PÚBLICO DE LA PROPIEDAD
            </p>

        </div>

        <p style="text-align: center"><strong>FOLIO REAL:</strong> {{ $movimientoRegistral->folioReal->folio }}</p>

        <p style="text-align: center"><strong>SECCIÓN:</strong> {{ $predio->folioReal->seccion_antecedente }}; <strong>DISTRITO:</strong> {{ $predio->folioReal->distrito}}; <strong>TOMO:</strong> {{ $predio->folioReal->tomo_antecedente }}({{ $tomo_numero }}), <strong>REGISTRO:</strong> {{ $predio->folioReal->registro_antecedente }} ({{ $registro_numero }}) DE FECHA {{ $fecha }}.</p>

        <div class="informacion">

            <p class="parrafo">
                <strong>CERTIFICA</strong> QUE DEACUERDO A LA BUSQUEDA EN EL INMUEBLE:

                <br>

                <strong>CÓDIGO POSTAL:</strong> {{ $predio->codigo_postal }}; <strong>TIPO DE ASENTAMIENTO:</strong> {{ $predio->tipo_asentamiento }}; <strong>NOMBRE DEL ASENTAMIENTO:</strong> {{ $predio->nombre_asentamiento }}; <strong>MUNICIPIO:</strong> {{ $predio->municipio }};

                <strong>CIUDAD:</strong> {{ $predio->ciudad }}; <strong>LOCALIDAD:</strong> {{ $predio->localidad }}; <strong>TIPO DE VIALIDAD:</strong> {{ $predio->tipo_vialidad }}; <strong>NOMBRE DE LA VIALIDAD:</strong> {{ $predio->nombre_vialidad }};

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

                DEL MUNICIPIO y DISTRITO DE <strong>{{ $predio->folioReal->distrito }}</strong> Y QUE SE REGISTRA A FAVOR DE:

                @foreach ($predio->propietarios() as $propietario)

                    <strong>{{ $propietario->persona->nombre }} {{ $propietario->persona->ap_paterno }} {{ $propietario->persona->ap_materno }} {{ $propietario->persona->razon_social }}</strong>

                @endforeach

            </p>

            <br>

            @if($gravamenes->count())

                <p>REPORTA EL(LOS) SIGUIENTE(S) GRAVAMEN(ES):</p>

                @foreach ($gravamenes as $gravamen)

                    <p class="parrafo">
                        <strong>Tomo: </strong> {{ $gravamen->movimientoRegistral->tomo_gravamen }}
                        <strong>Registro: </strong>{{ $gravamen->movimientoRegistral->tomo_gravamen }}
                        <strong>Distrito: </strong>{{ $gravamen->movimientoRegistral->distrito }}
                        CON FECHA DE INSCRIPCIÓN {{ Carbon\Carbon::parse($gravamen->fecha_inscripcion)->format('d-m-Y') }}
                        RELATIVO A {{ $gravamen->acto_contenido }}
                        CELEBRADO POR EL(LOS) ACREDOR(ES):
                        @foreach ($gravamen->acreedores as $acreedor)

                            {{ $acreedor->persona->nombre }} {{ $acreedor->persona->ap_paterno }} {{ $acreedor->persona->ap_materno }} {{ $acreedor->persona->razon_social }}

                        @endforeach
                        Y COMO DEUDOR(ES)
                        @foreach ($gravamen->deudores as $deudor)

                            @if($deudor->actor)

                                {{ $deudor->actor->persona->nombre }} {{ $deudor->actor->persona->ap_paterno }} {{ $deudor->actor->persona->ap_materno }} {{ $deudor->actor->persona->razon_social }}

                            @else

                                {{ $deudor->persona->nombre }} {{ $deudor->persona->ap_paterno }} {{ $deudor->persona->ap_materno }} {{ $deudor->persona->razon_social }}

                            @endif

                        @endforeach
                        POR LA CANTIDAD DE ${{ number_format($gravamen->valor_gravamen, 2) }} {{ $formatter->toWords($gravamen->valor_gravamen) }}.
                    </p>

                    <br>

                @endforeach

            @else

                <p class="parrafo">
                    LA MISMA BUSQUEDA DIO POR RESULTADO QUE A LA FECHA, NO APARECE DECLARATORIA ALGUNA QUE ESTABLEZCA SOBRE EL INMUEBLE DE REFERENCIA, PROVISIONES, DESTINOS, USOS
                    O RESERVAS CON ARREGLO A LO DISPUESTO POR LA LEY GENERAL DE ASENTAMIENTOS HUMANOS.
                </p>

                <br>

            @endif

            <p class="parrafo">
                A SOLIDITUD DE: <strong>{{ $movimientoRegistral->solicitante }}</strong> EXPEDIDO EL PRESENTE CERTIFICADO EN LA CIUDAD DE MORELIA, MICHOACÁN, A LAS
                {{ Carbon\Carbon::now()->locale('es')->isoFormat('H:s dddd D \d\e MMMM \d\e\l Y'); }}.
            </p>

            <p class="fundamento">datos de control</p>

            <div class="firma">

                <p class="atte">
                    <strong>A T E N T A M E N T E</strong>
                </p>

                <p class="borde">{{ $director }}</p>
                <p style="margin: 0">DIRECTOR DEL REGISTRO PÚBLICO  DE LA PROPIEDAD</p>

            </div>

            <div class="control">

                <strong>DATOS DE CONTROL</strong>

                <table style="font-size: 9px">
                    <tbody>
                        <tr>
                            <td style="padding-right: 40px; text-align:left; ; vertical-align: bottom; white-space: nowrap;">

                                <p style="margin: 0"><strong>NÚMERO DE CONTROL: </strong>{{ $movimientoRegistral->año }}-{{ $movimientoRegistral->tramite }}-{{ $movimientoRegistral->usuario }}</p>
                                <p style="margin: 0"><strong>DERECHOS: </strong>${{ number_format($movimientoRegistral->monto, 2) }}</p>
                                <p style="margin: 0"><strong>Tipo de servicio: </strong>{{ $movimientoRegistral->tipo_servicio }}</p>

                            </td>

                            <td style="padding-right: 40px; text-align:left; ; vertical-align: top; white-space: nowrap;">

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
