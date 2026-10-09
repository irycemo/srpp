<div class="bg-white rounded-lg p-4 shadow-xl grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 text-sm mb-3">

    <span class="flex items-center justify-center text-lg text-gray-700 md:col-span-3 col-span-1 sm:col-span-2 lg:col-span-6">Ubicación del predio</span>

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <strong>Municipio</strong>

        <p>{{ $vario->movimientoRegistral->folioReal->predio->municipio }}</p>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <strong>Ciudad</strong>

        <p>{{ $vario->movimientoRegistral->folioReal->predio->ciudad }}</p>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <strong>Localidad</strong>

        <p>{{ $vario->movimientoRegistral->folioReal->predio->localidad }}</p>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <strong>Código postal</strong>

        <p>{{ $vario->movimientoRegistral->folioReal->predio->codigo_postal }}</p>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <strong>Tipo de asentamiento</strong>

        <p>{{ $vario->movimientoRegistral->folioReal->predio->tipo_asentamiento }}</p>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <strong>Nombre del asentamiento</strong>

        <p>{{ $vario->movimientoRegistral->folioReal->predio->nombre_asentamiento }}</p>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <strong>Tipo de vialidad</strong>

        <p>{{ $vario->movimientoRegistral->folioReal->predio->tipo_vialidad }}</p>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <strong>Nombre de la vialidad</strong>

        <p>{{ $vario->movimientoRegistral->folioReal->predio->nombre_vialidad }}</p>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <strong>Número exterior</strong>

        <p>{{ $vario->movimientoRegistral->folioReal->predio->numero_exterior }}</p>

    </div>

    @if($vario->movimientoRegistral->folioReal->predio->numero_interior)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Número interior</strong>

            <p>{{ $vario->movimientoRegistral->folioReal->predio->numero_interior }}</p>

        </div>

    @endif

    @if($vario->movimientoRegistral->folioReal->predio->nombre_edificio)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Edificio</strong>

            <p>{{ $vario->movimientoRegistral->folioReal->predio->nombre_edificio }}</p>

        </div>

    @endif

    @if($vario->movimientoRegistral->folioReal->predio->departamento_edificio)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Departamento</strong>

            <p>{{ $vario->movimientoRegistral->folioReal->predio->departamento_edificio }}</p>

        </div>

    @endif

    @if($vario->movimientoRegistral->folioReal->predio->lote)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Lote</strong>

            <p>{{ $vario->movimientoRegistral->folioReal->predio->lote }}</p>

        </div>

    @endif

    @if($vario->movimientoRegistral->folioReal->predio->manzana)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Manzana</strong>

            <p>{{ $vario->movimientoRegistral->folioReal->predio->manzana }}</p>

        </div>

    @endif

    @if($vario->movimientoRegistral->folioReal->predio->ejido)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Ejido</strong>

            <p>{{ $vario->movimientoRegistral->folioReal->predio->ejido }}</p>

        </div>

    @endif

    @if($vario->movimientoRegistral->folioReal->predio->parcela)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Parcela</strong>

            <p>{{ $vario->movimientoRegistral->folioReal->predio->parcela }}</p>

        </div>

    @endif

    @if($vario->movimientoRegistral->folioReal->predio->solar)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Solar</strong>

            <p>{{ $vario->movimientoRegistral->folioReal->predio->solar }}</p>

        </div>

    @endif

    @if($vario->movimientoRegistral->folioReal->predio->poblado)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Poblado</strong>

            <p>{{ $vario->movimientoRegistral->folioReal->predio->poblado }}</p>

        </div>

    @endif

    @if($vario->movimientoRegistral->folioReal->predio->numero_exterior_2)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Número exterior 2</strong>

            <p>{{ $vario->movimientoRegistral->folioReal->predio->numero_exterior_2 }}</p>

        </div>

    @endif

    @if($vario->movimientoRegistral->folioReal->predio->numero_adicional)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Número adicional</strong>

            <p>{{ $vario->movimientoRegistral->folioReal->predio->numero_adicional }}</p>

        </div>

    @endif

    @if($vario->movimientoRegistral->folioReal->predio->numero_adicional_2)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Número adicional 2</strong>

            <p>{{ $vario->movimientoRegistral->folioReal->predio->numero_adicional_2 }}</p>

        </div>

    @endif

    @if($vario->movimientoRegistral->folioReal->predio->lote_fraccionador)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Lote del fraccionador</strong>

            <p>{{ $vario->movimientoRegistral->folioReal->predio->lote_fraccionador }}</p>

        </div>

    @endif

    @if($vario->movimientoRegistral->folioReal->predio->manzana_fraccionador)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Manzana del fraccionador</strong>

            <p>{{ $vario->movimientoRegistral->folioReal->predio->manzana_fraccionador }}</p>

        </div>

    @endif

    @if($vario->movimientoRegistral->folioReal->predio->etapa_fraccionador)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Etapa del fraccionador</strong>

            <p>{{ $vario->movimientoRegistral->folioReal->predio->etapa_fraccionador }}</p>

        </div>

    @endif

    @if($vario->movimientoRegistral->folioReal->predio->clave_edificio)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Clave del edificio</strong>

            <p>{{ $vario->movimientoRegistral->folioReal->predio->clave_edificio }}</p>

        </div>

    @endif

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <strong>Superficie de terreno</strong>

        <p>@if($vario->movimientoRegistral->folioReal->predio->unidad_area == 'Hectareas') {{ $vario->movimientoRegistral->folioReal->predio->superficie_terreno_formateada }} @else {{ $vario->movimientoRegistral->folioReal->predio->superficie_terreno }} @endif {{ $vario->movimientoRegistral->folioReal->predio->unidad_area }}</p>

    </div>

    @if($vario->movimientoRegistral->folioReal->predio->observaciones)

        <div class="rounded-lg bg-gray-100 py-1 px-2 md:col-span-3 col-span-1 sm:col-span-2 lg:col-span-6">

            <strong>Observaciones</strong>

            <p>{{ $vario->movimientoRegistral->folioReal->predio->observaciones }}</p>

        </div>

    @endif

    @if($vario->movimientoRegistral->folioReal->predio->descripcion)

        <div class="rounded-lg bg-gray-100 py-1 px-2 md:col-span-3 col-span-1 sm:col-span-2 lg:col-span-6">

            <strong>Descripción</strong>

            <p>{{ $vario->movimientoRegistral->folioReal->predio->descripcion }}</p>

        </div>

    @endif

