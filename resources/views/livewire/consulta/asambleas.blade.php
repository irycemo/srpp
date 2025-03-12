<div class="w-full mt-3 text-sm" x-data="{selected : null}">

    @foreach ($folioReal->reformas as $reforma)

        <div @click="selected != {{ $loop->index }} ? selected = {{ $loop->index }} : selected = null">

            <x-h4>

                Movimiento registral: ({{ $reforma->movimientoRegistral->folio }})

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

                <div class="bg-white p-4 rounded-lg  grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-3">

                    <span class="flex items-center justify-center  text-gray-700 md:col-span-3 col-span-1 sm:col-span-2 lg:col-span-6">Documento de entrada</span>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Tipo de documento</strong>

                        <p>{{ $reforma->movimientoRegistral->tipo_documento }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Cargo de la autoridad</strong>

                        <p>{{ $reforma->movimientoRegistral->autoridad_cargo }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Nombre de la autoridad</strong>

                        <p>{{ $reforma->movimientoRegistral->autoridad_nombre }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Número de documento</strong>

                        <p>{{ $reforma->movimientoRegistral->numero_documento }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Fecha de emisión</strong>

                        <p>{{ $reforma->movimientoRegistral->fecha_emision }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Dependencia</strong>

                        <p>{{ $reforma->movimientoRegistral->procedencia }}</p>

                    </div>

                </div>

                <div class="bg-white p-4 rounded-lg  grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-3">

                    <span class="flex items-center justify-center  text-gray-700 md:col-span-3 col-span-1 sm:col-span-2 lg:col-span-6">Datos de la asmablea</span>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Fecha de inscripción</strong>

                        <p>{{ $reforma->fecha_inscripcion }}</p>

                    </div>


                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Fecha de protocolización</strong>

                        <p>{{ $reforma->fecha_protocolizacion }}</p>

                    </div>


                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Acto contenido</strong>

                        <p>{{ $reforma->acto_contenido }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2 md:col-span-3 col-span-1 sm:col-span-2 lg:col-span-6">

                        <strong>Descripción</strong>

                        <p>{{ $reforma->descripcion }}</p>

                    </div>

                </div>

                <div class="bg-white p-4 rounded-lg mb-3 flex gap-3 items-center justify-end">

                    <div>

                        @if($reforma->movimientoRegistral->documentoEntrada())

                            <x-link-blue target="_blank" href="{{ $reforma->movimientoRegistral->documentoEntrada() }}">Documento de entrada</x-link-blue>

                        @endif

                    </div>

                    <div>

                        @if($reforma->movimientoRegistral->caratula())

                            <x-link-blue target="_blank" href="{{ $reforma->movimientoRegistral->caratula() }}">Caratula</x-link-blue>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    @endforeach

</div>
