<div class="grid grid-cols-3 gap-3">

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 mb-3 col-span-2 bg-white rounded-lg p-3">

        <span class="flex items-center justify-center text-lg text-gray-700 col-span-3">Ubicación del predio</span>

        <x-input-group for="tipo_vialidad" label="Tipo de vialidad" :error="$errors->first('tipo_vialidad')" class="w-full">

            <x-input-text id="tipo_vialidad" wire:model="tipo_vialidad" />

        </x-input-group>

        <x-input-group for="tipo_asentamiento" label="Tipo de asentamiento" :error="$errors->first('tipo_asentamiento')" class="w-full">

            <x-input-text id="tipo_asentamiento" wire:model="tipo_asentamiento" />

        </x-input-group>

        <x-input-group for="nombre_vialidad" label="Nombre de la vialidad" :error="$errors->first('nombre_vialidad')" class="w-full">

            <x-input-text id="nombre_vialidad" wire:model="nombre_vialidad" />

        </x-input-group>

        <x-input-group for="nombre_asentamiento" label="Nombre del asentamiento" :error="$errors->first('nombre_asentamiento')" class="w-full">

            <x-input-text id="nombre_asentamiento" wire:model="nombre_asentamiento" />

        </x-input-group>

        <x-input-group for="numero_exterior" label="Número exterior" :error="$errors->first('numero_exterior')" class="w-full">

            <x-input-text id="numero_exterior" wire:model="numero_exterior" />

        </x-input-group>

        <x-input-group for="numero_exterior_2" label="Número exterior 2" :error="$errors->first('numero_exterior_2')" class="w-full">

            <x-input-text id="numero_exterior_2" wire:model="numero_exterior_2" />

        </x-input-group>

        <x-input-group for="numero_adicional" label="Número adicional" :error="$errors->first('numero_adicional')" class="w-full">

            <x-input-text id="numero_adicional" wire:model="numero_adicional" />

        </x-input-group>

        <x-input-group for="numero_adicional_2" label="Número adicional 2" :error="$errors->first('numero_adicional_2')" class="w-full">

            <x-input-text id="numero_adicional_2" wire:model="numero_adicional_2" />

        </x-input-group>

        <x-input-group for="numero_interior" label="Número interior" :error="$errors->first('numero_interior')" class="w-full">

            <x-input-text id="numero_interior" wire:model="numero_interior" />

        </x-input-group>

        <x-input-group for="lote" label="Lote" :error="$errors->first('lote')" class="w-full">

            <x-input-text id="lote" wire:model="lote" />

        </x-input-group>

        <x-input-group for="manzana_ubicacion" label="Manzana" :error="$errors->first('manzana_ubicacion')" class="w-full">

            <x-input-text id="manzana_ubicacion" wire:model="manzana_ubicacion" />

        </x-input-group>

        <x-input-group for="codigo_postal" label="Código postal" :error="$errors->first('codigo_postal')" class="w-full">

            <x-input-text id="codigo_postal" wire:model="codigo_postal" />

        </x-input-group>

        <x-input-group for="lote_fraccionador" label="Lote del fraccionador" :error="$errors->first('lote_fraccionador')" class="w-full">

            <x-input-text id="lote_fraccionador" wire:model="lote_fraccionador" />

        </x-input-group>

        <x-input-group for="manzana_fraccionador" label="Manzana del fraccionador" :error="$errors->first('manzana_fraccionador')" class="w-full">

            <x-input-text id="manzana_fraccionador" wire:model="manzana_fraccionador" />

        </x-input-group>

        <x-input-group for="etapa_fraccionador" label="Etapa del fraccionador" :error="$errors->first('etapa_fraccionador')" class="w-full">

            <x-input-text id="etapa_fraccionador" wire:model="etapa_fraccionador" />

        </x-input-group>

        <x-input-group for="nombre_edificio" label="Nombre del edificio" :error="$errors->first('nombre_edificio')" class="w-full">

            <x-input-text id="nombre_edificio" wire:model="nombre_edificio" />

        </x-input-group>

        <x-input-group for="clave_edificio" label="Clave del edificio" :error="$errors->first('clave_edificio')" class="w-full">

            <x-input-text id="clave_edificio" wire:model="clave_edificio" />

        </x-input-group>

        <x-input-group for="departamento_edificio" label="Departamento del edificio" :error="$errors->first('departamento_edificio')" class="w-full">

            <x-input-text id="departamento_edificio" wire:model="departamento_edificio" />

        </x-input-group>

        <x-input-group for="municipio_ubicacion" label="Municipio" :error="$errors->first('municipio_ubicacion')" class="w-full">

            <x-input-text id="municipio_ubicacion" wire:model="municipio_ubicacion" />

        </x-input-group>

        <x-input-group for="ciudad" label="Ciudad" :error="$errors->first('ciudad')" class="w-full">

            <x-input-text id="ciudad" wire:model="ciudad" />

        </x-input-group>

        <x-input-group for="localidad_ubicacion" label="Localidad" :error="$errors->first('localidad_ubicacion')" class="w-full">

            <x-input-text id="localidad_ubicacion" wire:model="localidad_ubicacion" />

        </x-input-group>

        <x-input-group for="poblado" label="Poblado" :error="$errors->first('poblado')" class="w-full">

            <x-input-text id="poblado" wire:model="poblado" />

        </x-input-group>

        <x-input-group for="ejido" label="Ejido" :error="$errors->first('ejido')" class="w-full">

            <x-input-text id="ejido" wire:model="ejido" />

        </x-input-group>

        <x-input-group for="parcela" label="Parcela" :error="$errors->first('parcela')" class="w-full">

            <x-input-text id="parcela" wire:model="parcela" />

        </x-input-group>

        <x-input-group for="solar" label="Solar" :error="$errors->first('solar')" class="w-full">

            <x-input-text id="solar" wire:model="solar" />

        </x-input-group>

    </div>

    <div class="bg-white rounded-lg p-2 mb-3">

        <span class="flex items-center justify-center text-lg text-gray-700">Información de la base de datos</span>

    </div>

</div>

<div class=" flex justify-end items-center bg-white rounded-lg p-2">

    <x-button-blue
        wire:click="guardarUbicacionPredio"
        wire:loading.attr="disabled"
        wire:target="guardarUbicacionPredio">

        <img wire:loading wire:target="guardarUbicacionPredio" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
        Guardar y continuar
    </x-button-blue>

</div>
