<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Certificaciónes</title>
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

        <div style="text-align: center; font-weight: bold; font-size: 11px;">
            <p style="margin: 0">GOBIERNO DEL ESTADO DE MICHOACÁN DE OCAMPO</p>
            <P style="margin: 0">SECRETARÍA DE FINANZAS Y ADMINISTRACIÓN</P>
            <P style="margin: 0">INSTITUTO REGISTRAL Y CATASTRAL DEL ESTADO DE MICHOACÁN</P>
            <P style="margin: 0">DIRECCIÓN DEL REGISTRO PÚBLICO  DE LA PROPIEDAD</P>
        </div>

        <div style="font-size: 10px; margin-top: 20px;">

            <p class="titulo">Copias certificadas</p>

            <p class="parrafo">
                EL CIUDADANO <strong>@if($distrito == '02 URUAPAN') Lic. SANDRO MEDINA MORALES COORDINADOR REGIONAL URUAPAN @else LICENCIADO EN DERECHO {{ $director }}, DIRECTOR DEL REGISTRO PÚBLICO DE LA PROPIEDAD @endif </strong>
                EN EL ESTADO DE MICHOACÁN DE OCAMPO QUE ACTUA CONFORME A LA LEY, CERTIFICA QUE LA PRESENTE COPIA ES
                FIELMENTE TOMADA DE LA QUE OBRE EN EL

                @if($folio_real)
                    FOLIO REAL NÚMERO <strong>{{ $folio_real }} ({{ $folio_real_letra }})</strong>
                    @if($movimiento_registral)
                        movimiento registral <strong>{{ $movimiento_registral }} ({{ $movimiento_registral_letra }})</strong>
                    @endif
                @else
                    REGISTRO NÚMERO <strong>{{ $registro }} @if($registro_bis) BIS @endif</strong> <strong>({{ $registro_letras }})</strong>
                    DEL TOMO <strong>{{ $tomo }} @if($tomo_bis) BIS @endif</strong> <strong>({{ $tomo_letras }})</strong>
                    DEL LIBRO DE <STRONG>{{ $seccion }}</STRONG>
                @endif
                CORRESPONDIENTE AL DISTRITO DE <strong>{{ $distrito }}</strong>, Y SE COMPULSA
                EN <strong>{{ $paginas }}</strong> <strong>({{ $paginas_letras }})</strong>  PAGINA(S) UTILES DEBIDAMENTE COTEJADAS
                EN <strong>CARPETA CON FOLIO NO. {{ $folio_carpeta }}</strong> PARA ENTREGARSE A LA ORDEN DE: <strong>{{ $solicitante }}</strong>, DOY FE.-
            </p>

            <p class="parrafo">
                SE HACE LA SIGUIENTE CERTIFICACIÓN EN @if($distrito == '02 URUAPAN') URUAPAN DEL PROGRESO @else MORELIA @endif MICHOACÁN, A LAS {{ $hora }} ({{ $hora_letras }}) HORAS {{ $minutos }} ({{ $minutos_letras }})
                MINUTOS DEL DÍA {{ $dia }} ({{ $dia_letras }}) DE {{ $mes }} DEL {{ $año }} ({{ $año_letras }}).
            </p>

        </div>

        <div class="firma" style="font-size: 10px; margin-top: 40px">

            <p class="atte" style="margin-bottom: 40px">
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

        <div class="control no-break">

            <p class="separador">DATOS DE CONTROL</p>

            <table style="margin-top: 10px">

                <tbody>
                    <tr>
                        <td style="padding-right: 40px;">

                            <img class="qr" src="{{ $qr }}" alt="QR">
                        </td>
                        <td style="padding-right: 40px;">

                            <p style="margin: 0"><strong>NÚMERO DE CONTROL: </strong>{{ $numero_control }}</p>
                            <p style="margin: 0"><strong>DERECHOS: </strong>${{ number_format($derechos, 2) }}</p>
                            <p style="margin: 0"><strong>Tipo de servicio: </strong>{{ $tipo_servicio }}</p>
                            <p style="margin: 0"><strong>Servicio: </strong>{{ $servicio }}</p>
                            <p style="margin: 0"><strong>Elaborado en: </strong>{{ now()->format('d/m/Y') }}</p>
                            <p style="margin: 0"><strong>elaborado por:</strong> {{ $elaboro }}</p>

                        </td>
                    </tr>
                </tbody>

            </table>

        </div>

    </main>

</body>
</html>
