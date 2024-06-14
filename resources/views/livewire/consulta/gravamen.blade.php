<div class="w-full mt-3 text-sm" x-data="{selected : null}">

    @foreach ($folioReal->gravamenes as $gravamen)

        <div @click="selected != {{ $loop->index }} ? selected = {{ $loop->index }} : selected = null">

            <x-h4>

                Movimiento registral: ({{ $gravamen->movimientoRegistral->folio }})

                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 float-right" :class="selected == 1 ? 'transform rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="gray">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 9l-7 7-7-7" />
                </svg>

            </x-h4>

        </div>

        <div
            class="mb-2 overflow-hidden max-h-0 transition-all duration-500"
            x-ref="tab{{ $loop->index }}"
            :style="selected == {{ $loop->index }} ? 'max-height: ' + $refs.{{ 'tab' . $loop->index }}.scrollHeight + 'px;' :  ''">

            <div >

                <div class="bg-white p-4 rounded-lg  mb-3">

                    <span class="flex items-center justify-center  text-gray-700 md:col-span-3 col-span-1 sm:col-span-2">Antecedente</span>

                    <div class="flex gap-3 justify-center">

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <strong>Tomo</strong>

                            <p>{{ $gravamen->movimientoRegistral->tomo }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <strong>Regsitro</strong>

                            <p>{{ $gravamen->movimientoRegistral->registro }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <strong>Distrito</strong>

                            <p>{{ $gravamen->movimientoRegistral->distrito }}</p>

                        </div>

                    </div>

                </div>

                <div class="bg-white p-4 rounded-lg  grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-3">

                    <span class="flex items-center justify-center  text-gray-700 md:col-span-3 col-span-1 sm:col-span-2 lg:col-span-6">Documento de entrada</span>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Tipo de documento</strong>

                        <p>{{ $gravamen->movimientoRegistral->tipo_documento }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Cargo de la autoridad</strong>

                        <p>{{ $gravamen->movimientoRegistral->autoridad_cargo }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Nombre de la autoridad</strong>

                        <p>{{ $gravamen->movimientoRegistral->autoridad_nombre }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Número de documento</strong>

                        <p>{{ $gravamen->movimientoRegistral->numero_documento }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Fecha de emisión</strong>

                        <p>{{ $gravamen->movimientoRegistral->fecha_emision }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Dependencia</strong>

                        <p>{{ $gravamen->movimientoRegistral->procedencia }}</p>

                    </div>

                </div>

                <div class="bg-white p-4 rounded-lg  grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-3">

                    <span class="flex items-center justify-center  text-gray-700 md:col-span-3 col-span-1 sm:col-span-2 lg:col-span-6">Datos del gravámen</span>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Acto contenido</strong>

                        <p>{{ $gravamen->acto_contenido }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Tipo</strong>

                        <p>{{ $gravamen->tipo }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Valor del gravamen</strong>

                        <p>${{ number_format($gravamen->valor_gravamen, 2) }} {{ $gravamen->divisa }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Fecha de inscripción</strong>

                        <p>{{ $gravamen->fecha_inscripcion }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Estado</strong>

                        <p>{{ $gravamen->estado }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2 md:col-span-3 col-span-1 sm:col-span-2 lg:col-span-6">

                        <strong>Comentario del gravámen</strong>

                        <p>{{ $gravamen->observaciones }}</p>

                    </div>

                </div>

                <div class="bg-white p-4 rounded-lg mb-3">

                    <span class="flex items-center justify-center text-gray-700 ">Actores</span>

                    <x-table>

                        <x-slot name="head">
                            <x-table.heading >Tipo</x-table.heading>
                            <x-table.heading >Nombre / Razón social</x-table.heading>
                        </x-slot>

                        <x-slot name="body">

                            @foreach ($gravamen->deudores as $deudor)

                                <x-table.row >

                                    <x-table.cell>{{ $deudor->tipo }}</x-table.cell>
                                    @if($deudor->persona)

                                        <x-table.cell>{{ $deudor->persona->nombre }} {{ $deudor->persona->ap_paterno }} {{ $deudor->persona->ap_materno }} {{ $deudor->persona->razon_social }}</x-table.cell>

                                    @elseif($deudor->actor)

                                        <x-table.cell>{{ $deudor->actor->persona->nombre }} {{ $deudor->actor->persona->ap_paterno }} {{ $deudor->actor->persona->ap_materno }} {{ $deudor->actor->persona->razon_social }}</x-table.cell>

                                    @endif

                                </x-table.row>

                            @endforeach

                        </x-slot>

                        <x-slot name="tfoot"></x-slot>

                    </x-table>

                </div>

                <div class="bg-white p-4 rounded-lg mb-3">

                    <span class="flex items-center justify-center text-gray-700 ">Acreedores</span>

                    <x-table>

                        <x-slot name="head">
                            <x-table.heading >Nombre / Razón social</x-table.heading>
                        </x-slot>

                        <x-slot name="body">

                            @foreach ($gravamen->acreedores as $acreedor)

                                <x-table.row >

                                    <x-table.cell>{{ $acreedor->persona->nombre }} {{ $acreedor->persona->ap_paterno }} {{ $acreedor->persona->ap_materno }} {{ $acreedor->persona->razon_social }}</x-table.cell>

                                </x-table.row>

                            @endforeach

                        </x-slot>

                        <x-slot name="tfoot"></x-slot>

                    </x-table>

                </div>

            </div>

        </div>

    @endforeach

</div>
