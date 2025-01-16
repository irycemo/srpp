<x-table>

    <x-slot name="head">
        <x-table.heading >Nombre / Razón social</x-table.heading>
        <x-table.heading ></x-table.heading>
    </x-slot>

    <x-slot name="body">

        @forelse ($personas as $persona)

            <x-table.row wire:key="row-{{ $persona->id }}">

                <x-table.cell>{{ $persona->nombre }} {{ $persona->ap_paterno }} {{ $persona->ap_materno }} {{ $persona->razon_social }}</x-table.cell>
                <x-table.cell>
                    <div class="flex items-center gap-3">
                        <x-button-green
                            wire:click="seleccionar({{ $persona->id }})"
                            wire:loading.attr="disabled">
                            Seleccionar
                        </x-button-green>
                    </div>
                </x-table.cell>

            </x-table.row>

        @empty

            <x-table.row>

                <x-table.cell >
                    <div class="text-center ">
                        <span class="p-4 w-full tracking-widest">Sin resultados</span>
                    </div>
                </x-table.cell>

            </x-table.row>

        @endforelse

    </x-slot>

    <x-slot name="tfoot"></x-slot>

</x-table>
