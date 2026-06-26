<div class="">

    <div class="mb-6">

        <x-header>Consultas Inscripciones</x-header>

        <div class="flex justify-between">

            <div class="flex">

                <select class="bg-white rounded-l text-sm border border-r-transparent  focus:ring-0 @error('año') border-red-500 @enderror " wire:model="año">
                    @foreach ($años as $año)

                        <option value="{{ $año }}">{{ $año }}</option>

                    @endforeach
                </select>

                <input type="number" placeholder="# Control" min="1" class="bg-white w-24 text-sm focus:ring-0 @error('tramite') border-red-500 @enderror " wire:model="tramite">

                <input type="number" placeholder="Usuario" min="1" class="bg-white text-sm w-20 focus:ring-0 border-l-0 @error('tramite_usuario') border-red-500 @enderror" wire:model="tramite_usuario">

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

    <div class="rounded-lg shadow-xl border-t-2 border-t-gray-500 mb-5">

        @if($movimientoRegistral != null)

            <x-table>

                <x-slot name="head">

                    <x-table.heading ># Control</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('folio')" :direction="$sort === 'folio' ? $direction : null" >M.R.</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('estado')" :direction="$sort === 'estado' ? $direction : null" >estado</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('tomo')" :direction="$sort === 'tomo' ? $direction : null" >Tomo</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('registro')" :direction="$sort === 'registro' ? $direction : null" >Registro</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('numero_propiedad')" :direction="$sort === 'numero_propiedad' ? $direction : null" ># Propiedad</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('distrito')" :direction="$sort === 'distrito' ? $direction : null" >Distrito</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('servicio_nombre')" :direction="$sort === 'servicio_nombre' ? $direction : null" >Servicio</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('usuario_asignado')" :direction="$sort === 'usuario_asignado' ? $direction : null" >Asignado a</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('fecha_entrega')" :direction="$sort === 'fecha_entrega' ? $direction : null" >Fecha de entrega</x-table.heading>
                    @if(auth()->user()->hasRole('Administrador') || auth()->user()->ubicacion === 'Regional 4')
                        <x-table.heading >Acciones</x-table.heading>
                    @endif

                </x-slot>

                <x-slot name="body">

                    <x-table.row wire:loading.class.delaylongest="opacity-50" wire:key="row-{{$movimientoRegistral->id }}">

                        <x-table.cell title="# Control">

                            <span class="whitespace-nowrap">{{ $movimientoRegistral->año }}-{{ $movimientoRegistral->tramite }}-{{ $movimientoRegistral->usuario }}</span>

                        </x-table.cell>

                        <x-table.cell title="M.R.">

                            <span class="whitespace-nowrap flex items-center justify-center">

                                @if($movimientoRegistral->folioReal?->estado == 'pendiente')

                                    <span class="bg-pink-400 px-1 rounded-full text-white text-xs mr-2">P</span>

                                @elseif($movimientoRegistral->folioReal?->estado == 'captura')

                                    <span class="bg-yellow-400 px-1 rounded-full text-white text-xs mr-2">C</span>

                                @elseif($movimientoRegistral->folioReal?->estado == 'elaborado')

                                    <span class="bg-green-400 px-1 rounded-full text-white text-xs mr-2">E</span>

                                @endif

                                @if($movimientoRegistral->folioReal?->matriz) <span class="bg-pink-400 px-1 rounded-full text-white text-xs mr-2">M</span> @endif{{ $movimientoRegistral->folioReal?->folio }}-{{ $movimientoRegistral->folio }}

                            </span>

                        </x-table.cell>

                        <x-table.cell title="Estado">

                            <span class="bg-{{ $movimientoRegistral->estado_color }} py-1 px-2 rounded-full text-white text-xs whitespace-nowrap">{{ ucfirst($movimientoRegistral->estado) }}</span>

                        </x-table.cell>

                        <x-table.cell title="Tomo">

                            {{ $movimientoRegistral->tomo ?? 'N/A' }}

                        </x-table.cell>

                        <x-table.cell title="Registro">

                            {{ $movimientoRegistral->registro ?? 'N/A' }}

                        </x-table.cell>

                        <x-table.cell title="# Propiedad">

                            {{ $movimientoRegistral->numero_propiedad ?? 'N/A' }}

                        </x-table.cell>

                        <x-table.cell title="Distrito">

                            {{ $movimientoRegistral->distrito }}

                        </x-table.cell>

                        <x-table.cell title="Servicio">

                            {{ $movimientoRegistral->servicio_nombre ?? 'N/A' }}

                        </x-table.cell>

                        <x-table.cell title="Asignado a">

                            {{ $movimientoRegistral->asignadoA?->name }}

                        </x-table.cell>

                        <x-table.cell title="Fecha de entrega">

                            {{$movimientoRegistral->fecha_entrega }}

                        </x-table.cell>

                        @if(auth()->user()->hasRole('Administrador') || auth()->user()->ubicacion === 'Regional 4')

                            <x-table.cell title="Acciones">

                                <div class="ml-3 relative" x-data="{ open_drop_down:false }">

                                    <div>

                                        <button x-on:click="open_drop_down=true" type="button" class="rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">

                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                            </svg>

                                        </button>

                                    </div>

                                    <div x-cloak x-show="open_drop_down" x-on:click="open_drop_down=false" x-on:click.away="open_drop_down=false" class="z-50 origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu">

                                        @if(auth()->user()->hasRole('Administrador'))

                                            <button
                                                wire:click="$set('modal2', '!modal2')"
                                                wire:loading.attr="disabled"
                                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                role="menuitem">
                                                Reasignar
                                            </button>

                                            @if($movimientoRegistral->estado == 'nuevo')

                                                <button
                                                    wire:click="$set('modalRechazar', '!modalRechazar')"
                                                    wire:loading.attr="disabled"
                                                    class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                    role="menuitem">
                                                    Rechazar
                                                </button>

                                            @endif

                                            <a
                                                href="{{ route('auditoria') . "?modelo=". $modelo . "&modelo_id=" .  $modeloId }}"
                                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                role="menuitem">
                                                Auditar
                                            </a>

                                        @endif

                                        <button
                                            wire:click="abrirModalCambiarAntecedente({{ $movimientoRegistral->id }})"
                                            wire:loading.attr="disabled"
                                            class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                            role="menuitem">
                                            Cambiar antecedente
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

    @if(count($movimientos))

        <div class="rounded-lg shadow-xl border-t-2 border-t-gray-500">

            <x-table>

                <x-slot name="head">

                    <x-table.heading ># Control</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('folio')" :direction="$sort === 'folio' ? $direction : null" >M.R.</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('estado')" :direction="$sort === 'estado' ? $direction : null" >estado</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('tomo')" :direction="$sort === 'tomo' ? $direction : null" >Tomo</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('registro')" :direction="$sort === 'registro' ? $direction : null" >Registro</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('numero_propiedad')" :direction="$sort === 'numero_propiedad' ? $direction : null" ># Propiedad</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('distrito')" :direction="$sort === 'distrito' ? $direction : null" >Distrito</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('servicio_nombre')" :direction="$sort === 'servicio_nombre' ? $direction : null" >Servicio</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('usuario_asignado')" :direction="$sort === 'usuario_asignado' ? $direction : null" >Asignado a</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('fecha_entrega')" :direction="$sort === 'fecha_entrega' ? $direction : null" >Fecha de entrega</x-table.heading>
                    @if(auth()->user()->hasRole('Administrador'))
                        <x-table.heading >Acciones</x-table.heading>
                    @endif

                </x-slot>

                <x-slot name="body">

                    @foreach ($movimientos as $movimiento)

                        <x-table.row wire:loading.class.delaylongest="opacity-50" wire:key="row-{{$movimiento->id }}">

                            <x-table.cell title="# Control">

                                <span class="whitespace-nowrap">{{ $movimiento->año }}-{{ $movimiento->tramite }}-{{ $movimiento->usuario }}</span>

                            </x-table.cell>

                            <x-table.cell title="M.R.">

                                <span class="whitespace-nowrap flex items-center justify-center">

                                    @if($movimiento->folioReal?->estado == 'pendiente')

                                        <span class="bg-pink-400 px-1 rounded-full text-white text-xs mr-2">P</span>

                                    @elseif($movimiento->folioReal?->estado == 'captura')

                                        <span class="bg-yellow-400 px-1 rounded-full text-white text-xs mr-2">C</span>

                                    @elseif($movimiento->folioReal?->estado == 'elaborado')

                                        <span class="bg-green-400 px-1 rounded-full text-white text-xs mr-2">E</span>

                                    @endif

                                    @if($movimiento->folioReal?->matriz) <span class="bg-pink-400 px-1 rounded-full text-white text-xs mr-2">M</span> @endif{{ $movimiento->folioReal?->folio }}-{{ $movimiento->folio }}

                                </span>

                            </x-table.cell>

                            <x-table.cell title="Estado">

                                <span class="bg-{{ $movimiento->estado_color }} py-1 px-2 rounded-full text-white text-xs whitespace-nowrap">{{ ucfirst($movimiento->estado) }}</span>

                            </x-table.cell>

                            <x-table.cell title="Tomo">

                                {{ $movimiento->tomo ?? 'N/A' }}

                            </x-table.cell>

                            <x-table.cell title="Registro">

                                {{ $movimiento->registro ?? 'N/A' }}

                            </x-table.cell>

                            <x-table.cell title="# Propiedad">

                                {{ $movimiento->numero_propiedad ?? 'N/A' }}

                            </x-table.cell>

                            <x-table.cell title="Distrito">

                                {{ $movimiento->distrito }}

                            </x-table.cell>

                            <x-table.cell title="Servicio">

                                {{ $movimiento->servicio_nombre ?? 'N/A' }}

                            </x-table.cell>

                            <x-table.cell title="Asignado a">

                                {{ $movimiento->asignadoA?->name }}

                            </x-table.cell>

                            <x-table.cell title="Fecha de entrega">

                                {{$movimiento->fecha_entrega }}

                            </x-table.cell>

                            @if(auth()->user()->hasRole('Administrador'))

                                <x-table.cell title="Acciones">

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
                                                wire:click="$set('modal2', '!modal2')"
                                                wire:loading.attr="disabled"
                                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                role="menuitem">
                                                Reasignar
                                            </button>

                                            @if($movimiento->estado == 'nuevo')

                                                <button
                                                    wire:click="$set('modalRechazar', '!modalRechazar')"
                                                    wire:loading.attr="disabled"
                                                    class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                    role="menuitem">
                                                    Rechazar
                                                </button>

                                            @endif

                                            <a
                                                href="{{ route('auditoria') . "?modelo=". $modelo . "&modelo_id=" .  $modeloId }}"
                                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                role="menuitem">
                                                Auditar
                                            </a>

                                        </div>

                                    </div>

                                </x-table.cell>

                            @endif

                        </x-table.row>

                    @endforeach

                </x-slot>

                <x-slot name="tfoot">

                    <x-table.row>

                        <x-table.cell colspan="14" class="bg-gray-50">

                        </x-table.cell>

                    </x-table.row>

                </x-slot>

            </x-table>

        </div>

    @endif

    <x-dialog-modal wire:model="modalRechazar">

        <x-slot name="title">

            Rechazar

        </x-slot>

        <x-slot name="content">

            <div class="max-h-80 overflow-auto">
            @if(!$motivo)

                @foreach ($motivos as $key => $item)

                    <div
                        wire:click="seleccionarMotivo('{{ $key }}')"
                        wire:loading.attr="disabled"
                        class="border rounded-lg text-sm mb-2 p-2 hover:bg-gray-100 cursor-pointer">

                        <p>{{ $item }}</p>

                    </div>

                @endforeach

            @else

                <div class="border rounded-lg text-sm mb-2 p-2 relative pr-16">

                    <span
                        wire:click="$set('motivo', null)"
                        wire:loading.attr="disabled"
                        class="rounded-full px-2 border hover:bg-gray-700 hover:text-white absolute top-1 right-1 cursor-pointer">
                        x
                    </span>

                    <p>{{ $motivo }}</p>

                </div>

            @endif
        </div>

            <x-input-group for="observaciones" label="Observaciones" :error="$errors->first('observaciones')" class="w-full">

                <textarea autofocus="false" class="bg-white rounded text-xs w-full " rows="4" wire:model="observaciones" placeholder="Se lo mas especifico posible acerca del motivo del rechazo."></textarea>

            </x-input-group>

        </x-slot>

        <x-slot name="footer">

            <div class="flex items-center justify-end space-x-3">

                <x-button-blue
                    wire:click="rechazar"
                    wire:loading.attr="disabled"
                    wire:target="rechazar">

                    <img wire:loading wire:target="rechazar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    Rechazar
                </x-button-blue>

                <x-button-red
                    wire:click="$set('modalRechazar',false)"
                    wire:loading.attr="disabled"
                    wire:target="$set('modalRechazar',false)">
                    Cerrar
                </x-button-red>

            </div>

        </x-slot>

    </x-dialog-modal>

    <x-dialog-modal wire:model="modal2" maxWidth="sm">

        <x-slot name="title">

            Reasignar

        </x-slot>

        <x-slot name="content">

            <div class="flex flex-col md:flex-row justify-between md:space-x-3 mb-5">

                <div class="flex-auto ">

                    <div>

                        <Label>Usuarios</Label>
                    </div>

                    <div>

                        <select class="bg-white rounded text-sm w-full" wire:model="usuario">

                            <option value="" selected>Seleccione una opción</option>

                            @foreach ($usuarios as $usuario)

                                <option value="{{ $usuario->id }}" selected>{{ $usuario->name }}</option>

                            @endforeach

                        </select>

                    </div>

                    <div>

                        @error('usuario') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                    </div>

                </div>

            </div>

        </x-slot>

        <x-slot name="footer">

            <div class="flex items-center justify-end space-x-3">

                <x-button-blue
                    wire:click="reasignar"
                    wire:loading.attr="disabled"
                    wire:target="reasignar">

                    <img wire:loading wire:target="reasignar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    Reasignar
                </x-button-blue>

                <x-button-red
                    wire:click="$set('modal2',false)"
                    wire:loading.attr="disabled"
                    wire:target="$set('modal2',false)">
                    Cerrar
                </x-button-red>

            </div>

        </x-slot>

    </x-dialog-modal>

    @include('livewire.comun.inscripciones.modal-cambiar-antecedente')

</div>
