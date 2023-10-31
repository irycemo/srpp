<div class="">

    <div class="mb-6">

        <x-header>Consultas</x-header>

        <div class="flex justify-between">

            <div class="flex">

                <select class="bg-white rounded-l text-sm border border-r-transparent  focus:ring-0 @error('año') border-red-500 @enderror " wire:model="año">
                    <option value="2023">2023</option>
                </select>

                <input type="number" placeholder="Número de control" min="1" class="bg-white text-sm w-full focus:ring-0 @error('tramite') border-red-500 @enderror " wire:model="tramite">

                <button
                    wire:click="consultar"
                    wire:loading.attr="disabled"
                    wire:target="consultar"
                    type="button"
                    class="bg-blue-400 hover:shadow-lg text-white font-bold px-4 rounded-r text-sm hover:bg-blue-700 focus:outline-blue-400 focus:outline-offset-2">

                    <img wire:loading wire:target="consultar" class="mx-auto h-5 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <svg wire:loading.remove xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>

                </button>

            </div>

        </div>

    </div>

    <div class="overflow-x-auto rounded-lg shadow-xl border-t-2 border-t-gray-500">

        @if($certificacion != null)

            <x-table>

                <x-slot name="head">

                    <x-table.heading sortable wire:click="sortBy('año')" :direction="$sort === 'año' ? $direction : null" >Año</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('tramite')" :direction="$sort === 'tramite' ? $direction : null" ># Control</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('estado')" :direction="$sort === 'estado' ? $direction : null" >Estado</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('tipo_servicio')" :direction="$sort === 'tipo_servicio' ? $direction : null" >Tipo de servicio</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('solicitante')" :direction="$sort === 'solicitante' ? $direction : null" >Solicitante</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('tomo')" :direction="$sort === 'tomo' ? $direction : null" >Tomo / Bis</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('registro')" :direction="$sort === 'registro' ? $direction : null" >Registro / Bis</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('distrito')" :direction="$sort === 'distrito' ? $direction : null" >Distrito</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('seccion')" :direction="$sort === 'seccion' ? $direction : null" >Sección</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('usuario_asignado')" :direction="$sort === 'usuario_asignado' ? $direction : null" >Asignado a</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('fecha_entrega')" :direction="$sort === 'fecha_entrega' ? $direction : null" >Fecha de entrega</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('observaciones')" :direction="$sort === 'observaciones' ? $direction : null" >Observaciones</x-table.heading>
                    @if(auth()->user()->hasRole('Administrador'))
                        <x-table.heading >Acciones</x-table.heading>
                    @endif

                </x-slot>

                <x-slot name="body">

                    <x-table.row wire:loading.class.delaylongest="opacity-50" wire:key="row-{{ $certificacion->id }}">

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Año</span>

                            {{ $certificacion->año }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl"># Control</span>

                            {{ $certificacion->tramite }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Estado</span>

                            <span class="bg-{{ $certificacion->estado_color }} py-1 px-2 rounded-full text-white text-xs">{{ ucfirst($certificacion->estado) }}</span>

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Tipo de servicio</span>

                            {{ $certificacion->tipo_servicio }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Solicitante</span>

                            {{ $certificacion->solicitante }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Tomo / Bis</span>

                            {{ $certificacion->tomo }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Registro / Bis</span>

                            {{ $certificacion->registro }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Distrito</span>

                            {{ $certificacion->distrito }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Sección</span>

                            {{ $certificacion->seccion }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Asignado a</span>

                            {{ $certificacion->asignadoA->name }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Fecha de entrega</span>

                            {{ $certificacion->fecha_entrega }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Observaciones</span>

                            {{ $certificacion->certificacion->observaciones }}

                        </x-table.cell>

                        @if(auth()->user()->hasRole('Administrador'))

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Acciones</span>

                                <div class="flex flex-col justify-center lg:justify-start gap-2">

                                    <x-button-blue
                                        wire:click="$set('modal', '!modal')"
                                        wire:loading.attr="disabled"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4 mr-3">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>

                                        <span>Corregir</span>

                                    </x-button-blue>

                                    <x-button-gray
                                        wire:click="$set('modal2', '!modal2')"
                                        wire:loading.attr="disabled"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                        </svg>

                                        <span>Reasignar</span>

                                    </x-button-gray>

                                    @if($certificacion->estado == 'nuevo')

                                        <x-button-red
                                            wire:click="$set('modalRechazar', '!modalRechazar')"
                                            wire:loading.attr="disabled"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>

                                            <span>Recahzar</span>

                                        </x-button-red>

                                    @endif

                                </div>

                            </x-table.cell>

                        @endif

                    </x-table.row>

                </x-slot>

                <x-slot name="tfoot">

                    <x-table.row>

                        <x-table.cell colspan="13" class="bg-gray-50">

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
            Corregir certificación
        </x-slot>

        <x-slot name="content">

            <div class="flex flex-col md:flex-row justify-between md:space-x-3 mb-5">

                <div class="flex-auto mr-1 ">

                    <div>

                        <Label>Número de paginas</Label>

                    </div>

                    <div>

                        <input type="number" class="bg-white rounded text-sm w-full" wire:model="paginas">

                    </div>

                    <div>

                        @error('paginas') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                    </div>

                </div>

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
                Corregir
            </x-danger-button>

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

</div>
