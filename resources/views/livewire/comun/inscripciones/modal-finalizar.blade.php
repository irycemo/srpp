<x-confirmation-modal wire:model="modalFinalizar" maxWidth="sm">

    <x-slot name="title">
        Finalizar movimiento registral
    </x-slot>

    <x-slot name="content">
        ¿Esta seguro que desea finalizar el movimiento registral?
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
            wire:click="finalizar"
            wire:loading.attr="disabled"
            wire:target="finalizar"
        >
            Finalizar
        </x-danger-button>

    </x-slot>

</x-confirmation-modal>