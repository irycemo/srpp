<div>

    <x-header>Asignación de folio real de persona moral</x-header>

    @if(!$movimientoRegistral)

        <div class="p-4 bg-white shadow-xl rounded-xl mb-5 space-y-3">

            <span class="flex items-center justify-center text-lg text-gray-700 md:col-span-3 col-span-1 sm:col-span-2">Documento de entrada</span>

            <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto">

                <x-input-group for="escritura_numero" label="Número de escritura" :error="$errors->first('escritura_numero')" class="w-full">

                    <x-input-text type="number" id="escritura_numero" wire:model="escritura_numero" />

                </x-input-group>

                <x-input-group for="escritura_notaria" label="Número de notaría" :error="$errors->first('escritura_notaria')" class="w-full">

                    <x-input-text type="number" id="escritura_notaria" wire:model="escritura_notaria" />

                </x-input-group>

                <x-input-group for="escritura_nombre_notario" label="Nombre del notario" :error="$errors->first('escritura_nombre_notario')" class="w-full">

                    <x-input-text id="escritura_nombre_notario" wire:model="escritura_nombre_notario" />

                </x-input-group>

            </div>

            <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto">

                <x-input-group for="escritura_fecha_inscripcion" label="Fecha de inscripcion" :error="$errors->first('escritura_fecha_inscripcion')" class="w-full">

                    <x-input-text type="date" id="escritura_fecha_inscripcion" wire:model="escritura_fecha_inscripcion" />

                </x-input-group>

                <x-input-group for="escritura_fecha_escritura" label="Fecha de la escritura" :error="$errors->first('escritura_fecha_escritura')" class="w-full">

                    <x-input-text type="date" id="escritura_fecha_escritura" wire:model="escritura_fecha_escritura" />

                </x-input-group>

                <x-input-group for="escritura_numero_hojas" label="Número de hojas" :error="$errors->first('escritura_numero_hojas')" class="w-full">

                    <x-input-text type="number" id="escritura_numero_hojas" wire:model="escritura_numero_hojas" />

                </x-input-group>

                <x-input-group for="escritura_numero_paginas" label="Número de paginas" :error="$errors->first('escritura_numero_paginas')" class="w-full">

                    <x-input-text type="number" id="escritura_numero_paginas" wire:model="escritura_numero_paginas" />

                </x-input-group>

            </div>

        </div>

    @endif

    <div class="p-4 bg-white shadow-xl rounded-xl mb-5 space-y-3">

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto">

            <x-input-group for="denominacion" label="Denominación ó razón social" :error="$errors->first('denominacion')" class="w-full">

                <x-input-text id="denominacion" wire:model="denominacion" />

            </x-input-group>

        </div>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto">

            <x-input-group for="fecha_constitucion" label="Fecha de constitución" :error="$errors->first('fecha_constitucion')" class="w-full">

                <x-input-text type="date" id="fecha_constitucion" wire:model="fecha_constitucion" />

            </x-input-group>

            <x-input-group for="capital" label="Capital" :error="$errors->first('capital')" class="w-full">

                <x-input-text type="number" id="capital" wire:model="capital" />

            </x-input-group>

            <x-input-group for="duracion" label="Duración" :error="$errors->first('duracion')" class="w-full">

                <x-input-text type="number" id="duracion" wire:model="duracion" />

            </x-input-group>

            <x-input-group for="distrito" label="Distrito" :error="$errors->first('distrito')" class="w-full">

                <x-input-select id="distrito" wire:model="distrito" class="w-full">

                    <option value="">Seleccione una opción</option>

                    @foreach ($distritos as $key => $distrito)

                        <option value="{{ $key }}">{{ $distrito }}</option>

                    @endforeach

                </x-input-select>

            </x-input-group>

        </div>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto">

            <x-input-group for="descripcion" label="Descripción" :error="$errors->first('descripcion')" class="w-full">

                <textarea rows="3" class="w-full bg-white rounded" wire:model="descripcion"></textarea>

            </x-input-group>

        </div>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto">

            <x-input-group for="observaciones" label="Observaciones" :error="$errors->first('observaciones')" class="w-full">

                <textarea rows="3" class="w-full bg-white rounded" wire:model="observaciones"></textarea>

            </x-input-group>

        </div>

    </div>

    <div class="p-4 bg-white shadow-xl rounded-xl mb-5">

        <div class="w-full lg:w-1/2 justify-center mx-auto">

            <span class="flex items-center justify-center text-gray-700">Participantes</span>

            <div class="">

                <div class="mb-2 flex justify-end">

                    <x-button-blue wire:click="abrirModalCrear">Agregar participante</x-button-blue>

                </div>

                <div>

                    <x-table>

                        <x-slot name="head">
                            <x-table.heading >Participante</x-table.heading>
                            <x-table.heading >Tipo</x-table.heading>
                            <x-table.heading ></x-table.heading>
                        </x-slot>

                        <x-slot name="body">

                            {{-- @foreach ($reforma?->actores as $participante)

                                <x-table.row >

                                    <x-table.cell>{{ $participante->persona->razon_social }}</x-table.cell>
                                    <x-table.cell>
                                        <div class="flex items-center gap-3">
                                            <x-button-blue
                                                wire:click="abrirModalEditarActor({{ $participante->id }})"
                                                wire:loading.attr="disabled"
                                            >
                                                Editar
                                            </x-button-blue>
                                            <x-button-red
                                                wire:click="borrarActor({{ $participante->id }})"
                                                wire:loading.attr="disabled">
                                                Borrar
                                            </x-button-red>
                                        </div>
                                    </x-table.cell>

                                </x-table.row>

                            @endforeach --}}

                        </x-slot>

                        <x-slot name="tfoot"></x-slot>

                    </x-table>

                </div>

            </div>

        </div>

    </div>

    @if(count($errors) > 0)

        <div class="mb-5 bg-white rounded-lg p-2 shadow-lg flex gap-2 flex-wrap ">

            <ul class="flex gap-2 felx flex-wrap list-disc ml-5">
            @foreach ($errors->all() as $error)

                <li class="text-red-500 text-xs md:text-sm ml-5">
                    {{ $error }}
                </li>

            @endforeach

        </ul>

        </div>

    @endif

    <div class="bg-white rounded-lg p-3 flex justify-end shadow-lg gap-3">

        @if($movimientoRegistral)

            @if(!$movimientoRegistral->documentoEntrada())

                <x-button-blue
                    wire:click="abrirModalFinalizar"
                    wire:loading.attr="disabled"
                    wire:target="abrirModalFinalizar">

                    <img wire:loading wire:target="abrirModalFinalizar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    Subir documento de entrada

                </x-button-blue>

            @else

                <div class="inline-block">

                    <x-link-blue target="_blank" href="{{ $vario->movimientoRegistral->documentoEntrada() }}">Documento de entrada</x-link-blue>

                </div>

            @endif

        @endif

        <x-button-blue
            wire:click="guardar"
            wire:loading.attr="disabled"
            wire:target="guardar">

            <img wire:loading wire:target="guardar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

            Guardar y continuar

        </x-button-blue>

        <x-button-green
            wire:click="finalizar"
            wire:loading.attr="disabled"
            wire:target="finalizar">

            <img wire:loading wire:target="finalizar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

            Finalizar inscripción

        </x-button-green>

    </div>

</div>
