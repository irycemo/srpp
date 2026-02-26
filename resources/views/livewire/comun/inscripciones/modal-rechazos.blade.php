<x-dialog-modal  wire:model="modal_rechazos" maxWidth="sm">

    <x-slot name="title">
        Rechazos
    </x-slot>

    <x-slot name="content">

        <div class="divide-y-2 space-y-2">

            @foreach ($modelo_editar->rechazos as $rechazo)

                <div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2 my-2">

                        <strong>Motivo</strong>

                        <p>{{ $rechazo->fundamento }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2 mb-2">

                        <strong>Observaciones</strong>

                        <p>{{ $rechazo->observaciones }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2 mb-2">

                        <strong>Rechazado en</strong>

                        <p>{{ $rechazo->created_at }}</p>

                    </div>

                    <x-button-blue
                        class="w-full"
                        wire:click="reimprimirRechazo({{ $rechazo->id }})"
                        wire:loading.attr="disabled"
                        wire:target="reimprimirRechazo({{ $rechazo->id }})"
                    >
                        Reimprimir
                    </x-button-blue>

                </div>

            @endforeach

        </div>

    </x-slot>

    <x-slot name="footer">

        <x-button-red
            wire:click="$toggle('modal_rechazos')"
            wire:loading.attr="disabled"
        >
            No
        </x-button-red>

    </x-slot>

</x-dialog-modal >