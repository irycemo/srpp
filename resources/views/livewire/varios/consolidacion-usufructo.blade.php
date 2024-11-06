<div>

    <div class="p-4 bg-white shadow-xl rounded-xl mb-5">

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto">

            <span>{{ $vario->acto_contenido }}</span>

        </div>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto">

            <x-input-group for="vario.descripcion" label="Comentario del movimiento" :error="$errors->first('vario.descripcion')" class="w-full">

                <textarea rows="3" class="w-full bg-white rounded" wire:model="vario.descripcion"></textarea>

            </x-input-group>

        </div>

    </div>

    <div class="p-4 bg-white shadow-xl rounded-xl mb-5">

        <div class="w-full lg:w-1/2 justify-center mx-auto">

            <span class="flex items-center justify-center text-gray-700">Propietarios</span>

            <div class="">

                <div>

                    <x-table>

                        <x-slot name="head">
                            <x-table.heading >Nombre / Razón social</x-table.heading>
                            <x-table.heading >% de propiedad</x-table.heading>
                            <x-table.heading >% de nuda</x-table.heading>
                            <x-table.heading >% de usufructo</x-table.heading>
                            <x-table.heading ></x-table.heading>
                        </x-slot>

                        <x-slot name="body">

                            @foreach ($vario->actores as $propietario)

                                <x-table.row >

                                    <x-table.cell>{{ $propietario->persona->nombre }} {{ $propietario->persona->ap_paterno }} {{ $propietario->persona->ap_materno }} {{ $propietario->persona->razon_social }}</x-table.cell>
                                    <x-table.cell>{{ $propietario->porcentaje_propiedad }}</x-table.cell>
                                    <x-table.cell>{{ $propietario->porcentaje_nuda }}</x-table.cell>
                                    <x-table.cell>{{ $propietario->porcentaje_usufructo }}</x-table.cell>
                                    <x-table.cell>
                                        <div class="flex items-center gap-3">
                                            <x-button-blue
                                                wire:click="abrirModalEditarPropietario({{ $propietario->id }})"
                                                wire:loading.attr="disabled"
                                            >
                                                Editar
                                            </x-button-blue>
                                        </div>
                                    </x-table.cell>

                                </x-table.row>

                            @endforeach

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

        @if(!$vario->movimientoRegistral->documentoEntrada())

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

    <x-dialog-modal wire:model="modalPersona">

        <x-slot name="title">

            Editar porcentajes

        </x-slot>

        <x-slot name="content">

            <div class="flex flex-col md:flex-row justify-between gap-3 mb-3">

                <x-input-group for="porcentaje_propiedad" label="% Propiedad" :error="$errors->first('porcentaje_propiedad')" class="w-full">

                    <x-input-text id="porcentaje_propiedad" wire:model="porcentaje_propiedad" />

                </x-input-group>

                <x-input-group for="porcentaje_nuda" label="% Nuda" :error="$errors->first('porcentaje_nuda')" class="w-full">

                    <x-input-text id="porcentaje_nuda" wire:model="porcentaje_nuda" />

                </x-input-group>

                <x-input-group for="porcentaje_usufructo" label="% Usufructo" :error="$errors->first('porcentaje_usufructo')" class="w-full">

                    <x-input-text id="porcentaje_usufructo" wire:model="porcentaje_usufructo" />

                </x-input-group>

            </div>

        </x-slot>

        <x-slot name="footer">

            <div class="flex gap-3">

                <x-button-blue
                    wire:click="actualizarPorcentajes"
                    wire:loading.attr="disabled"
                    wire:target="actualizarPorcentajes">

                    <img wire:loading wire:target="actualizarPorcentajes" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <span>Actualizar</span>

                </x-button-blue>

                <x-button-red
                    wire:click="$toggle('modalPersona')"
                    wire:loading.attr="disabled"
                    wire:target="$toggle('modalPersona')"
                    type="button">
                    Cerrar
                </x-button-red>

            </div>

        </x-slot>

    </x-dialog-modal>

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

            <x-filepond wire:model.live="documento" accept="['application/pdf']"/>

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

</div>
