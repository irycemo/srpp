<div class="">

    <div class="mb-6">

        <x-header>Indices y Tomos</x-header>

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

        </div>

    </div>

    <div class="overflow-x-auto rounded-lg shadow-xl border-t-2 border-t-gray-500">

        <x-table>

            <x-slot name="head">

                <x-table.heading sortable wire:click="sortBy('año')" :direction="$sort === 'año' ? $direction : null" >Año</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('tramite')" :direction="$sort === 'tramite' ? $direction : null" ># Control</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('usuario')" :direction="$sort === 'usuario' ? $direction : null" >Usuario</x-table.heading>
                @if (auth()->user()->hasRole('Administrador'))
                    <x-table.heading sortable wire:click="sortBy('estado')" :direction="$sort === 'estado' ? $direction : null" >Estado</x-table.heading>
                @endif
                <x-table.heading sortable wire:click="sortBy('solicitante')" :direction="$sort === 'solicitante' ? $direction : null" >Solicitante</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('distrito')" :direction="$sort === 'distrito' ? $direction : null" >Distrito</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('seccion')" :direction="$sort === 'seccion' ? $direction : null" >Sección</x-table.heading>
                <x-table.heading>Solicitante</x-table.heading>
                @if (!auth()->user()->hasRole('Consulta'))
                    <x-table.heading>Finalizado en</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('usuario_asignado')" :direction="$sort === 'usuario_asignado' ? $direction : null" >Asignado a</x-table.heading>
                @endif
                <x-table.heading sortable wire:click="sortBy('created_at')" :direction="$sort === 'created_at' ? $direction : null">Registro</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('updated_at')" :direction="$sort === 'updated_at' ? $direction : null">Actualizado</x-table.heading>
                @if (!auth()->user()->hasRole('Administrador'))
                    <x-table.heading >Acciones</x-table.heading>
                @endif

            </x-slot>

            <x-slot name="body">

                @forelse ($consultas as $consulta)

                    <x-table.row wire:loading.class.delaylongest="opacity-50" wire:key="row-{{ $consulta->id }}">

                        <x-table.cell title="Año">

                            {{ $consulta->año }}

                        </x-table.cell>

                        <x-table.cell title="# Control">

                            {{ $consulta->tramite }}

                        </x-table.cell>

                        <x-table.cell title="Usuario">

                            {{ $consulta->usuario }}

                        </x-table.cell>

                        @if (auth()->user()->hasRole('Administrador'))

                            <x-table.cell title="Estado">

                                <span class="bg-{{ $consulta->estado_color }} py-1 px-2 rounded-full text-white text-xs">{{ ucfirst($consulta->estado) }}</span>

                            </x-table.cell>

                        @endif

                        <x-table.cell title="Solicitante">

                            {{ $consulta->solicitante }}

                        </x-table.cell>

                        <x-table.cell title="Distrito">

                            {{ $consulta->distrito }}

                        </x-table.cell>

                        <x-table.cell title="Sección">

                            {{ $consulta->seccion }}

                        </x-table.cell>

                        <x-table.cell title="Solicitante">

                            {{ $certificado->solicitante }}

                        </x-table.cell>

                        @if (!auth()->user()->hasRole('Consulta'))

                            <x-table.cell title="Finalizado en">

                                {{ $consulta->certificacion->finalizado_en ? $consulta->certificacion->finalizado_en->format('d-m-Y H:i:s') : 'N/A' }}

                            </x-table.cell>

                            <x-table.cell title="Asignado a">

                                {{ $consulta->asignadoA->name }}

                            </x-table.cell>

                        @endif

                        <x-table.cell title="Registrado">

                            {{ $consulta->created_at }}

                        </x-table.cell>

                        <x-table.cell title="Actualizado">

                            <span class="font-semibold">@if($consulta->actualizadoPor != null)Actualizado por: {{$consulta->actualizadoPor->name}} @else Actualizado: @endif</span> <br>

                            {{ $consulta->updated_at }}

                        </x-table.cell>

                        @if (!auth()->user()->hasRole('Administrador'))

                            <x-table.cell title="Acciones">

                                <div class="flex justify-center lg:justify-start gap-2">

                                    @can('Finalizar consulta')

                                        <x-button-blue
                                            wire:click="abrirModalEditar({{ $consulta->certificacion->id }})"
                                            wire:loading.attr="disabled"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4 mr-2">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>

                                            <span>Finalizar</span>

                                        </x-button-blue>

                                    @endcan

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

                        {{ $consultas->links()}}

                    </x-table.cell>

                </x-table.row>

            </x-slot>

        </x-table>

    </div>

    <x-confirmation-modal wire:model="modal" maxWidth="sm">

        <x-slot name="title">
            Finalizar trámite
        </x-slot>

        <x-slot name="content">
            ¿Esta seguro que desea finalizar el trámite?
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
                wire:click="finalizar"
                wire:loading.attr="disabled"
                wire:target="finalizar"
            >
                Finalizar
            </x-danger-button>

        </x-slot>

    </x-confirmation-modal>

</div>
