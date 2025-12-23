<div wire:ignore>

    <textarea id="{{ $attributes['id'] }}" wire:model.defer="{{ $attributes['property'] }}">

    </textarea>

</div>

@push('scripts')

    <script src="https://cdn.ckeditor.com/ckeditor5/35.1.0/classic/ckeditor.js"></script>

    <script>

        document.addEventListener("livewire:init", () => {

            ClassicEditor
                .create(document.querySelector(`#{{ $attributes['id'] }}`), {
                    ckfinder: {
                        uploadUrl: "{{ route('ckImage', ['_token' => csrf_token()]) }}"
                    },
                    mediaEmbed: {
                        previewsInData:true
                    },
                })
                .then(editor => {
                    editor.model.document.on('change:data', () => {
                        @this.set(`{{ $attributes['property'] }}`, editor.getData());
                    })

                    @this.revisarContenido()

                    Livewire.on('loadInitial', (event) => {
                        editor.setData(event[0])
                    })

                    Livewire.on('reinit', () => {
                        editor.setData('', '')
                    })
                })
                .catch(error => {
                    console.error(error);
                });
        });

    </script>

@endpush