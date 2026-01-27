<div>

    <div class="bg-white rounded-lg p-4 shadow-lg mb-4">

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto mb-4">

            <span>{{ $inscripcion->acto_contenido }}</span>

        </div>

        <x-input-group for="inscripcion.descripcion_acto" label="Descripción del acto" :error="$errors->first('inscripcion.descripcion_acto')" class="w-full lg:w-1/2 mx-auto">

            <textarea class="bg-white rounded text-xs w-full  @error('inscripcion.descripcion_acto') border-1 border-red-500 @enderror" rows="4" wire:model="inscripcion.descripcion_acto"></textarea>

        </x-input-group>

    </div>

    @if($fideicomiso)

        <div class="bg-white rounded-lg p-4 shadow-lg mb-4">

            <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto mb-4 text-sm">

                <span>{{ $fideicomiso->tipo }}</span>

            </div>

            <x-input-group for="fideicomiso.objeto" label="Objeto del fideicomiso"  class="w-full lg:w-1/2 mx-auto mb-3">

                <textarea class="bg-white rounded text-xs w-full" readonly>{{ $fideicomiso->objeto }}</textarea>

            </x-input-group>

            <div class="w-full lg:w-1/2 mx-auto">

                <x-table>

                    <x-slot name="head">
                        <x-table.heading >Nombre / Razón social</x-table.heading>
                        <x-table.heading >Tipo de actor</x-table.heading>
                    </x-slot>

                    <x-slot name="body">

                        @foreach ($fideicomiso->actores as $actor)

                            <x-table.row wire:loading.class.delaylongest="opacity-50" wire:key="row-{{ $actor->id }}">

                                <x-table.cell>

                                    <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Nombre / Razón social</span>

                                    <p class="pt-4">{{ $actor->persona->nombre }} {{ $actor->persona->ap_paterno }} {{ $actor->persona->ap_materno }} {{ $actor->persona->razon_social }}</p>

                                </x-table.cell>
                                <x-table.cell>
                                    {{ $actor->tipo_actor }}
                                </x-table.cell>

                            </x-table.row>

                        @endforeach

                    </x-slot>

                    <x-slot name="tfoot"></x-slot>

                </x-table>

            </div>

        </div>

    @else

        <div class="bg-white rounded-lg p-4 shadow-lg mb-4">

            <div class="space-y-2 items-center w-full lg:w-1/2 text-center mx-auto mb-4">

                <x-input-group for="movimiento_folio" label="Folio del fideicomiso" :error="$errors->first('movimiento_folio')" class="inline-block">

                    <x-input-text type="number" id="movimiento_folio" wire:model="movimiento_folio" />

                </x-input-group>

                <x-button-blue
                    wire:click="buscarFideicomiso"
                    wire:loading.attr="disabled"
                    wire:target="buscarFideicomiso" class="mx-auto">

                    <img wire:loading wire:target="buscarFideicomiso" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    Buscar

                </x-button-blue>

            </div>

        </div>

    @endif

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

    <div class="bg-white rounded-lg p-3  justify-end shadow-lg flex gap-3">

        @if(!$inscripcion->movimientoRegistral->documentoEntrada())

            <x-button-blue
                wire:click="abrirModalDocumento"
                wire:loading.attr="disabled"
                wire:target="abrirModalDocumento">

                <img wire:loading wire:target="abrirModalDocumento" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                Subir documento de entrada

            </x-button-blue>

        @else

            <div class="inline-block">

                <x-link-blue target="_blank" href="{{ $inscripcion->movimientoRegistral->documentoEntrada() }}">Ver documento de entrada</x-link-blue>

            </div>

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

    <x-dialog-modal wire:model="modalContraseña" maxWidth="sm">

        <x-slot name="title">

            Ingresa tu contraseña

        </x-slot>

        <x-slot name="content">

            <x-input-group for="contraseña" label="Contraseña" :error="$errors->first('contraseña')" class="w-full">

                <x-input-text type="password" id="contraseña" wire:model="contraseña" />

            </x-input-group>

        </x-slot>

        <x-slot name="footer">

            <div class="flex gap-3">

                <x-button-blue
                    wire:click="inscribir"
                    wire:loading.attr="disabled"
                    wire:target="inscribir">

                    <img wire:loading wire:target="inscribir" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <span>Ingresar contraseña</span>
                </x-button-blue>

                <x-button-red
                    wire:click="$toggle('modalContraseña')"
                    wire:loading.attr="disabled"
                    wire:target="$toggle('modalContraseña')"
                    type="button">
                    Cerrar
                </x-button-red>

            </div>

        </x-slot>

    </x-dialog-modal>

    <x-dialog-modal wire:model="modalDocumento" maxWidth="sm">

        <x-slot name="title">

            Subir archivo

        </x-slot>

        <x-slot name="content">

            <x-filepond::upload wire:model="documento" :accepted-file-types="['application/pdf']"/>

            {{-- <x-filepond wire:model.live="documento" accept="['application/pdf']"/> --}}

            <div>

                @error('documento') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

            </div>

        </x-slot>

        <x-slot name="footer">

            <div class="flex gap-3">

                <x-button-blue
                    wire:click="guardarDocumento"
                    wire:loading.attr="disabled"
                    wire:target="guardarDocumento">

                    <img wire:loading wire:target="guardarDocumento" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <span>Guardar</span>

                </x-button-blue>

                <x-button-red
                    wire:click="$toggle('modalDocumento')"
                    wire:loading.attr="disabled"
                    wire:target="$toggle('modalDocumento')"
                    type="button">

                    <span>Cerrar</span>

                </x-button-red>

            </div>

        </x-slot>

    </x-dialog-modal>

    @filepondScripts

</div>
