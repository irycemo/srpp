<div class="">

    <div class="mb-6">

        <x-header>Copias Certificadas</x-header>

        <div class="flex justify-between">

            <div class="flex">

                <select class="bg-white rounded-l text-sm border border-r-transparent  focus:ring-0 @error('año') border-red-500 @enderror " wire:model="año">
                    @foreach ($años as $año)

                        <option value="{{ $año }}">{{ $año }}</option>

                    @endforeach
                </select>

                <input type="number" placeholder="# Control" min="1" class="bg-white w-24 text-sm focus:ring-0 @error('tramite') border-red-500 @enderror " wire:model="tramite">

                <input type="number" placeholder="Usuario" min="1" class="bg-white text-sm w-20 focus:ring-0 border-l-0 @error('usuario') border-red-500 @enderror" wire:model="usuario">

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

    @if($copiaConsultada)

        <div class="overflow-x-auto rounded-lg shadow-xl border-t-2 border-t-gray-500">

            <x-table>

                <x-slot name="head">
                    <x-table.heading># Control</x-table.heading>
                    <x-table.heading>Estado</x-table.heading>
                    <x-table.heading>Tipo de servicio</x-table.heading>
                    <x-table.heading>Tomo / Bis</x-table.heading>
                    <x-table.heading>Registro / Bis</x-table.heading>
                    <x-table.heading>Distrito</x-table.heading>
                    <x-table.heading>Sección</x-table.heading>
                    <x-table.heading>Solicitante</x-table.heading>
                    <x-table.heading>Número de páginas</x-table.heading>
                    @if (auth()->user()->hasRole(['Supervisor certificaciones', 'Administrador', 'Supervisor uruapan', 'Jefe de departamento certificaciones', 'Operador']))
                        <x-table.heading>Folio de carpeta</x-table.heading>
                        <x-table.heading>Asignado a</x-table.heading>
                    @endif
                    @if (auth()->user()->hasRole(['Administrador', 'Operador', 'Director', 'Jefe de departamento jurídico']))
                        <x-table.heading >Reimpreso en</x-table.heading>
                    @endif
                    <x-table.heading>Fecha de entrega</x-table.heading>
                    <x-table.heading>Ingreso</x-table.heading>
                    <x-table.heading>Actualizado</x-table.heading>
                    @if (!auth()->user()->hasRole(['Administrador', 'Operador', 'Director', 'Jefe de departamento jurídico']))
                        <x-table.heading>Acciones</x-table.heading>
                    @endif

                </x-slot>

                <x-slot name="body">

                    <x-table.row wire:loading.class.delaylongest="opacity-50" wire:key="row-{{ $copiaConsultada->id }}">

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl"># Control</span>

                            <div class="text-center">

                                <span class="whitespace-nowrap">{{ $copiaConsultada->año }}-{{ $copiaConsultada->tramite }}-{{ $copiaConsultada->usuario }}</span>

                                @if(array_key_exists($copiaConsultada->usuario, $usuarios_regionales))

                                    <span class="text-xs rounded-full px-1 bg-rojo">Regional {{ $usuarios_regionales[$copiaConsultada->usuario] }}</span>

                                @endif

                            </div>

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Estado</span>

                            <span class="bg-{{ $copiaConsultada->estado_color }} py-1 px-2 rounded-full text-white text-xs">{{ ucfirst($copiaConsultada->estado) }}</span>

                        </x-table.cell>
                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Tipo de servicio</span>

                            {{ $copiaConsultada->tipo_servicio }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Tomo / Bis</span>

                            {{ $copiaConsultada->tomo }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Registro / Bis</span>

                            {{ $copiaConsultada->registro }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Distrito</span>

                            {{ $copiaConsultada->distrito }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Sección</span>

                            {{ $copiaConsultada->seccion }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Solicitante</span>

                            {{ $copiaConsultada->solicitante }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Número de páginas</span>

                            {{ $copiaConsultada->certificacion->numero_paginas }}

                        </x-table.cell>

                        @if (auth()->user()->hasRole(['Supervisor certificaciones', 'Administrador', 'Supervisor uruapan', 'Jefe de departamento certificaciones', 'Operador']))

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Folio de carpeta</span>

                                {{ $copiaConsultada->certificacion->folio_carpeta_copias ?? 'N/A' }}

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Folio de carpeta</span>

                                {{ $copiaConsultada->asignadoA->name ?? 'N/A' }}

                            </x-table.cell>

                        @endif

                        @if (auth()->user()->hasRole(['Administrador', 'Operador', 'Director', 'Jefe de departamento jurídico']))

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Reimpreso en</span>

                                {{ optional($copiaConsultada->certificacion->reimpreso_en)->format('d-m-Y H:i:s') ?? 'N/A' }}

                            </x-table.cell>

                        @endif

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Fecha de entrega</span>

                            {{ optional($copiaConsultada->fecha_entrega)->format('d-m-Y') ?? 'N/A' }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Ingreso</span>

                            {{ $copiaConsultada->created_at }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="font-semibold">@if($copiaConsultada->actualizadoPor != null)Actualizado por: {{$copiaConsultada->actualizadoPor->name}} @else Actualizado: @endif</span> <br>

                            {{ $copiaConsultada->updated_at }}

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

                                        @can('Reimprimir documento')

                                            @if ($copiaConsultada->certificacion->reimpreso_en == null && $copiaConsultada->certificacion->folio_carpeta_copias != null)

                                                <button
                                                    wire:click="reimprimir({{ $copiaConsultada->certificacion->id }})"
                                                    wire:loading.attr="disabled"
                                                    class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                    role="menuitem">
                                                    Reimprimir
                                                </button>

                                            @endif

                                        @endcan

                                        @can('Finalizar copias certificadas')

                                            @if(!$copiaConsultada->certificacion->folio_carpeta_copias)

                                                @if(auth()->user()->hasRole(['Certificador Juridico', 'Certificador Oficialia']))

                                                    <button
                                                        wire:click="generarCertificacion({{ $copiaConsultada->certificacion->id }})"
                                                        wire:loading.attr="disabled"
                                                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                        role="menuitem">

                                                        <span>Generar certificación</span>

                                                    </button>

                                                    @if($copiaConsultada->certificacion->movimiento_registral)

                                                        @include('livewire.certificaciones.botones-movimiento')

                                                    @elseif(!$copiaConsultada->certificacion->movimiento_registral && $copiaConsultada->certificacion->folio_real)

                                                        @include('livewire.certificaciones.botones-folio-real')

                                                    @endif

                                                @else

                                                    <button
                                                        wire:click="abrirModalFolio({{ $copiaConsultada->certificacion->id }})"
                                                        wire:loading.attr="disabled"
                                                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                        role="menuitem">

                                                        <span>Asignar folio de carpeta</span>

                                                    </button>

                                                @endif

                                            @elseif($copiaConsultada->certificacion->movimiento_registral)

                                                @include('livewire.certificaciones.botones-movimiento')

                                            @elseif(!$copiaConsultada->certificacion->movimiento_registral && $copiaConsultada->certificacion->folio_real)

                                                @include('livewire.certificaciones.botones-folio-real')

                                            @endif

                                            @if(auth()->user()->hasRole(['Supervisor certificaciones', 'Certificador Oficialia', 'Certificador Juridico', 'Jefe de departamento certificaciones']))

                                                @if($copiaConsultada->certificacion->folio_carpeta_copias)

                                                    <button
                                                        wire:click="concluir({{ $copiaConsultada->certificacion->id }})"
                                                        wire:loading.attr="disabled"
                                                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                        role="menuitem">

                                                        <span>Concluir</span>

                                                    </button>

                                                @endif

                                            @else

                                                <button
                                                    wire:click="abrirModalRechazar({{ $copiaConsultada->certificacion->id }})"
                                                    wire:loading.attr="disabled"
                                                    class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                    role="menuitem">

                                                    <span>Rechazar</span>

                                                </button>

                                            @endif

                                        @endcan

                                    </div>

                                </div>

                            </x-table.cell>

                        @endif

                    </x-table.row>

                </x-slot>

                <x-slot name="tfoot">

                    <x-table.row>

                        <x-table.cell colspan="20" class="bg-gray-50">

                        </x-table.cell>

                    </x-table.row>

                </x-slot>

            </x-table>

        </div>

    @endif

    <x-dialog-modal wire:model="modal" maxWidth="sm">

        <x-slot name="title">

            Finalizar

        </x-slot>

        <x-slot name="content">

            <div class="flex flex-col md:flex-row justify-between md:space-x-3 mb-5">

                <div class="flex-auto ">

                    <div>

                        <Label>Folio de carpeta</Label>
                    </div>

                    <div>

                        <input type="number" class="bg-white rounded text-sm w-full" wire:model="modelo_editar.folio_carpeta_copias">

                    </div>

                    <div>

                        @error('modelo_editar.folio_carpeta_copias') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                    </div>

                </div>

            </div>

        </x-slot>

        <x-slot name="footer">

            <div class="flex items-center justify-end space-x-3">

                <x-button-blue
                    wire:click="asignarFolio"
                    wire:loading.attr="disabled"
                    wire:target="asignarFolio">

                    <img wire:loading wire:target="asignarFolio" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <span>Asignar folio</span>
                </x-button-blue>

                <x-button-red
                    wire:click="toggle('modal')"
                    wire:loading.attr="disabled"
                    wire:target="toggle('modal')"
                    type="button">
                    <span>Cerrar</span>
                </x-button-red>

            </div>

        </x-slot>

    </x-dialog-modal>

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

@push('scripts')

    <script>

        window.addEventListener('imprimir_documento', event => {

            const documento = event.detail[0].documento;

            var url = "{{ route('copia_certificada', '')}}" + "/" + documento;

            window.open(url, '_blank');

            window.location.href = "{{ route('copias_certificadas')}}";

        });

        window.addEventListener('imprimir_documento_oficialia', event => {

            const documento = event.detail[0].documento;

            var url = "{{ route('copia_certificada', '')}}" + "/" + documento;

            window.open(url, '_blank');

            window.location.href = "{{ route('copias_certificadas')}}";

        });

    </script>

@endpush
