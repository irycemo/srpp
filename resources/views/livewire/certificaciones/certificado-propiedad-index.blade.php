<div class="">

    <div class="mb-6">

        <x-header>Certificados de propiedad</x-header>

        <div class="flex justify-between">

            <div>

                <input type="text" wire:model.live.debounce.500ms="search" placeholder="Buscar" class="bg-white rounded-full text-sm">

                <select class="bg-white rounded-full text-sm" wire:model.live="pagination">

                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>

                </select>

            </div>

            {{-- @if(auth()->user()->hasRole(['Certificador Propiedad', 'Certificador Oficialia', 'Certificador Juridico']))

                <button wire:click="$set('modalCarga', '!modalCarga')" wire:loading.attr="disabled" class="bg-gray-500 hover:shadow-lg hover:bg-gray-700 text-sm py-2 px-4 text-white rounded-full hidden md:block items-center justify-center focus:outline-gray-400 focus:outline-offset-2">

                    <img wire:loading wire:target="modalCarga" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                    Imprimir carga de trabajo

                </button>

                <button wire:click="$set('modalCarga', '!modalCarga')" class="bg-gray-500 hover:shadow-lg hover:bg-gray-700 float-right text-sm py-2 px-4 text-white rounded-full focus:outline-none md:hidden">+</button>

           @endif --}}

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
                @if (auth()->user()->hasRole(['Administrador', 'Operador']))
                    <x-table.heading >Reimpreso en</x-table.heading>
                @endif
                <x-table.heading sortable wire:click="sortBy('fecha_entrega')" :direction="$sort === 'fecha_entrega' ? $direction : null">Fecha de entrega</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('created_at')" :direction="$sort === 'created_at' ? $direction : null">Ingreso</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('updated_at')" :direction="$sort === 'updated_at' ? $direction : null">Actualizado</x-table.heading>
                @if (!auth()->user()->hasRole(['Administrador', 'Operador']))
                    <x-table.heading >Acciones</x-table.heading>
                @endif

            </x-slot>

            <x-slot name="body">

                @forelse ($certificados as $certificado)

                    @if($certificado->tomo && $certificado->registro && $certificado->numero_propiedad && !$certificado->folio_real) @continue @endif

                    <x-table.row wire:loading.class.delaylongest="opacity-50" wire:key="row-{{ $certificado->id }}">

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Folio real</span>

                            <span class="whitespace-nowrap">{{ $certificado->folioReal ? $certificado->folioReal->folio . '-' . $certificado->folio : 'N/A'}}</span>

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl"># Control</span>

                            <span class="whitespace-nowrap">{{ $certificado->año ?? 'N/A' }}-{{ $certificado->tramite ?? 'N/A' }}-{{ $certificado->usuario ?? 'N/A' }}</span>

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

                        @if (auth()->user()->hasRole(['Supervisor certificaciones', 'Administrador', 'Jefe de departamento certificaciones', 'Supervisor uruapan', 'Operador']))

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Folio de carpeta</span>

                                {{ $certificado->asignadoA->name ?? 'N/A' }}

                            </x-table.cell>

                        @endif

                        @if (auth()->user()->hasRole(['Administrador', 'Operador']))

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

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Acciones</span>

                            @if (!auth()->user()->hasRole(['Administrador', 'Operador']))

                                <div class="flex justify-center lg:justify-start gap-2">

                                    @if(auth()->user()->hasRole(['Certificador Propiedad', 'Certificador Oficialia', 'Certificador Juridico']))

                                        <x-button-red
                                            wire:click="abrirModalRechazar({{ $certificado->id }})"
                                            wire:loading.attr="disabled">

                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4 mr-2">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>

                                            <span>Rechazar</span>

                                        </x-button-red>

                                        <x-button-blue
                                            wire:click="elaborar({{  $certificado->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="elaborar({{  $certificado->id }})">
                                            Elaborar
                                        </x-button-blue>

                                    @elseif(auth()->user()->hasRole('Jefe de departamento certificaciones') && $certificado->estado != 'elaborado')

                                        <x-button-red
                                            wire:click="abrirModalRechazar({{ $certificado->id }})"
                                            wire:loading.attr="disabled">

                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4 mr-2">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>

                                            <span>Rechazar</span>

                                        </x-button-red>

                                        <x-button-blue
                                            wire:click="elaborar({{  $certificado->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="elaborar({{  $certificado->id }})">
                                            Elaborar
                                        </x-button-blue>

                                    @elseif(auth()->user()->hasRole(['Supervisor certificaciones', 'Jefe de departamento certificaciones', 'Supervisor uruapan']) && $certificado->estado == 'elaborado')

                                        @if ($certificado->certificacion->reimpreso_en == null)

                                            <x-button-blue
                                                wire:click="reimprimir({{  $certificado->id }})"
                                                wire:loading.attr="disabled"
                                                wire:target="reimprimir({{  $certificado->id }})">
                                                Reimprimir
                                            </x-button-blue>

                                        @endif

                                        @if($certificado->estado == 'elaborado')

                                            @if($certificado->folio_real)

                                                <x-button-green
                                                    wire:click="abrirModalFinalizar({{  $certificado->id }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="abrirModalFinalizar({{  $certificado->id }})">
                                                    Finalizar
                                                </x-button-green>

                                            @else

                                                <x-button-green
                                                    wire:click="finalizar({{  $certificado->id }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="finalizar({{  $certificado->id }})">
                                                    Finalizar
                                                </x-button-green>

                                            @endif

                                        @endif

                                    @endif

                                </div>

                            @endif

                        </x-table.cell>

                    </x-table.row>

                @empty

                    <x-table.row>

                        <x-table.cell colspan="21">

                            <div class="bg-white text-gray-500 text-center p-5 rounded-full text-lg">

                                No hay resultados.

                            </div>

                        </x-table.cell>

                    </x-table.row>

                @endforelse

            </x-slot>

            <x-slot name="tfoot">

                <x-table.row>

                    <x-table.cell colspan="21" class="bg-gray-50">

                        {{ $certificados->links()}}

                    </x-table.cell>

                </x-table.row>

            </x-slot>

        </x-table>

    </div>

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

        window.addEventListener('imprimir_negativo_propiedad', event => {

            const documento = event.detail[0].certificacion;

            var url = "{{ route('certificado_negativo_propiedad_pdf', '')}}" + "/" + documento;

            window.open(url, '_blank');

            window.location.href = "{{ route('certificados_propiedad')}}";

        });

        window.addEventListener('imprimir_propiedad', event => {

            const documento = event.detail[0].certificacion;

            var url = "{{ route('certificado_propiedad_pdf', '')}}" + "/" + documento;

            window.open(url, '_blank');

            window.location.href = "{{ route('certificados_propiedad')}}";

        });

        window.addEventListener('imprimir_unico_propiedad', event => {

            const documento = event.detail[0].certificacion;

            var url = "{{ route('certificado_unico_propiedad_pdf', '')}}" + "/" + documento;

            window.open(url, '_blank');

            window.location.href = "{{ route('certificados_propiedad')}}";

        });

        window.addEventListener('imprimir_certificado_colindancias', event => {

            const documento = event.detail[0].certificacion;

            var url = "{{ route('certificado_propiedad_colindancias_pdf', '')}}" + "/" + documento;

            window.open(url, '_blank');

            window.location.href = "{{ route('certificados_propiedad')}}";

        });

        window.addEventListener('imprimir_negativo', event => {

            const documento = event.detail[0].certificacion;

            var url = "{{ route('certificado_negativo_pdf', '')}}" + "/" + documento;

            window.open(url, '_blank');

            window.location.href = "{{ route('certificados_propiedad')}}";

        });

    </script>

@endpush
