<x-dialog-modal wire:model="modal_documento_entrada" maxWidth="sm">

    <x-slot name="title">

        Subir archivo

    </x-slot>

    <x-slot name="content">

        <x-filepond::upload wire:model="documento_entrada_pdf" :accepted-file-types="['application/pdf']"/>

        <div>

            @error('documento_entrada_pdf') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

        </div>

    </x-slot>

    <x-slot name="footer">

        <div class="flex gap-3">

            <x-button-blue
                wire:click="guardarDocumentoEntradaPdf"
                wire:loading.attr="disabled"
                wire:target="guardarDocumentoEntradaPdf">

                <img wire:loading wire:target="guardarDocumentoEntradaPdf" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                <span>Guardar</span>

            </x-button-blue>

            <x-button-red
                wire:click="$toggle('modal_documento_entrada')"
                wire:loading.attr="disabled"
                wire:target="$toggle('modal_documento_entrada')"
                type="button">

                <span>Cerrar</span>

            </x-button-red>

        </div>

    </x-slot>

</x-dialog-modal>