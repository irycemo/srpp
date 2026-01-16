<div>

    <div class="mb-6">

        <x-header>Folio real de persona moral</x-header>

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

            @can('Asignación')

                <a href="{{ route('asignacion') }}" class="bg-gray-500 hover:shadow-lg hover:bg-gray-700 text-sm py-2 px-4 text-white rounded-full hidden md:block items-center justify-center focus:outline-gray-400 focus:outline-offset-2">

                    <img wire:loading wire:target="abrirModalCrear" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                    Asignar nuevo folio

                </a>

                <a href="{{ route('asignacion') }}" class="bg-gray-500 hover:shadow-lg hover:bg-gray-700 float-right text-sm py-2 px-4 text-white rounded-full md:hidden focus:outline-gray-400 focus:outline-offset-2">+</a>

            @endcan

        </div>

    </div>

    <div class="overflow-x-auto rounded-lg shadow-xl border-t-2 border-t-gray-500">

        <x-table>

            <x-slot name="head">

                <x-table.heading sortable wire:click="sortBy('folio_real')" :direction="$sort === 'folio_real' ? $direction : null" >Mov. Reg.</x-table.heading>
                <x-table.heading ># control</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('estado')" :direction="$sort === 'estado' ? $direction : null">Estado</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('tipo_servicio')" :direction="$sort === 'tipo_servicio' ? $direction : null" >Tipo de servicio</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('distrito')" :direction="$sort === 'distrito' ? $direction : null" >Distrito</x-table.heading>
                @if(auth()->user()->hasRole(['Supervisor varios', 'Supervisor uruapan', 'Administrador', 'Jefe de departamento', 'Operador']))
                    <x-table.heading sortable wire:click="sortBy('usuario_asignado')" :direction="$sort === 'usuario_asignado' ? $direction : null">Usuario asignado</x-table.heading>
                @endif
                <x-table.heading sortable wire:click="sortBy('fecha_entrega')" :direction="$sort === 'fecha_entrega' ? $direction : null" >Fecha de entrega</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('created_at')" :direction="$sort === 'created_at' ? $direction : null">Ingreso</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('updated_at')" :direction="$sort === 'updated_at' ? $direction : null">Actualizado</x-table.heading>
                @if (!auth()->user()->hasRole(['Administrador', 'Operador']))
                    <x-table.heading >Acciones</x-table.heading>
                @endif

            </x-slot>

            <x-slot name="body">

                @forelse ($movimientos as $movimiento)

                    <x-table.row wire:loading.class.delaylongest="opacity-50" wire:key="row-{{ $movimiento->id }}">

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Mov. Reg.</span>

                            <span class="whitespace-nowrap">{{ $movimiento->folioRealPersona?->folio }}-{{ $movimiento->folio }}</span>

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Número de control</span>

                            <div class="text-center flex flex-col">

                                <span class="whitespace-nowrap">{{ $movimiento->año }}-{{ $movimiento->tramite }}-{{ $movimiento->usuario }}</span>

                                @if(array_key_exists($movimiento->usuario, $usuarios_regionales))

                                    <span class="text-xs">Regional {{ $usuarios_regionales[$movimiento->usuario] }}</span>

                                @endif

                            </div>

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Estado</span>

                            @if($movimiento->folioRealPersona)

                                <span class="bg-{{ $movimiento->folioRealPersona?->estado_color }} py-1 px-2 rounded-full text-white text-xs">{{ ucfirst($movimiento->folioRealPersona?->estado) }}</span>

                            @else

                                <span class="bg-{{ $movimiento->estado_color }} py-1 px-2 rounded-full text-white text-xs">{{ ucfirst($movimiento->estado) }}</span>

                            @endif

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Tipo de servicio</span>

                            {{ $movimiento->tipo_servicio }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Distrito</span>

                            {{ $movimiento->distrito }}

                        </x-table.cell>

                        @if(auth()->user()->hasRole(['Supervisor varios', 'Supervisor uruapan', 'Administrador', 'Jefe de departamento', 'Operador']))

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Usuario asignado</span>

                                <p class="mt-2">{{ $movimiento->asignadoA->name ?? 'N/A' }}</p>

                            </x-table.cell>

                        @endif

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Fecha de entrega</span>

                            {{ $movimiento->fecha_entrega->format('d-m-Y') }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Ingreso</span>

                            {{ $movimiento->created_at }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Actualizado</span>

                            <p class="mt-2">

                                <span class="font-semibold">@if($movimiento->actualizadoPor != null)Actualizado por: {{$movimiento->actualizadoPor->name}} @else Actualizado: @endif</span> <br>

                                {{ $movimiento->updated_at }}

                            </p>

                        </x-table.cell>

                        @if (!auth()->user()->hasRole(['Administrador', 'Operador']))

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

                                        @if($movimiento->estado === 'no recibido' && !auth()->user()->hasRole(['Supervisor inscripciones']))

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
                                                wire:click="abrirModalReasignar({{ $movimiento->id }})"
                                                wire:loading.attr="disabled"
                                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                role="menuitem">
                                                Reasignar
                                            </button>

                                        @endcan

                                        @can('Elaborar pase a folio')

                                            @if($movimiento->folioRealPersona)

                                                @if($movimiento->estado !== 'no recibido'  && $movimiento->folioRealPersona->estado != 'pendiente')

                                                    <a class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100" href="{{ route('elaboracion_folio', $movimiento->id) }}">Elaborar</a>

                                                @endif

                                            @else

                                                @if($movimiento->estado !== 'no recibido')

                                                    <a class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100" href="{{ route('elaboracion_folio', $movimiento->id) }}">Elaborar</a>

                                                @endif

                                            @endif

                                        @endcan

                                        @can('Corregir pase a folio')

                                            @if($movimiento->folioRealPersona && in_array($movimiento->folioRealPersona->estado, ['elaborado', 'pendiente', 'captura']))

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

                                            @if($movimiento->folioRealPersona && in_array($movimiento->folioRealPersona->estado, ['elaborado', 'pendiente']))

                                                <button
                                                    wire:click="imprimir({{ $movimiento->id }})"
                                                    wire:loading.attr="disabled"
                                                    class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                    role="menuitem">
                                                    Imprimir
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

                                    </div>

                                </div>

                            </x-table.cell>

                        @endif

                    </x-table.row>

                @empty

                    <x-table.row>

                        <x-table.cell colspan="12">

                            <div class="bg-white text-gray-500 text-center p-5 rounded-full text-lg">

                                No hay resultados.

                            </div>

                        </x-table.cell>

                    </x-table.row>

                @endforelse

            </x-slot>

            <x-slot name="tfoot">

                <x-table.row>

                    <x-table.cell colspan="12" class="bg-gray-50">

                        {{ $movimientos->links()}}

                    </x-table.cell>

                </x-table.row>

            </x-slot>

        </x-table>

    </div>

    @include('livewire.comun.inscripciones.modal-rechazar')

    @include('livewire.comun.inscripciones.modal-recibir-documento')

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

                <x-button-blue
                    wire:click="reasignarUsuarioAleatoriamente"
                    wire:loading.attr="disabled"
                    wire:target="reasignarUsuarioAleatoriamente">

                    <img wire:loading wire:target="reasignarUsuarioAleatoriamente" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <span>Aleatorio</span>
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

</div>

<script>

    window.addEventListener('imprimir_documento', event => {

        const documento = event.detail[0].caratula;

        var url = "{{ route('varios.inscripcion.acto', '')}}" + "/" + documento;

        window.open(url, '_blank');

    });

</script>
