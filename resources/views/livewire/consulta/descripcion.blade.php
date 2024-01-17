<div class="bg-white rounded-lg p-4 shadow-xl grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 text-sm mb-3">

    <span class="flex items-center justify-center text-lg text-gray-700 md:col-span-3 col-span-1 sm:col-span-2 lg:col-span-6">Descripción del predio</span>

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <strong>Cuenta predial</strong>

        <p>{{ $folioReal->predio->cuentaPredial() }}</p>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <strong>Clave catastral</strong>

        <p>{{ $folioReal->predio->claveCatastral() }}</p>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <strong>Superficie de terreno</strong>

        <p>{{ number_format($folioReal->predio->superficie_terreno, 2) }} {{ $folioReal->predio->unidad_area }}</p>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <strong>Superficie de construcción</strong>

        <p>{{ number_format($folioReal->predio->superficie_construccion, 2) }} {{ $folioReal->predio->unidad_area }}</p>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <strong>Monto de la transacción</strong>

        <p>${{ number_format($folioReal->predio->monto_transaccion, 2) }} {{ $folioReal->predio->divisa }}</p>

    </div>

    @if($folioReal->predio->curt)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>CURT</strong>

            <p>{{ $folioReal->predio->curt }}</p>

        </div>

    @endif

    @if($folioReal->predio->superficie_judicial)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Superficie judicial</strong>

            <p>{{ number_format($folioReal->predio->superficie_judicial, 2) }} {{ $folioReal->predio->unidad_area }}</p>

        </div>

    @endif

    @if($folioReal->predio->superficie_notarial)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Superficie notarial</strong>

            <p>{{ number_format($folioReal->predio->superficie_notarial, 2) }} {{ $folioReal->predio->unidad_area }}</p>

        </div>

    @endif

    @if($folioReal->predio->area_comun_terreno)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Área de terreno común</strong>

            <p>{{ number_format($folioReal->predio->area_comun_terreno, 2) }} {{ $folioReal->predio->unidad_area }}</p>

        </div>

    @endif

    @if($folioReal->predio->area_comun_construccion)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Área de contrucción común</strong>

            <p>{{ number_format($folioReal->predio->area_comun_construccion, 2) }} {{ $folioReal->predio->unidad_area }}</p>

        </div>

    @endif

    @if($folioReal->predio->valor_terreno_comun)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Valor de terreno común</strong>

            <p>${{ number_format($folioReal->predio->valor_terreno_comun, 2) }} {{ $folioReal->predio->divisa }}</p>

        </div>

    @endif

    @if($folioReal->predio->valor_construccion_comun)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Valor de construcción común</strong>

            <p>${{ number_format($folioReal->predio->valor_construccion_comun, 2) }} {{ $folioReal->predio->divisa }}</p>

        </div>

    @endif

    @if($folioReal->predio->valor_total_terreno)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Valor total del terreno</strong>

            <p>${{ number_format($folioReal->predio->valor_total_terreno, 2) }} {{ $folioReal->predio->divisa }}</p>

        </div>

    @endif

    @if($folioReal->predio->valor_total_construccion)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Valor total de la contrucción</strong>

            <p>${{ number_format($folioReal->predio->valor_total_construccion, 2) }} {{ $folioReal->predio->divisa }}</p>

        </div>

    @endif

    @if($folioReal->predio->valor_catastral)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Valor catastral</strong>

            <p>${{ number_format($folioReal->predio->valor_catastral, 2) }} {{ $folioReal->predio->divisa }}</p>

        </div>

    @endif

    @if($folioReal->predio->descripcion)

        <div class="rounded-lg bg-gray-100 py-1 px-2 md:col-span-3 col-span-1 sm:col-span-2 lg:col-span-6">

            <strong>Descripción</strong>

            <p>{{ $folioReal->predio->descripcion }}</p>

        </div>

    @endif

</div>

<div class="bg-white rounded-lg p-4 shadow-xl text-sm">

    <span class="flex items-center justify-center text-lg text-gray-700">Colindancias</span>

    @foreach ($folioReal->predio->colindancias as $colindancia)

        <div class="grid grid-cols-1 md:cols-2 lg:grid-cols-12 gap-3 items-start my-2  ">

            <div class="rounded-lg bg-gray-100 py-1 px-2 col-span-1 lg:col-span-2">

                <strong>Viento</strong>

                <p>{{ $colindancia->viento }}</p>

            </div>

            <div class="rounded-lg bg-gray-100 py-1 px-2 col-span-1 lg:col-span-2">

                <strong>Longitud</strong>

                <p>{{ $colindancia->longitud }}</p>

            </div>

            <div class="rounded-lg bg-gray-100 py-1 px-2 col-span-1 lg:col-span-8">

                <strong>Descripción</strong>

                <p>{{ $colindancia->descripcion }}</p>

            </div>

        </div>

        @if (!$loop->last)

            <hr class="my-2">

        @endif

    @endforeach

</div>
