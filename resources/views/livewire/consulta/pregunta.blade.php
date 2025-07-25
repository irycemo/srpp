<div>

    <x-header>Crear o editar pregunta</x-header>

    <div class="bg-white rounded-lg shadow-xl p-4">

        <div class="w-full lg:w-1/2 mx-auto mb-5">

            <x-input-group for="titulo" label="Título" :error="$errors->first('titulo')" class="w-full mb-5">

                <x-input-text id="titulo" wire:model="titulo" />

            </x-input-group>

            <x-input-group for="categoria" label="Categoría" :error="$errors->first('categoria')" class="mb-5">

                <x-input-select id="categoria" wire:model="categoria" class="">

                    <option value="">Seleccione una opción</option>

                    @foreach ($categorias as $category)

                        <option value="{{ $category }}">{{ $category }}</option>

                    @endforeach

                </x-input-select>

            </x-input-group>

            <x-input-group for="area" label="Área" :error="$errors->first('area')" class="mb-5">

                <x-input-select id="area" wire:model="area" class="">

                    <option value="">Seleccione una opción</option>

                    @foreach ($areas as $item)

                        <option value="{{ $item }}">{{ $item }}</option>

                    @endforeach

                </x-input-select>

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

        <div class="w-full lg:w-1/2 mx-auto flex justify-end gap-4">

            @if ($pregunta)

                <x-button-green
                    wire:click="publicar">
                    Publicar
                </x-button-green>

                <x-button-blue
                    wire:click="actualizar">
                    Actualizar
                </x-button-blue>

            @else

                <x-button-blue
                    wire:click="guardar">
                    Guardar
                </x-button-blue>

            @endif

        </div>

    </div>

</div>

{{-- @push('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.js"></script>
@endpush --}}
