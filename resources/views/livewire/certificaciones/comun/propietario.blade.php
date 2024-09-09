<div class="flex gap-3 justify-center items-center">

    <x-input-group for="nombre" label="Nombre" :error="$errors->first('nombre')" class="w-full">

        <x-input-text id="nombre" wire:model.live.debounce="nombre" />

    </x-input-group>

    <x-input-group for="ap_paterno" label="Apellido paterno" :error="$errors->first('ap_paterno')" class="w-full">

        <x-input-text id="ap_paterno" wire:model.live.debounce="ap_paterno" />

    </x-input-group>

    <x-input-group for="ap_materno" label="Apellido materno" :error="$errors->first('ap_materno')" class="w-full">

        <x-input-text id="ap_materno" wire:model.live.debounce="ap_materno" />

    </x-input-group>

</div>
