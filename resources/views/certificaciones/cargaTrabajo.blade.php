<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Carga de trabajo</title>
</head>
<style>
    main{
        font-size: 12px;
    }
</style>
<body>
    <main>

        <div class="">

            <div>

                <p class="parrafo">
                    Carga de trabajo. Copias @if($servicio == 'DL13') certificadas @else simples @endif. Fecha inicial: {{ $fecha_inicio }} - Fecha final: {{ $fecha_final }}
                </p>
                <p>{{ auth()->user()->name }}</p>

                <div>

                    <table class="table">

                        <thead>

                            <tr>
                                <th>
                                    Número de control
                                </th>
                                <th>
                                    Tipo de servicio
                                </th>
                                <th>
                                    Tomo
                                </th>
                                <th>
                                    Registro
                                </th>
                                <th>
                                    Distrito
                                </th>
                                <th>
                                    Sección
                                </th>
                                <th>
                                    Número de páginas
                                </th>
                                <th>
                                    Fecha de entrega
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach ($carga as $item)

                                <tr>

                                    <td>{{ $item->tramite }}</td>
                                    <td>{{ $item->tipo_servicio }}</td>
                                    <td>{{ $item->tomo }}</td>
                                    <td>{{ $item->registro }}</td>
                                    <td>{{ $item->distrito }}</td>
                                    <td>{{ $item->seccion }}</td>
                                    <td>{{ $item->certificacion->numero_paginas }}</td>
                                    <td>{{ $item->fecha_entrega->format('d-m-Y') }}</td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


            </div>

        </div>

    </main>

</body>
</html>
