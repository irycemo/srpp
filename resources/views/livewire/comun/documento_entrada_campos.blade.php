<x-h4>Documento de entrada</x-h4>

<div class="grid grid-cols-1 md:grid-cols-3 gap-3 bg-white p-4 rounded-lg mb-3 shadow-md">

    <x-input-group for="tipo_documento" label="Tipo de documento" :error="$errors->first('tipo_documento')" class="w-full">

        <x-input-select id="tipo_documento" wire:model.live="tipo_documento" class="w-full">

            <option value="">Seleccione una opción</option>

            @foreach ($documentos_entrada as $key => $value)

                <option value="{{ $value }}">{{ $value }}</option>

            @endforeach

        </x-input-select>

    </x-input-group>

    <x-input-group for="autoridad_cargo" label="Autoridad cargo" :error="$errors->first('autoridad_cargo')" class="w-full">

        <x-input-select id="autoridad_cargo" wire:model.live="autoridad_cargo" class="w-full">

            <option value="">Seleccione una opción</option>

            @foreach ($cargos_autoridad as $key => $value)

                <option value="{{ $value }}">{{ $value }}</option>

            @endforeach

        </x-input-select>

    </x-input-group>

    <x-input-group for="autoridad_nombre" label="Nombre de la autoridad" :error="$errors->first('autoridad_nombre')" class="w-full">

        <x-input-text id="autoridad_nombre" wire:model="autoridad_nombre" />

    </x-input-group>

    <x-input-group for="numero_documento" label="{{ $label_numero_documento }}" :error="$errors->first('numero_documento')" class="w-full">

        <x-input-text id="numero_documento" wire:model="numero_documento" />

    </x-input-group>

    <x-input-group for="fecha_emision" label="Fecha de emisión" :error="$errors->first('fecha_emision')" class="w-full">

        <x-input-text type="date" id="fecha_emision" wire:model="fecha_emision" />

    </x-input-group>

    <x-input-group for="procedencia" label="Dependencia" :error="$errors->first('procedencia')" class="w-full">

        <x-input-text id="procedencia" wire:model="procedencia" />

    </x-input-group>

</div>