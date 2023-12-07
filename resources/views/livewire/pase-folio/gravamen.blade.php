<div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

        <div class=" gap-3 mb-3 col-span-2 bg-white rounded-lg p-3 shadow-lg">

            <span class="flex items-center justify-center text-lg text-gray-700 mb-5">Gravamenes</span>

            <div class="flex justify-end mb-2">

                <x-button-gray
                        wire:click="agregarGravamen"
                        wire:loading.attr="disabled"
                        wire:target="agregarGravamen">

                        <img wire:loading wire:target="agregarGravamen" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                        Agregar gravamen
                </x-button-gray>

            </div>

            <x-table>

                <x-slot name="head">
                    <x-table.heading >Acto contenido</x-table.heading>
                    <x-table.heading >Tipo</x-table.heading>
                    <x-table.heading >Tomo</x-table.heading>
                    <x-table.heading >Registro</x-table.heading>
                    <x-table.heading >Distrito</x-table.heading>
                    <x-table.heading ></x-table.heading>
                </x-slot>

                <x-slot name="body">

                    @if($gravamenes)

                        @foreach ($gravamenes as $gravamen)

                            <x-table.row >

                                <x-table.cell>{{ $gravamen->acto_contenido }}</x-table.cell>
                                <x-table.cell>{{ $gravamen->tipo }}</x-table.cell>
                                <x-table.cell>{{ $gravamen->movimientoRegistral->tomo }}</x-table.cell>
                                <x-table.cell>{{ $gravamen->movimientoRegistral->registro }}</x-table.cell>
                                <x-table.cell>{{ $gravamen->movimientoRegistral->distrito }}</x-table.cell>
                                <x-table.cell>
                                    <div class="flex items-center gap-3">
                                        <x-button-blue
                                            wire:click="$dispatch('openModal', { component: 'pase-folio.modal-gravamen', arguments: { editar: true, gravamen: {{ $gravamen->id }}, movimientoRegistral: {{ $gravamen->movimientoRegistral->id }} } } )"
                                            wire:loading.attr="disabled"
                                        >
                                            Editar
                                        </x-button-blue>
                                        <x-button-red
                                            wire:click="abrirModalBorrar({{ $gravamen->id }})"
                                            wire:loading.attr="disabled">
                                            Borrar
                                        </x-button-red>
                                    </div>
                                </x-table.cell>

                            </x-table.row>

                        @endforeach

                    @endif

                </x-slot>

                <x-slot name="tfoot"></x-slot>

            </x-table>

        </div>

        <div class="bg-white rounded-lg p-2 mb-3 shadow-lg">

            <span class="flex items-center justify-center text-lg text-gray-700">Información de la base de datos</span>

        </div>

    </div>

    <x-confirmation-modal wire:model="modalBorrar" maxWidth="sm">

        <x-slot name="title">
            Eliminar gravamen
        </x-slot>

        <x-slot name="content">
            ¿Esta seguro que desea eliminar el gravamen? No sera posible recuperar la información.
        </x-slot>

        <x-slot name="footer">

            <x-secondary-button
                wire:click="$toggle('modalBorrar')"
                wire:loading.attr="disabled"
            >
                No
            </x-secondary-button>

            <x-danger-button
                class="ml-2"
                wire:click="borrar"
                wire:loading.attr="disabled"
                wire:target="borrar"
            >
                Borrar
            </x-danger-button>

        </x-slot>

    </x-confirmation-modal>

</div>
