<x-dialog-modal  wire:model="modal_recibir_documento" maxWidth="sm">

    <x-slot name="title">
        Recibir documentación
    </x-slot>

    <x-slot name="content">

        <x-input-group for="contraseña" label="Contraseña" :error="$errors->first('contraseña')" class="w-full">

            <x-input-text type="password" id="contraseña" wire:model="contraseña" />

        </x-input-group>

    </x-slot>

    <x-slot name="footer">

        <x-button-red
            wire:click="$toggle('modal_recibir_documento')"
            wire:loading.attr="disabled"
        >
            No
        </x-button-red>

        <x-button-blue
            class="ml-2"
            wire:click="recibirDocumentacion"
            wire:loading.attr="disabled"
            wire:target="recibirDocumentacion"
        >
            Recibir
        </x-button-blue>

    </x-slot>

</x-dialog-modal >