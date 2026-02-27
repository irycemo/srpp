<div class="">

    <div class="mb-2 lg:mb-5">

        <x-header>Certificados de gravamen</x-header>

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

                <input type="number" wire:model.live.debounce.500ms="filters.folio" placeholder="M.R." class="bg-white rounded-full text-sm w-24">

                <select class="bg-white rounded-full text-sm w-min" wire:model.live="filters.estado">

                    <option value="" selected>Estado</option>
                    <option value="nuevo">Nuevo</option>
                    <option value="elaborado">Elaborado</option>
                    <option value="rechazado">Rechazado</option>
                    <option value="finalizado">Finalizado</option>
                    <option value="correccion">Corrección</option>

                </select>

                <select class="bg-white rounded-full text-sm w-min" wire:model.live="filters.usuario_asignado">

                    <option value="">Asignado a</option>
                    @foreach ($usuarios as $usuario)

                        <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>

                    @endforeach

                </select>

                <select class="bg-white rounded-full text-sm" wire:model.live="pagination">

                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>

                </select>

            </div>

            @if(auth()->user()->ubicacion === 'Regional 4')

                <div class="">

                    <button wire:click="$toggle('modal_reasignarme_movimiento_registral')" class="bg-gray-500 hover:shadow-lg hover:bg-gray-700 text-sm py-2 px-4 text-white rounded-full hidden md:block items-center justify-center focus:outline-gray-400 focus:outline-offset-2">

                        <img wire:loading wire:target="$toggle('modal_reasignarme_movimiento_registral')" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                        Asignarme trámite

                    </button>

                    <button wire:click="$toggle('modal_reasignarme_movimiento_registral')" class="bg-gray-500 hover:shadow-lg hover:bg-gray-700 float-right text-sm py-2 px-4 text-white rounded-full md:hidden focus:outline-gray-400 focus:outline-offset-2">+</button>

                </div>

            @endif

        </div>

    </div>

    <div class="overflow-x-auto rounded-lg shadow-xl border-t-2 border-t-gray-500">

        <x-table>

            <x-slot name="head">

                <x-table.heading sortable wire:click="sortBy('folio_real')" :direction="$sort === 'folio_real' ? $direction : null" >Mov. Reg.</x-table.heading>
                <x-table.heading># Control</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('estado')" :direction="$sort === 'estado' ? $direction : null" >Estado</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('tipo_servicio')" :direction="$sort === 'tipo_servicio' ? $direction : null" >Tipo de servicio</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('distrito')" :direction="$sort === 'distrito' ? $direction : null" >Distrito</x-table.heading>
                <x-table.heading>Solicitante</x-table.heading>
                @if (auth()->user()->hasRole(['Supervisor certificaciones', 'Administrador', 'Jefe de departamento certificaciones', 'Supervisor uruapan', 'Operador']))
                    <x-table.heading sortable wire:click="sortBy('usuario_asignado')" :direction="$sort === 'usuario_asignado' ? $direction : null" >Asignado a</x-table.heading>
                @endif
                @if (auth()->user()->hasRole(['Administrador', 'Operador', 'Jefe de departamento jurídico']))
                    <x-table.heading >Reimpreso en</x-table.heading>
                @endif
                <x-table.heading sortable wire:click="sortBy('fecha_entrega')" :direction="$sort === 'fecha_entrega' ? $direction : null">Fecha de entrega</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('created_at')" :direction="$sort === 'created_at' ? $direction : null">Ingreso</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('updated_at')" :direction="$sort === 'updated_at' ? $direction : null">Actualizado</x-table.heading>
                @if (!auth()->user()->hasRole(['Administrador', 'Operador', 'Jefe de departamento jurídico']))
                    <x-table.heading >Acciones</x-table.heading>
                @endif

            </x-slot>

            <x-slot name="body">

                @forelse ($certificados as $certificado)

                    <x-table.row wire:loading.class.delaylongest="opacity-50" wire:key="row-{{ $certificado->id }}">

                        <x-table.cell title="Movimiento registral">

                            <span class="whitespace-nowrap">{{ $certificado->folioReal->folio }}-{{ $certificado->folio }}</span>

                        </x-table.cell>

                        <x-table.cell title="# Control">

                            <div class="text-center flex flex-col">

                                <span class="whitespace-nowrap">{{ $certificado->año }}-{{ $certificado->tramite }}-{{ $certificado->usuario }}</span>

                                @if(array_key_exists($certificado->usuario, $usuarios_regionales))

                                    <span class="text-xs">Regional {{ $usuarios_regionales[$certificado->usuario] }}</span>

                                @endif

                            </div>

                        </x-table.cell>

                        <x-table.cell title="Estado">

                            <span class="bg-{{ $certificado->estado_color }} py-1 px-2 rounded-full text-white text-xs">{{ ucfirst($certificado->estado) }}</span>

                        </x-table.cell>

                        <x-table.cell title="Tipo de servicio">

                            {{ $certificado->tipo_servicio }}

                        </x-table.cell>

                        <x-table.cell title="Distrito">

                            {{ $certificado->distrito }}

                        </x-table.cell>

                        <x-table.cell title="Solicitante">

                            {{ $certificado->solicitante }}

                        </x-table.cell>

                        @if (auth()->user()->hasRole(['Supervisor certificaciones', 'Administrador', 'Operador', 'Jefe de departamento certificaciones', 'Supervisor uruapan', 'Jefe de departamento certificaciones']))

                            <x-table.cell title="Usuario asignado">

                                {{ $certificado->asignadoA->name ?? 'N/A' }}

                            </x-table.cell>

                        @endif

                        @if (auth()->user()->hasRole(['Administrador', 'Operador', 'Jefe de departamento jurídico']))

                            <x-table.cell title="Reimpreso en">

                                {{ optional($certificado->certificacion->reimpreso_en)->format('d-m-Y H:i:s') ?? 'N/A' }}

                            </x-table.cell>

                        @endif

                        <x-table.cell title="Fecha de entrega">

                            {{ optional($certificado->fecha_entrega)->format('d-m-Y') ?? 'N/A' }}

                        </x-table.cell>

                        <x-table.cell title="Ingreso">

                            {{ $certificado->created_at }}

                        </x-table.cell>

                        <x-table.cell title="Actualizado">

                            <span class="font-semibold">@if($certificado->actualizadoPor != null)Actualizado por: {{$certificado->actualizadoPor->name}} @else Actualizado: @endif</span> <br>

                            {{ $certificado->updated_at }}

                        </x-table.cell>

                        @if (!auth()->user()->hasRole(['Administrador', 'Operador', 'Jefe de departamento jurídico']))

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

                                        @can('Rechazar certificado')

                                            @if(in_array($certificado->estado, ['nuevo' ,'correccion']))

                                                <button
                                                    wire:click="abrirModalRechazar({{ $certificado->id }})"
                                                    wire:loading.attr="disabled"
                                                    class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                    role="menuitem">

                                                    Rechazar

                                                </button>

                                            @endif

                                        @endcan

                                        @can('Elaborar certificado')

                                            @if(in_array($certificado->estado, ['nuevo' ,'correccion']))

                                                <button
                                                    wire:click="visualizarGravamenes({{ $certificado->certificacion->id }})"
                                                    wire:loading.attr="disabled"
                                                    class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                    role="menuitem">

                                                    Revisar

                                                </button>

                                            @endif

                                        @endcan

                                        @can('Reasignar certificado')

                                            @if(in_array($certificado->estado, ['nuevo' ,'correccion']))

                                                <button
                                                    wire:click="abrirModalReasignar({{ $certificado->certificacion->id }})"
                                                    wire:loading.attr="disabled"
                                                    class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                    role="menuitem">

                                                    Reasignar

                                                </button>

                                            @endif

                                        @endcan

                                        @can('Corregir certificado')

                                            @if(in_array($certificado->estado, ['elaborado','finalizado', 'concluido']))

                                                <button
                                                    wire:click="corregir({{  $certificado->id }})"
                                                    wire:loading.attr="disabled"
                                                    class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                    role="menuitem">
                                                    Corregir
                                                </button>

                                            @endif

                                        @endcan

                                        @can('Reimprimir certificado')

                                            @if(in_array($certificado->estado, ['elaborado','finalizado', 'concluido']))

                                                <button
                                                    wire:click="reimprimir({{  $certificado->id }})"
                                                    wire:loading.attr="disabled"
                                                    class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                    role="menuitem">
                                                    Reimprimir
                                                </button>

                                            @endif

                                        @endcan

                                        @can('Finalizar certificado')

                                            @if(in_array($certificado->estado, ['elaborado','finalizado', 'concluido']))

                                                <button
                                                    wire:click="abrirModalFinalizar({{ $certificado->certificacion->id }})"
                                                    wire:loading.attr="disabled"
                                                    class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                    role="menuitem">

                                                    <span>Finalizar</span>

                                                </button>

                                            @endif

                                        @endcan

                                        @if($certificado->estado === 'rechazado')

                                            <button
                                                wire:click="abrirModalRechazos({{  $certificado->id }})"
                                                wire:loading.attr="disabled"
                                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                role="menuitem">
                                                Ver rechazos
                                            </button>

                                        @endif

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

                        {{ $certificados->links()}}

                    </x-table.cell>

                </x-table.row>

            </x-slot>

        </x-table>

    </div>

    <x-dialog-modal wire:model="modal" maxWidth="2xl">

        <x-slot name="title">

            Gravamenes

        </x-slot>

        <x-slot name="content">

            @if($predio)

                @if($gravamenes->count())

                    @foreach ($gravamenes as $gravamen)

                        <div class="p-4 bg-gray-100 mb-2 rounded-lg">

                            <p><strong>Tomo: </strong> {{ $gravamen->movimientoRegistral->tomo_gravamen }} <strong>Registro: </strong>{{ $gravamen->movimientoRegistral->registro_gravamen }} <strong>Distrito: </strong>{{ $gravamen->movimientoRegistral->distrito }}</p>
                            <p><strong>Acto: </strong>{{ $gravamen->acto_contenido }}</p>
                            <p><strong>Tipo: </strong>{{ $gravamen->tipo }}</p>
                            <p><strong>Valor: </strong>{{ number_format($gravamen->valor_gravamen, 2) }} {{ $gravamen->divisa }}, <strong>Fecha de inscripción: </strong>{{ $gravamen->fecha_inscripcion }}</p>

                            <p><strong>Deudores</strong></p>
                            @foreach ($gravamen->deudores as $deudor)

                                <p>{{ $deudor->persona->nombre }} {{ $deudor->persona->ap_paterno }} {{ $deudor->persona->ap_materno }} {{ $deudor->persona->razon_social }}</p>

                            @endforeach

                            <p><strong>Acreedores</strong></p>
                            @foreach ($gravamen->acreedores as $acreedor)

                                <p>{{ $acreedor->persona->nombre }} {{ $acreedor->persona->ap_paterno }} {{ $acreedor->persona->ap_materno }} {{ $acreedor->persona->razon_social }}</p>

                            @endforeach

                            <p><strong>Observaciones: </strong>{{ $gravamen->observaciones }}</p>

                        </div>

                    @endforeach

                @else

                    <div class="p-4 bg-gray-100 mb-2 rounded-lg">

                        <strong>No se encontraron gravmenes activos</strong>

                    </div>

                @endif

            @endif

        </x-slot>

        <x-slot name="footer">

            <div class="flex items-center justify-end space-x-3">

                <x-button-blue
                    wire:click="generarCertificado"
                    wire:loading.attr="disabled"
                    wire:target="generarCertificado">

                    <img wire:loading wire:target="generarCertificado" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <span>Generar certificado</span>

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

    <x-confirmation-modal wire:model="modalFinalizar" maxWidth="sm">

        <x-slot name="title">
            Finalizar
        </x-slot>

        <x-slot name="content">
            ¿Esta seguro que desea finalizar?
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
                wire:click="finalizarSupervisor"
                wire:loading.attr="disabled"
                wire:target="finalizarSupervisor"
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

                <x-input-group for="usuario_asignado" label="Área" :error="$errors->first('usuario_asignado')" class="w-full">

                    <x-input-select id="usuario_asignado" wire:model="usuario_asignado" class="w-full">

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

    @include('livewire.comun.inscripciones.modal-reasignarme-movimiento-registral')

    @include('livewire.comun.inscripciones.modal-rechazar')

    @include('livewire.comun.inscripciones.modal-rechazos')

</div>

@push('scripts')

    <script>

        window.addEventListener('imprimir_documento', event => {

            const documento = event.detail[0].gravamen;

            var url = "{{ route('certificado_gravamen_pdf', '')}}" + "/" + documento;

            window.open(url, '_blank');

        });

    </script>

@endpush
