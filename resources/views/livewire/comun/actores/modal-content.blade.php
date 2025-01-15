<div class="flex justify-center gap-3 mb-3 col-span-2 rounded-lg p-3">

    <x-input-group for="sub_tipo" label="Tipo de {{ $tipo_actor }}" :error="$errors->first('sub_tipo')" class="w-full">

        <x-input-select id="sub_tipo" wire:model="sub_tipo" class="w-full">

            <option value="">Seleccione una opción</option>

            @foreach ($sub_tipos as $tipo)

                <option value="{{ $tipo }}">{{ $tipo }}</option>

            @endforeach

        </x-input-select>

    </x-input-group>

    <x-input-group for="tipo_persona" label="Tipo de persona" :error="$errors->first('tipo_persona')" class="w-full">

        <x-input-select id="tipo_persona" wire:model.live="tipo_persona" class="w-full" :disabled="$editar && $actor->getKey()">

            <option value="">Seleccione una opción</option>
            <option value="MORAL">MORAL</option>
            <option value="FISICA">FISICA</option>

        </x-input-select>

    </x-input-group>

</div>

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 mb-3 col-span-2 rounded-lg p-3">

    @if($tipo_persona == 'FISICA')

        <x-input-group for="nombre" label="Nombre(s)" :error="$errors->first('nombre')" class="w-full">

            <x-input-text id="nombre" wire:model="nombre" :readonly="$editar && $actor->persona->nombre" />

        </x-input-group>

        <x-input-group for="ap_paterno" label="Apellido paterno" :error="$errors->first('ap_paterno')" class="w-full">

            <x-input-text id="ap_paterno" wire:model="ap_paterno" :readonly="$editar && $actor->persona->ap_paterno" />

        </x-input-group>

        <x-input-group for="ap_materno" label="Apellido materno" :error="$errors->first('ap_materno')" class="w-full">

            <x-input-text id="ap_materno" wire:model="ap_materno" :readonly="$editar && $actor->persona->ap_materno" />

        </x-input-group>

        <div class=" col-span-3 rounded-lg">

            <x-input-group for="multiple_nombre" label="Nombre multiple (Opcional)" :error="$errors->first('multiple_nombre')" class="sm:col-span-2 lg:col-span-3">

                <textarea rows="3" class="w-full bg-white rounded text-sm" wire:model="multiple_nombre"></textarea>

            </x-input-group>

        </div>

        <x-input-group for="curp" label="CURP" :error="$errors->first('curp')" class="w-full">

            <x-input-text id="curp" wire:model="curp" :readonly="$editar && $actor->persona->curp" />

        </x-input-group>

        <x-input-group for="fecha_nacimiento" label="Fecha de nacimiento" :error="$errors->first('fecha_nacimiento')" class="w-full">

            <x-input-text type="date" id="fecha_nacimiento" wire:model="fecha_nacimiento" />

        </x-input-group>

        <x-input-group for="estado_civil" label="Estado civil" :error="$errors->first('estado_civil')" class="w-full">

            <x-input-text id="estado_civil" wire:model="estado_civil" />

        </x-input-group>

    @elseif($tipo_persona == 'MORAL')

        <x-input-group for="razon_social" label="Razon social" :error="$errors->first('razon_social')" class="w-full">

            <x-input-text id="razon_social" wire:model="razon_social" :readonly="$editar && $actor->persona->razon_social" />

        </x-input-group>

    @endif

    <x-input-group for="rfc" label="RFC" :error="$errors->first('rfc')" class="w-full">

        <x-input-text id="rfc" wire:model="rfc" :readonly="$editar && $actor->persona->rfc" />

    </x-input-group>

    <x-input-group for="nacionalidad" label="Nacionalidad" :error="$errors->first('nacionalidad')" class="w-full">

        <x-input-text id="nacionalidad" wire:model="nacionalidad" />

    </x-input-group>

    <span class="flex items-center justify-center text-lg text-gray-700 md:col-span-3 col-span-1 sm:col-span-2">Domicilio</span>

    <x-input-group for="cp" label="Código postal" :error="$errors->first('cp')" class="w-full">

        <x-input-text type="number" id="cp" wire:model="cp" />

    </x-input-group>

    <x-input-group for="entidad" label="Estado" :error="$errors->first('entidad')" class="w-full">

        <x-input-text id="entidad" wire:model="entidad" />

    </x-input-group>

    <x-input-group for="municipio" label="Municipio" :error="$errors->first('municipio')" class="w-full">

        <x-input-text id="municipio" wire:model="municipio" />

    </x-input-group>

    <x-input-group for="ciudad" label="Ciudad" :error="$errors->first('ciudad')" class="w-full">

        <x-input-text id="ciudad" wire:model="ciudad" />

    </x-input-group>

    <x-input-group for="colonia" label="Colonia" :error="$errors->first('colonia')" class="w-full">

        <x-input-text id="colonia" wire:model="colonia" />

    </x-input-group>

    <x-input-group for="calle" label="Calle" :error="$errors->first('calle')" class="w-full">

        <x-input-text id="calle" wire:model="calle" />

    </x-input-group>

    <x-input-group for="numero_exterior" label="Número exterior" :error="$errors->first('numero_exterior')" class="w-full">

        <x-input-text id="numero_exterior" wire:model="numero_exterior" />

    </x-input-group>

    <x-input-group for="numero_interior" label="Número interior" :error="$errors->first('numero_interior')" class="w-full">

        <x-input-text id="numero_interior" wire:model="numero_interior" />

    </x-input-group>

</div>
