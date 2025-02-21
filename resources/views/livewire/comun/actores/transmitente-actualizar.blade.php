<div>

    <x-button-blue wire:click="abrirModal">Actualizar</x-button-blue>

    <x-dialog-modal wire:model="modal">

        <x-slot name="title">Actualizar transmitente</x-slot>

        <x-slot name="content">

            @include('livewire.comun.actores.modal-content-form')

        </x-slot>

        <x-slot name="footer">

            @include('livewire.comun.actores.modal-footer')

        </x-slot>

    </x-dialog-modal>

</div>
