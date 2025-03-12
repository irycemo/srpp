<div class="flex justify-center gap-3 mb-3 col-span-2 rounded-lg p-3">

    @if($sub_tipos)

        <x-input-group for="sub_tipo" label="Tipo de {{ $tipo_actor }}" :error="$errors->first('sub_tipo')" class="w-full">

            <x-input-select id="sub_tipo" wire:model="sub_tipo" class="w-full">

                <option value="">Seleccione una opción</option>

                @foreach ($sub_tipos as $tipo)

                    <option value="{{ $tipo }}">{{ $tipo }}</option>

                @endforeach

            </x-input-select>

        </x-input-group>

    @endif

    <x-input-group for="tipo_persona" label="Tipo de persona" :error="$errors->first('tipo_persona')" class="w-full">

        <x-input-select id="tipo_persona" wire:model.live="tipo_persona" class="w-full" :disabled="$editar && $persona->getKey()">

            <option value="">Seleccione una opción</option>
            <option value="MORAL">MORAL</option>
            <option value="FISICA">FISICA</option>

        </x-input-select>

    </x-input-group>

</div>

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 mb-3 col-span-2 rounded-lg p-3">

    @if($tipo_persona == 'FISICA')

        <x-input-group for="nombre" label="Nombre(s)" :error="$errors->first('nombre')" class="w-full">

            <x-input-text id="nombre" wire:model="nombre" :readonly="$editar && $persona->nombre" />

        </x-input-group>

        <x-input-group for="ap_paterno" label="Apellido paterno" :error="$errors->first('ap_paterno')" class="w-full">

            <x-input-text id="ap_paterno" wire:model="ap_paterno" :readonly="$editar && $persona->ap_paterno" />

        </x-input-group>

        <x-input-group for="ap_materno" label="Apellido materno" :error="$errors->first('ap_materno')" class="w-full">

            <x-input-text id="ap_materno" wire:model="ap_materno" :readonly="$editar && $persona->ap_materno" />

        </x-input-group>

        <div class=" col-span-3 rounded-lg">

            <x-input-group for="multiple_nombre" label="Nombre multiple (Opcional)" :error="$errors->first('multiple_nombre')" class="sm:col-span-2 lg:col-span-3">

                <textarea rows="3" class="w-full bg-white rounded text-sm" wire:model="multiple_nombre"></textarea>

            </x-input-group>

        </div>

        <x-input-group for="curp" label="CURP" :error="$errors->first('curp')" class="w-full">

            <x-input-text id="curp" wire:model="curp" :readonly="$editar && $persona->curp" />

        </x-input-group>

        <x-input-group for="fecha_nacimiento" label="Fecha de nacimiento" :error="$errors->first('fecha_nacimiento')" class="w-full">

            <x-input-text type="date" id="fecha_nacimiento" wire:model="fecha_nacimiento" />

        </x-input-group>

        <x-input-group for="estado_civil" label="Estado civil" :error="$errors->first('estado_civil')" class="w-full">

            <x-input-text id="estado_civil" wire:model="estado_civil" />

        </x-input-group>

    @elseif($tipo_persona == 'MORAL')

        <x-input-group for="razon_social" label="Razon social" :error="$errors->first('razon_social')" class="w-full">

            <x-input-text id="razon_social" wire:model="razon_social" :readonly="$editar && $persona->razon_social" />

        </x-input-group>

    @endif

    <x-input-group for="rfc" label="RFC" :error="$errors->first('rfc')" class="w-full">

        <x-input-text id="rfc" wire:model="rfc" :readonly="$editar && $persona->rfc" />

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

    @if($tipo_actor === 'propietario')

        <span class="flex items-center justify-center text-lg text-gray-700 md:col-span-3 col-span-1 sm:col-span-2">Porcentajes</span>

        <x-input-group for="porcentaje_propiedad" label="Porcentaje propiedad" :error="$errors->first('porcentaje_propiedad')" class="w-full">

            <x-input-text type="number" id="porcentaje_propiedad" wire:model.lazy="porcentaje_propiedad" />

        </x-input-group>

        <x-input-group for="porcentaje_nuda" label="Porcentaje nuda" :error="$errors->first('porcentaje_nuda')" class="w-full">

            <x-input-text type="number" id="porcentaje_nuda" wire:model.lazy="porcentaje_nuda" />

        </x-input-group>

        <x-input-group for="porcentaje_usufructo" label="Porcentaje usufructo" :error="$errors->first('porcentaje_usufructo')" class="w-full">

            <x-input-text type="number" id="porcentaje_usufructo" wire:model.lazy="porcentaje_usufructo" />

        </x-input-group>

    @elseif($tipo_actor === 'representante')

        <span class="flex items-center justify-center text-lg text-gray-700 md:col-span-3 col-span-1 sm:col-span-2" >Representados</span>

        <div class="md:col-span-3 col-span-1 sm:col-span-2">

            <div class="flex space-x-4 items-center">

                <Label>Seleccione los representados</Label>

            </div>

            <div
                x-data = "{ model: @entangle('representados') }"
                x-init =
                "
                    select2 = $($refs.select)
                        .select2({
                            placeholder: 'Propietarios y transmitentes',
                            width: '100%',
                        })

                    select2.on('change', function(){
                        $wire.set('representados', $(this).val())
                    })

                    select2.on('keyup', function(e) {
                        if (e.keyCode === 13){
                            $wire.set('representados', $('.select2').val())
                        }
                    });

                    $watch('model', (value) => {
                        select2.val(value).trigger('change');
                    });

                    Livewire.on('recargarActores', function(e) {

                        var newOption = new Option(e[0].description, e[0].id, false, false);

                        $($refs.select).append(newOption).trigger('change');

                    });

                    Livewire.on('cargarSeleccion', function(e) {

                        $($refs.select).trigger('change');

                    });

                "
                wire:ignore>

                <select
                    class="bg-white rounded text-sm w-full z-50"
                    wire:model.live="representados"
                    x-ref="select"
                    multiple="multiple">

                    @if($predio)

                        @foreach ($predio->propietarios() as $propietario)

                            <option value="{{ $propietario->id }}">{{ $propietario->persona->nombre }} {{ $propietario->persona->ap_paterno }} {{ $propietario->persona->ap_materno }} {{ $propietario->persona->razon_social }}</option>

                        @endforeach

                        @foreach ($predio->transmitentes() as $transmitente)

                            <option value="{{ $transmitente->id }}">{{ $transmitente->persona->nombre }} {{ $transmitente->persona->ap_paterno }} {{ $transmitente->persona->ap_materno }} {{ $transmitente->persona->razon_social }}</option>

                        @endforeach

                    @endif

                </select>

            </div>

            <div>

                @error('representados') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

            </div>

        </div>

    @endif

</div>
