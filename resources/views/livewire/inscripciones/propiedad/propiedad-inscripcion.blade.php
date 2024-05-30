@push('styles')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endpush

<div>

    <x-header>Inscripción de propiedad <span class="text-sm tracking-widest">Folio real: {{ $inscripcion->movimientoRegistral->folioReal->folio }}</span></x-header>

    <div class="bg-white rounded-lg p-2 shadow-xl grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-1 text-sm mb-3">

        <span class="flex items-center justify-center text-base text-gray-700 md:col-span-3 col-span-1 sm:col-span-2 lg:col-span-6">Antecedente</span>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Tomo:</strong> {{ $inscripcion->movimientoRegistral->tomo }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Registro:</strong> {{ $inscripcion->movimientoRegistral->registro }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Número de propiedad:</strong> {{ $inscripcion->movimientoRegistral->numero_propiedad }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Distrito:</strong> {{ $inscripcion->movimientoRegistral->distrito }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Sección:</strong> {{ $inscripcion->movimientoRegistral->seccion }}</p>

        </div>

        <span class="flex items-center justify-center text-base text-gray-700 md:col-span-3 col-span-1 sm:col-span-2 lg:col-span-6">Documento de entrada</span>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Tipo de documento: </strong> {{ $inscripcion->movimientoRegistral->tipo_documento }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Autoridad cargo: </strong> {{ $inscripcion->movimientoRegistral->autoridad_cargo }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Nombre de la autoridad:</strong> {{ $inscripcion->movimientoRegistral->autoridad_nombre }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Número de documento: </strong> {{ $inscripcion->movimientoRegistral->numero_documento }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Fecha de emisión:</strong> {{ $inscripcion->movimientoRegistral->fecha_emision }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Procedencia:</strong> {{ $inscripcion->movimientoRegistral->procedencia }}</p>

        </div>

    </div>

    <div class="bg-white rounded-lg p-4 shadow-lg mb-4">

        <x-input-group for="inscripcion.acto_contenido" label="Acto" :error="$errors->first('inscripcion.acto_contenido')" class="w-full lg:w-1/4 mx-auto">

            <x-input-select id="inscripcion.acto_contenido" wire:model="inscripcion.acto_contenido" class="">

                <option value="">Seleccione una opción</option>

                @foreach ($actos as $acto)

                    <option value="{{ $acto }}">{{ $acto }}</option>

                @endforeach

            </x-input-select>

        </x-input-group>

    </div>

    <div class="bg-white rounded-lg p-4 shadow-lg mb-4">

            <div class="space-y-1 sm:col-span-2 lg:col-span-3 mx-auto text-center mb-2">

                <span class="flex items-center justify-center text-gray-700">Cuenta predial</span>

                <input title="Localidad" placeholder="Localidad" type="number" class="bg-white rounded text-xs w-20 @error('inscripcion.cp_localidad') border-1 border-red-500 @enderror" wire:model.lazy="inscripcion.cp_localidad">

                <input title="Oficina" placeholder="Oficina" type="number" class="bg-white rounded text-xs w-20 @error('inscripcion.cp_oficina') border-1 border-red-500 @enderror" wire:model.defer="inscripcion.cp_oficina">

                <input title="Tipo de predio" placeholder="Tipo" type="number" class="bg-white rounded text-xs w-20 @error('inscripcion.cp_tipo_predio') border-1 border-red-500 @enderror" wire:model.defer="inscripcion.cp_tipo_predio">

                <input title="Número de registro" placeholder="Registro" type="number" class="bg-white rounded text-xs w-20 @error('inscripcion.cp_registro') border-1 border-red-500 @enderror" wire:model.lazy="inscripcion.cp_registro">

            </div>

            <div class="space-y-1 sm:col-span-2 lg:col-span-3 mx-auto text-center">

                <span class="flex items-center justify-center text-gray-700">Clave catastral</span>

                <input placeholder="Estado" type="number" class="bg-white rounded text-xs w-10" title="Estado" value="16" readonly>

                <input title="Región catastral" placeholder="Región" type="number" class="bg-white rounded text-xs w-16  @error('inscripcion.cc_region_catastral') border-1 border-red-500 @enderror" wire:model.defer="inscripcion.cc_region_catastral">

                <input title="Municipio" placeholder="Municipio" type="number" class="bg-white rounded text-xs w-20 @error('inscripcion.cc_municipio') border-1 border-red-500 @enderror" wire:model.defer="inscripcion.cc_municipio">

                <input title="Zona" placeholder="Zona" type="number" class="bg-white rounded text-xs w-16 @error('inscripcion.cc_zona_catastral') border-1 border-red-500 @enderror" wire:model.defer="inscripcion.cc_zona_catastral">

                <input title="Localidad" placeholder="Localidad" type="number" class="bg-white rounded text-xs w-20 @error('inscripcion.cp_localidad') border-1 border-red-500 @enderror" wire:model.lazy="inscripcion.cp_localidad">

                <input title="Sector" placeholder="Sector" type="number" class="bg-white rounded text-xs w-20 @error('inscripcion.cc_sector') border-1 border-red-500 @enderror" wire:model.defer="inscripcion.cc_sector">

                <input title="Manzana" placeholder="Manzana" type="number" class="bg-white rounded text-xs w-20 @error('inscripcion.cc_manzana') border-1 border-red-500 @enderror" wire:model.defer="inscripcion.cc_manzana">

                <input title="Predio" placeholder="Predio" type="number" class="bg-white rounded text-xs w-20 @error('inscripcion.cc_predio') border-1 border-red-500 @enderror" wire:model.lazy="inscripcion.cc_predio">

                <input title="Edificio" placeholder="Edificio" type="number" class="bg-white rounded text-xs w-16 @error('inscripcion.cc_edificio') border-1 border-red-500 @enderror" wire:model.defer="inscripcion.cc_edificio">

                <input title="Departamento" placeholder="Departamento" type="number" class="bg-white rounded text-xs w-28 @error('inscripcion.cc_departamento') border-1 border-red-500 @enderror" wire:model.defer="inscripcion.cc_departamento">

            </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-1 mb-2">

        @if($propiedad)

            <div class="mb-3 bg-white rounded-lg p-3 shadow-lg">

                <span class="flex items-center justify-center text-lg text-gray-700 mb-5">Transmitentes ({{ $inscripcion->transmitentes()->count() }})</span>

                <div class="flex justify-end mb-2">

                    <x-button-gray
                            wire:click="agregarTransmitente"
                            wire:loading.attr="disabled"
                            wire:target="agregarTransmitente">

                            <img wire:loading wire:target="agregarTransmitente" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                            Agregar transmitente
                    </x-button-gray>

                </div>

                <x-table>

                    <x-slot name="head">
                        <x-table.heading >Nombre / Razón social</x-table.heading>
                        <x-table.heading >% propiedad</x-table.heading>
                        <x-table.heading >% nuda</x-table.heading>
                        <x-table.heading >% usufructo</x-table.heading>
                        <x-table.heading ></x-table.heading>
                    </x-slot>

                    <x-slot name="body">

                        @foreach ($inscripcion->transmitentes() as $transmitente)

                            <x-table.row >

                                <x-table.cell>{{ $transmitente->persona->nombre }} {{ $transmitente->persona->ap_paterno }} {{ $transmitente->persona->ap_materno }} {{ $transmitente->persona->razon_social }}</x-table.cell>
                                <x-table.cell>{{ $transmitente->porcentaje_propiedad }}</x-table.cell>
                                <x-table.cell>{{ $transmitente->porcentaje_nuda }}</x-table.cell>
                                <x-table.cell>{{ $transmitente->porcentaje_usufructo }}</x-table.cell>
                                <x-table.cell>
                                    <div class="flex flex-col items-center gap-3">
                                        <x-button-red
                                            wire:click="borrarActor({{ $transmitente->id }})"
                                            wire:loading.attr="disabled">

                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>

                                        </x-button-red>
                                    </div>
                                </x-table.cell>

                            </x-table.row>

                        @endforeach

                    </x-slot>

                    <x-slot name="tfoot"></x-slot>

                </x-table>

            </div>

            <div class="mb-3 bg-white rounded-lg p-3 shadow-lg">

                <span class="flex items-center justify-center text-lg text-gray-700 mb-5">Adquirientes ({{ $inscripcion->propietarios()->count() }})</span>

                <div class="flex justify-end mb-2">

                    {{-- <x-button-red
                            wire:click="cargarPropietarios"
                            wire:loading.attr="disabled"
                            wire:target="cargarPropietarios">

                            <img wire:loading wire:target="cargarPropietarios" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                            Borrar propietarios
                    </x-button-red> --}}

                    <x-button-gray
                            wire:click="agregarPropietario"
                            wire:loading.attr="disabled"
                            wire:target="agregarPropietario">

                            <img wire:loading wire:target="agregarPropietario" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                            Agregar adquiriente
                    </x-button-gray>

                </div>

                <x-table>

                    <x-slot name="head">
                        <x-table.heading >Nombre / Razón social</x-table.heading>
                        <x-table.heading >% propiedad</x-table.heading>
                        <x-table.heading >% nuda</x-table.heading>
                        <x-table.heading >% usufructo</x-table.heading>
                        <x-table.heading ></x-table.heading>
                    </x-slot>

                    <x-slot name="body">

                        @foreach ($inscripcion->propietarios() as $propietario)

                            <x-table.row >

                                <x-table.cell>{{ $propietario->persona->nombre }} {{ $propietario->persona->ap_paterno }} {{ $propietario->persona->ap_materno }} {{ $propietario->persona->razon_social }}</x-table.cell>
                                <x-table.cell>{{ number_format($propietario->porcentaje_propiedad, 2) }}%</x-table.cell>
                                <x-table.cell>{{ number_format($propietario->porcentaje_nuda, 2) }}%</x-table.cell>
                                <x-table.cell>{{ number_format($propietario->porcentaje_usufructo, 2) }}%</x-table.cell>
                                <x-table.cell>
                                    <div class="flex flex-col items-center gap-3">
                                        <x-button-blue
                                            wire:click="editarActor({{ $propietario->id }}, 'propietario')"
                                            wire:traget="editarActor({{ $propietario->id }}, 'propietario')"
                                            wire:loading.attr="disabled"
                                        >

                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg>

                                        </x-button-blue>
                                        <x-button-red
                                            wire:click="borrarActor({{ $propietario->id }})"
                                            wire:loading.attr="disabled">

                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>

                                        </x-button-red>
                                    </div>
                                </x-table.cell>

                            </x-table.row>

                        @endforeach

                    </x-slot>

                    <x-slot name="tfoot"></x-slot>

                </x-table>

            </div>

            <div class="mb-3 bg-white rounded-lg p-3 shadow-lg">

                <span class="flex items-center justify-center text-lg text-gray-700 mb-5">Representantes ({{ $inscripcion->representantes()->count() }})</span>

                <div class="flex justify-end mb-2">

                    <x-button-gray
                            wire:click="agregarRepresentante"
                            wire:loading.attr="disabled"
                            wire:target="agregarRepresentante">

                            <img wire:loading wire:target="agregarRepresentante" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                            Agregar representante
                    </x-button-gray>

                </div>

                <x-table>

                    <x-slot name="head">
                        <x-table.heading >Nombre / Razón social</x-table.heading>
                        <x-table.heading >Representados</x-table.heading>
                        <x-table.heading ></x-table.heading>
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
                                <x-table.cell>
                                    <div class="flex flex-col items-center gap-3">
                                        <x-button-blue
                                            wire:click="editarActor({{ $representante->id }}, 'representante')"
                                            wire:loading.attr="disabled"
                                        >

                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg>

                                        </x-button-blue>
                                        <x-button-red
                                            wire:click="borrarActor({{ $representante->id }})"
                                            wire:loading.attr="disabled">

                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>

                                        </x-button-red>
                                    </div>
                                </x-table.cell>

                            </x-table.row>

                        @endforeach

                    </x-slot>

                    <x-slot name="tfoot"></x-slot>

                </x-table>

            </div>

        @endif

    </div>

    <div class="bg-white rounded-lg p-1 flex justify-end shadow-lg mb-4">

        <div class=" mx-auto lg:w-1/2">

            <div>

                <h4 class="text-lg mb-1 text-center">Descripción</h4>

            </div>

            <div>

                <textarea class="bg-white rounded text-xs w-full  @error('inscripcion.descripcion_acto') border-1 border-red-500 @enderror" rows="4" wire:model="inscripcion.descripcion_acto"></textarea>

            </div>

        </div>

    </div>

    <div class="bg-white rounded-lg p-3 flex justify-end shadow-lg mb-4">

        <table class="mx-auto">

            <thead class="border-b border-gray-300 ">

                <tr class="text-sm text-gray-500 text-left traling-wider whitespace-nowrap">

                    <th class="px-2">Nombre / Razón social</th>
                    <th class="px-2">% Porpiedad</th>
                    <th class="px-2">% Nuda</th>
                    <th class="px-2">% Usufructo</th>

                </tr>

            </thead>

            <tbody class="divide-y divide-gray-200">

                @foreach ($transmitentes as $key => $transmitente)

                    <tr class="text-gray-500 text-sm leading-relaxed">
                        <td class=" px-2">(Tra.) {{ $transmitente['nombre'] }} {{ $transmitente['ap_paterno'] }} {{ $transmitente['ap_materno'] }} {{ $transmitente['razon_social'] }}</td>
                        <td class=" px-2">
                            <input wire:model.live="transmitentes.{{ $key }}.porcentaje" type="number" class="bg-white text-sm w-full rounded-md p-2 border border-gray-500 outline-none ring-blue-600 focus:ring-1 focus:border-blue-600">
                        </td>
                        <td class=" px-2">
                            <input wire:model.live="transmitentes.{{ $key }}.porcentaje_nuda" type="number" class="bg-white text-sm w-full rounded-md p-2 border border-gray-500 outline-none ring-blue-600 focus:ring-1 focus:border-blue-600">
                        </td>
                        <td class=" px-2">
                            <input wire:model.live="transmitentes.{{ $key }}.porcentaje_usufructo" type="number" class="bg-white text-sm w-full rounded-md p-2 border border-gray-500 outline-none ring-blue-600 focus:ring-1 focus:border-blue-600">
                        </td>
                    </tr>

                @endforeach

                @foreach ($this->inscripcion->propietarios() as $adquiriente)

                    <tr class="text-gray-500 text-sm leading-relaxed">
                        <td class=" px-2">(Adq.){{ $adquiriente->persona->nombre }} {{ $adquiriente->persona->ap_paterno }} {{ $adquiriente->persona->ap_materno }} {{ $adquiriente->persona->razon_social }}</td>
                        <td class=" px-2">{{ $adquiriente->porcentaje_propiedad ?? '0' }}</td>
                        <td class=" px-2">{{ $adquiriente->porcentaje_nuda ?? '0' }} </td>
                        <td class=" px-2">{{ $adquiriente->porcentaje_usufructo ?? '0' }}</td>
                    </tr>

                @endforeach

            </tbody>

        </table>

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

    <div class="bg-white rounded-lg p-3 flex justify-end shadow-lg">

        <x-button-green
            wire:click="finalizar"
            wire:loading.attr="disabled"
            wire:target="finalizar">

            <img wire:loading wire:target="finalizar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

            Finalizar inscripción

        </x-button-green>

    </div>

    <x-dialog-modal wire:model="modalPropietario">

        <x-slot name="title">

            @if($crear)
                Nuevo Propietario
            @elseif($editar)
                Editar Propietario
            @endif

        </x-slot>

        <x-slot name="content">

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 mb-3 col-span-2 rounded-lg p-3">

                <x-input-group for="tipo_persona" label="Tipo de persona" :error="$errors->first('tipo_persona')" class="w-full">

                    <x-input-select id="tipo_persona" wire:model.live="tipo_persona" class="w-full">

                        <option value="">Seleccione una opción</option>
                        <option value="MORAL">MORAL</option>
                        <option value="FISICA">FISICA</option>

                    </x-input-select>

                </x-input-group>

                @if($tipo_persona == 'FISICA')

                    <x-input-group for="nombre" label="Nombre(s)" :error="$errors->first('nombre')" class="w-full">

                        <x-input-text id="nombre" wire:model="nombre" />

                    </x-input-group>

                    <x-input-group for="ap_paterno" label="Apellido paterno" :error="$errors->first('ap_paterno')" class="w-full">

                        <x-input-text id="ap_paterno" wire:model="ap_paterno" />

                    </x-input-group>

                    <x-input-group for="ap_materno" label="Apellido materno" :error="$errors->first('ap_materno')" class="w-full">

                        <x-input-text id="ap_materno" wire:model="ap_materno" />

                    </x-input-group>

                    <x-input-group for="curp" label="CURP" :error="$errors->first('curp')" class="w-full">

                        <x-input-text id="curp" wire:model="curp" />

                    </x-input-group>

                    <x-input-group for="fecha_nacimiento" label="Fecha de nacimiento" :error="$errors->first('fecha_nacimiento')" class="w-full">

                        <x-input-text type="date" id="fecha_nacimiento" wire:model="fecha_nacimiento" />

                    </x-input-group>

                    <x-input-group for="estado_civil" label="Estado civil" :error="$errors->first('estado_civil')" class="w-full">

                        <x-input-text id="estado_civil" wire:model="estado_civil" />

                    </x-input-group>

                @elseif($tipo_persona == 'MORAL')

                    <x-input-group for="razon_social" label="Razon social" :error="$errors->first('razon_social')" class="w-full">

                        <x-input-text id="razon_social" wire:model="razon_social" />

                    </x-input-group>

                @endif

                <x-input-group for="rfc" label="RFC" :error="$errors->first('rfc')" class="w-full">

                    <x-input-text id="rfc" wire:model="rfc" />

                </x-input-group>

                <x-input-group for="nacionalidad" label="Nacionalidad" :error="$errors->first('nacionalidad')" class="w-full">

                    <x-input-text id="nacionalidad" wire:model="nacionalidad" />

                </x-input-group>

                <span class="flex items-center justify-center text-lg text-gray-700 md:col-span-3 col-span-1 sm:col-span-2">Domicilio</span>

                <x-input-group for="cp" label="Código postal" :error="$errors->first('cp')" class="w-full">

                    <x-input-text type="number" id="cp" wire:model="cp" />

                </x-input-group>

                <x-input-group for="entidad" label="Estado" :error="$errors->first('entidad')" class="w-full">

                    <x-input-text id="entidad" wire:model="entidad" />

                </x-input-group>

                <x-input-group for="municipio_propietario" label="Municipio" :error="$errors->first('municipio_propietario')" class="w-full">

                    <x-input-text id="municipio_propietario" wire:model="municipio_propietario" />

                </x-input-group>

                <x-input-group for="ciudad" label="Ciudad" :error="$errors->first('ciudad')" class="w-full">

                    <x-input-text id="ciudad" wire:model="ciudad" />

                </x-input-group>

                <x-input-group for="colonia" label="Colonia" :error="$errors->first('colonia')" class="w-full">

                    <x-input-text id="colonia" wire:model="colonia" />

                </x-input-group>

                <x-input-group for="calle" label="Calle" :error="$errors->first('calle')" class="w-full">

                    <x-input-text id="calle" wire:model="calle" />

                </x-input-group>

                <x-input-group for="numero_exterior_propietario" label="Número exterior" :error="$errors->first('numero_exterior_propietario')" class="w-full">

                    <x-input-text id="numero_exterior_propietario" wire:model="numero_exterior_propietario" />

                </x-input-group>

                <x-input-group for="numero_interior_propietario" label="Número interior" :error="$errors->first('numero_interior_propietario')" class="w-full">

                    <x-input-text id="numero_interior_propietario" wire:model="numero_interior_propietario" />

                </x-input-group>

                <span class="flex items-center justify-center text-lg text-gray-700 md:col-span-3 col-span-1 sm:col-span-2">Porcentajes</span>

                <x-input-group for="porcentaje_propiedad" label="Porcentaje propiedad" :error="$errors->first('porcentaje_propiedad')" class="w-full">

                    <x-input-text type="number" id="porcentaje_propiedad" wire:model.lazy="porcentaje_propiedad" />

                </x-input-group>

                <x-input-group for="porcentaje_nuda" label="Porcentaje nuda" :error="$errors->first('porcentaje_nuda')" class="w-full">

                    <x-input-text type="number" id="porcentaje_nuda" wire:model.lazy="porcentaje_nuda" />

                </x-input-group>

                <x-input-group for="porcentaje_usufructo" label="Porcentaje sufructo" :error="$errors->first('porcentaje_usufructo')" class="w-full">

                    <x-input-text type="number" id="porcentaje_usufructo" wire:model.lazy="porcentaje_usufructo" />

                </x-input-group>

            </div>

        </x-slot>

        <x-slot name="footer">
            {{ $errors }}

            <div class="flex gap-3">

                @if($crear)

                    <x-button-blue
                        wire:click="guardarPropietario"
                        wire:loading.attr="disabled"
                        wire:target="guardarPropietario">

                        <img wire:loading wire:target="guardarPropietario" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        <span>Guardar</span>
                    </x-button-blue>

                @elseif($editar)

                    <x-button-blue
                        wire:click="actualizarActor"
                        wire:loading.attr="disabled"
                        wire:target="actualizarActor">

                        <img wire:loading wire:target="actualizarActor" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        <span>Actualizar</span>
                    </x-button-blue>

                @endif

                <x-button-red
                    wire:click="resetear"
                    wire:loading.attr="disabled"
                    wire:target="resetear"
                    type="button">
                    Cerrar
                </x-button-red>

            </div>

        </x-slot>

    </x-dialog-modal>

    <x-dialog-modal wire:model="modalTransmitente" maxWidth="sm">

        <x-slot name="title">

            Agregar transmitente

        </x-slot>

        <x-slot name="content">

            <x-input-group for="propietario" label="Propietario" :error="$errors->first('propietario')" class="w-full">

                <x-input-select id="propietario" wire:model.live="propietario" class="w-full">

                    <option value="">Seleccione una opción</option>

                    @foreach ($predio->propietarios() as $propietario)

                        <option value="{{ $propietario->id }}">{{ $propietario->persona->nombre }} {{ $propietario->persona->ap_paterno }} {{ $propietario->persona->ap_materno }} {{ $propietario->persona->razon_social }}</option>

                    @endforeach

                </x-input-select>

            </x-input-group>

        </x-slot>

        <x-slot name="footer">

            {{ $errors }}

            <div class="flex gap-3">

                <x-button-blue
                    wire:click="guardarTransmitente"
                    wire:loading.attr="disabled"
                    wire:target="guardarTransmitente">

                    <img wire:loading wire:target="guardarTransmitente" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <span>Agregar transmitente</span>
                </x-button-blue>

                <x-button-red
                    wire:click="resetear"
                    wire:loading.attr="disabled"
                    wire:target="resetear"
                    type="button">
                    Cerrar
                </x-button-red>

            </div>

        </x-slot>

    </x-dialog-modal>

    <x-dialog-modal wire:model="modalRepresentante">

        <x-slot name="title">

            @if($crear)
                Nuevo Representante
            @elseif($editar)
                Editar Representante
            @endif

        </x-slot>

        <x-slot name="content">

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 mb-3 col-span-2 rounded-lg p-3">

                <x-input-group for="tipo_persona" label="Tipo de persona" :error="$errors->first('tipo_persona')" class="w-full">

                    <x-input-select id="tipo_persona" wire:model.live="tipo_persona" class="w-full">

                        <option value="">Seleccione una opción</option>
                        <option value="MORAL">MORAL</option>
                        <option value="FISICA">FISICA</option>

                    </x-input-select>

                </x-input-group>

                @if($tipo_persona == 'FISICA')

                    <x-input-group for="nombre" label="Nombre(s)" :error="$errors->first('nombre')" class="w-full">

                        <x-input-text id="nombre" wire:model="nombre" />

                    </x-input-group>

                    <x-input-group for="ap_paterno" label="Apellido paterno" :error="$errors->first('ap_paterno')" class="w-full">

                        <x-input-text id="ap_paterno" wire:model="ap_paterno" />

                    </x-input-group>

                    <x-input-group for="ap_materno" label="Apellido materno" :error="$errors->first('ap_materno')" class="w-full">

                        <x-input-text id="ap_materno" wire:model="ap_materno" />

                    </x-input-group>

                    <x-input-group for="curp" label="CURP" :error="$errors->first('curp')" class="w-full">

                        <x-input-text id="curp" wire:model="curp" />

                    </x-input-group>

                    <x-input-group for="fecha_nacimiento" label="Fecha de nacimiento" :error="$errors->first('fecha_nacimiento')" class="w-full">

                        <x-input-text type="date" id="fecha_nacimiento" wire:model="fecha_nacimiento" />

                    </x-input-group>

                    <x-input-group for="estado_civil" label="Estado civil" :error="$errors->first('estado_civil')" class="w-full">

                        <x-input-text id="estado_civil" wire:model="estado_civil" />

                    </x-input-group>

                @elseif($tipo_persona == 'MORAL')

                    <x-input-group for="razon_social" label="Razon social" :error="$errors->first('razon_social')" class="w-full">

                        <x-input-text id="razon_social" wire:model="razon_social" />

                    </x-input-group>

                @endif

                <x-input-group for="rfc" label="RFC" :error="$errors->first('rfc')" class="w-full">

                    <x-input-text id="rfc" wire:model="rfc" />

                </x-input-group>

                <x-input-group for="nacionalidad" label="Nacionalidad" :error="$errors->first('nacionalidad')" class="w-full">

                    <x-input-text id="nacionalidad" wire:model="nacionalidad" />

                </x-input-group>

                <span class="flex items-center justify-center text-lg text-gray-700 md:col-span-3 col-span-1 sm:col-span-2">Domicilio</span>

                <x-input-group for="cp" label="Código postal" :error="$errors->first('cp')" class="w-full">

                    <x-input-text type="number" id="cp" wire:model="cp" />

                </x-input-group>

                <x-input-group for="entidad" label="Estado" :error="$errors->first('entidad')" class="w-full">

                    <x-input-text id="entidad" wire:model="entidad" />

                </x-input-group>

                <x-input-group for="municipio_propietario" label="Municipio" :error="$errors->first('municipio_propietario')" class="w-full">

                    <x-input-text id="municipio_propietario" wire:model="municipio_propietario" />

                </x-input-group>

                <x-input-group for="colonia" label="Colonia" :error="$errors->first('colonia')" class="w-full">

                    <x-input-text id="colonia" wire:model="colonia" />

                </x-input-group>

                <x-input-group for="calle" label="Calle" :error="$errors->first('calle')" class="w-full">

                    <x-input-text id="calle" wire:model="calle" />

                </x-input-group>

                <x-input-group for="numero_exterior_propietario" label="Número exterior" :error="$errors->first('numero_exterior_propietario')" class="w-full">

                    <x-input-text id="numero_exterior_propietario" wire:model="numero_exterior_propietario" />

                </x-input-group>

                <x-input-group for="numero_interior_propietario" label="Número interior" :error="$errors->first('numero_interior_propietario')" class="w-full">

                    <x-input-text id="numero_interior_propietario" wire:model="numero_interior_propietario" />

                </x-input-group>

                <span class="flex items-center justify-center text-lg text-gray-700 md:col-span-3 col-span-1 sm:col-span-2" >Representados</span>

                <div class="md:col-span-3 col-span-1 sm:col-span-2">

                    <div class="flex space-x-4 items-center">

                        <Label>Seleccione los representados</Label>

                    </div>

                    <div
                        x-data = "{ model: @entangle('representados') }"
                        x-init =
                        "
                            select2 = $($refs.select)
                                .select2({
                                    placeholder: 'Propietarios y transmitentes',
                                    width: '100%',
                                })

                            select2.on('change', function(){
                                $wire.set('representados', $(this).val())
                            })

                            select2.on('keyup', function(e) {
                                if (e.keyCode === 13){
                                    $wire.set('representados', $('.select2').val())
                                }
                            });

                            $watch('model', (value) => {
                                select2.val(value).trigger('change');
                            });

                            Livewire.on('recargar', function(e) {

                                var newOption = new Option(e[0].description, e[0].id, false, false);

                                $($refs.select).append(newOption).trigger('change');

                            });

                        "
                        wire:ignore>

                        <select
                            class="bg-white rounded text-sm w-full z-50"
                            wire:model.live="representados"
                            x-ref="select"
                            multiple="multiple">

                            @if($inscripcion)

                                @foreach ($inscripcion->propietarios() as $propietario)

                                    <option value="{{ $propietario->id }}">{{ $propietario->persona->nombre }} {{ $propietario->persona->ap_paterno }} {{ $propietario->persona->ap_materno }} {{ $propietario->persona->razon_social }}</option>

                                @endforeach

                                @foreach ($inscripcion->transmitentes() as $transmitente)

                                    <option value="{{ $transmitente->id }}">{{ $transmitente->persona->nombre }} {{ $transmitente->persona->ap_paterno }} {{ $transmitente->persona->ap_materno }} {{ $transmitente->persona->razon_social }}</option>

                                @endforeach

                            @endif

                        </select>

                    </div>

                    <div>

                        @error('representados') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                    </div>

                </div>

            </div>

        </x-slot>

        <x-slot name="footer">

            <div class="flex gap-3">

                @if($crear)

                    <x-button-blue
                        wire:click="guardarRepresentante"
                        wire:loading.attr="disabled"
                        wire:target="guardarRepresentante">

                        <img wire:loading wire:target="guardarRepresentante" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        <span>Guardar</span>
                    </x-button-blue>

                @elseif($editar)

                    <x-button-blue
                        wire:click="actualizarActor"
                        wire:loading.attr="disabled"
                        wire:target="actualizarActor">

                        <img wire:loading wire:target="actualizarActor" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        <span>Actualizar</span>
                    </x-button-blue>

                @endif

                <x-button-red
                    wire:click="resetear"
                    wire:loading.attr="disabled"
                    wire:target="resetear"
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
                    wire:click="resetear"
                    wire:loading.attr="disabled"
                    wire:target="resetear"
                    type="button">
                    Cerrar
                </x-button-red>

            </div>

        </x-slot>

    </x-dialog-modal>

</div>

@push('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>

        window.addEventListener('imprimir_documento', event => {

            const documento = event.detail[0].inscripcion;

            var url = "{{ route('propiedad.inscripcion.boleta_presentacion', '')}}" + "/" + documento;

            window.open(url, '_blank');

            var url = "{{ route('propiedad.inscripcion.acto', '')}}" + "/" + documento;

            window.open(url, '_blank');

            window.location.href = "{{ route('propiedad')}}";

        });

    </script>

@endpush
