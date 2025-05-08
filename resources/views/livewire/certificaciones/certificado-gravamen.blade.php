<div class="">

    <div class="mb-6">

        <x-header>Certificados de gravamen</x-header>

        <div class="flex justify-between">

            @include('livewire.comun.filtros-inscripciones')

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

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Movimiento registral</span>

                            <span class="whitespace-nowrap">{{ $certificado->folioReal->folio }}-{{ $certificado->folio }}</span>

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl"># Control</span>

                            <div class="text-center flex flex-col">

                                <span class="whitespace-nowrap">{{ $certificado->año }}-{{ $certificado->tramite }}-{{ $certificado->usuario }}</span>

                                @if(array_key_exists($certificado->usuario, $usuarios_regionales))

                                    <span class="text-xs">Regional {{ $usuarios_regionales[$certificado->usuario] }}</span>

                                @endif

                            </div>

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Estado</span>

                            <span class="bg-{{ $certificado->estado_color }} py-1 px-2 rounded-full text-white text-xs">{{ ucfirst($certificado->estado) }}</span>

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Tipo de servicio</span>

                            {{ $certificado->tipo_servicio }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Distrito</span>

                            {{ $certificado->distrito }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Solicitante</span>

                            {{ $certificado->solicitante }}

                        </x-table.cell>

                        @if (auth()->user()->hasRole(['Supervisor certificaciones', 'Administrador', 'Operador', 'Jefe de departamento certificaciones', 'Supervisor uruapan', 'Jefe de departamento certificaciones']))

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Folio de carpeta</span>

                                {{ $certificado->asignadoA->name ?? 'N/A' }}

                            </x-table.cell>

                        @endif

                        @if (auth()->user()->hasRole(['Administrador', 'Operador', 'Jefe de departamento jurídico']))

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Reimpreso en</span>

                                {{ optional($certificado->certificacion->reimpreso_en)->format('d-m-Y H:i:s') ?? 'N/A' }}

                            </x-table.cell>

                        @endif

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Fecha de entrega</span>

                            {{ optional($certificado->fecha_entrega)->format('d-m-Y') ?? 'N/A' }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Ingreso</span>

                            {{ $certificado->created_at }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="font-semibold">@if($certificado->actualizadoPor != null)Actualizado por: {{$certificado->actualizadoPor->name}} @else Actualizado: @endif</span> <br>

                            {{ $certificado->updated_at }}

                        </x-table.cell>

                        @if (!auth()->user()->hasRole(['Administrador', 'Operador', 'Jefe de departamento jurídico']))

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

                                        @if (auth()->user()->hasRole(['Certificador Gravamen', 'Certificador Oficialia', 'Certificador Juridico']))

                                            <button
                                                wire:click="abrirModalRechazar({{ $certificado->certificacion->id }})"
                                                wire:loading.attr="disabled"
                                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                role="menuitem">

                                                Rechazar

                                            </button>

                                            <button
                                                wire:click="visualizarGravamenes({{ $certificado->certificacion->id }})"
                                                wire:loading.attr="disabled"
                                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                role="menuitem">

                                                Revisar

                                            </button>

                                        @elseif(auth()->user()->hasRole('Jefe de departamento certificaciones') && $certificado->estado != 'elaborado')

                                            <button
                                                wire:click="abrirModalRechazar({{ $certificado->certificacion->id }})"
                                                wire:loading.attr="disabled"
                                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                role="menuitem">

                                                Rechazar

                                            </button>

                                            <button
                                                wire:click="visualizarGravamenes({{ $certificado->certificacion->id }})"
                                                wire:loading.attr="disabled"
                                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                role="menuitem">

                                                Revisar

                                            </button>

                                        @elseif(auth()->user()->hasRole(['Supervisor certificaciones', 'Jefe de departamento certificaciones', 'Supervisor uruapan', 'Regional']) && $certificado->estado == 'elaborado')

                                            @if($certificado->estado == 'elaborado')

                                                <button
                                                    wire:click="abrirModalFinalizar({{ $certificado->certificacion->id }})"
                                                    wire:loading.attr="disabled"
                                                    class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                    role="menuitem">

                                                    <span>Finalizar</span>

                                                </button>

                                            @endif

                                        @endif

                                        @if(in_array($certificado->estado, ['elaborado','finalizado', 'concluido']))

                                            @if(!auth()->user()->hasRole(['Regional']))

                                                <button
                                                    wire:click="corregir({{  $certificado->certificacion->id }})"
                                                    wire:loading.attr="disabled"
                                                    class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                    role="menuitem">
                                                    Corregir
                                                </button>

                                            @endif

                                            <button
                                                wire:click="reimprimir({{ $certificado->id }})"
                                                wire:loading.attr="disabled"
                                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                role="menuitem">

                                                Reimprimir

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
                    wire:click="$toggle('modalCarga')"
                    wire:loading.attr="disabled"
                    wire:target="$toggle('modalCarga')">
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
