<div class="">

    <div class="mb-2 lg:mb-5">

        <x-header>Certificados de propiedad</x-header>

        @include('livewire.comun.filtros-inscripciones')

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

                        <x-table.cell title="Folio real">

                            <span class="whitespace-nowrap">{{ $certificado->folioReal ? $certificado->folioReal->folio . '-' . $certificado->folio : 'N/A'}}</span>

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

                        @if (auth()->user()->hasRole(['Supervisor certificaciones', 'Administrador', 'Jefe de departamento certificaciones', 'Supervisor uruapan', 'Operador']))

                            <x-table.cell  title="Usuario asignado">

                                {{ $certificado->asignadoA->name ?? 'N/A' }}

                            </x-table.cell>

                        @endif

                        @if (auth()->user()->hasRole(['Administrador', 'Operador', 'Jefe de departamento jurídico', 'Director']))

                            <x-table.cell title="Reimpreso en">

                                {{ optional($certificado->certificacion->reimpreso_en)->format('d-m-Y H:i:s') ?? 'N/A' }}

                            </x-table.cell>

                        @endif

                        <x-table.cell title="Fecha de entrega">

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Fecha de entrega</span>

                            {{ optional($certificado->fecha_entrega)->format('d-m-Y') ?? 'N/A' }}

                        </x-table.cell>

                        <x-table.cell title="Ingreso">

                            {{ $certificado->created_at }}

                        </x-table.cell>

                        <x-table.cell title="Actualizado">

                            <span class="font-semibold">@if($certificado->actualizadoPor != null)Actualizado por: {{$certificado->actualizadoPor->name}} @else Actualizado: @endif</span> <br>

                            {{ $certificado->updated_at }}

                        </x-table.cell>

                        <x-table.cell title="Acciones">

                            @if (!auth()->user()->hasRole(['Administrador', 'Operador', 'Jefe de departamento jurídico']))

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
                                                    wire:click="elaborar({{  $certificado->id }})"
                                                    wire:loading.attr="disabled"
                                                    class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                    role="menuitem">
                                                    Elaborar
                                                </button>

                                            @endif

                                        @endcan

                                        @can('Reasignar certificado')

                                            @if(in_array($certificado->estado, ['nuevo' ,'correccion']))

                                                <button
                                                    wire:click="abrirModalReasignar({{ $certificado->id }})"
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
                                                    wire:click="abrirModalFinalizar({{ $certificado->id }})"
                                                    wire:loading.attr="disabled"
                                                    class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                    role="menuitem">

                                                    <span>Finalizar</span>

                                                </button>

                                            @endif

                                        @endcan

                                    </div>

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
