<div>

    <x-header>Indices Propiedad</x-header>

    <div class="bg-white p-4 rounded-lg mb-5 shadow-xl">

        <div class="flex gap-3 justify-center items-center lg:w-1/2 mx-auto mb-4">

            <x-input-group for="nombre" label="Nombre" :error="$errors->first('nombre')" class="w-full">

                <x-input-text id="nombre" wire:model="nombre" />

            </x-input-group>

            <x-input-group for="ap_paterno" label="Apellido paterno" :error="$errors->first('ap_paterno')" class="w-full">

                <x-input-text id="ap_paterno" wire:model="ap_paterno" />

            </x-input-group>

            <x-input-group for="ap_materno" label="Apellido materno" :error="$errors->first('ap_materno')" class="w-full">

                <x-input-text id="ap_materno" wire:model="ap_materno" />

            </x-input-group>

        </div>

        <div class="felx justify-center mb-5">

            <x-button-blue
                wire:click="buscarPorPropietario"
                wire:target="buscarPorPropietario"
                wire:loading.attr="disabled"
                class="mx-auto">

                <img wire:loading wire:target="buscarPorPropietario" class="h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                Buscar por propietario
            </x-button-blue>

        </div>


        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 mb-3 col-span-2 rounded-lg w-full lg:w-1/2 mx-auto">

            <x-input-group for="distrito" label="Distrito" :error="$errors->first('distrito')" class="w-full">

                <x-input-select id="distrito" wire:model="distrito" class="w-full">

                    <option value="">Seleccione una opción</option>
                    @foreach ($distritos as $key => $value)

                        <option value="{{ $key }}">{{ $value }}</option>

                    @endforeach

                </x-input-select>

            </x-input-group>

            <x-input-group for="tomo" label="Tomo" :error="$errors->first('tomo')" class="w-full">

                <x-input-text id="tomo" wire:model="tomo"/>

            </x-input-group>

            <x-input-group for="registro" label="Registro" :error="$errors->first('registro')" class="w-full">

                <x-input-text id="registro" wire:model="registro"/>

            </x-input-group>

            <x-input-group for="numero_propiedad" label="Número de propiedad" :error="$errors->first('numero_propiedad')" class="w-full">

                <x-input-text id="numero_propiedad" wire:model="numero_propiedad"/>

            </x-input-group>

        </div>

        <div class="felx justify-center">

            <x-button-blue
                wire:click="buscar"
                wire:target="buscar"
                wire:loading.attr="disabled"
                class="mx-auto">

                <img wire:loading wire:target="buscar" class="h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                Buscar
            </x-button-blue>

        </div>

    </div>

    @if(!$propiedad->getKey())

        <div class="overflow-x-auto rounded-lg shadow-xl border-t-2 border-t-gray-500">

            <x-table>

                <x-slot name="head">

                    <x-table.heading >Distrito</x-table.heading>
                    <x-table.heading >Tomo</x-table.heading>
                    <x-table.heading >Registro</x-table.heading>
                    <x-table.heading ># Propiead</x-table.heading>
                    <x-table.heading >Propietarios</x-table.heading>
                    <x-table.heading >Ubicación</x-table.heading>
                    <x-table.heading >Acciones</x-table.heading>

                </x-slot>

                <x-slot name="body">

                    @forelse ($propiedades as $item)

                        <x-table.row wire:loading.class.delaylongest="opacity-50" wire:key="row-{{$item->id }}">

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Distrito</span>

                                {{$item->distrito }}

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Tomo</span>

                                {{$item->tomo }}

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Registro</span>

                                {{$item->registro }}

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl"># Propiedad</span>

                                {{$item->noprop }}

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Propietarios</span>

                                {{ Str::limit($item->propietarios, 50) }}

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Ubicación</span>

                                {{ Str::limit($item->ubicacion, 70) }}

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Acciones</span>

                                <div class="flex justify-center lg:justify-start gap-2">

                                    <x-button-green
                                        wire:click="abrirModalVer({{$item->id }})"
                                        wire:target="abrirModalVer({{$item->id }})"
                                        wire:loading.attr="disabled"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>

                                        <span>Ver</span>

                                    </x-button-green>

                                </div>

                            </x-table.cell>

                        </x-table.row>

                    @empty

                        <x-table.row>

                            <x-table.cell colspan="9">

                                <div class="bg-white text-gray-500 text-center p-5 rounded-full text-lg">

                                    No hay resultados.

                                </div>

                            </x-table.cell>

                        </x-table.row>

                    @endforelse

                </x-slot>

                <x-slot name="tfoot">
                </x-slot>

            </x-table>

        </div>

    @else

        <div x-data="{ activeTab: 0 }">

            <div class="flex px-4 gap-4 justify-center items-center mb-5">

                <x-button-pill @click="activeTab = 0" x-bind:class="{ 'bg-gray-300 bg-opacity-5 text-black ': activeTab === 0 }">Propiedad</x-button-pill>

                <x-button-pill @click="activeTab = 1" x-bind:class="{ 'bg-gray-300 bg-opacity-5 text-black ': activeTab === 1 }">Antecedente ({{ $antecedentes->count() }})</x-button-pill>

                <x-button-pill @click="activeTab = 2" x-bind:class="{ 'bg-gray-300 bg-opacity-5 text-black ': activeTab === 2 }">Ventas ({{ $ventas->count() }})</x-button-pill>

                <x-button-pill @click="activeTab = 3" x-bind:class="{ 'bg-gray-300 bg-opacity-5 text-black ': activeTab === 3 }">Gravamenes ({{ $gravamenes->count() }})</x-button-pill>

                <x-button-pill @click="activeTab = 4" x-bind:class="{ 'bg-gray-300 bg-opacity-5 text-black ': activeTab === 4 }">Sentencias ({{ $sentencias->count() }})</x-button-pill>

                <x-button-pill @click="activeTab = 5" x-bind:class="{ 'bg-gray-300 bg-opacity-5 text-black ': activeTab === 5 }">Varios ({{ $varios->count() }})</x-button-pill>

            </div>

            <div class="tab-panel" :class="{ 'active': activeTab === 0 }" x-show.transition.in.opacity.duration.800="activeTab === 0" x-cloak>

                @include('livewire.consulta.indices-propiedad-datos', ['propiedad' => $propiedad])

            </div>

            <div class="tab-panel" :class="{ 'active': activeTab === 1 }" x-show.transition.in.opacity.duration.800="activeTab === 1" x-cloak>

                @foreach ($antecedentes as $antecedente_item)

                    <div id="antecedente-{{ $loop->index }}" class="mb-5">

                        @include('livewire.consulta.indices-antecedentes-datos', ['antecedente' => $antecedente_item])

                    </div>

                @endforeach

            </div>

            <div class="tab-panel" :class="{ 'active': activeTab === 2 }" x-show.transition.in.opacity.duration.800="activeTab === 2" x-cloak>

                @foreach ($ventas as $venta_item)

                    <div id="venta-{{ $loop->index }}" class="mb-5">

                        @include('livewire.consulta.indices-antecedentes-datos', ['antecedente' => $venta_item])

                    </div>

                @endforeach

            </div>

            <div class="tab-panel" :class="{ 'active': activeTab === 3 }" x-show.transition.in.opacity.duration.800="activeTab === 3" x-cloak>

                @foreach ($gravamenes as $gravamen_item)

                    <div id="gravamen-{{ $loop->index }}" class="mb-5">

                        @include('livewire.consulta.indices-gravamen-datos', ['gravamen' => $gravamen_item])

                    </div>

                @endforeach

            </div>

            <div class="tab-panel" :class="{ 'active': activeTab === 4 }" x-show.transition.in.opacity.duration.800="activeTab === 4" x-cloak>

                @foreach ($sentencias as $sentencia_item)

                    <div id="sentencia-{{ $loop->index }}" class="mb-5">

                        @include('livewire.consulta.indices-sentencia-datos', ['sentencia' => $sentencia_item])

                    </div>

                @endforeach

            </div>

            <div class="tab-panel" :class="{ 'active': activeTab === 5 }" x-show.transition.in.opacity.duration.800="activeTab === 5" x-cloak>

                @foreach ($varios as $varios_item)

                    <div id="vario-{{ $loop->index }}" class="mb-5">

                        @include('livewire.consulta.indices-varios-datos', ['vario' => $varios_item])

                    </div>

                @endforeach

            </div>

        </div>

    @endif

</div>
