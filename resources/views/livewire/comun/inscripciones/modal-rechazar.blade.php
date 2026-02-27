<x-dialog-modal wire:model="modal_rechazar">

    <x-slot name="title">

        Rechazar

    </x-slot>

    <x-slot name="content">

        <div class="max-h-80 overflow-auto">

        @if(!$motivo_rechazo)

            @foreach ($motivos_rechazo as $key => $item)

                <div
                    wire:click="seleccionarMotivo('{{ $key }}')"
                    wire:loading.attr="disabled"
                    class="border rounded-lg text-sm mb-2 p-2 hover:bg-gray-100 cursor-pointer">

                    <p>{{ $item }}</p>

                </div>

            @endforeach

        @else

            <div class="border rounded-lg text-sm mb-2 p-2 relative pr-16">

                <span
                    wire:click="$set('motivo_rechazo', null)"
                    wire:loading.attr="disabled"
                    class="rounded-full px-2 border hover:bg-gray-700 hover:text-white absolute top-1 right-1 cursor-pointer">
                    x
                </span>

                <p>{{ $motivo_rechazo }}</p>

            </div>

        @endif

    </div>

        <x-input-group for="observaciones" label="Observaciones" :error="$errors->first('observaciones')" class="w-full">

            <textarea autofocus="false" class="bg-white rounded text-xs w-full " rows="4" wire:model="observaciones" placeholder="Se lo mas especifico posible acerca del motivo del rechazo."></textarea>

        </x-input-group>

        @error('motivo_rechazo')

            <div class="text-red-500 text-sm mt-1"> {{ $message }} </div>

        @enderror

    </x-slot>

    <x-slot name="footer">

        <div class="flex items-center justify-end space-x-3">

            <x-button-blue
                wire:click="rechazar"
                wire:loading.attr="disabled"
                wire:target="rechazar">

                <span wire:loading wire:target="rechazar" class="mr-1">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>

                Rechazar
            </x-button-blue>

            <x-button-red
                wire:click="$set('modal_rechazar',false)"
                wire:loading.attr="disabled"
                wire:target="$set('modal_rechazar',false)">
                Cerrar
            </x-button-red>

        </div>

    </x-slot>

</x-dialog-modal>