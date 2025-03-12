<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 mb-3 col-span-2 rounded-lg p-3">

    <x-input-group for="nombre" label="Nombre(s)" :error="$errors->first('nombre')" class="w-full">

        <x-input-text id="nombre" wire:model="nombre" :readonly="$flag_agregar"/>

    </x-input-group>

    <x-input-group for="ap_paterno" label="Apellido paterno" :error="$errors->first('ap_paterno')" class="w-full">

        <x-input-text id="ap_paterno" wire:model="ap_paterno" :readonly="$flag_agregar"/>

    </x-input-group>

    <x-input-group for="ap_materno" label="Apellido materno" :error="$errors->first('ap_materno')" class="w-full">

        <x-input-text id="ap_materno" wire:model="ap_materno" :readonly="$flag_agregar"/>

    </x-input-group>

    <x-input-group for="rfc" label="RFC" :error="$errors->first('rfc')" class="w-full">

        <x-input-text id="rfc" wire:model="rfc" :readonly="$flag_agregar"/>

    </x-input-group>

    <x-input-group for="curp" label="CURP" :error="$errors->first('curp')" class="w-full">

        <x-input-text id="curp" wire:model="curp" :readonly="$flag_agregar"/>

    </x-input-group>

    <x-input-group for="razon_social" label="Razon social" :error="$errors->first('razon_social')" class="w-full">

        <x-input-text id="razon_social" wire:model="razon_social" :readonly="$flag_agregar" />

    </x-input-group>

    <div class="sm:col-span-2 lg:col-span-3 flex gap-3">

        <x-button-blue
            wire:click="buscarPersonas"
            wire:target="buscarPersonas"
            wire:loading.attr="disabled"
            class="w-full">
            Buscar persona
        </x-button-blue>

        <x-button-blue class="w-full" wire:click="agregarNuevo">Agregar nuevo</x-button-blue>

    </div>

</div>
