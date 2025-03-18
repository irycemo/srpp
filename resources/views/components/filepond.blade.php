<div>

    @push('styles')

        <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />

    @endpush

    <div
        wire:ignore
        x-data
        x-init="
            FilePond.registerPlugin(FilePondPluginFileValidateSize);
            FilePond.registerPlugin(FilePondPluginFileValidateType);
            FilePond.setOptions({
                acceptedFileTypes: {{ isset($attributes['accept']) ? $attributes['accept'] : 'null' }},
                allowMultiple: {{ isset($attributes['multiple']) ? 'true' : 'false' }},
                allowFileSizeValidation: true,
                server: {
                    process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                        @this.upload('{{ $attributes['wire:model.live'] }}', file, load, error, progress);
                    },
                    revert: (filename, load) => {
                        @this.removeUpload('{{ $attributes['wire:model.live'] }}', filename, load);
                    }
                },
                labelFileTypeNotAllowed: 'Formato de archivo invalido',
                fileValidateTypeLabelExpectedTypes: 'Formatos validos: {allTypes}',
                labelIdle: 'Selecciona o arrastra los archivos aquí.',
                labelInvalidField: 'El campo contiene archivos invalidos',
                labelTapToCancel: 'Click para cancelar',
                labelTapToRetry: 'Click para reintentar',
                labelTapToUndo: 'Click para deshacer',
                labelFileLoading: 'Cargando',
                labelFileLoadError: 'Error al cargar',
                labelFileProcessing: 'Cargando',
                labelFileProcessingComplete: 'Carga terminada',
                labelFileProcessingAborted: 'Carga cancelada',
                labelFileProcessingError: 'Error al subir',
                labelFileRemoveError: 'Error al borrar',
                labelButtonRemoveItem:'Quitar',
                labelButtonAbortItemLoad: 'Abortar',
                labelButtonRetryItemLoad: 'Reintentar',
                labelButtonAbortItemProcessing: 'Cancelar',
                labelButtonUndoItemProcessing: 'Deshacer',
                labelButtonRetryItemProcessing: 'Reintentar',
                labelButtonProcessItem: 'Subir',
                labelMaxFileSizeExceeded: 'El archivo es demasiado grande',
                labelMaxTotalFileSizeExceeded: 'El archivo es demasiado grande',
                labelMaxFileSize: 'Tamaño máximo {filesize}',
                maxFileSize: '150MB',
                maxTotalFileSize: '150MB'
            });

            this.addEventListener('removeFiles', e => {
                Pond.removeFiles();
            })

            Pond = FilePond.create($refs.input);
        "
    >

        <input type="file" x-ref="input">

    </div>

    @push('scripts')

        <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
        <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
        <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>

    @endpush

</div>
