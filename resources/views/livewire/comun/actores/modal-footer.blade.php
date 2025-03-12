<div class="flex gap-3">

    @if($persona->getKey())

        <x-button-blue
            wire:click="actualizar"
            wire:loading.attr="disabled"
            wire:target="actualizar">

            <img wire:loading wire:target="actualizar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

            <span>Actualizar</span>
        </x-button-blue>

        <x-button-red
            wire:click="$toggle('modal')"
            wire:loading.attr="disabled"
            wire:target="$toggle('modal')"
            type="button">
            Cerrar
        </x-button-red>

    @endif

    @if($flag_agregar)

        <x-button-gray
            wire:click="resetearCampos"
            wire:loading.attr="disabled"
            wire:target="resetearCampos">

            <img wire:loading wire:target="resetearCampos" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

            <span>Buscar</span>
        </x-button-gray>

        <x-button-green
            wire:click="guardar"
            wire:loading.attr="disabled"
            wire:target="guardar">

            <img wire:loading wire:target="guardar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

            <span>Agregar</span>
        </x-button-green>

        <x-button-red
            wire:click="resetearTodo"
            wire:loading.attr="disabled"
            wire:target="resetearTodo"
            type="button">
            Cerrar
        </x-button-red>

    @endif

</div>
