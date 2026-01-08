<div class="">

    <div class="mb-6">

        <x-header>Consultas FR-I</x-header>

        <div class="grid grid-cols-1 lg:grid-cols-6 md:grid-cols-3 sm:grid-cols-2 gap-3 mb-3 bg-white rounded-lg p-3 shadow-lg items-end">

            <span class="lg:col-span-6 md:col-span-7 sm:col-span- text-center">Antecedente</span>

            <x-input-group for="folio_real" label="Folio real" class="w-full">

                <x-input-text id="folio_real" wire:model="folio_real"/>

            </x-input-group>

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

            <x-input-group for="seccion" label="Sección" class="w-full">

                <x-input-text id="seccion" wire:model="seccion"/>

            </x-input-group>

            <span class="lg:col-span-6 md:col-span-7 sm:col-span- text-center">Ubicación</span>

            {{-- <x-input-group for="codigo_postal" label="Código postal" :error="$errors->first('codigo_postal')" class="w-full">

                <x-input-text id="codigo_postal" wire:model.lazy="codigo_postal" />

            </x-input-group> --}}

            <x-input-group for="municipio" label="Municipio" :error="$errors->first('municipio')" class="w-full">

                <x-input-text id="municipio" wire:model="municipio"/>

            </x-input-group>

            {{-- <x-input-group for="ciudad" label="Ciudad" :error="$errors->first('ciudad')" class="w-full">

                <x-input-text id="ciudad" wire:model="ciudad"/>

            </x-input-group> --}}

            {{-- <x-input-group for="tipo_asentamiento" label="Tipo de asentamiento" :error="$errors->first('tipo_asentamiento')" class="w-full">

                <x-input-select id="tipo_asentamiento" wire:model="tipo_asentamiento" class="w-full">

                    <option value="">Seleccione una opción</option>

                    @foreach ($tipos_asentamientos as $nombre)

                        <option value="{{ $nombre }}">{{ $nombre }}</option>

                    @endforeach

                </x-input-select>

            </x-input-group> --}}

           {{--  <x-input-group for="nombre_asentamiento" label="Nombre del asentamiento" :error="$errors->first('nombre_asentamiento')" class="w-full">

                <x-input-select id="nombre_asentamiento" wire:model="nombre_asentamiento" class="w-full">

                    <option value="">Seleccione una opción</option>

                    @if($nombres_asentamientos)

                        @foreach ($nombres_asentamientos as $nombre)

                            <option value="{{ $nombre }}">{{ $nombre }}</option>

                        @endforeach

                    @endif

                </x-input-select>

            </x-input-group> --}}

            <x-input-group for="localidad_ubicacion" label="Localidad" :error="$errors->first('localidad_ubicacion')" class="w-full">

                <x-input-text id="localidad_ubicacion" wire:model="localidad_ubicacion" />

            </x-input-group>

            {{-- <x-input-group for="tipo_vialidad" label="Tipo de vialidad" :error="$errors->first('tipo_vialidad')" class="w-full">

                <x-input-select id="tipo_vialidad" wire:model="tipo_vialidad" class="w-full">

                    <option value="">Seleccione una opción</option>

                    @foreach ($tipos_vialidades as $vialidad)

                        <option value="{{ $vialidad }}">{{ $vialidad }}</option>

                    @endforeach

                </x-input-select>

            </x-input-group> --}}

            <x-input-group for="nombre_asentamiento" label="Nombre del asentamiento" :error="$errors->first('nombre_asentamiento')" class="w-full">

                <x-input-text id="nombre_asentamiento" wire:model="nombre_asentamiento" />

            </x-input-group>

            <x-input-group for="nombre_vialidad" label="Nombre de la vialidad" :error="$errors->first('nombre_vialidad')" class="w-full">

                <x-input-text id="nombre_vialidad" wire:model="nombre_vialidad" />

            </x-input-group>

            {{-- <x-input-group for="numero_exterior" label="Número exterior" :error="$errors->first('numero_exterior')" class="w-full">

                <x-input-text id="numero_exterior" wire:model="numero_exterior" />

            </x-input-group> --}}

            <span class="lg:col-span-6 md:col-span-7 sm:col-span- text-center">Propietario</span>

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

            <div class="col-span-1 sm:col-span-2 md:col-span-3 lg:col-span-6 flex justify-end gap-3">

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

    @if($folios_reales && $folios_reales->count() > 0)

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

                    @foreach ($folios_reales as $folio)

                        <x-table.row wire:loading.class.delaylongest="opacity-50" wire:key="row-{{ $folio->id }}">

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Folio</span>

                                {{ $folio->folio }}

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Estado</span>

                                <span class="bg-{{ $folio->estado_color }} py-1 px-2 rounded-full text-white text-xs">{{ ucfirst($folio->estado) }}</span>

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Ubicación</span>

                                <p class="mt-2">

                                    {{
                                        $folio->predio?->municipio . ', ' .
                                        $folio->predio?->ciudad . ', ' .
                                        $folio->predio?->codigo_postal . ', ' .
                                        $folio->predio?->nombre_asentamiento . ', ' .
                                        $folio->predio?->nombre_vialidad . ', # ' .
                                        $folio->predio?->numero_exterior
                                    }}

                                </p>

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Propietario</span>

                                <p class="mt-2">{{ $folio->predio?->primerPropietario() }}</p>

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Acciones</span>

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
