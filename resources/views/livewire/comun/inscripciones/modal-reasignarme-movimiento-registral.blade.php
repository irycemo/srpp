<x-dialog-modal  wire:model="modal_reasignarme_movimiento_registral" maxWidth="sm">

    <x-slot name="title">
        Buscar tramite
    </x-slot>

    <x-slot name="content">

        <div class="flex justify-center">

            <select class="bg-white rounded-l text-sm border border-r-transparent  focus:ring-0 @error('año') border-red-500 @enderror " wire:model="año">
                @foreach ($años as $año)

                    <option value="{{ $año }}">{{ $año }}</option>

                @endforeach
            </select>

            <input type="number" placeholder="# Control" min="1" class="bg-white w-24 text-sm focus:ring-0 @error('tramite') border-red-500 @enderror " wire:model="tramite">

            <input type="number" placeholder="Usuario" min="1" class="bg-white text-sm w-20 focus:ring-0 border-l-0 rounded-r @error('usuario') border-red-500 @enderror" wire:model="usuario">

        </div>

    </x-slot>

    <x-slot name="footer">

        <x-button-red
            wire:click="$toggle('modal_reasignarme_movimiento_registral')"
            wire:loading.attr="disabled"
        >
            No
        </x-button-red>

        <x-button-blue
            class="ml-2"
            wire:click="asignarmeMovimientoRegistral"
            wire:loading.attr="disabled"
            wire:target="asignarmeMovimientoRegistral"
        >
            Asignarme
        </x-button-blue>

    </x-slot>

</x-dialog-modal>