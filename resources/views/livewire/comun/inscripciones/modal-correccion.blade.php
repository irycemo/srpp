<x-confirmation-modal wire:model="modalCorreccion" maxWidth="sm">

    <x-slot name="title">
        Corrección
    </x-slot>

    <x-slot name="content">
        ¿Esta seguro que desea enviar el movimiento registral a corrección? Si el movimiento registral ha generado nuevos folios reales estos serán eliminados junto con sus movimientos registrales. La información eliminada no podra ser recuperada.
    </x-slot>

    <x-slot name="footer">

        <x-secondary-button
            wire:click="$toggle('modalCorreccion')"
            wire:loading.attr="disabled"
        >
            No
        </x-secondary-button>

        <x-danger-button
            class="ml-2"
            wire:click="correccion"
            wire:loading.attr="disabled"
            wire:target="correccion"
        >
            Si
        </x-danger-button>

    </x-slot>

</x-confirmation-modal>