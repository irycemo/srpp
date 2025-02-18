<div>

    @for ($i = 0; $i < $certificacion->numero_paginas; $i++)

        <div class="flex gap-3 justify-center items-center">

            <x-input-group for="propietarios.{{ $i }}.nombre" label="Nombre" :error="$errors->first('propietarios.'. $i . '.nombre')" class="w-full">

                <x-input-text id="propietarios.{{ $i }}.nombre" wire:model.lazy="propietarios.{{ $i }}.nombre" />

            </x-input-group>

            <x-input-group for="propietarios.{{ $i }}.ap_paterno" label="Apellido paterno" :error="$errors->first('propietarios.'. $i .'.ap_paterno')" class="w-full">

                <x-input-text id="propietarios.{{ $i }}.ap_paterno" wire:model.lazy="propietarios.{{ $i }}.ap_paterno" />

            </x-input-group>

            <x-input-group for="propietarios.{{ $i }}.ap_materno" label="Apellido materno" :error="$errors->first('propietarios.'. $i .'.ap_materno')" class="w-full">

                <x-input-text id="propietarios.{{ $i }}.ap_materno" wire:model.lazy="propietarios.{{ $i }}.ap_materno" />

            </x-input-group>

        </div>

    @endfor

    <div class="flex gap-3 justify-center items-center mt-4">

        @error('propietarios') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

    </div>

</div>
