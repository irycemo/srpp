<div class="">

    @if($ver_filtros)

        <div class="mb-6">

            <x-header>Consultas FR-I</x-header>

            <div class="mb-3 bg-white rounded-lg p-3 shadow-lg">

                <div class="text-center mb-3">

                    <span >Folio real</span>

                    <x-input-group for="folio_real" label="" class="w-fit mx-auto">

                        <x-input-text id="folio_real" wire:model="folio_real"/>

                    </x-input-group>

                </div>

                <div class="grid grid-cols-1 lg:grid-cols-4 sm:grid-cols-2 gap-3 items-end mb-3">

                    <span class="lg:col-span-4 sm:col-span-2 text-center">Antecedente</span>

                    <x-input-group for="tomo" label="Tomo" class="w-full">

                        <x-input-text id="tomo" wire:model="tomo"/>

                    </x-input-group>

                    <x-input-group for="registro" label="Registro" class="w-full">

                        <x-input-text id="registro" wire:model="registro"/>

                    </x-input-group>

                    <x-input-group for="numero_propiedad" label="Número de propiedad" class="w-full">

                        <x-input-text id="numero_propiedad" wire:model="numero_propiedad"/>

                    </x-input-group>

                    <x-input-group for="distrito" label="Distrito" class="w-full">

                        <x-input-select id="distrito" wire:model="distrito" class="w-full">

                            <option value="">Seleccione una opción</option>

                            @foreach ($distritos as $key => $nombre)

                                <option value="{{ $key }}">{{ $nombre }}</option>

                            @endforeach

                        </x-input-select>

                    </x-input-group>

                </div>

                <div class="grid grid-cols-1 lg:grid-cols-4 md:grid-cols-2 gap-3 items-end mb-3">

                    <span class="lg:col-span-4 md:col-span-2 text-center">Ubicación</span>

                    <x-input-group for="municipio" label="Municipio" :error="$errors->first('municipio')" class="w-full">

                        <x-input-text id="municipio" wire:model="municipio"/>

                    </x-input-group>

                    <x-input-group for="localidad_ubicacion" label="Localidad" :error="$errors->first('localidad_ubicacion')" class="w-full">

                        <x-input-text id="localidad_ubicacion" wire:model="localidad_ubicacion" />

                    </x-input-group>

                    <x-input-group for="nombre_asentamiento" label="Nombre del asentamiento" :error="$errors->first('nombre_asentamiento')" class="w-full">

                        <x-input-text id="nombre_asentamiento" wire:model="nombre_asentamiento" />

                    </x-input-group>

                    <x-input-group for="nombre_vialidad" label="Nombre de la vialidad" :error="$errors->first('nombre_vialidad')" class="w-full">

                        <x-input-text id="nombre_vialidad" wire:model="nombre_vialidad" />

                    </x-input-group>

                </div>

                <div class="grid grid-cols-1 lg:grid-cols-4 md:grid-cols-2 gap-3 items-end mb-3">

                    <span class="lg:col-span-4 md:col-span-3 text-center">Propietario</span>

                    <x-input-group for="nombre_propietario" label="Nombre del propietario" :error="$errors->first('nombre_propietario')" class="w-full">

                        <x-input-text id="nombre_propietario" wire:model="nombre_propietario" />

                    </x-input-group>

                    <x-input-group for="ap_paterno" label="Ap. paterno del propietario" :error="$errors->first('ap_paterno')" class="w-full">

                        <x-input-text id="ap_paterno" wire:model="ap_paterno" />

                    </x-input-group>

                    <x-input-group for="ap_materno" label="Ap. materno del propietario" :error="$errors->first('ap_materno')" class="w-full">

                        <x-input-text id="ap_materno" wire:model="ap_materno" />

                    </x-input-group>

                    <x-input-group for="razon_social" label="Razón social" :error="$errors->first('razon_social')" class="w-full">

                        <x-input-text id="razon_social" wire:model="razon_social" />

                    </x-input-group>

                </div>

                <div class="grid grid-cols-1 lg:grid-cols-6 md:grid-cols-3 gap-3 items-end mb-3">

                    <span class="lg:col-span-6 md:col-span-3 text-center">Documento de entrada</span>

                    <x-input-group for="tipo_documento" label="Tipo de documento" :error="$errors->first('tipo_documento')" class="w-full">

                        <x-input-text id="tipo_documento" wire:model="tipo_documento" />

                    </x-input-group>

                    <x-input-group for="numero_documento" label="Número de documento" :error="$errors->first('numero_documento')" class="w-full">

                        <x-input-text id="numero_documento" wire:model="numero_documento" />

                    </x-input-group>

                    <x-input-group for="autoridad_cargo" label="Cargo de la autoridad" :error="$errors->first('autoridad_cargo')" class="w-full">

                        <x-input-text id="autoridad_cargo" wire:model="autoridad_cargo" />

                    </x-input-group>

                    <x-input-group for="autoridad_nombre" label="Nombre de la autoridad" :error="$errors->first('autoridad_nombre')" class="w-full">

                        <x-input-text id="autoridad_nombre" wire:model="autoridad_nombre" />

                    </x-input-group>

                    <x-input-group for="fecha_emision" label="Fecha de emisión" :error="$errors->first('fecha_emision')" class="w-full">

                        <x-input-text type="date" id="fecha_emision" wire:model="fecha_emision" />

                    </x-input-group>

                    <x-input-group for="procedencia" label="Procedencia" :error="$errors->first('procedencia')" class="w-full">

                        <x-input-text id="procedencia" wire:model="procedencia" />

                    </x-input-group>

                </div>

                <div class="flex gap-4 items-center justify-center">

                    <x-button-green
                        wire:click="limpiar"
                        wire:loading.attr="disabled"
                        wire:target="limpiar">

                        <img wire:loading wire:target="limpiar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        Limpiar

                    </x-button-green>

                    <x-button-blue
                        wire:click="buscar"
                        wire:loading.attr="disabled"
                        wire:target="buscar">

                        <img wire:loading wire:target="buscar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        Buscar

                    </x-button-blue>

                </div>

            </div>

        </div>

    @else

        <div class="mb-3 bg-white rounded-lg p-3 shadow-lg text-center">

            <span class="rounded-full px-2 tracking-widest bg-gray-400 text-sm text-white py-1 cursor-pointer" wire:click="$toggle('ver_filtros')">Ver filtros</span>

        </div>

    @endif

    @if($this->foliosReales->count())

        <div class="overflow-x-auto rounded-lg shadow-xl border-t-2 border-t-gray-500">

            <x-table>

                <x-slot name="head">

                    <x-table.heading >Folio</x-table.heading>
                    <x-table.heading >Estado</x-table.heading>
                    <x-table.heading >Ubicación</x-table.heading>
                    <x-table.heading >Propietario</x-table.heading>
                    <x-table.heading >Acciones</x-table.heading>

                </x-slot>

                <x-slot name="body">

                    @foreach ($this->foliosReales as $folio)

                        <x-table.row wire:loading.class.delaylongest="opacity-50" wire:key="row-{{ $folio->id }}">

                            <x-table.cell title="Folio">

                                {{ $folio->folio }}

                            </x-table.cell>

                            <x-table.cell title="Estado">

                                <span class="bg-{{ $folio->estado_color }} py-1 px-2 rounded-full text-white text-xs">{{ ucfirst($folio->estado) }}</span>

                            </x-table.cell>

                            <x-table.cell title="Ubicación">

                                {{
                                    $folio->predio?->municipio . ', ' .
                                    $folio->predio?->ciudad . ', ' .
                                    $folio->predio?->codigo_postal . ', ' .
                                    $folio->predio?->nombre_asentamiento . ', ' .
                                    $folio->predio?->nombre_vialidad . ', # ' .
                                    $folio->predio?->numero_exterior
                                }}

                            </x-table.cell>

                            <x-table.cell title="Propietario">

                                {{ $folio->predio?->primerPropietario() }}

                            </x-table.cell>

                            <x-table.cell title="Acciones">

                                <div class="flex flex-col justify-center lg:justify-start gap-2">

                                    <x-button-green
                                        wire:click="ver({{ $folio->id }})"
                                        wire:loading.attr="disabled">

                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>

                                        <span>Ver</span>

                                    </x-button-green>

                                </div>

                            </x-table.cell>

                        </x-table.row>

                    @endforeach

                </x-slot>

                <x-slot name="tfoot">

                    <x-table.row>

                        <x-table.cell colspan="13" class="bg-gray-50">

                            {{ $this->foliosReales->links()}}

                        </x-table.cell>

                    </x-table.row>

                </x-slot>

            </x-table>

        </div>

    @endif

    @if ($folioReal)

        <x-h4 class="mb-5">Folio real: {{ $folioReal->folio }} <span class="text-sm tracking-widest capitalize">({{ $folioReal->estado }}) @if($folioReal->matriz) matriz @endif</span></x-h4>

        <div x-data="{ activeTab: 8 }" class=" w-full">

            <div class="flex gap-4 lg:justify-center lg:items-center overflow-auto">

                <x-button-pill @click="activeTab = 8" x-bind:class="{ 'bg-gray-300 bg-opacity-5 text-black ': activeTab === 8 }">Folio Real</x-button-pill>

                <x-button-pill @click="activeTab = 4" x-bind:class="{ 'bg-gray-300 bg-opacity-5 text-black ': activeTab === 4 }">Propiedad</x-button-pill>

                <x-button-pill @click="activeTab = 5" x-bind:class="{ 'bg-gray-300 bg-opacity-5 text-black ': activeTab === 5 }">Gravamen</x-button-pill>

                <x-button-pill @click="activeTab = 6" x-bind:class="{ 'bg-gray-300 bg-opacity-5 text-black ': activeTab === 6 }">Sentencias</x-button-pill>

                <x-button-pill @click="activeTab = 7" x-bind:class="{ 'bg-gray-300 bg-opacity-5 text-black ': activeTab === 7 }">Varios</x-button-pill>

                <x-button-pill @click="activeTab = 9" x-bind:class="{ 'bg-gray-300 bg-opacity-5 text-black ': activeTab === 9 }">Cancelaciones</x-button-pill>

                <x-button-pill @click="activeTab = 10" x-bind:class="{ 'bg-gray-300 bg-opacity-5 text-black ': activeTab === 10 }">Certificaciones</x-button-pill>

            </div>

            <div class="tab-panel rounded-lg" :class="{ 'active': activeTab === 8 }" x-show.transition.in.opacity.duration.800="activeTab === 8" x-cloak>

                @include('livewire.consulta.documento-entrada')

                @include('livewire.consulta.ubicacion')

                @include('livewire.consulta.descripcion')

                @include('livewire.consulta.propietarios')

            </div>

            <div class="tab-panel rounded-lg" :class="{ 'active': activeTab === 7 }" x-show.transition.in.opacity.duration.800="activeTab === 7" x-cloak>

                @include('livewire.consulta.varios')

            </div>

            <div class="tab-panel rounded-lg" :class="{ 'active': activeTab === 6 }" x-show.transition.in.opacity.duration.800="activeTab === 6" x-cloak>

                @include('livewire.consulta.sentencia')

            </div>

            <div class="tab-panel" :class="{ 'active': activeTab === 4 }" x-show.transition.in.opacity.duration.800="activeTab === 4">

                @include('livewire.consulta.propiedad')

            </div>

            <div class="tab-panel rounded-lg" :class="{ 'active': activeTab === 5 }" x-show.transition.in.opacity.duration.800="activeTab === 5" x-cloak>

                @include('livewire.consulta.gravamen')

            </div>

            <div class="tab-panel" :class="{ 'active': activeTab === 9 }" x-show.transition.in.opacity.duration.800="activeTab === 9">

                @include('livewire.consulta.cancelaciones')

            </div>

            <div class="tab-panel" :class="{ 'active': activeTab === 10 }" x-show.transition.in.opacity.duration.800="activeTab === 10">

                @include('livewire.consulta.certificaciones')

            </div>

        </div>

    @endif

</div>
