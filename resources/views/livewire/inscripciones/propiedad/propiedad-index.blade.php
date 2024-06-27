<div>

    <x-header>Inscripciones de propiedad</x-header>

    <div class="overflow-x-auto rounded-lg shadow-xl border-t-2 border-t-gray-500">

        <x-table>

            <x-slot name="head">

                <x-table.heading sortable wire:click="sortBy('folio_real')" :direction="$sort === 'folio_real' ? $direction : null" >Folio Real</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('año')" :direction="$sort === 'año' ? $direction : null" >Año</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('tramite')" :direction="$sort === 'tramite' ? $direction : null" ># control</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('usuario')" :direction="$sort === 'usuario' ? $direction : null" >Usuario</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('numero_propiedad')" :direction="$sort === 'numero_propiedad' ? $direction : null" ># propiedad</x-table.heading>
                @if(auth()->user()->hasRole('Administrador'))
                    <x-table.heading sortable wire:click="sortBy('estado')" :direction="$sort === 'estado' ? $direction : null">Estado</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('usuario_asignado')" :direction="$sort === 'usuario_asignado' ? $direction : null">Usuario asignado</x-table.heading>
                @endif
                <x-table.heading sortable wire:click="sortBy('created_at')" :direction="$sort === 'created_at' ? $direction : null">Registro</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('updated_at')" :direction="$sort === 'updated_at' ? $direction : null">Actualizado</x-table.heading>
                @if (!auth()->user()->hasRole('Administrador'))
                    <x-table.heading >Acciones</x-table.heading>
                @endif

            </x-slot>

            <x-slot name="body">

                @forelse ($movimientos as $movimiento)

                    <x-table.row wire:loading.class.delaylongest="opacity-50" wire:key="row-{{ $movimiento->id }}">

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Folio Real</span>

                            {{ $movimiento->folioReal->folio }}-{{ $movimiento->folio }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Año</span>

                            {{ $movimiento->año }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Número de control</span>

                            {{ $movimiento->tramite }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Usuario</span>

                            {{ $movimiento->usuario }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Número de propiedad</span>

                            {{ $movimiento->numero_propiedad }}

                        </x-table.cell>

                        @if(auth()->user()->hasRole('Administrador'))

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Estado</span>

                                <span class="bg-{{ $movimiento->estado_color }} py-1 px-2 rounded-full text-white text-xs">{{ ucfirst($movimiento->estado) }}</span>

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Usuario asignado</span>

                                {{ $movimiento->asignadoA->name ?? 'N/A' }}

                            </x-table.cell>

                        @endif

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Registrado</span>

                            {{ $movimiento->created_at }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="font-semibold">@if($movimiento->actualizadoPor != null)Actualizado por: {{$movimiento->actualizadoPor->name}} @else Actualizado: @endif</span> <br>

                            {{ $movimiento->updated_at }}

                        </x-table.cell>

                        @if (!auth()->user()->hasRole('Administrador'))

                            @if (auth()->user()->hasRole(['Supervisor propiedad', 'Supervisor uruapan']))

                                <x-table.cell>

                                    <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Acciones</span>

                                    <div class="flex justify-center lg:justify-start gap-2">

                                        <x-button-blue
                                            wire:click="reimprimir({{  $movimiento->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="reimprimir({{  $movimiento->id }})">
                                            Reimprimir
                                        </x-button-blue>

                                        <x-button-green
                                            wire:click="finalizar({{  $movimiento->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="finalizar({{  $movimiento->id }})">
                                            Finalizar
                                        </x-button-green>

                                    </div>

                                </x-table.cell>

                            @else

                                <x-table.cell>

                                    <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Acciones</span>

                                    <div class="flex justify-center lg:justify-start gap-2">

                                        <x-button-blue
                                            wire:click="elaborar({{  $movimiento->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="elaborar({{  $movimiento->id }})">
                                            Elaborar
                                        </x-button-blue>

                                    </div>

                                </x-table.cell>

                            @endif

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

</div>

<script>

    window.addEventListener('imprimir_documento', event => {

        const documento = event.detail[0].inscripcion;

        var url = "{{ route('propiedad.inscripcion.acto', '')}}" + "/" + documento;

        window.open(url, '_blank');

    });

</script>
