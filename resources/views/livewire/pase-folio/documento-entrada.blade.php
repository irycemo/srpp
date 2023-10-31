<div class="grid grid-cols-3 gap-3">

    <div class="grid grid-cols-1 md:grid-cols-3 sm:grid-cols-2 gap-3 mb-3 col-span-2 bg-white rounded-lg p-3">

        <span class="flex items-center justify-center text-lg text-gray-700 col-span-3">Documento de entrada</span>

        <x-input-group for="tipo_documento" label="Tipo de documento" :error="$errors->first('tipo_documento')" class="w-full">

            <x-input-text id="tipo_documento" wire:model="tipo_documento" />

        </x-input-group>

        <x-input-group for="autoridad_cargo" label="Autoridad cargo" :error="$errors->first('autoridad_cargo')" class="w-full">

            <x-input-text id="autoridad_cargo" wire:model="autoridad_cargo" />

        </x-input-group>

        <x-input-group for="autoridad_nombre" label="Nombre de la autoridad" :error="$errors->first('autoridad_nombre')" class="w-full">

            <x-input-text id="autoridad_nombre" wire:model="autoridad_nombre" />

        </x-input-group>

        <x-input-group for="numero_documento" label="Número de documento" :error="$errors->first('numero_documento')" class="w-full">

            <x-input-text id="numero_documento" wire:model="numero_documento" />

        </x-input-group>

        <x-input-group for="fecha_emision" label="Fecha de emisión" :error="$errors->first('fecha_emision')" class="w-full">

            <x-input-text type="date" id="fecha_emision" wire:model="fecha_emision" />

        </x-input-group>

        <x-input-group for="fecha_inscripcion" label="Fecha de inscripción" :error="$errors->first('fecha_inscripcion')" class="w-full">

            <x-input-text type="date" id="fecha_inscripcion" wire:model="fecha_inscripcion" />

        </x-input-group>

        <x-input-group for="procedencia" label="Procedencia" :error="$errors->first('procedencia')" class="w-full">

            <x-input-text id="procedencia" wire:model="procedencia" />

        </x-input-group>

        <span class="flex items-center justify-center text-lg text-gray-700  col-span-3">Escritura</span>

        <x-input-group for="escritura_tomo" label="Tomo" :error="$errors->first('escritura_tomo')" class="w-full">

            <x-input-text type="number" id="escritura_tomo" wire:model="escritura_tomo" />

        </x-input-group>

        <x-input-group for="escritura_registro" label="Registro" :error="$errors->first('escritura_registro')" class="w-full">

            <x-input-text type="number" id="escritura_registro" wire:model="escritura_registro" />

        </x-input-group>

        <x-input-group for="escritura_distrito" label="Distrito" :error="$errors->first('escritura_distrito')" class="w-full">

            <x-input-select id="escritura_distrito" wire:model="escritura_distrito" class="w-full">

                <option value="">Seleccione una opción</option>

                @foreach ($distritos as $key => $distrito)

                    <option value="{{ $key }}">{{ $distrito }}</option>

                @endforeach

            </x-input-select>

        </x-input-group>

        <x-input-group for="escritura_fecha_inscripcion" label="Fecha de inscripcion" :error="$errors->first('escritura_fecha_inscripcion')" class="w-full">

            <x-input-text type="date" id="escritura_fecha_inscripcion" wire:model="escritura_fecha_inscripcion" />

        </x-input-group>

        <x-input-group for="escritura_fecha_escritura" label="Fecha de la escritura" :error="$errors->first('escritura_fecha_escritura')" class="w-full">

            <x-input-text type="date" id="escritura_fecha_escritura" wire:model="escritura_fecha_escritura" />

        </x-input-group>

        <x-input-group for="escritura_numero_hojas" label="Número de hojas" :error="$errors->first('escritura_numero_hojas')" class="w-full">

            <x-input-text type="number" id="escritura_numero_hojas" wire:model="escritura_numero_hojas" />

        </x-input-group>

        <x-input-group for="escritura_numero_paginas" label="Número de paginas" :error="$errors->first('escritura_numero_paginas')" class="w-full">

            <x-input-text type="number" id="escritura_numero_paginas" wire:model="escritura_numero_paginas" />

        </x-input-group>

        <x-input-group for="escritura_tipo_fedatario" label="Tipo de fedatario" :error="$errors->first('escritura_tipo_fedatario')" class="w-full">

            <x-input-text type="number" id="escritura_tipo_fedatario" wire:model="escritura_tipo_fedatario" />

        </x-input-group>

        <x-input-group for="escritura_documento_presentado" label="Documento presentado" :error="$errors->first('escritura_documento_presentado')" class="w-full">

            <x-input-text type="number" id="escritura_documento_presentado" wire:model="escritura_documento_presentado" />

        </x-input-group>

        <x-input-group for="escritura_notaria" label="Notaría" :error="$errors->first('escritura_notaria')" class="w-full">

            <x-input-text type="number" id="escritura_notaria" wire:model="escritura_notaria" />

        </x-input-group>

        <x-input-group for="escritura_nombre_notario" label="Nombre del notario" :error="$errors->first('escritura_nombre_notario')" class="w-full">

            <x-input-text id="escritura_nombre_notario" wire:model="escritura_nombre_notario" />

        </x-input-group>

        <x-input-group for="escritura_estado_notario" label="Estado del notario" :error="$errors->first('escritura_estado_notario')" class="w-full">

            <x-input-text id="escritura_estado_notario" wire:model="escritura_estado_notario" />

        </x-input-group>

        <x-input-group for="escritura_observaciones" label="Observaciones" :error="$errors->first('escritura_observaciones')" class="sm:col-span-2 lg:col-span-3">

            <textarea rows="3" class="w-full bg-white rounded" wire:model="escritura_observaciones"></textarea>

        </x-input-group>

    </div>

    <div class="bg-white rounded-lg p-2 mb-3">

        <span class="flex items-center justify-center text-lg text-gray-700">Información de la base de datos</span>

    </div>

</div>

<div class=" flex justify-end items-center bg-white rounded-lg p-2">

    <x-button-blue
        wire:click="guardarDocumentoEntrada"
        wire:loading.attr="disabled"
        wire:target="guardarDocumentoEntrada">

        <img wire:loading wire:target="guardarDocumentoEntrada" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
        Guardar y continuar
    </x-button-blue>

</div>
