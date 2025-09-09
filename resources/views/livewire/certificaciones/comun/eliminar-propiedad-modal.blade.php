<x-dialog-modal wire:model.live="modalObservaciones" maxWidth="sm">

    <x-slot name="title">

        Observaciones

    </x-slot>

    <x-slot name="content">

        <x-input-group for="motivo" label="" :error="$errors->first('motivo')">

            <textarea class="bg-white rounded text-xs w-full " rows="4" wire:model="motivo" placeholder="Se lo más especifico posible acerca del motivo por el cual se ignora la propiedad"></textarea>

        </x-input-group>

    </x-slot>

    <x-slot name="footer">

        <div class="flex gap-3">

            <x-button-blue
                wire:click="quitarPropiedad"
                wire:loading.attr="disabled"
                wire:target="quitarPropiedad">

                <img wire:loading wire:target="quitarPropiedad" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                Remover
            </x-button-blue>

            <x-button-red
                wire:click="$toggle('modalObservaciones')"
                wire:loading.attr="disabled"
                wire:target="$toggle('modalObservaciones')">
                Cerrar
            </x-button-red>

        </div>

    </x-slot>

</x-dialog-modal>