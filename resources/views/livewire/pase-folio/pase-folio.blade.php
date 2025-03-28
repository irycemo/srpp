<div class="">

    <div class="mb-6">

        <x-header>Asignación de folio</x-header>

        <div class="flex justify-between">

            <div class="flex gap-3">

                <input type="text" wire:model.live.debounce.500ms="search" placeholder="Buscar" class="bg-white rounded-full text-sm">

                <x-input-select class="bg-white rounded-full text-sm w-min" wire:model.live="pagination">

                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>

                </x-input-select>

            </div>

            <button wire:click="abrirModalNuevoFolio" class="bg-gray-500 hover:shadow-lg hover:bg-gray-700 text-sm py-2 px-4 text-white rounded-full hidden md:block items-center justify-center focus:outline-gray-400 focus:outline-offset-2">

                <img wire:loading wire:target="abrirModalNuevoFolio" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                Agregar nuevo folio

            </button>

            <button wire:click="abrirModalNuevoFolio" class="bg-gray-500 hover:shadow-lg hover:bg-gray-700 float-right text-sm py-2 px-4 text-white rounded-full md:hidden focus:outline-gray-400 focus:outline-offset-2">+</button>

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

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Folio real</span>

                            {{ $movimiento->folioReal->folio ?? 'N/A' }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Número de control</span>

                            <span class="whitespace-nowrap">{{ $movimiento->año }}-{{ $movimiento->tramite }}-{{ $movimiento->usuario }}</span>

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

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Número de propiedad</span>

                            {{ $movimiento->numero_propiedad ?? 'N/A' }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Distrito</span>

                            {{ $movimiento->distrito }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Estado</span>

                            @if($movimiento->folio_real)

                                <span class="bg-{{ $movimiento->folioReal?->estado_color }} py-1 px-2 rounded-full text-white text-xs">{{ ucfirst($movimiento->folioReal?->estado) }}</span>

                            @else

                                <span class="bg-{{ $movimiento->estado_color }} py-1 px-2 rounded-full text-white text-xs">{{ ucfirst($movimiento->estado) }}</span>

                            @endif

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Usuario asignado</span>

                            {{ $movimiento->asignadoA?->name }}

                        </x-table.cell>

                        @if(auth()->user()->hasRole(['Administrador' , 'Operador']))

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Supervisor</span>

                                {{ $movimiento->supervisor?->name }}

                            </x-table.cell>

                        @endif

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Registrado</span>

                            {{ $movimiento->created_at }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="font-semibold">@if($movimiento->actualizadoPor != null)Actualizado por: {{$movimiento->actualizadoPor->name}} @else Actualizado: @endif</span> <br>

                            {{ $movimiento->updated_at }}

                        </x-table.cell>

                        @if(!auth()->user()->hasRole(['Administrador', 'Operador']))

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

                                        @if($movimiento->folioReal)

                                            @if($movimiento->folioReal->estado == 'elaborado' )

                                                <button
                                                    wire:click="pasarCaptura({{ $movimiento->id }})"
                                                    wire:loading.attr="disabled"
                                                    class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                    role="menuitem">
                                                    Corregir
                                                </button>

                                                <button
                                                    wire:click="abrirModalFinalizar({{ $movimiento->id }})"
                                                    wire:loading.attr="disabled"
                                                    class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                    role="menuitem">
                                                    Finalizar
                                                </button>

                                                <button
                                                    wire:click="imprimir({{ $movimiento->id }})"
                                                    wire:loading.attr="disabled"
                                                    class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                    role="menuitem">
                                                    Imprimir
                                                </button>

                                            @elseif(!$supervisor)

                                                <a class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100" href="{{ route('elaboracion_folio', $movimiento->id) }}">Elaborar</a>

                                            @endif

                                        @elseif(!$supervisor)

                                            <a class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100" href="{{ route('elaboracion_folio', $movimiento->id) }}">Elaborar</a>

                                            <button
                                                wire:click="abrirModalRechazar({{ $movimiento->id }})"
                                                wire:loading.attr="disabled"
                                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                role="menuitem">
                                                Rechazar
                                            </button>

                                        @endif

                                        @if(auth()->user()->hasRole(['Jefe de departamento certificaciones', 'Jefe de departamento inscripciones']) || $supervisor)

                                            <button
                                                wire:click="reasignarAleatoriamente({{ $movimiento->id }})"
                                                wire:loading.attr="disabled"
                                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                role="menuitem">
                                                Reasignar
                                            </button>

                                            @if($movimiento->folioReal)

                                                @if($movimiento->folioReal->estado == 'pendiente' )

                                                    <button
                                                        wire:click="imprimir({{ $movimiento->id }})"
                                                        wire:loading.attr="disabled"
                                                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                        role="menuitem">
                                                        Imprimir
                                                    </button>

                                                    <button
                                                        wire:click="abrirModalFinalizar({{ $movimiento->id }})"
                                                        wire:loading.attr="disabled"
                                                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                        role="menuitem">
                                                        Finalizar
                                                    </button>

                                                @endif

                                            @endif

                                        @endif

                                        @if(!$movimiento->folio_real)

                                            <button
                                                wire:click="abrirModalRechazar({{ $movimiento->id }})"
                                                wire:loading.attr="disabled"
                                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                role="menuitem">
                                                Rechazar
                                            </button>

                                        @endif

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

    <x-dialog-modal wire:model="modal" maxWidth="sm">

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
                    wire:click="$toggle('modal')"
                    wire:loading.attr="disabled"
                    wire:target="$toggle('modal')"
                    type="button">
                    <span>Cerrar</span>
                </x-button-red>

            </div>

        </x-slot>

    </x-dialog-modal>

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

    <x-dialog-modal wire:model="modalNuevoFolio" maxWidth="sm">

        <x-slot name="title">

            Ingresar antecedente

        </x-slot>

        <x-slot name="content">

            <div class="flex flex-col md:flex-row justify-between gap-3 mb-3">

                <x-input-group for="tomo" label="Tomo" :error="$errors->first('tomo')" class="w-full">

                    <x-input-text type="number" id="tomo" wire:model="tomo" />

                </x-input-group>

                <x-input-group for="registro" label="Regsitro" :error="$errors->first('registro')" class="w-full">

                    <x-input-text type="number" id="registro" wire:model="registro" />

                </x-input-group>

            </div>

            <div class="flex flex-col md:flex-row justify-between gap-3 mb-3">

                <x-input-group for="numero_propiedad" label="Número de propiedad" :error="$errors->first('numero_propiedad')" class="w-full">

                    <x-input-text type="number" id="numero_propiedad" wire:model="numero_propiedad" />

                </x-input-group>

                <x-input-group for="distrito" label="Distrito" :error="$errors->first('distrito')" class="w-full">

                    <x-input-select id="distrito" wire:model="distrito" class="w-full">

                        <option value="">Seleccione una opción</option>

                        @foreach ($distritos as $key => $distrito)
                            <option value="{{ $key }}">{{ $distrito }}</option>
                        @endforeach

                    </x-input-select>

                </x-input-group>

            </div>


        </x-slot>

        <x-slot name="footer">

            <div class="flex items-center justify-end space-x-3">

                <x-button-blue
                    wire:click="buscarAntecedente"
                    wire:loading.attr="disabled"
                    wire:target="buscarAntecedente">

                    <img wire:loading wire:target="buscarAntecedente" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <span>Crear nuevo folio</span>
                </x-button-blue>

                <x-button-red
                    wire:click="$toggle('modalNuevoFolio')"
                    wire:loading.attr="disabled"
                    wire:target="$toggle('modalNuevoFolio')"
                    type="button">
                    <span>Cerrar</span>
                </x-button-red>

            </div>

        </x-slot>

    </x-dialog-modal>

    <x-confirmation-modal wire:model="modalFinalizar" maxWidth="sm">

        <x-slot name="title">
            Finalizar
        </x-slot>

        <x-slot name="content">
            ¿Esta seguro que desea finalizar el folio real?
        </x-slot>

        <x-slot name="footer">

            <x-secondary-button
                wire:click="$toggle('modalFinalizar')"
                wire:loading.attr="disabled"
            >
                No
            </x-secondary-button>

            <x-danger-button
                class="ml-2"
                wire:click="finalizar"
                wire:loading.attr="disabled"
                wire:target="finalizar"
            >
                Finalizar
            </x-danger-button>

        </x-slot>

    </x-confirmation-modal>

</div>
