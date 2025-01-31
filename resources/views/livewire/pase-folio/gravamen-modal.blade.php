<div>

    <x-button-gray
        wire:click="agregarGravamen"
        wire:loading.attr="disabled"
        wire:target="agregarGravamen">

        <img wire:loading wire:target="agregarGravamen" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
        Agregar gravamen
    </x-button-gray>

    <x-dialog-modal wire:model="modal">

        <x-slot name="title">

            @if($editar)
                Editar Gravamen
            @else
                Nuevo Gravamen
            @endif

        </x-slot>

        <x-slot name="content">

            @livewire('comun.actores.deudor-crear', ['sub_tipos' => $actores, 'modelo' => $folioReal])

        </x-slot>

        <x-slot name="footer">

            <div class="flex gap-3">

                <x-button-blue
                    wire:click="inactivar"
                    wire:loading.attr="disabled"
                    wire:target="inactivar">

                    <img wire:loading wire:target="inactivar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <span>Ingresar contraseña</span>
                </x-button-blue>

                <x-button-red
                    wire:click="$toggle('modalInactivar')"
                    wire:loading.attr="disabled"
                    wire:target="$toggle('modalInactivar')"
                    type="button">
                    Cerrar
                </x-button-red>

            </div>

        </x-slot>

    </x-dialog-modal>

</div>
