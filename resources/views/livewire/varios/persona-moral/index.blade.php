<div>

    <x-header>Folio real de persona moral</x-header>

    <div class="overflow-x-auto rounded-lg shadow-xl border-t-2 border-t-gray-500">

        <x-table>

            <x-slot name="head">

                <x-table.heading sortable wire:click="sortBy('folio_real')" :direction="$sort === 'folio_real' ? $direction : null" >Mov. Reg.</x-table.heading>
                <x-table.heading ># control</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('estado')" :direction="$sort === 'estado' ? $direction : null">Estado</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('tipo_servicio')" :direction="$sort === 'tipo_servicio' ? $direction : null" >Tipo de servicio</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('distrito')" :direction="$sort === 'distrito' ? $direction : null" >Distrito</x-table.heading>
                @if(auth()->user()->hasRole(['Supervisor varios', 'Supervisor uruapan', 'Administrador', 'Jefe de departamento']))
                    <x-table.heading sortable wire:click="sortBy('usuario_asignado')" :direction="$sort === 'usuario_asignado' ? $direction : null">Usuario asignado</x-table.heading>
                @endif
                <x-table.heading sortable wire:click="sortBy('fecha_entrega')" :direction="$sort === 'fecha_entrega' ? $direction : null" >Fecha de entrega</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('created_at')" :direction="$sort === 'created_at' ? $direction : null">Ingreso</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('updated_at')" :direction="$sort === 'updated_at' ? $direction : null">Actualizado</x-table.heading>
                @if (!auth()->user()->hasRole('Administrador'))
                    <x-table.heading >Acciones</x-table.heading>
                @endif

            </x-slot>

            <x-slot name="body">

                @forelse ($movimientos as $movimiento)

                    <x-table.row wire:loading.class.delaylongest="opacity-50" wire:key="row-{{ $movimiento->id }}">

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Mov. Reg.</span>

                            <span class="whitespace-nowrap">{{ $movimiento->folioRealPersona?->folio }}-{{ $movimiento->folio }}</span>

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Número de control</span>

                            {{ $movimiento->año }}-{{ $movimiento->tramite }}-{{ $movimiento->usuario }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Estado</span>

                            <span class="bg-{{ $movimiento->estado_color }} py-1 px-2 rounded-full text-white text-xs">{{ ucfirst($movimiento->estado) }}</span>

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Tipo de servicio</span>

                            {{ $movimiento->tipo_servicio }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Distrito</span>

                            {{ $movimiento->distrito }}

                        </x-table.cell>

                        @if(auth()->user()->hasRole(['Supervisor varios', 'Supervisor uruapan', 'Administrador', 'Jefe de departamento']))

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Usuario asignado</span>

                                {{ $movimiento->asignadoA->name ?? 'N/A' }}

                            </x-table.cell>

                        @endif

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Fecha de entrega</span>

                            {{ $movimiento->fecha_entrega->format('d-m-Y') }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Ingreso</span>

                            {{ $movimiento->created_at }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="font-semibold">@if($movimiento->actualizadoPor != null)Actualizado por: {{$movimiento->actualizadoPor->name}} @else Actualizado: @endif</span> <br>

                            {{ $movimiento->updated_at }}

                        </x-table.cell>

                        @if (!auth()->user()->hasRole('Administrador'))

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Acciones</span>

                                <div class="flex justify-center lg:justify-start gap-2">

                                    @if(in_array($movimiento->estado, ['nuevo', 'captura']) && !auth()->user()->hasRole(['Jefe de departamento', 'Supervisor varios', 'Supervisor uruapan']))

                                        <x-button-blue
                                            wire:click="elaborar({{  $movimiento->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="elaborar({{  $movimiento->id }})">
                                            Elaborar
                                        </x-button-blue>

                                        <x-button-red
                                            wire:click="abrirModalRechazar({{  $movimiento->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="abrirModalRechazar({{  $movimiento->id }})">

                                            <img wire:loading wire:target="abrirModalRechazar({{  $movimiento->id }})" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                                            Rechazar

                                        </x-button-red>

                                    @elseif($movimiento->estado == 'elaborado'  && !auth()->user()->hasRole(['Jefe de departamento', 'Supervisor varios', 'Supervisor uruapan']))

                                        <x-button-green
                                            wire:click="imprimir({{  $movimiento->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="imprimir({{  $movimiento->id }})">
                                            Imprimir
                                        </x-button-green>

                                        <x-button-green
                                            wire:click="abrirModalFinalizar({{  $movimiento->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="abrirModalFinalizar({{  $movimiento->id }})">
                                            Finalizar
                                        </x-button-green>

                                    @elseif($movimiento->estado == 'finalizado' && auth()->user()->hasRole(['Jefe de departamento', 'Supervisor varios', 'Supervisor uruapan']))

                                        <x-button-blue
                                            wire:click="imprimir({{  $movimiento->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="imprimir({{  $movimiento->id }})">
                                            Imprimir
                                        </x-button-blue>

                                        <x-button-green
                                            wire:click="abrirModalConcluir({{  $movimiento->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="abrirModalConcluir({{  $movimiento->id }})">
                                            Finalizar
                                        </x-button-green>

                                    @elseif(in_array($movimiento->estado, ['nuevo', 'captura', 'elaborado']) && auth()->user()->hasRole(['Jefe de departamento', 'Supervisor varios', 'Supervisor uruapan']))

                                        <x-button-red
                                            wire:click="abrirModalReasignar({{  $movimiento->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="abrirModalReasignar({{  $movimiento->id }})">
                                            Reasignar
                                        </x-button-red>

                                    @endif

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

    <x-dialog-modal wire:model="modalFinalizar" maxWidth="sm">

        <x-slot name="title">

            Subir archivo

        </x-slot>

        <x-slot name="content">

            <x-filepond wire:model.live="documento" accept="['application/pdf']"/>

            <div>

                @error('documento') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

            </div>

        </x-slot>

        <x-slot name="footer">

            <div class="flex gap-3">

                <x-button-blue
                    wire:click="finalizar"
                    wire:loading.attr="disabled"
                    wire:target="finalizar">

                    <img wire:loading wire:target="finalizar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <span>Finalizar</span>

                </x-button-blue>

                <x-button-red
                    wire:click="$toggle('modalFinalizar')"
                    wire:loading.attr="disabled"
                    wire:target="$toggle('modalFinalizar')"
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

    <x-confirmation-modal wire:model="modalConcluir" maxWidth="sm">

        <x-slot name="title">
            Concluir movimiento registral
        </x-slot>

        <x-slot name="content">
            ¿Esta seguro que desea concluir el movimiento registral?
        </x-slot>

        <x-slot name="footer">

            <x-secondary-button
                wire:click="$toggle('modalConcluir')"
                wire:loading.attr="disabled"
            >
                No
            </x-secondary-button>

            <x-danger-button
                class="ml-2"
                wire:click="concluir"
                wire:loading.attr="disabled"
                wire:target="concluir"
            >
                Concluir
            </x-danger-button>

        </x-slot>

    </x-confirmation-modal>

</div>

<script>

    window.addEventListener('imprimir_documento', event => {

        const documento = event.detail[0].caratula;

        var url = "{{ route('varios.inscripcion.acto', '')}}" + "/" + documento;

        window.open(url, '_blank');

    });

</script>
