@push('styles')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endpush

<div>

    <x-header>Inscripción de gravamen  <span class="text-sm tracking-widest">Folio real: {{ $gravamen->movimientoRegistral->folioReal->folio }} - {{ $gravamen->movimientoRegistral->folio }}</span></x-header>

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

    {{-- @if($gravamen->acto_contenido == 'DIVISIÓN DE HIPOTECA')

        <div class="p-4 bg-white shadow-xl rounded-xl mb-5">

            <div class="w-full  justify-center mx-auto">

                <div class="flex-auto text-center mb-3 lg:w-1/2 mx-auto">

                    <div >

                        <Label class="text-base tracking-widest rounded-xl border-gray-500">Folio del gravmen</Label>

                    </div>

                    <div class="inline-flex">

                        <input type="number" class="bg-white text-sm w-20 rounded-l focus:ring-0 @error('folio') border-red-500 @enderror" value="{{ $gravamen->movimientoRegistral->folioReal->folio }}" readonly>

                        <input type="number" class="bg-white text-sm w-20 border-l-0 rounded-r focus:ring-0 @error('folio_gravamen') border-red-500 @enderror" wire:model="folio_gravamen">

                    </div>

                    <button
                        wire:click="buscarGravamen"
                        wire:loading.attr="disabled"
                        wire:target="buscarGravamen"
                        type="button"
                        class="bg-blue-400 mx-auto mt-3 hover:shadow-lg text-white font-bold px-4 py-2 rounded text-xs hover:bg-blue-700 focus:outline-none flex items-center justify-center focus:outline-blue-400 focus:outline-offset-2">

                        <img wire:loading wire:target="buscarGravamen" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        Buscar gravamen

                    </button>

                </div>

                @if($gravamenHipoteca)

                    <div class="lg:w-1/2 mx-auto">

                        <div class="flex gap-3 items-center justify-center mx-auto mb-4">

                            <input type="text" value="{{ $gravamenHipoteca->gravamen->acto_contenido }}" class="bg-white rounded text-sm w-full" readonly>

                            <input type="text" value="{{ $gravamenHipoteca->gravamen->tipo }}" class="bg-white rounded text-sm w-full" readonly>

                        </div>

                        <div class="flex gap-3 items-center justify-center mx-auto mb-4">

                            <input type="text" value="{{ $gravamenHipoteca->gravamen->valor_gravamen }}" class="bg-white rounded text-sm w-full" readonly>

                            <input type="text" value="{{ $gravamenHipoteca->gravamen->fecha_inscripcion }}" class="bg-white rounded text-sm w-full" readonly>

                        </div>

                        <textarea class="bg-white rounded text-sm w-full" readonly>{{ $gravamenHipoteca->gravamen->observaciones }}</textarea>

                    </div>

                    <div class="flex-auto text-center mb-3 lg:w-1/2 mx-auto">

                        <div >

                            <Label class="text-base tracking-widest rounded-xl border-gray-500">Folio real</Label>

                        </div>

                        <div class="inline-flex">

                            <input type="number" class="bg-white text-sm w-20 rounded focus:ring-0 @error('folio_real_division') border-red-500 @enderror" wire:model="folio_real_division">

                        </div>

                        <button
                            wire:click="agregarFolioReal"
                            wire:loading.attr="disabled"
                            wire:target="agregarFolioReal"
                            type="button"
                            class="bg-blue-400 mx-auto mt-3 hover:shadow-lg text-white font-bold px-4 py-2 rounded text-xs hover:bg-blue-700 focus:outline-none flex items-center justify-center focus:outline-blue-400 focus:outline-offset-2">

                            <img wire:loading wire:target="agregarFolioReal" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                            Agregar folio real

                        </button>

                    </div>

                    <div class="flex-auto text-center mb-3 mx-auto">

                        @if(count($folios_reales))

                        <table class="mx-auto">

                            <thead class="border-b border-gray-300 ">

                                <tr class="text-sm text-gray-500 text-left traling-wider whitespace-nowrap">

                                    <th class="px-2">Folio</th>
                                    <th class="px-2">Propietario</th>
                                    <th class="px-2">Ubicación</th>
                                    <th class="px-2">Valor de gravamen</th>
                                    <th class="px-2"></th>

                                </tr>

                            </thead>

                            <tbody class="divide-y divide-gray-200">

                                @foreach ($folios_reales as $key => $folio)

                                    <tr class="text-gray-500 text-sm leading-relaxed">
                                        <td class=" p-2">{{ $folio->folio }}</td>
                                        <td class=" p-2">{{ $folio->predio->primerPropietario() }}</td>
                                        <td class=" p-2">{{ $folio->predio->nombre_vialidad }} {{ $folio->predio->numero_exterior }}</td>
                                        <td class=" p-2">${{ number_format($gravamenHipoteca->gravamen->valor_gravamen / count($folios_reales), 2) }}</td>
                                        <td class=" p-2">
                                            <button
                                                wire:click="quitarFolio({{ $key }})"
                                                wire:loading.attr="disabled"
                                                wire:target="quitarFolio({{ $key }})"
                                                class=" bg-red-400 text-white text-xs p-1 items-center rounded-full hover:bg-red-700 flex justify-center focus:outline-none"
                                            >

                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>

                                            </button>
                                        </td>
                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                        @endif

                    </div>

                @endif

            </div>

        </div>

    @endif --}}

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
