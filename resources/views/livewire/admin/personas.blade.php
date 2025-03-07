<div>

    <x-header>Personas</x-header>

    <div class="bg-white p-4">

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 mb-3 col-span-2 rounded-lg w-full lg:w-1/2 mx-auto">

            <x-input-group for="nombre" label="Nombre(s)" :error="$errors->first('nombre')" class="w-full">

                <x-input-text id="nombre" wire:model="nombre"/>

            </x-input-group>

            <x-input-group for="ap_paterno" label="Apellido paterno" :error="$errors->first('ap_paterno')" class="w-full">

                <x-input-text id="ap_paterno" wire:model="ap_paterno"/>

            </x-input-group>

            <x-input-group for="ap_materno" label="Apellido materno" :error="$errors->first('ap_materno')" class="w-full">

                <x-input-text id="ap_materno" wire:model="ap_materno"/>

            </x-input-group>

            <x-input-group for="rfc" label="RFC" :error="$errors->first('rfc')" class="w-full">

                <x-input-text id="rfc" wire:model="rfc"/>

            </x-input-group>

            <x-input-group for="curp" label="CURP" :error="$errors->first('curp')" class="w-full">

                <x-input-text id="curp" wire:model="curp"/>

            </x-input-group>

            <x-input-group for="razon_social" label="Razon social" :error="$errors->first('razon_social')" class="w-full">

                <x-input-text id="razon_social" wire:model="razon_social" />

            </x-input-group>

        </div>

        <div class="felx justify-center">

            <x-button-blue
                wire:click="buscar"
                wire:target="buscar"
                wire:loading.attr="disabled"
                class="mx-auto">
                Buscar persona
            </x-button-blue>

        </div>

    </div>

</div>
