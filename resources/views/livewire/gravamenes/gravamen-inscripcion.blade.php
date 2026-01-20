@push('styles')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endpush

<div>

    <x-header>Inscripción de gravamen  <span class="text-sm tracking-widest">Folio real: {{ $gravamen->movimientoRegistral->folioReal->folio }} - {{ $gravamen->movimientoRegistral->folio }}</span></x-header>

    @include('livewire.comun.documento_entrada_campos')

    <div class="p-4 bg-white shadow-xl rounded-xl mb-5">

        <span class="flex items-center justify-center text-gray-700">Datos del gravamen</span>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto mb-4">

            <x-input-group for="gravamen.acto_contenido" label="Acto contenido" :error="$errors->first('gravamen.acto_contenido')" class="w-full">

                <x-input-select id="gravamen.acto_contenido" wire:model.live="gravamen.acto_contenido" class="w-full">

                    <option value="">Seleccione una opción</option>

                    @foreach ($actos as $acto)

                        <option value="{{ $acto }}">{{ $acto }}</option>

                    @endforeach

                </x-input-select>

            </x-input-group>

        </div>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto mb-4">

            <x-input-group for="gravamen.tipo" label="Tipo" :error="$errors->first('gravamen.tipo')" class="w-full">

                <x-input-text id="gravamen.tipo" wire:model="gravamen.tipo" />

            </x-input-group>

        </div>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto mb-4">

            <x-input-group for="gravamen.expediente" label="Expediente" :error="$errors->first('gravamen.expediente')" class="">

                <x-input-text id="gravamen.expediente" wire:model="gravamen.expediente" />

            </x-input-group>

            <x-input-group for="gravamen.valor_gravamen" label="Valor del gravamen" :error="$errors->first('gravamen.valor_gravamen')" class="w-full relative">

                <x-input-text type="number" id="gravamen.valor_gravamen" wire:model="gravamen.valor_gravamen" />

                <div class="absolute right-0 top-6">

                    <x-input-select id="divisa" wire:model="gravamen.divisa">

                        <option value="" selected>Divisa</option>

                        @foreach ($divisas as $divisa)

                            <option value="{{ $divisa }}">{{ $divisa }}</option>

                        @endforeach

                    </x-input-select>

                </div>

            </x-input-group>

        </div>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto">

            <x-input-group for="gravamen.observaciones" label="Descripción del acto contenido" :error="$errors->first('gravamen.observaciones')" class="w-full">

                <textarea rows="3" class="w-full bg-white rounded" wire:model="gravamen.observaciones"></textarea>

            </x-input-group>

        </div>

    </div>

    <div class="p-4 bg-white shadow-xl rounded-xl mb-5"  x-data>

        <div class="w-full lg:w-1/2 justify-center mx-auto">

            <span class="flex items-center justify-center text-gray-700 col-span-3">Deudores</span>

            <div class="flex justify-end mb-3">

                @livewire('comun.actores.deudor-crear', ['sub_tipos' => $tipo_deudores, 'modelo' => $gravamen, 'visible' => true])

            </div>

            <div>

                <x-table>

                    <x-slot name="head">
                        <x-table.heading >Actor</x-table.heading>
                        <x-table.heading >Tipo</x-table.heading>
                        <x-table.heading ></x-table.heading>
                    </x-slot>

                    <x-slot name="body">

                        @foreach ($gravamen->deudores as $participante)

                            <x-table.row wire:key="row-{{ $participante->id }}">

                                <x-table.cell>{{ $participante->persona->nombre }} {{ $participante->persona->ap_paterno }} {{ $participante->persona->ap_materno }} {{ $participante->persona->razon_social }}</x-table.cell>
                                <x-table.cell>{{ $participante->tipo_deudor }}</x-table.cell>
                                <x-table.cell>
                                    <div class="flex justify-end items-center gap-3">

                                        <div>

                                            <livewire:comun.actores.deudor-actualizar :sub_tipos="$tipo_deudores" :actor="$participante" wire:key="button-deudor-{{ $participante->id }}" />

                                        </div>

                                        <x-button-red
                                            wire:click="eliminarActor({{ $participante->id }})"
                                            wire:loading.attr="disabled">
                                            Borrar
                                        </x-button-red>
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

    <div class="p-4 bg-white shadow-xl rounded-xl mb-5">

        <div class="w-full lg:w-1/2 justify-center mx-auto">

            <span class="flex items-center justify-center text-gray-700">Acreedores</span>

            <div class="flex justify-end mb-3">

                @livewire('comun.actores.acreedor-crear', ['modelo' => $gravamen, 'visible' => true])

            </div>

            <div>

                <x-table>

                    <x-slot name="head">
                        <x-table.heading >Acreedor</x-table.heading>
                        <x-table.heading ></x-table.heading>
                    </x-slot>

                    <x-slot name="body">

                        @foreach ($gravamen->acreedores as $participante)

                            <x-table.row wire:key="row-{{ $participante->id }}">

                                <x-table.cell>{{ $participante->persona->nombre }} {{ $participante->persona->ap_paterno }} {{ $participante->persona->ap_materno }} {{ $participante->persona->razon_social }}</x-table.cell>
                                <x-table.cell>
                                    <div class="flex justify-end items-center gap-3">

                                        <div>

                                            <livewire:comun.actores.acreedor-actualizar :actor="$participante" wire:key="button-acreedor-{{ $participante->id }}" />

                                        </div>

                                        <x-button-red
                                            wire:click="eliminarActor({{ $participante->id }})"
                                            wire:loading.attr="disabled">
                                            Borrar
                                        </x-button-red>
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

        @if(!$gravamen->movimientoRegistral->documentoEntrada())

            <x-button-blue
                wire:click="abrirModalFinalizar"
                wire:loading.attr="disabled"
                wire:target="abrirModalFinalizar">

                <img wire:loading wire:target="abrirModalFinalizar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                Subir documento de entrada

            </x-button-blue>

        @else

            <div class="inline-block">

                <x-link-blue target="_blank" href="{{ $gravamen->movimientoRegistral->documentoEntrada() }}">Documento de entrada</x-link-blue>

            </div>

            <x-button-red
                wire:click="eliminarDocumentoEntradaPDF"
                wire:confirm="¿Esta seguro que desea eliminar el documento de entrada?"
                wire:loading.attr="disabled"
                wire:target="eliminarDocumentoEntradaPDF">

                <img wire:loading wire:target="eliminarDocumentoEntradaPDF" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                Eliminar documento de entrada

            </x-button-red>

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

@push('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>

        window.addEventListener('imprimir_documento', event => {

            const documento = event.detail[0].gravamen;

            var url = "{{ route('gravamen.inscripcion.acto', '')}}" + "/" + documento;

            window.open(url, '_blank');

            window.location.href = "{{ route('gravamen')}}";

        });

        window.addEventListener('ver_documento', event => {

            const documento = event.detail[0].url;

            window.open(documento, '_blank');

        });

    </script>

@endpush
