<div class="">

    <div class="mb-6">

        <x-header>Copiador  </x-header>

        <div class="flex justify-between">

            <div>

                <input type="text" wire:model.live.debounce.500ms="search" placeholder="Buscar" class="bg-white rounded-full text-sm">

                <select class="bg-white rounded-full text-sm" wire:model="pagination">

                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>

                </select>

            </div>

            @if(auth()->user()->hasRole(['Copiador']))

                <button wire:click="$set('modalCarga', '!modalCarga')" wire:loading.attr="disabled" class="bg-gray-500 hover:shadow-lg hover:bg-gray-700 text-sm py-2 px-4 text-white rounded-full hidden md:block items-center justify-center focus:outline-gray-400 focus:outline-offset-2">

                    <img wire:loading wire:target="modalCarga" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                    Imprimir carga de trabajo

                </button>

                <button wire:click="$set('modalCarga', '!modalCarga')" class="bg-gray-500 hover:shadow-lg hover:bg-gray-700 float-right text-sm py-2 px-4 text-white rounded-full focus:outline-none md:hidden">+</button>

            @endif

        </div>

    </div>

    <div class="overflow-x-auto rounded-lg shadow-xl border-t-2 border-t-gray-500">

        <x-table>

            <x-slot name="head">
                <x-table.heading># Control</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('estado')" :direction="$sort === 'estado' ? $direction : null" >Estado</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('tipo_servicio')" :direction="$sort === 'tipo_servicio' ? $direction : null" >Tipo de servicio</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('tomo')" :direction="$sort === 'tomo' ? $direction : null" >Tomo / Bis</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('registro')" :direction="$sort === 'registro' ? $direction : null" >Registro / Bis</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('distrito')" :direction="$sort === 'distrito' ? $direction : null" >Distrito</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('seccion')" :direction="$sort === 'seccion' ? $direction : null" >Sección</x-table.heading>
                <x-table.heading>Solicitante</x-table.heading>
                <x-table.heading >Número de páginas</x-table.heading>
                @if (auth()->user()->hasRole(['Supervisor certificaciones', 'Administrador', 'Supervisor uruapan', 'Jefe de departamento certificaciones', 'Operador']))
                    <x-table.heading >Folio de carpeta</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('usuario_asignado')" :direction="$sort === 'usuario_asignado' ? $direction : null" >Asignado a</x-table.heading>
                @endif
                @if (auth()->user()->hasRole(['Administrador', 'Operador', 'Director', 'Jefe de departamento jurídico']))
                    <x-table.heading >Reimpreso en</x-table.heading>
                @endif
                <x-table.heading sortable wire:click="sortBy('fecha_entrega')" :direction="$sort === 'fecha_entrega' ? $direction : null">Fecha de entrega</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('created_at')" :direction="$sort === 'created_at' ? $direction : null">Ingreso</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('updated_at')" :direction="$sort === 'updated_at' ? $direction : null">Actualizado</x-table.heading>
                @if (!auth()->user()->hasRole(['Administrador', 'Operador', 'Director', 'Jefe de departamento jurídico']))
                    <x-table.heading >Acciones</x-table.heading>
                @endif

            </x-slot>

            <x-slot name="body">

                @forelse ($this->copias as $copia)

                    <x-table.row wire:loading.class.delaylongest="opacity-50" wire:key="row-{{ $copia->id }}">

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl"># Control</span>

                            <div class="text-center">

                                <span class="whitespace-nowrap">{{ $copia->año }}-{{ $copia->tramite }}-{{ $copia->usuario }}</span>

                                @if(array_key_exists($copia->usuario, $usuarios_regionales))

                                    <span class="text-xs rounded-full px-1 bg-rojo">Regional {{ $usuarios_regionales[$copia->usuario] }}</span>

                                @endif

                            </div>

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Estado</span>

                            <span class="bg-{{ $copia->estado_color }} py-1 px-2 rounded-full text-white text-xs">{{ ucfirst($copia->estado) }}</span>

                        </x-table.cell>
                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Tipo de servicio</span>

                            {{ $copia->tipo_servicio }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Tomo / Bis</span>

                            {{ $copia->tomo }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Registro / Bis</span>

                            {{ $copia->registro }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Distrito</span>

                            {{ $copia->distrito }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Sección</span>

                            {{ $copia->seccion }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Solicitante</span>

                            {{ $copia->solicitante }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Número de páginas</span>

                            {{ $copia->certificacion->numero_paginas }}

                        </x-table.cell>

                        @if (auth()->user()->hasRole(['Supervisor certificaciones', 'Administrador', 'Supervisor uruapan', 'Jefe de departamento certificaciones', 'Operador']))

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Folio de carpeta</span>

                                {{ $copia->certificacion->folio_carpeta_copias ?? 'N/A' }}

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Folio de carpeta</span>

                                {{ $copia->asignadoA->name ?? 'N/A' }}

                            </x-table.cell>

                        @endif

                        @if (auth()->user()->hasRole(['Administrador', 'Operador', 'Director', 'Jefe de departamento jurídico']))

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Reimpreso en</span>

                                {{ optional($copia->certificacion->reimpreso_en)->format('d-m-Y H:i:s') ?? 'N/A' }}

                            </x-table.cell>

                        @endif

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Fecha de entrega</span>

                            {{ optional($copia->fecha_entrega)->format('d-m-Y') ?? 'N/A' }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Ingreso</span>

                            {{ $copia->created_at }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="font-semibold">@if($copia->actualizadoPor != null)Actualizado por: {{$copia->actualizadoPor->name}} @else Actualizado: @endif</span> <br>

                            {{ $copia->updated_at }}

                        </x-table.cell>

                        @if (!auth()->user()->hasRole(['Administrador', 'Operador', 'Director', 'Jefe de departamento jurídico']))

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

                                        @if($copia->estado == 'nuevo')

                                            <button
                                                wire:click="copiasElaboradas({{ $copia->id }})"
                                                wire:loading.attr="disabled"
                                                wire:confirm="¿Esta seguro que desea elaborar?"
                                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                role="menuitem">

                                                <span>Elaborar</span>

                                            </button>

                                            @can('Rechazar copias certificadas')

                                                <button
                                                    wire:click="abrirModalRechazar({{ $copia->certificacion->id }})"
                                                    wire:loading.attr="disabled"
                                                    class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                    role="menuitem">

                                                    <span>Rechazar</span>

                                                </button>

                                            @endcan

                                        @endif

                                        @can('Reasignar copias')

                                            <button
                                                wire:click="abrirModalReasignar({{ $copia->certificacion->id }})"
                                                wire:loading.attr="disabled"
                                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                role="menuitem">

                                                <span>Reasignar</span>

                                            </button>

                                        @endcan

                                    </div>

                                </div>

                            </x-table.cell>

                        @endif

                    </x-table.row>

                @empty

                    <x-table.row>

                        <x-table.cell colspan="20">

                            <div class="bg-white text-gray-500 text-center p-5 rounded-full text-lg">

                                No hay resultados.

                            </div>

                        </x-table.cell>

                    </x-table.row>

                @endforelse

            </x-slot>

            <x-slot name="tfoot">

                <x-table.row>

                    <x-table.cell colspan="20" class="bg-gray-50">

                        {{ $this->copias->links()}}

                    </x-table.cell>

                </x-table.row>

            </x-slot>

        </x-table>

    </div>

    <x-dialog-modal wire:model="modalRechazar" maxWidth="sm">

        <x-slot name="title">

            Rechazar

        </x-slot>

        <x-slot name="content">

            <div class="flex flex-col md:flex-row justify-between md:space-x-3 mb-5">

                <div class="flex-auto ">

                    <div>

                        <Label>Observaciones</Label>
                    </div>

                    <div>

                        <textarea rows="5" class="bg-white rounded text-sm w-full" wire:model="observaciones"></textarea>

                    </div>

                    <div>

                        @error('observaciones') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                    </div>

                </div>

            </div>

        </x-slot>

        <x-slot name="footer">

            <div class="flex items-center justify-end space-x-3">

                <x-button-blue
                    wire:click="rechazar"
                    wire:loading.attr="disabled"
                    wire:target="rechazar">

                    <img wire:loading wire:target="rechazar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <span>Rechazar</span>
                </x-button-blue>

                <x-button-red
                    wire:click="resetearTodo"
                    wire:loading.attr="disabled"
                    wire:target="resetearTodo"
                    type="button">
                    <span>Cerrar</span>
                </x-button-red>

            </div>

        </x-slot>

    </x-dialog-modal>

    <x-dialog-modal wire:model="modalCarga" maxWidth="sm">

        <x-slot name="title">

            Carga de trabajo

        </x-slot>

        <x-slot name="content">

            <div class="flex flex-col md:flex-row justify-between md:space-x-3 mb-5">

                <div class="flex-auto ">

                    <div>

                        <Label>Fecha inicial</Label>
                    </div>

                    <div>

                        <input type="date" class="bg-white rounded text-sm w-full" wire:model="fecha_inicio">

                    </div>

                    <div>

                        @error('fecha_inicio') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                    </div>

                </div>

                <div class="flex-auto ">

                    <div>

                        <Label>Fecha final</Label>
                    </div>

                    <div>

                        <input type="date" class="bg-white rounded text-sm w-full" wire:model="fecha_final">

                    </div>

                    <div>

                        @error('fecha_final') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                    </div>

                </div>

            </div>

        </x-slot>

        <x-slot name="footer">

            <div class="flex items-center justify-end space-x-3">

                <x-button-blue
                    wire:click="imprimirCarga"
                    wire:loading.attr="disabled"
                    wire:target="imprimirCarga">

                    <img wire:loading wire:target="imprimirCarga" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <span>Imprimir</span>
                </x-button-blue>

                <x-button-red
                    wire:click="resetearTodo"
                    wire:loading.attr="disabled"
                    wire:target="resetearTodo">
                    <span>Cerrar</span>
                </x-button-red>

            </div>

        </x-slot>

    </x-dialog-modal>

    <x-confirmation-modal wire:model="modalReasignar" maxWidth="sm">

        <x-slot name="title">
            Reasignar
        </x-slot>

        <x-slot name="content">
            ¿Esta seguro que desea reasignar el movimiento registral?
        </x-slot>

        <x-slot name="footer">

            <x-secondary-button
                wire:click="$toggle('modalReasignar')"
                wire:loading.attr="disabled"
            >
                No
            </x-secondary-button>

            <x-danger-button
                class="ml-2"
                wire:click="reasignar"
                wire:loading.attr="disabled"
                wire:target="reasignar"
            >
                Reasignar
            </x-danger-button>

        </x-slot>

    </x-confirmation-modal>

</div>
