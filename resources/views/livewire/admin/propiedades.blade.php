<div class="">

    <div class="mb-6">

        <x-header>Propiedades</x-header>

        <div class="flex gap-3 justify-between overflow-auto p-1">

            <div class="flex">

                <select class="bg-white rounded-l text-sm border border-r-transparent  focus:ring-0 @error('distrito') border-red-500 @enderror " wire:model="distrito">
                    @foreach ($distritos as $key => $distrito)

                        <option value="{{ $key }}">{{ $distrito }}</option>

                    @endforeach
                </select>

                <input type="number" placeholder="Tomo" min="1" class="bg-white w-24 text-sm focus:ring-0 @error('tomo') border-red-500 @enderror " wire:model="tomo">

                <input type="number" placeholder="Registro" min="1" class="bg-white text-sm w-20 focus:ring-0 border-l-0 @error('registro') border-red-500 @enderror" wire:model="registro">

                <input type="number" placeholder="# Propiedad" min="1" class="bg-white text-sm w-28 focus:ring-0 border-l-0 @error('numero_propiedad') border-red-500 @enderror" wire:model="numero_propiedad">

                <button
                    wire:click="consultar"
                    wire:loading.attr="disabled"
                    wire:target="consultar"
                    type="button"
                    class="relative bg-blue-400 hover:shadow-lg text-white font-bold px-4 rounded-r text-sm hover:bg-blue-700 focus:outline-blue-400 focus:outline-offset-2">

                    {{-- <img wire:loading wire:target="consultar" class="mx-auto h-5 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading"> --}}

                    <div wire:loading.flex class="flex absolute top-2 right-2 items-center">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                    <svg wire:loading.remove xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>

                </button>

            </div>

        </div>

    </div>

    <div class="rounded-lg shadow-xl border-t-2 border-t-gray-500">

        @if($modelo_editar->id)

            <x-table>

                <x-slot name="head">

                    <x-table.heading>Distrito</x-table.heading>
                    <x-table.heading>Tomo</x-table.heading>
                    <x-table.heading>Registro</x-table.heading>
                    <x-table.heading># Propiedad</x-table.heading>
                    <x-table.heading>Estado</x-table.heading>
                    <x-table.heading>Ubicación</x-table.heading>
                    <x-table.heading>Propietarios</x-table.heading>
                    @if(auth()->user()->hasRole('Administrador'))
                        <x-table.heading >Acciones</x-table.heading>
                    @endif

                </x-slot>

                <x-slot name="body">

                    <x-table.row wire:loading.class.delaylongest="opacity-50" wire:key="row-{{ $modelo_editar->id }}">

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Distrito</span>

                            {{ $modelo_editar->distrito }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Tomo</span>

                            {{ $modelo_editar->tomo }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Registro</span>

                            {{ $modelo_editar->registro }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl"># Propiedad</span>

                            {{ $modelo_editar->noprop }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Estado</span>

                            {{ $modelo_editar->status }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Ubicación</span>

                            {{ $modelo_editar->ubicacion }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Propietarios</span>

                            {{ $modelo_editar->propietarios }}

                        </x-table.cell>

                        @if(auth()->user()->hasRole('Administrador'))

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Acciones</span>

                                <div class="ml-3 relative" x-data="{ open_drop_down:false }">

                                    <div>

                                        <button x-on:click="open_drop_down=true" type="button" class="rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">

                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                            </svg>

                                        </button>

                                    </div>

                                    <div x-cloak x-show="open_drop_down" x-on:click="open_drop_down=false" x-on:click.away="open_drop_down=false" class="z-50 origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu">

                                        <button
                                            wire:click="abrirModalEditar({{ $modelo_editar->id }})"
                                            wire:loading.attr="disabled"
                                            class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                            role="menuitem">
                                            Actualizar estado
                                        </button>

                                    </div>

                                </div>

                            </x-table.cell>

                        @endif

                    </x-table.row>

                </x-slot>

                <x-slot name="tfoot">

                    <x-table.row>

                        <x-table.cell colspan="14" class="bg-gray-50">

                        </x-table.cell>

                    </x-table.row>

                </x-slot>

            </x-table>

        @else

            <div class="border-b border-gray-300 bg-white text-gray-500 text-center p-5  text-lg">

                No hay resultados.

            </div>

        @endif

    </div>

    <x-dialog-modal wire:model="modal" maxWidth="sm">

        <x-slot name="title">
            Cambiar estado
        </x-slot>

        <x-slot name="content">

            <div class="flex flex-col md:flex-row justify-between md:space-x-3 mb-5">

                <x-input-group for="modelo_editar.status" label="Status" :error="$errors->first('modelo_editar.status')" class="w-full">

                    <x-input-select id="modelo_editar.status" wire:model="modelo_editar.status" class="w-full">

                        <option value="">Seleccione una opción</option>
                        <option value="P">Vendida parcialmente (P)</option>
                        <option value="V">Vendida (V)</option>

                    </x-input-select>

                </x-input-group>

            </div>

        </x-slot>

        <x-slot name="footer">

            <x-secondary-button
                wire:click="$toggle('modal')"
                wire:loading.attr="disabled"
            >
                No
            </x-secondary-button>

            <x-danger-button
                class="ml-2"
                wire:click="save"
                wire:loading.attr="disabled"
                wire:target="save"
            >
                Actualizar
            </x-danger-button>

        </x-slot>

    </x-dialog-modal>

</div>