</div>

<div class="mb-3 bg-white rounded-lg p-3 shadow-lg">

    <span class="flex items-center justify-center text-lg text-gray-700 mb-5">Propietarios</span>

    <x-table>

        <x-slot name="head">
            <x-table.heading >Nombre / Razón social</x-table.heading>
            <x-table.heading >Porcentaje propiedad</x-table.heading>
            <x-table.heading >Porcentaje nuda</x-table.heading>
            <x-table.heading >Porcentaje usufructo</x-table.heading>
        </x-slot>

        <x-slot name="body">

            @if($vario->movimientoRegistral->folioReal->predio->propietarios()->count() > 0)

                @foreach ($vario->movimientoRegistral->folioReal->predio->propietarios() as $propietario)

                    <x-table.row >

                        <x-table.cell>{{ $propietario->persona->nombre }} {{ $propietario->persona->ap_paterno }} {{ $propietario->persona->ap_materno }} {{ $propietario->persona->razon_social }}</x-table.cell>
                        <x-table.cell>{{ $propietario->porcentaje_propiedad }}%</x-table.cell>
                        <x-table.cell>{{ $propietario->porcentaje_nuda }}%</x-table.cell>
                        <x-table.cell>{{ $propietario->porcentaje_usufructo }}%</x-table.cell>

                    </x-table.row>

                @endforeach

            @endif

        </x-slot>

        <x-slot name="tfoot"></x-slot>

    </x-table>

</div>
