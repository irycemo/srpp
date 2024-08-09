<div class="p-4 sm:max-w-md md:max-w-xl lg:max-w-2xl">

    <div class="text-lg text-gray-700 mb-3">

        @if($editar)
            Editar {{ $title }}
        @else
            Nuevo {{ $title }}
        @endif

    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 mb-3 col-span-2 rounded-lg p-3">

        <x-input-group for="persona.tipo" label="Tipo de persona" :error="$errors->first('persona.tipo')" class="w-full">

            <x-input-select id="persona.tipo" wire:model.live="persona.tipo" class="w-full">

                <option value="">Seleccione una opción</option>
                <option value="MORAL">MORAL</option>
                <option value="FISICA">FISICA</option>

            </x-input-select>

        </x-input-group>

        @if($persona->tipo == 'FISICA')

            <x-input-group for="persona.nombre" label="Nombre(s)" :error="$errors->first('persona.nombre')" class="w-full">

                <x-input-text id="persona.nombre" wire:model="persona.nombre" />

            </x-input-group>

            <x-input-group for="persona.ap_paterno" label="Apellido paterno" :error="$errors->first('persona.ap_paterno')" class="w-full">

                <x-input-text id="persona.ap_paterno" wire:model="persona.ap_paterno" />

            </x-input-group>

            <x-input-group for="persona.ap_materno" label="Apellido materno" :error="$errors->first('persona.ap_materno')" class="w-full">

                <x-input-text id="persona.ap_materno" wire:model="persona.ap_materno" />

            </x-input-group>

            <x-input-group for="persona.curp" label="CURP" :error="$errors->first('persona.curp')" class="w-full">

                <x-input-text id="persona.curp" wire:model="persona.curp" />

            </x-input-group>

            <x-input-group for="persona.fecha_nacimiento" label="Fecha de nacimiento" :error="$errors->first('persona.fecha_nacimiento')" class="w-full">

                <x-input-text type="date" id="persona.fecha_nacimiento" wire:model="persona.fecha_nacimiento" />

            </x-input-group>

            <x-input-group for="persona.estado_civil" label="Estado civil" :error="$errors->first('persona.estado_civil')" class="w-full">

                <x-input-text id="persona.estado_civil" wire:model="persona.estado_civil" />

            </x-input-group>

        @elseif($persona->tipo == 'MORAL')

            <x-input-group for="persona.razon_social" label="Razon social" :error="$errors->first('persona.razon_social')" class="w-full">

                <x-input-text id="persona.razon_social" wire:model="persona.razon_social" />

            </x-input-group>

        @endif

        <x-input-group for="persona.rfc" label="RFC" :error="$errors->first('persona.rfc')" class="w-full">

            <x-input-text id="persona.rfc" wire:model="persona.rfc" />

        </x-input-group>

        <x-input-group for="persona.nacionalidad" label="Nacionalidad" :error="$errors->first('persona.nacionalidad')" class="w-full">

            <x-input-text id="persona.nacionalidad" wire:model="persona.nacionalidad" />

        </x-input-group>

        <span class="flex items-center justify-center text-lg text-gray-700 md:col-span-3 col-span-1 sm:col-span-2">Domicilio</span>

        <x-input-group for="persona.cp" label="Código postal" :error="$errors->first('persona.cp')" class="w-full">

            <x-input-text type="number" id="persona.cp" wire:model="persona.cp" />

        </x-input-group>

        <x-input-group for="persona.entidad" label="Entidad" :error="$errors->first('persona.entidad')" class="w-full">

            <x-input-text id="persona.entidad" wire:model="persona.entidad" />

        </x-input-group>

        <x-input-group for="persona.municipio" label="Municipio" :error="$errors->first('persona.municipio')" class="w-full">

            <x-input-text id="persona.municipio" wire:model="persona.municipio" />

        </x-input-group>

        <x-input-group for="persona.colonia" label="Colonia" :error="$errors->first('persona.colonia')" class="w-full">

            <x-input-text id="persona.colonia" wire:model="persona.colonia" />

        </x-input-group>

        <x-input-group for="persona.calle" label="Calle" :error="$errors->first('persona.calle')" class="w-full">

            <x-input-text id="persona.calle" wire:model="persona.calle" />

        </x-input-group>

        <x-input-group for="persona.numero_exterior" label="Número exterior" :error="$errors->first('persona.numero_exterior')" class="w-full">

            <x-input-text id="persona.numero_exterior" wire:model="persona.numero_exterior" />

        </x-input-group>

        <x-input-group for="persona.numero_interior" label="Número interior" :error="$errors->first('persona.numero_interior')" class="w-full">

            <x-input-text id="persona.numero_interior" wire:model="persona.numero_interior" />

        </x-input-group>

    </div>

    <div class="bg-gray-100 p-3">

        <div class="flex justify-end gap-3">

            @if($crear)

                <x-button-blue
                    wire:click="guardar"
                    wire:loading.attr="disabled"
                    wire:target="guardar">

                    <img wire:loading wire:target="guardar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <span>Guardar</span>
                </x-button-blue>

            @elseif($editar)

                <x-button-blue
                    wire:click="actualizar"
                    wire:loading.attr="disabled"
                    wire:target="actualizar">

                    <img wire:loading wire:target="actualizar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <span>Actualizar</span>
                </x-button-blue>

            @endif

            <x-button-red
                wire:click="$dispatch('closeModal')"
                wire:loading.attr="disabled"
                wire:target="$dispatch('closeModal')"
                type="button">
                Cerrar
            </x-button-red>

        </div>

    </div>

</div>
