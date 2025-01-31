<div>

    <div class="mb-2 flex justify-end">

        <x-button-blue wire:click="abrirModal">Agregar actor</x-button-blue>

    </div>

    <x-dialog-modal wire:model="modal">

        <x-slot name="title">Nuevo actor</x-slot>

        <x-slot name="content">

            @include('livewire.comun.actores.modal-content-search')

            @if($flag_agregar)

                @include('livewire.comun.actores.modal-content-form')

            @else

                @include('livewire.comun.actores.modal-content-table')

            @endif

        </x-slot>

        <x-slot name="footer">

            @include('livewire.comun.actores.modal-footer')

        </x-slot>

    </x-dialog-modal>

</div>
