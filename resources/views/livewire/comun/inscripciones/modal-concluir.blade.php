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