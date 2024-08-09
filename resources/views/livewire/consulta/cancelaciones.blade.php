<div class="w-full mt-3 text-sm" x-data="{selected : null}">

    @foreach ($folioReal->cancelaciones as $cancelacion)

        <div @click="selected != {{ $loop->index }} ? selected = {{ $loop->index }} : selected = null">

            <x-h4>

                Movimiento registral: ({{ $cancelacion->movimientoRegistral->folio }})

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

                            <p>{{ $cancelacion->movimientoRegistral->tomo_gravamen }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <strong>Regsitro</strong>

                            <p>{{ $cancelacion->movimientoRegistral->registro_gravamen }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <strong>Distrito</strong>

                            <p>{{ $cancelacion->movimientoRegistral->distrito }}</p>

                        </div>

                    </div>

                </div>

                <div class="bg-white p-4 rounded-lg  grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-3">

                    <span class="flex items-center justify-center  text-gray-700 md:col-span-3 col-span-1 sm:col-span-2 lg:col-span-6">Documento de entrada</span>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Tipo de documento</strong>

                        <p>{{ $cancelacion->movimientoRegistral->tipo_documento }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Cargo de la autoridad</strong>

                        <p>{{ $cancelacion->movimientoRegistral->autoridad_cargo }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Nombre de la autoridad</strong>

                        <p>{{ $cancelacion->movimientoRegistral->autoridad_nombre }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Número de documento</strong>

                        <p>{{ $cancelacion->movimientoRegistral->numero_documento }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Fecha de emisión</strong>

                        <p>{{ $cancelacion->movimientoRegistral->fecha_emision }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Dependencia</strong>

                        <p>{{ $cancelacion->movimientoRegistral->procedencia }}</p>

                    </div>

                </div>

                <div class="bg-white p-4 rounded-lg  grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-3">

                    <span class="flex items-center justify-center  text-gray-700 md:col-span-3 col-span-1 sm:col-span-2 lg:col-span-6">Datos del gravámen</span>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Acto contenido</strong>

                        <p>{{ $cancelacion->acto_contenido }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Tipo</strong>

                        <p>{{ $cancelacion->tipo }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Fecha de inscripción</strong>

                        <p>{{ $cancelacion->fecha_inscripcion }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <strong>Estado</strong>

                        <p>{{ $cancelacion->estado }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2 md:col-span-3 col-span-1 sm:col-span-2 lg:col-span-6">

                        <strong>Comentario del gravámen</strong>

                        <p>{{ $cancelacion->observaciones }}</p>

                    </div>

                </div>

                <div class="bg-white p-4 rounded-lg mb-3 flex gap-3 items-center justify-end">

                    <x-link-blue target="_blank" href="{{ $cancelacion->movimientoRegistral->documentoEntrada() }}">Documento de entrada</x-link-blue>

                    <x-link-blue target="_blank" href="{{ $cancelacion->movimientoRegistral->caratula() }}">Caratula</x-link-blue>

                </div>

            </div>

        </div>

    @endforeach

</div>
