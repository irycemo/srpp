<div class="">

    <div class="mb-2 lg:mb-5">

        <x-header>Asignación de folio simplificado</x-header>

        <div class="flex justify-between items-center">

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

                <input type="number" wire:model.live.debounce.500ms="filters.folio" placeholder="M.R." class="bg-white rounded-full text-sm w-24">

                <select class="bg-white rounded-full text-sm w-min" wire:model.live="filters.estado">

                    <option value="">Estado</option>
                    <option value="nuevo">Nuevo</option>
                    <option value="elaborado">Elaborado</option>
                    <option value="rechazado">Rechazado</option>
                    <option value="finalizado">Finalizado</option>
                    <option value="correccion">Corrección</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="no recibido">No recibido</option>

                </select>

                <select class="bg-white rounded-full text-sm" wire:model.live="pagination">

                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>

                </select>

            </div>

            <div class="ml-3 relative" x-data="{ open_drop_down:false }">

                <div>

                    <button x-on:click="open_drop_down=true" type="button" class="border-gray-500 border-2 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                        </svg>

                    </button>

                </div>

                <div x-cloak x-show="open_drop_down" x-on:click="open_drop_down=false" x-on:click.away="open_drop_down=false" class="z-50 origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu">

                    @if(auth()->user()->hasRole(['Pase a folio', 'Propiedad', 'Registrador Propiedad']) && auth()->user()->ubicacion === 'Regional 4')

                        <button
                            wire:click="$toggle('modalBuscarTramite')"
                            wire:loading.attr="disabled"
                            class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                            role="menuitem">
                            Reasignarme pase a folio
                        </button>

                    @endif

                </div>

            </div>

        </div>

    </div>

    <div class="overflow-x-auto rounded-lg shadow-xl border-t-2 border-t-gray-500">

        <x-table>

            <x-slot name="head">
                <x-table.heading sortable wire:click="sortBy('folio_real')" :direction="$sort === 'folio_real' ? $direction : null" >Folio real</x-table.heading>
                <x-table.heading># Control</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('tomo')" :direction="$sort === 'tomo' ? $direction : null" >Tomo</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('registro')" :direction="$sort === 'registro' ? $direction : null">Registro</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('numero_propiedad')" :direction="$sort === 'numero_propiedad' ? $direction : null" ># propiedad</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('distrito')" :direction="$sort === 'distrito' ? $direction : null">Distrito</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('estado')" :direction="$sort === 'estado' ? $direction : null">Estado</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('usuario_asignado')" :direction="$sort === 'usuario_asignado' ? $direction : null">Usuario asignado</x-table.heading>
                @if(auth()->user()->hasRole(['Administrador' , 'Operador']))
                    <x-table.heading sortable wire:click="sortBy('usuario_supervisor')" :direction="$sort === 'usuario_supervisor' ? $direction : null">Supervisor</x-table.heading>
                @endif
                <x-table.heading sortable wire:click="sortBy('created_at')" :direction="$sort === 'created_at' ? $direction : null">Registro</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('updated_at')" :direction="$sort === 'updated_at' ? $direction : null">Actualizado</x-table.heading>
                @if(!auth()->user()->hasRole(['Administrador' , 'Operador']))
                    <x-table.heading >Acciones</x-table.heading>
                @endif

            </x-slot>

            <x-slot name="body">

                @forelse ($movimientos as $movimiento)

                    <x-table.row wire:loading.class.delaylongest="opacity-50" wire:key="row-{{ $movimiento->id }}">

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Folio real</span>

                            <span>
                                {{ $movimiento->folioReal->folio ?? 'N/A' }}
                            </span>
                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Número de control</span>

                            <span class="whitespace-nowrap">{{ $movimiento->año }}-{{ $movimiento->tramite }}-{{ $movimiento->usuario }}</span>

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Tomo</span>

                            {{ $movimiento->tomo ?? 'N/A' }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Registro</span>

                            {{ $movimiento->registro ?? 'N/A' }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Número de propiedad</span>

                            {{ $movimiento->numero_propiedad ?? 'N/A' }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Distrito</span>

                            {{ $movimiento->distrito }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Estado</span>

                            @if($movimiento->folio_real)

                                <span class="bg-{{ $movimiento->folioReal?->estado_color }} py-1 px-2 rounded-full text-white text-xs whitespace-nowrap">{{ ucfirst($movimiento->folioReal?->estado) }}</span>

                            @else

                                <span class="bg-{{ $movimiento->estado_color }} py-1 px-2 rounded-full text-white text-xs whitespace-nowrap">{{ ucfirst($movimiento->estado) }}</span>

                            @endif

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Usuario asignado</span>

                            <p class="mt-2">{{ $movimiento->asignadoA?->name }}</p>

                        </x-table.cell>

                        @if(auth()->user()->hasRole(['Administrador' , 'Operador']))

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Supervisor</span>

                                <p class="mt-2">{{ $movimiento->supervisor?->name }}</p>

                            </x-table.cell>

                        @endif

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Registrado</span>

                            {{ $movimiento->created_at }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Actualizado</span>

                            <p class="mt-2">

                                <span class="font-semibold">@if($movimiento->actualizadoPor != null)Actualizado por: {{$movimiento->actualizadoPor->name}} @else Actualizado: @endif</span> <br>

                                {{ $movimiento->updated_at }}

                            </p>

                        </x-table.cell>

                        @if(!auth()->user()->hasRole(['Administrador', 'Operador']))

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Acciones</span>

                                <div class="ml-3 relative" x-data="{ open_drop_down:false }">

                                    <div>

                                        <button x-on:click="open_drop_down=true" type="button" class="rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">

                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                            </svg>

                                        </button>

                                    </div>

                                    <div x-cloak x-show="open_drop_down" x-on:click="open_drop_down=false" x-on:click.away="open_drop_down=false" class="z-50 origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu">

                                        @if($movimiento->estado === 'no recibido' && !auth()->user()->hasRole(['Supervisor inscripciones', 'Supervisor uruapan']))

                                            <button
                                                wire:click="abrirModalRecibirDocumentacion({{  $movimiento->id }})"
                                                wire:loading.attr="disabled"
                                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                role="menuitem">
                                                Recibir documentación
                                            </button>

                                        @endif

                                        @can('Reasignar pase a folio')

                                            <button
                                                wire:click="abrirModalReasignarUsuario({{ $movimiento->id }})"
                                                wire:loading.attr="disabled"
                                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                role="menuitem">
                                                Reasignar
                                            </button>

                                        @endcan

                                        @can('Elaborar pase a folio')

                                            @if($movimiento->folioReal)

                                                @if($movimiento->estado !== 'no recibido'  && !in_array($movimiento->folioReal->estado, ['elaborado', 'pendiente']))

                                                    <a class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100" href="{{ route('elaboracion_folio_simplificado', $movimiento->id) }}">Elaborar</a>

                                                @endif

                                            @else

                                                @if($movimiento->estado !== 'no recibido')

                                                    <a class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100" href="{{ route('elaboracion_folio_simplificado', $movimiento->id) }}">Elaborar</a>

                                                @endif

                                            @endif

                                        @endcan

                                        @can('Corregir pase a folio')

                                            @if($movimiento->folioReal && in_array($movimiento->folioReal->estado, ['elaborado', 'pendiente', 'captura']))

                                                <button
                                                    wire:click="pasarCaptura({{ $movimiento->id }})"
                                                    wire:loading.attr="disabled"
                                                    class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                    role="menuitem">
                                                    Corregir
                                                </button>

                                            @endif

                                        @endcan

                                        @can('Imprimir pase a folio')

                                            @if($movimiento->folioReal && in_array($movimiento->folioReal->estado, ['elaborado', 'pendiente']))

                                                <button
                                                    wire:click="imprimir({{ $movimiento->id }})"
                                                    wire:loading.attr="disabled"
                                                    class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                    role="menuitem">
                                                    Imprimir folio real
                                                </button>

                                                <button
                                                    wire:click="imprimirInscripcionPropiedad({{ $movimiento->id }})"
                                                    wire:loading.attr="disabled"
                                                    class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                    role="menuitem">
                                                    Imprimir inscripción de propiedad
                                                </button>

                                            @endif

                                        @endcan

                                        @can('Rechazar pase a folio')

                                            <button
                                                wire:click="abrirModalRechazar({{ $movimiento->id }})"
                                                wire:loading.attr="disabled"
                                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                role="menuitem">
                                                Rechazar
                                            </button>

                                        @endcan

                                        @can('Finalizar pase a folio')

                                            @if($movimiento->folioReal && in_array($movimiento->folioReal->estado, ['elaborado', 'pendiente']))

                                                <button
                                                    wire:click="abrirModalFinalizar({{ $movimiento->id }})"
                                                    wire:loading.attr="disabled"
                                                    class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                    role="menuitem">
                                                    Finalizar
                                                </button>

                                            @endif

                                        @endcan

                                    </div>

                                </div>

                            </x-table.cell>

                        @endif

                    </x-table.row>

                @empty

                    <x-table.row>

                        <x-table.cell colspan="11">

                            <div class="bg-white text-gray-500 text-center p-5 rounded-full text-lg">

                                No hay resultados.

                            </div>

                        </x-table.cell>

                    </x-table.row>

                @endforelse

            </x-slot>

            <x-slot name="tfoot">

                <x-table.row>

                    <x-table.cell colspan="11" class="bg-gray-50">

                        {{ $movimientos->links()}}

                    </x-table.cell>

                </x-table.row>

            </x-slot>

        </x-table>

    </div>

    @include('livewire.comun.inscripciones.modal-rechazar')

    @include('livewire.comun.inscripciones.modal-recibir-documento')

    @include('livewire.comun.inscripciones.modal-reasignarme-movimiento-registral')

    @include('livewire.comun.inscripciones.modal-reasignar-usuario')

    @include('livewire.comun.inscripciones.modal-finalizar')

</div>
