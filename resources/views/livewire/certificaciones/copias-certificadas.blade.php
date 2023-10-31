<div class="">

    <div class="mb-6">

        <x-header>Copias Certificadas</x-header>

        <div class="flex justify-between">

            <div>

                <input type="text" wire:model.debounce.500ms="search" placeholder="Buscar" class="bg-white rounded-full text-sm">

                <select class="bg-white rounded-full text-sm" wire:model.live="pagination">

                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>

                </select>

            </div>

            @if(auth()->user()->hasRole(['Certificador', 'Certificador Oficialia', 'Certificador Juridico']))

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

                <x-table.heading sortable wire:click="sortBy('año')" :direction="$sort === 'año' ? $direction : null" >Año</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('tramite')" :direction="$sort === 'tramite' ? $direction : null" ># Control</x-table.heading>
                @if (auth()->user()->hasRole('Administrador'))
                    <x-table.heading sortable wire:click="sortBy('estado')" :direction="$sort === 'estado' ? $direction : null" >Estado</x-table.heading>
                @endif
                <x-table.heading sortable wire:click="sortBy('tipo_servicio')" :direction="$sort === 'tipo_servicio' ? $direction : null" >Tipo de servicio</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('solicitante')" :direction="$sort === 'solicitante' ? $direction : null" >Solicitante</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('tomo')" :direction="$sort === 'tomo' ? $direction : null" >Tomo / Bis</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('registro')" :direction="$sort === 'registro' ? $direction : null" >Registro / Bis</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('distrito')" :direction="$sort === 'distrito' ? $direction : null" >Distrito</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('seccion')" :direction="$sort === 'seccion' ? $direction : null" >Sección</x-table.heading>
                <x-table.heading >Número de páginas</x-table.heading>
                @if (!auth()->user()->hasRole('Certificador'))
                    <x-table.heading >Folio de carpeta</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('usuario_asignado')" :direction="$sort === 'usuario_asignado' ? $direction : null" >Asignado a</x-table.heading>
                @endif
                @if (!auth()->user()->hasRole(['Certificador', 'Supervisor Copias']))
                    <x-table.heading >Fecha de firma</x-table.heading>
                    <x-table.heading >Reimpreso en</x-table.heading>
                @endif
                <x-table.heading sortable wire:click="sortBy('fecha_entrega')" :direction="$sort === 'fecha_entrega' ? $direction : null">Fecha de entrega</x-table.heading>
                <x-table.heading >Observaciones</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('created_at')" :direction="$sort === 'created_at' ? $direction : null">Registro</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('updated_at')" :direction="$sort === 'updated_at' ? $direction : null">Actualizado</x-table.heading>
                @if (!auth()->user()->hasRole('Administrador'))
                    <x-table.heading >Acciones</x-table.heading>
                @endif

            </x-slot>

            <x-slot name="body">

                @forelse ($copias as $copia)

                    <x-table.row wire:loading.class.delaylongest="opacity-50" wire:key="row-{{ $copia->id }}">

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Año</span>

                            {{ $copia->año }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl"># Control</span>

                            {{ $copia->tramite }}

                        </x-table.cell>

                        @if (auth()->user()->hasRole('Administrador'))

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Estado</span>

                                <span class="bg-{{ $copia->estado_color }} py-1 px-2 rounded-full text-white text-xs">{{ ucfirst($copia->estado) }}</span>

                            </x-table.cell>

                        @endif

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Tipo de servicio</span>

                            {{ $copia->tipo_servicio }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Solicitante</span>

                            {{ $copia->solicitante }}

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

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Número de páginas</span>

                            {{ $copia->certificacion->numero_paginas }}

                        </x-table.cell>

                        @if (!auth()->user()->hasRole('Certificador'))

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Folio de carpeta</span>

                                {{ $copia->certificacion->folio_carpeta_copias ?? 'N/A' }}

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Folio de carpeta</span>

                                {{ $copia->asignadoA->name ?? 'N/A' }}

                            </x-table.cell>

                        @endif

                        @if (!auth()->user()->hasRole(['Certificador', 'Supervisor Copias']))

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Fecha de firma</span>

                                {{ optional($copia->certificacion->firma)->format('d-m-Y H:i:s') ?? 'N/A' }}

                            </x-table.cell>

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

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Observaciones</span>

                            {{ $copia->certificacion->observaciones ?? 'N/A' }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Registrado</span>

                            {{ $copia->created_at }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="font-semibold">@if($copia->actualizadoPor != null)Actualizado por: {{$copia->actualizadoPor->name}} @else Actualizado: @endif</span> <br>

                            {{ $copia->updated_at }}

                        </x-table.cell>

                        @if (!auth()->user()->hasRole('Administrador'))

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Acciones</span>

                                <div class="flex flex-col justify-center lg:justify-start gap-2">

                                    @can('Reimprimir documento')

                                        @if ($copia->certificacion->reimpreso_en == null && $copia->certificacion->folio_carpeta_copias != null)

                                            <x-button-red
                                                wire:click="reimprimir({{ $copia->certificacion->id }})"
                                                wire:loading.attr="disabled"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4 mr-2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>

                                                <span>Reimprimir</span>

                                            </x-button-red>

                                        @endif

                                    @endcan

                                    @can('Rechazar copias certificadas')

                                        <x-button-red
                                            wire:click="abrirModalRechazar({{ $copia->certificacion->id }})"
                                            wire:loading.attr="disabled"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4 mr-2">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>

                                            <span>Rechazar</span>

                                        </x-button-red>

                                    @endcan

                                    @can('Finalizar copias certificadas')

                                        @if(auth()->user()->hasRole('Supervisor Copias'))

                                            <x-button-blue
                                                wire:click="finalizarSupervisor({{ $copia->certificacion->id }})"
                                                wire:loading.attr="disabled"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4 mr-2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>

                                                <span>Finalizar</span>

                                            </x-button-blue>

                                        @else

                                            <x-button-blue
                                                wire:click="abrirModalEditar({{ $copia->certificacion->id }})"
                                                wire:loading.attr="disabled"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4 mr-2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>

                                                <span>Finalizar</span>

                                            </x-button-blue>

                                        @endif

                                    @endcan

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

                        {{ $copias->links()}}

                    </x-table.cell>

                </x-table.row>

            </x-slot>

        </x-table>

    </div>

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
                    wire:click="finalizar"
                    wire:loading.attr="disabled"
                    wire:target="finalizar">

                    <img wire:loading wire:target="finalizar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <span>Finalizar</span>
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
