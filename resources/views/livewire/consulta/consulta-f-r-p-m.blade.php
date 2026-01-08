<div>

    <x-header>Consultas FR-PM</x-header>

    <div class="grid grid-cols-1 lg:grid-cols-6 md:grid-cols-3 sm:grid-cols-2 gap-3 mb-3 bg-white rounded-lg p-3 shadow-lg items-end">

        {{-- <span class="lg:col-span-6 md:col-span-7 sm:col-span- text-center">Antecedente</span> --}}

        <x-input-group for="folio" label="Folio real" class="w-full">

            <x-input-text id="folio" wire:model="folio"/>

        </x-input-group>

        <x-input-group for="tomo" label="Tomo" class="w-full">

            <x-input-text id="tomo" wire:model="tomo"/>

        </x-input-group>

        <x-input-group for="registro" label="Registro" class="w-full">

            <x-input-text id="registro" wire:model="registro"/>

        </x-input-group>

        <x-input-group for="distrito" label="Distrito" class="w-full">

            <x-input-select id="distrito" wire:model="distrito" class="w-full">

                <option value="">Seleccione una opción</option>

                @foreach ($distritos as $key => $nombre)

                    <option value="{{ $key }}">{{ $nombre }}</option>

                @endforeach

            </x-input-select>

        </x-input-group>

        <x-input-group for="denominacion" label="Denominación" class="w-full col-span-2">

            <x-input-text id="denominacion" wire:model="denominacion"/>

        </x-input-group>

    </div>

    <div class="flex justify-center gap-3 bg-white rounded-lg p-3 shadow-lg mb-5">

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

    @if($folios_reales && $folios_reales->count() > 0)

        <div class="overflow-x-auto rounded-lg shadow-xl border-t-2 border-t-gray-500">

            <x-table>

                <x-slot name="head">

                    <x-table.heading >Folio</x-table.heading>
                    <x-table.heading >Estado</x-table.heading>
                    <x-table.heading >Tomo</x-table.heading>
                    <x-table.heading >Registro</x-table.heading>
                    <x-table.heading >Dsitrito</x-table.heading>
                    <x-table.heading >Denominación</x-table.heading>
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

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Tomo</span>

                                {{ $folio->tomo_antecedente ?? 'N/A' }}

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Registro</span>

                                {{ $folio->registro_antecedente ?? 'N/A' }}

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Distrito</span>

                                {{ $folio->distrito }}

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Denominación</span>

                                <p class="mt-2">{{ Str::limit($folio->denominacion, 150) }}</p>

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Acciones</span>

                                <div >

                                    <x-button-green
                                        wire:click="ver({{ $folio->id }})"
                                        wire:loading.attr="disabled"
                                        class="mx-auto">

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

        <x-h4 class="mt-5">Folio real PM: {{ $folioReal->folio }} <span class="text-sm tracking-widest capitalize">({{ $folioReal->estado }})</span></x-h4>

        <div x-data="{ activeTab: 1 }">

            <div class="flex px-4 gap-4 justify-center items-center">

                <x-button-pill @click="activeTab = 1" x-bind:class="{ 'bg-gray-300 bg-opacity-5 text-black ': activeTab === 1 }">Folio Real</x-button-pill>

                <x-button-pill @click="activeTab = 2" x-bind:class="{ 'bg-gray-300 bg-opacity-5 text-black ': activeTab === 2 }">Asambleas</x-button-pill>

            </div>

            <div class="tab-panel rounded-lg" :class="{ 'active': activeTab === 1 }" x-show.transition.in.opacity.duration.800="activeTab === 1" x-cloak>

                @include('livewire.consulta.folio_persona_moral')

            </div>

            <div class="tab-panel rounded-lg" :class="{ 'active': activeTab === 2 }" x-show.transition.in.opacity.duration.800="activeTab === 2" x-cloak>

                @include('livewire.consulta.asambleas')

            </div>

        </div>

    @endif

</div>
