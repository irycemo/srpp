<div>

    <x-button-gray
        wire:click="agregarGravamen"
        wire:loading.attr="disabled"
        wire:target="agregarGravamen">

        <img wire:loading wire:target="agregarGravamen" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
        Agregar gravamen
    </x-button-gray>

</div>
