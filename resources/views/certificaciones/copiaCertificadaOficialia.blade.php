<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Certificaciónes</title>
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
        margin-top: 150px;
        counter-reset: page;
        height: 100%;
        background-image: url("storage/img/escudo_fondo.png");
        background-size: cover;
        font-family: sans-serif;
        font-weight: normal;
        line-height: 1.5;
    }

    .center{
        display: block;
        margin-left: auto;
        margin-right: auto;
        width: 50%;
    }

    .container{
        font-size: 14px;
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
                    EL CIUDADANO <strong>@if($distrito == '02 URUAPAN') L.A. SANDRO MEDINA MORALES COORDINADOR REGIONAL URUAPAN @else LICENCIADO EN DERECHO {{ $director }}, DIRECTOR DEL REGISTRO PÚBLICO DE LA PROPIEDAD @endif </strong>
                    EN EL ESTADO DE MICHOACÁN DE OCAMPO QUE ACTUA CONFORME A LA LEY, CERTIFICA QUE LA PRESENTE COPIA ES
                    FIELMENTE TOMADA DE LA QUE OBRE EN EL REGISTRO NÚMERO <strong>{{ $registro }} @if($registro_bis) BIS @endif</strong> <strong>({{ $registro_letras }})</strong>
                    DEL TOMO <strong>{{ $tomo }} @if($tomo_bis) BIS @endif</strong> <strong>({{ $tomo_letras }})</strong> DEL LIBRO DE <STRONG>{{ $seccion }}</STRONG> CORRESPONDIENTE AL
                    DISTRITO DE <strong>@if($distrito == '02 URUAPAN') URUAPAN @else {{ $distrito }} @endif</strong>, Y SE COMPULSA
                    EN <strong>{{ $paginas }}</strong> <strong>({{ $paginas_letras }})</strong>  PAGINA(S) UTILES DEBIDAMENTE COTEJADAS,
                    SOLICITADAS MEDIANTE OFICIO <strong>{{ $numero_oficio }}</strong> PARA ENTREGARSE A LA ORDEN DE: <strong>{{ $solicitante }}</strong>, DOY FE.-
                </p>

                <p class="parrafo">
                    SE HACE LA SIGUIENTE CERTIFICACIÓN EN @if($distrito == '02 URUAPAN') URUAPAN DEL PROGRESO @else MORELIA @endif MICHOACÁN, A LAS {{ $hora }} ({{ $hora_letras }}) HORAS {{ $minutos }} ({{ $minutos_letras }})
                    MINUTOS DEL DÍA {{ $dia }} ({{ $dia_letras }}) DE {{ $mes }} DEL {{ $año }} ({{ $año_letras }}).
                </p>

            </div>

            <div class="firma">

                <p class="atte">
                    <strong>A T E N T A M E N T E</strong>
                </p>

                <p class="borde">
                    @if($distrito == '02 URUAPAN' )

                        <p class="borde">Lic. SANDRO MEDINA MORALES </p>
                        <p style="margin:0;">COORDINADOR REGIONAL 4 PURHÉPECHA (URUAPAN)</p>

                    @else {{ $director }} @endif
                </p>

            </div>

            <div class="control">

                <strong>DATOS DE CONTROL</strong>

                <table class="tabla">

                    <tbody>

                        <tr>
                            <td>NÚMERO DE CONTROL</td>
                            <td>{{ $numero_control }}</td>
                            <td>SUPERVISO</td>
                            <td>{{ $superviso }}</td>
                        </tr>

                        <tr>
                            <td>FOLIO DE CARPETA</td>
                            <td>{{ $folio_carpeta }}</td>
                            <td>ELABORO</td>
                            <td>{{ $elaboro }}</td>
                        </tr>

                        <tr>
                            <td>DERECHOS</td>
                            <td>${{ number_format($derechos, 2) }}</td>
                            <td>FECHA EXPEDICION</td>
                            <td>{{ now()->format('d-m-Y') }}</td>
                        </tr>

                        <tr>
                            <td>SERVICIO</td>
                            <td>{{ $tipo_servicio }}</td>
                            <td>FECHA ENTREGA</td>
                            <td>{{ $fecha_entrega->format('d-m-Y') }}</td>
                        </tr>

                    </tbody>

                </table>

                <div class="">

                    <img class="qr" src="{{ $qr }}" alt="QR">

                </div>

            </div>

        </div>

    </main>

    <script type="text/php">
        if (isset($pdf)) {
            $x = 280;
            $y = 810;
            $text = "Página: {PAGE_NUM} de {PAGE_COUNT}";
            $font = null;
            $size = 9;
            $color = array(0,0,0);
            $word_space = 0.0;  //  default
            $char_space = 0.0;  //  default
            $angle = 0.0;   //  default
            $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
        }
    </script>

</body>
</html>
