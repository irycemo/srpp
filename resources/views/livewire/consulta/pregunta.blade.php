<div>

    <x-header>Crear o editar pregunta</x-header>

    <div class="bg-white rounded-lg shadow-xl p-4">

        <div class="w-full lg:w-1/2 mx-auto mb-5">

            <x-input-group for="titulo" label="Título" :error="$errors->first('titulo')" class="w-full mb-5">

                <x-input-text id="titulo" wire:model="titulo" />

            </x-input-group>

            <x-ck-editor property="contenido" id="content" class="w-full"></x-ck-editor>

            {{-- <div wire:ignore>

                <input id="x" type="hidden" name="content" value="{{ $contenido }}">

                <trix-editor
                    input="x"
                    class="trix-content"
                    wire:ignore
                    x-data
                    x-init="

                        const trixEditor = $el;

                        trixEditor.addEventListener('trix-change', (event) => {
                            @this.set('contenido', event.target.value)
                        });

                        trixEditor.addEventListener('trix-attachment-add', (event) => {

                            attachment = event.attachment;

                            @this.upload(
                                'images',
                                attachment.file,
                                function(uploadedUrl){

                                    const eventName = `srpp:trix-upload-completed:${btoa(uploadedUrl)}`;

                                    const listener = function(event){

                                        attachment.setAttributes(event.detail);

                                        window.removeEventListener(eventName, listener);

                                    }

                                    window.addEventListener(eventName, listener);

                                    @this.completeUplad(uploadedUrl, eventName);

                                },
                                function(){
                                },
                                function(event){

                                    attachment.setUploadProgress(event.detail.progress);

                                }
                            )

                            console.log(trixEditor.editor.getDocument())

                        });

                        trixEditor.addEventListener('trix-attachment-remove', (event) => {

                            @this.deleteImage(event.attachment.attachment.attributes.values[0].url)

                        });

                    "
                ></trix-editor>
            </div> --}}

            @if($errors->first('contenido'))

                <div class="text-red-500 text-sm mt-1"> {{ $errors->first('contenido') }} </div>

            @endif

        </div>

        <div class="w-full lg:w-1/2 mx-auto flex justify-end">

            <x-button-blue
                wire:click="guardar">
                Guardar
            </x-button-blue>

        </div>

    </div>

</div>

{{-- @push('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.js"></script>
@endpush --}}
