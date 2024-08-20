<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rechazo</title>
</head>
<style>

@page {
        size: A5 landscape;
    }

    header{
        position: fixed;
        top: 0cm;
        left: 0cm;
        right: 0cm;
        height: 80px;
        text-align: center;
    }

    header img{
        height: 80px;
        display: block;
        margin-left: auto;
        margin-right: auto;
    }


    body{
        margin-top: 90px;
        margin-bottom: 20px;
        margin-left: auto;
        margin-right: auto;
        counter-reset: page;
        height: 100%;
        background-image: url("storage/img/escudo_fondo.png");
        background-size: contain;
        background-position:center;
        background-repeat: no-repeat;
        font-family: sans-serif;
        font-weight: normal;
        line-height: 1.5;
        text-transform: uppercase
    }

    .container{
        font-size: 10px;
        display: flex;
        align-content: space-around;
    }

    .tabla{
        width: 100%;
        font-size: 10px;
        margin-bottom: 30px;;
        margin-left: auto;
        margin-right: auto;
    }

    .borde{
        display: inline;
        border-top: 1px solid;
    }

</style>
<body>

    <header>


            <img src="{{ public_path('storage/img/encabezado.png') }}" alt="encabezado">


    </header>

    <main>

        <div class="container">

            <p style="text-align: center; font-weight: bold; font-size: 11px;">Movimiento registral rechazado</p>

            <p>
                el movimiento registral con numero de control: {{ $movimientoRegistral->año }}-{{ $movimientoRegistral->tramite }}-{{ $movimientoRegistral->usuario }}
                ha sido rechazado.
            </p>
            <p><strong>Motivo del rechazo: </strong>{{ $motivo }}</p>
            <p><strong>Observaciones: </strong>{{ $observaciones }}</p>

            <div class="firmas">

                <table class="tabla">

                    <thead>

                        <tr>
                            <th >
                                <p>Rechaza</p>
                                <p class="borde" style="font-weight: 400; vertical-align: top">{{ auth()->user()->name }}</p>
                            </th>
                            <th>
                                <p>Autoriza</p>
                                <p class="borde" style="font-weight: 400; vertical-align: top">{{ auth()->user()->name }}</p>
                            </th>
                        </tr>

                    </thead>

                </table>

            </div>

            <p><strong>Impreso por: </strong>{{ auth()->user()->name }}, el {{ now()->format('d-m-Y H:i:s') }}</p>

        </div>

    </main>
</body>
</html>
