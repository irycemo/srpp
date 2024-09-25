<div class="">

    <div class="mb-6">

        <x-header>Movimientos Registrales</x-header>

        <div class="flex justify-between">

            <div class="flex gap-3 overflow-auto p-1">

                <select class="bg-white rounded-full text-sm" wire:model.live="filters.año">

                    <option value="">Año</option>

                    @foreach ($años as $año)

                        <option value="{{ $año }}">{{ $año }}</option>

                    @endforeach

                </select>

                <input type="number" wire:model.live.debounce.500ms="filters.tramite" placeholder="# control" class="bg-white rounded-full text-sm w-24">

                <input type="number" wire:model.live.debounce.500ms="filters.usuario" placeholder="Usuario" class="bg-white rounded-full text-sm w-20">

                <input type="number" wire:model.live.debounce.500ms="filters.folio_real" placeholder="F. Real" class="bg-white rounded-full text-sm w-24">

                <input type="number" wire:model.live.debounce.500ms="filters.folio" placeholder="Folio" class="bg-white rounded-full text-sm w-24">

                <select class="bg-white rounded-full text-sm w-min" wire:model.live="filters.estado">

                    <option value="nuevo">Nuevo</option>
                    <option value="elaborado">Elaborado</option>
                    <option value="rechazado">Rechazado</option>
                    <option value="finalizado">Finalizado</option>

                </select>

                <input type="number" wire:model.live.debounce.500ms="filters.tomo" placeholder="Tomo" class="bg-white rounded-full text-sm w-24">

                <input type="number" wire:model.live.debounce.500ms="filters.registro" placeholder="Registro" class="bg-white rounded-full text-sm w-24">

                <select class="bg-white rounded-full text-sm" wire:model.live="filters.distrito">

                    <option value="">Seleccione una opción</option>

                    @foreach ($distritos as $key => $distrito)

                        <option value="{{ $key }}">{{ $distrito }}</option>

                    @endforeach

                </select>

                <select class="bg-white rounded-full text-sm" wire:model.live="filters.usuario_asignado">

                    <option value="">Seleccione una opción</option>

                    @foreach ($usuarios as $usuario)

                        <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>

                    @endforeach

                </select>

                <x-input-select class="bg-white rounded-full text-sm w-min" wire:model.live="pagination">

                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>

                </x-input-select>

            </div>

        </div>

    </div>

    <div class="overflow-x-auto rounded-lg shadow-xl border-t-2 border-t-gray-500">

        <x-table>

            <x-slot name="head">

                <x-table.heading ># Control</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('folio')" :direction="$sort === 'folio' ? $direction : null" >Folio</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('estado')" :direction="$sort === 'estado' ? $direction : null" >estado</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('tomo')" :direction="$sort === 'tomo' ? $direction : null" >Tomo</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('registro')" :direction="$sort === 'registro' ? $direction : null" >Registro</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('numero_propiedad')" :direction="$sort === 'numero_propiedad' ? $direction : null" ># Propiedad</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('distrito')" :direction="$sort === 'distrito' ? $direction : null" >Distrito</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('seccion')" :direction="$sort === 'seccion' ? $direction : null" >Sección</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('usuario_asignado')" :direction="$sort === 'usuario_asignado' ? $direction : null" >Asignado a</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('usuario_supervisor')" :direction="$sort === 'usuario_supervisor' ? $direction : null" >Supervisor</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('created_at')" :direction="$sort === 'created_at' ? $direction : null">Registro</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('updated_at')" :direction="$sort === 'updated_at' ? $direction : null">Actualizado</x-table.heading>
                <x-table.heading >Acciones</x-table.heading>

            </x-slot>

            <x-slot name="body">

                @forelse ($movimientos as $movimiento)

                    <x-table.row wire:loading.class.delaylongest="opacity-50" wire:key="row-{{ $movimiento->id }}">

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Folio</span>

                            <span class="whitespace-nowrap">{{ $movimiento->año }}-{{ $movimiento->tramite }}-{{ $movimiento->usuario }}</span>

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Tomo</span>

                            {{ $movimiento->folioReal?->folio }}-{{ $movimiento->folio }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Estado</span>

                            <span class="bg-{{ $movimiento->estado_color }} py-1 px-2 rounded-full text-white text-xs">{{ ucfirst($movimiento->estado) }}</span>

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Tomo</span>

                            {{ $movimiento->tomo ?? 'N/A' }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Registro</span>

                            {{ $movimiento->registro ?? 'N/A' }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl"># Propiedad</span>

                            {{ $movimiento->numero_propiedad ?? 'N/A' }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Distrito</span>

                            {{ $movimiento->distrito }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Sección</span>

                            {{ $movimiento->seccion }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Asignado a</span>

                            {{ $movimiento->asignadoA?->name }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Supervisor</span>

                            {{ $movimiento->supervisor?->name }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Registrado</span>

                            {{ $movimiento->created_at }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="font-semibold">@if($movimiento->actualizadoPor != null)Actualizado por: {{$movimiento->actualizadoPor->name}} @else Actualizado: @endif</span> <br>

                            {{ $movimiento->updated_at }}

                        </x-table.cell>

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

                                    @if(in_array($movimiento->estado, ['concluido', 'finalizado', 'elaborado']))

                                        <button
                                            wire:click="abrirModalCorreccion({{ $movimiento->id }})"
                                            wire:loading.attr="disabled"
                                            class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                            role="menuitem">
                                            Corregir
                                        </button>

                                    @else

                                        @can('Reasignar movimiento')

                                            <button
                                                wire:click="abrirModalReasignarUsuario({{ $movimiento->id }})"
                                                wire:loading.attr="disabled"
                                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                role="menuitem">
                                                Reasignar usuario
                                            </button>

                                            <button
                                                wire:click="abrirModalReasignarSupervisor({{ $movimiento->id }})"
                                                wire:loading.attr="disabled"
                                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                role="menuitem">
                                                Reasignar supervisor
                                            </button>

                                        @endif

                                    @endif

                                </div>

                            </div>

                        </x-table.cell>

                    </x-table.row>

                @empty

                    <x-table.row>

                        <x-table.cell colspan="15">

                            <div class="bg-white text-gray-500 text-center p-5 rounded-full text-lg">

                                No hay resultados.

                            </div>

                        </x-table.cell>

                    </x-table.row>

                @endforelse

            </x-slot>

            <x-slot name="tfoot">

                <x-table.row>

                    <x-table.cell colspan="15" class="bg-gray-50">

                        {{ $movimientos->links()}}

                    </x-table.cell>

                </x-table.row>

            </x-slot>

        </x-table>

    </div>

    <x-dialog-modal wire:model="modalReasignarUsuario" maxWidth="sm">

        <x-slot name="title">

            Reasignar usuario

        </x-slot>

        <x-slot name="content">

            <div class="flex flex-col md:flex-row justify-between gap-3 mb-3">

                <x-input-group for="modelo_editar.usuario_asignado" label="Área" :error="$errors->first('modelo_editar.usuario_asignado')" class="w-full">

                    <x-input-select id="modelo_editar.usuario_asignado" wire:model="modelo_editar.usuario_asignado" class="w-full">

                        <option value="">Seleccione una opción</option>

                        @foreach ($usuarios as $usuario)

                            <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>

                        @endforeach

                    </x-input-select>

                </x-input-group>

            </div>

        </x-slot>

        <x-slot name="footer">

            <div class="flex gap-3">

                <x-button-blue
                    wire:click="reasignarUsuario"
                    wire:loading.attr="disabled"
                    wire:target="reasignarUsuario">

                    <img wire:loading wire:target="reasignarUsuario" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <span>Reasignar</span>
                </x-button-blue>

                <x-button-red
                    wire:click="$toggle('modalReasignarUsuario')"
                    wire:loading.attr="disabled"
                    wire:target="$toggle('modalReasignarUsuario')"
                    type="button">
                    Cerrar
                </x-button-red>

            </div>

        </x-slot>

    </x-dialog-modal>

    <x-dialog-modal wire:model="modalReasignarSupervisor" maxWidth="sm">

        <x-slot name="title">

            Reasignar supervisor

        </x-slot>

        <x-slot name="content">

            <div class="flex flex-col md:flex-row justify-between gap-3 mb-3">

                <x-input-group for="modelo_editar.usuario_supervisor" label="Área" :error="$errors->first('modelo_editar.usuario_supervisor')" class="w-full">

                    <x-input-select id="modelo_editar.usuario_supervisor" wire:model="modelo_editar.usuario_supervisor" class="w-full">

                        <option value="">Seleccione una opción</option>

                        @foreach ($supervisores as $supervisor)

                            <option value="{{ $supervisor->id }}">{{ $supervisor->name }}</option>

                        @endforeach

                    </x-input-select>

                </x-input-group>

            </div>

        </x-slot>

        <x-slot name="footer">

            <div class="flex gap-3">

                <x-button-blue
                    wire:click="reasignarSupervisor"
                    wire:loading.attr="disabled"
                    wire:target="reasignarSupervisor">

                    <img wire:loading wire:target="reasignarSupervisor" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <span>Reasignar</span>
                </x-button-blue>

                <x-button-red
                    wire:click="$toggle('modalReasignarSupervisor')"
                    wire:loading.attr="disabled"
                    wire:target="$toggle('modalReasignarSupervisor')"
                    type="button">
                    Cerrar
                </x-button-red>

            </div>

        </x-slot>

    </x-dialog-modal>

    <x-confirmation-modal wire:model="modalCorreccion" maxWidth="sm">

        <x-slot name="title">
            Corrección
        </x-slot>

        <x-slot name="content">
            ¿Esta seguro que desea cambiar estado a corrección?
        </x-slot>

        <x-slot name="footer">

            <x-secondary-button
                wire:click="$toggle('modalCorreccion')"
                wire:loading.attr="disabled"
            >
                No
            </x-secondary-button>

            <x-danger-button
                class="ml-2"
                wire:click="correccion"
                wire:loading.attr="disabled"
                wire:target="correccion"
            >
                Si
            </x-danger-button>

        </x-slot>

    </x-confirmation-modal>

</div>
