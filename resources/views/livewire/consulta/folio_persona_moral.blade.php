<div class="w-full mt-3 text-sm" x-data="{selected : null}">

    <div class="bg-white rounded-lg p-4 shadow-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 text-sm mb-3">

        <span class="flex items-center justify-center text-lg text-gray-700 md:col-span-3 col-span-1 sm:col-span-2 lg:col-span-6">Antecedente</span>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Tomo</strong>

            <p>{{ $folioReal->tomo_antecedente  ?? 'N/A' }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Registro</strong>

            <p>{{ $folioReal->registro_antecedente  ?? 'N/A' }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Distrito</strong>

            <p>{{ $folioReal->distrito }}</p>

        </div>

    </div>

    <div class="bg-white rounded-lg p-4 shadow-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 text-sm mb-3">

        <span class="flex items-center justify-center text-lg text-gray-700 md:col-span-3 col-span-1 sm:col-span-2 lg:col-span-5">Escritura</span>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Número de escritura</strong>

            <p>{{ $folioReal->escritura->numero }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Número de notaría</strong>

            <p>{{ $folioReal->escritura->notaria }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Nombre del notario</strong>

            <p>{{ $folioReal->escritura->nombre_notario }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Estado del notario</strong>

            <p>{{ $folioReal->escritura->estado_notario }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Fecha de inscripcion</strong>

            <p>{{ $folioReal->escritura->fecha_inscripcion }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Fecha de la escritura</strong>

            <p>{{ $folioReal->escritura->fecha_escritura }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Número de hojas</strong>

            <p>{{ $folioReal->escritura->numero_hojas }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Número de paginas</strong>

            <p>{{ $folioReal->escritura->numero_paginas }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2 col-span-1 sm:col-span-2 md:col-span-3 lg:col-span-5">

            <strong>Observaciones</strong>

            <p>{{ $folioReal->escritura->comentario }}</p>

        </div>

    </div>

    <div class="bg-white rounded-lg p-4 shadow-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 text-sm mb-3">

        <span class="flex items-center justify-center text-lg text-gray-700 md:col-span-3 col-span-1 sm:col-span-2 lg:col-span-6">Generales</span>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Denominación</strong>

            <p>{{ $folioReal->denominacion }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Fecha de constitución</strong>

            <p>{{ $folioReal->fecha_constitucion }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Fecha de inscripción</strong>

            <p>{{ $folioReal->fecha_inscripcion }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Fecha de disolución</strong>

            <p>{{ $folioReal->fecha_disolucion ?? 'N/A' }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Duración</strong>

            <p>{{ $folioReal->duracion }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Capital</strong>

            <p>{{ $folioReal->capital }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Tipo</strong>

            <p>{{ $folioReal->tipo }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2 col-span-2">

            <strong>Domicilio</strong>

            <p>{{ $folioReal->domicilio }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2 col-span-3">

            <strong>Observaciones</strong>

            <p>{{ $folioReal->observaciones ?? 'N/A' }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2 md:col-span-3 col-span-1 sm:col-span-2 lg:col-span-6">

            <strong>Objeto</strong>

            <p>{{ $folioReal->objetoActual?->objeto  }}</p>

        </div>

    </div>

    <div class="bg-white rounded-lg p-4 shadow-xl text-sm mb-3">

        <span class="flex items-center justify-center text-lg text-gray-700 md:col-span-3 col-span-1 sm:col-span-2 lg:col-span-6">Actores</span>

        <x-table>

            <x-slot name="head">
                <x-table.heading >Nombre / Razón social</x-table.heading>
                <x-table.heading >Tipo</x-table.heading>
            </x-slot>

            <x-slot name="body">

                @forelse ($folioReal->actores as $actor)

                    <x-table.row >

                        <x-table.cell>{{ $actor->persona->nombre }} {{ $actor->persona->ap_paterno }} {{ $actor->persona->ap_materno }} {{ $actor->persona->razon_social }}</x-table.cell>
                        <x-table.cell>{{ $actor->tipo_socio }}</x-table.cell>

                    </x-table.row>

                @empty

                @endforelse

            </x-slot>

            <x-slot name="tfoot"></x-slot>

        </x-table>

    </div>

    <div class="bg-white p-4 rounded-lg mb-3 flex gap-3 items-center justify-between shadow-lg">

        <div>

            @if($folioReal->documentoEntrada())

                <x-link-blue target="_blank" href="{{ $folioReal->documentoEntrada() }}">Documento de entrada</x-link-blue>

            @endif

        </div>

        <div>

            @if($folioReal->caratula())

                <x-link-blue target="_blank" href="{{ $folioReal->caratula() }}">Caratula</x-link-blue>

            @endif

        </div>

    </div>


</div>
