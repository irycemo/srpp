<div class="flex gap-3">

    @if($persona->getKey())

        <x-button-blue
            wire:click="actualizar"
            wire:loading.attr="disabled"
            wire:target="actualizar">

            <img wire:loading wire:target="actualizar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

            <span>Actualizar</span>
        </x-button-blue>

    @endif

    @if($flag_agregar)

        <x-button-blue
            wire:click="guardar"
            wire:loading.attr="disabled"
            wire:target="guardar">

            <img wire:loading wire:target="guardar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

            <span>Agregar</span>
        </x-button-blue>

    @endif

    <x-button-red
        wire:click="$toggle('modal')"
        wire:loading.attr="disabled"
        wire:target="$toggle('modal')"
        type="button">
        Cerrar
    </x-button-red>

</div>
