<div class="w-full mt-3 text-sm" x-data="{selected : null}">

    @foreach ($folioReal->propiedad as $inscripcion)

        <div @click="selected != {{ $loop->index }} ? selected = {{ $loop->index }} : selected = null">

            <x-h4>

                Movimiento registral: ({{ $inscripcion->movimientoRegistral->folio }})

                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 float-right" :class="selected == 1 ? 'transform rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="gray">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 9l-7 7-7-7" />
                </svg>

            </x-h4>

        </div>

        <div
            class="mb-2 overflow-hidden max-h-0 transition-all duration-500"
            x-ref="tab{{ $loop->index }}"
            :style="selected == {{ $loop->index }} ? 'max-height: ' + $refs.{{ 'tab' . $loop->index }}.scrollHeight + 'px;' :  ''">

            <div>

                <div class="bg-white p-4 rounded-lg  mb-3">

                    <span class="flex items-center justify-center  text-gray-700 md:col-span-3 col-span-1 sm:col-span-2">Antecedente</span>

                    <div class="flex gap-3 justify-center">

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <strong>Tomo</strong>

                            <p>{{ $inscripcion->movimientoRegistral->tomo }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <strong>Regsitro</strong>

                            <p>{{ $inscripcion->movimientoRegistral->registro }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <strong>Distrito</strong>

                            <p>{{ $inscripcion->movimientoRegistral->distrito }}</p>

                        </div>

                    </div>

                </div>

                <div class="bg-white p-4 rounded-lg  grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-3">

                    <span class="flex items-center justify-center  text-gray-700 md:col-span-3 col-span-1 sm:col-span-2 lg:col-span-6">Documento de entrada</span>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Tipo de documento</strong>

                        <p>{{ $inscripcion->movimientoRegistral->tipo_documento }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Cargo de la autoridad</strong>

                        <p>{{ $inscripcion->movimientoRegistral->autoridad_cargo }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Nombre de la autoridad</strong>

                        <p>{{ $inscripcion->movimientoRegistral->autoridad_nombre }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Número de documento</strong>

                        <p>{{ $inscripcion->movimientoRegistral->numero_documento }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Fecha de emisión</strong>

                        <p>{{ $inscripcion->movimientoRegistral->fecha_emision }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Dependencia</strong>

                        <p>{{ $inscripcion->movimientoRegistral->procedencia }}</p>

                    </div>

                </div>

                <div class="bg-white p-4 rounded-lg  grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-3">

                    <span class="flex items-center justify-center  text-gray-700 md:col-span-3 col-span-1 sm:col-span-2 lg:col-span-6">Datos de la inscripción de propiedad</span>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Acto contenido</strong>

                        <p>{{ $inscripcion->acto_contenido }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Fecha de inscripción</strong>

                        <p>{{ $inscripcion->fecha_inscripcion }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2 md:col-span-3 col-span-1 sm:col-span-2 lg:col-span-6">

                        <strong>Comentario de la inscripción</strong>

                        <p>{{ $inscripcion->observaciones }}</p>

                    </div>

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

                            @if($inscripcion->propietarios()->count() > 0)

                                @foreach ($inscripcion->propietarios() as $propietario)

                                    <x-table.row >

                                        <x-table.cell>{{ $propietario->persona->nombre }} {{ $propietario->persona->ap_paterno }} {{ $propietario->persona->ap_materno }} {{ $propietario->persona->razon_social }}</x-table.cell>
                                        <x-table.cell>{{ number_format($propietario->porcentaje_propiedad, 2) }}%</x-table.cell>
                                        <x-table.cell>{{ number_format($propietario->porcentaje_nuda, 2) }}%</x-table.cell>
                                        <x-table.cell>{{ number_format($propietario->porcentaje_usufructo, 2) }}%</x-table.cell>

                                    </x-table.row>

                                @endforeach

                            @endif

                        </x-slot>

                        <x-slot name="tfoot"></x-slot>

                    </x-table>

                </div>

                <div class="mb-3 bg-white rounded-lg p-3 shadow-lg">

                    <span class="flex items-center justify-center text-lg text-gray-700 mb-5">Transmitentes</span>

                    <x-table>

                        <x-slot name="head">
                            <x-table.heading >Nombre / Razón social</x-table.heading>
                        </x-slot>

                        <x-slot name="body">

                            @if($inscripcion->transmitentes()->count() > 0)

                                @foreach ($inscripcion->transmitentes() as $transmitente)

                                    <x-table.row >

                                        <x-table.cell>{{ $transmitente->persona->nombre }} {{ $transmitente->persona->ap_paterno }} {{ $transmitente->persona->ap_materno }} {{ $transmitente->persona->razon_social }}</x-table.cell>

                                    </x-table.row>

                                @endforeach

                            @endif

                        </x-slot>

                        <x-slot name="tfoot"></x-slot>

                    </x-table>

                </div>

                @if($inscripcion->representantes()->count() > 0)

                    <div class="mb-3 bg-white rounded-lg p-3 shadow-lg">

                        <span class="flex items-center justify-center text-lg text-gray-700 mb-5">Representantes</span>

                        <x-table>

                            <x-slot name="head">
                                <x-table.heading >Nombre / Razón social</x-table.heading>
                                <x-table.heading >Representados</x-table.heading>
                            </x-slot>

                            <x-slot name="body">

                                @foreach ($inscripcion->representantes() as $representante)

                                    <x-table.row >

                                        <x-table.cell>{{ $representante->persona->nombre }} {{ $representante->persona->ap_paterno }} {{ $representante->persona->ap_materno }} {{ $representante->persona->razon_social }}</x-table.cell>
                                        <x-table.cell>

                                            @foreach ($representante->representados as $representado)

                                                <p>{{ $representado->persona->nombre }} {{ $representado->persona->ap_paterno }} {{ $representado->persona->ap_materno }} {{ $representante->persona->razon_social }}</p>

                                            @endforeach

                                        </x-table.cell>

                                    </x-table.row>

                                @endforeach

                            </x-slot>

                            <x-slot name="tfoot"></x-slot>

                        </x-table>

                    </div>

                @endif

                <div class="bg-white p-4 rounded-lg mb-3 flex gap-3 items-center justify-end">

                    <div>

                        @if($inscripcion->movimientoRegistral->documentoEntrada())

                            <x-link-blue target="_blank" href="{{ $inscripcion->movimientoRegistral->documentoEntrada() }}">Documento de entrada</x-link-blue>

                        @endif

                    </div>

                    <div>

                        @if($inscripcion->movimientoRegistral->caratula())

                            <x-link-blue target="_blank" href="{{ $inscripcion->movimientoRegistral->caratula() }}">Documento de entrada</x-link-blue>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    @endforeach

</div>
