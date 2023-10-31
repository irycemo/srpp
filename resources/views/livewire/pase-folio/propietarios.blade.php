
<div class="grid grid-cols-3 gap-3">

    <div class=" gap-3 mb-3 col-span-2 bg-white rounded-lg p-3">

        <span class="flex items-center justify-center text-lg text-gray-700 mb-5">Propietarios</span>

        <div class="flex justify-end mb-2">

            <x-button-gray
                    wire:click="agregarPropietario"
                    wire:loading.attr="disabled"
                    wire:target="agregarPropietario">

                    <img wire:loading wire:target="agregarPropietario" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                    Agregar propietario
            </x-button-gray>

        </div>

        <x-table>

            <x-slot name="head">
                <x-table.heading >Nombre</x-table.heading>
                <x-table.heading >Apellido paterno</x-table.heading>
                <x-table.heading >Apellido materno</x-table.heading>
                <x-table.heading >Tipo de propietario</x-table.heading>
                <x-table.heading >Porcentaje</x-table.heading>
                <x-table.heading ></x-table.heading>
            </x-slot>

            <x-slot name="body">

                @if($propiedad)

                    @foreach ($propiedad->propietarios as $propietario)

                        <x-table.row >

                            <x-table.cell>{{ $propietario->persona->nombre }}</x-table.cell>
                            <x-table.cell>{{ $propietario->persona->ap_paterno }}</x-table.cell>
                            <x-table.cell>{{ $propietario->persona->ap_materno }}</x-table.cell>
                            <x-table.cell>{{ $propietario->tipo }}</x-table.cell>
                            <x-table.cell>{{ number_format($propietario->porcentaje, 2) }}%</x-table.cell>
                            <x-table.cell>
                                <div class="flex items-center gap-3">
                                    <x-button-blue
                                        wire:click="editarPropietario({{ $propietario->id }})"
                                        wire:loading.attr="disabled"
                                    >
                                        Editar
                                    </x-button-blue>
                                    <x-button-red
                                        wire:click="borrarPropietario({{ $propietario->id }})"
                                        wire:loading.attr="disabled">
                                        Borrar
                                    </x-button-red>
                                </div>
                            </x-table.cell>

                        </x-table.row>

                    @endforeach

                @endif

            </x-slot>

            <x-slot name="tfoot"></x-slot>

        </x-table>

    </div>

    <div class="bg-white rounded-lg p-2 mb-3">

        <span class="flex items-center justify-center text-lg text-gray-700">Información de la base de datos</span>

    </div>

    <x-dialog-modal wire:model="modal">

        <x-slot name="title">

            @if($crear)
                Nuevo Propietario
            @elseif($editar)
                Editar Usuario
            @endif

        </x-slot>

        <x-slot name="content">

            {{ $errors }}

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 mb-3 col-span-2 rounded-lg p-3">

                <x-input-group for="tipo_propietario" label="Tipo de propietario" :error="$errors->first('tipo_propietario')" class="w-full">

                    <x-input-select id="tipo_propietario" wire:model="tipo_propietario" class="w-full">

                        <option value="">Seleccione una opción</option>

                        @foreach ($tipos_propietarios as $tipo)

                            <option value="{{ $tipo }}">{{ $tipo }}</option>

                        @endforeach

                    </x-input-select>

                </x-input-group>

                <x-input-group for="porcentaje" label="Porcentaje" :error="$errors->first('porcentaje')" class="w-full">

                    <x-input-text type="number" id="porcentaje" wire:model="porcentaje" />

                </x-input-group>

                <x-input-group for="tipo_persona" label="Tipo de persona" :error="$errors->first('tipo_persona')" class="w-full">

                    <x-input-select id="tipo_persona" wire:model="tipo_persona" class="w-full">

                        <option value="">Seleccione una opción</option>
                        <option value="MORAL">MORAL</option>
                        <option value="FISICA">FISICA</option>

                    </x-input-select>

                </x-input-group>

                <x-input-group for="nombre" label="Nombre" :error="$errors->first('nombre')" class="w-full">

                    <x-input-text id="nombre" wire:model="nombre" />

                </x-input-group>

                <x-input-group for="ap_paterno" label="Apellido paterno" :error="$errors->first('ap_paterno')" class="w-full">

                    <x-input-text id="ap_paterno" wire:model="ap_paterno" />

                </x-input-group>

                <x-input-group for="ap_materno" label="Apellido materno" :error="$errors->first('ap_materno')" class="w-full">

                    <x-input-text id="ap_materno" wire:model="ap_materno" />

                </x-input-group>

                <x-input-group for="curp" label="CURP" :error="$errors->first('curp')" class="w-full">

                    <x-input-text id="curp" wire:model="curp" />

                </x-input-group>

                <x-input-group for="rfc" label="RFC" :error="$errors->first('rfc')" class="w-full">

                    <x-input-text id="rfc" wire:model="rfc" />

                </x-input-group>

                <x-input-group for="razon_social" label="Razon social" :error="$errors->first('razon_social')" class="w-full">

                    <x-input-text id="razon_social" wire:model="razon_social" />

                </x-input-group>

                <x-input-group for="fecha_nacimiento" label="Fecha de nacimiento" :error="$errors->first('fecha_nacimiento')" class="w-full">

                    <x-input-text type="date" id="fecha_nacimiento" wire:model="fecha_nacimiento" />

                </x-input-group>

                <x-input-group for="nacionalidad" label="Nacionalidad" :error="$errors->first('nacionalidad')" class="w-full">

                    <x-input-text id="nacionalidad" wire:model="nacionalidad" />

                </x-input-group>

                <x-input-group for="estado_civil" label="Estado civil" :error="$errors->first('estado_civil')" class="w-full">

                    <x-input-text id="estado_civil" wire:model="estado_civil" />

                </x-input-group>

                <x-input-group for="calle" label="Calle" :error="$errors->first('calle')" class="w-full">

                    <x-input-text id="calle" wire:model="calle" />

                </x-input-group>

                <x-input-group for="numero_exterior_propietario" label="Número exterior" :error="$errors->first('numero_exterior_propietario')" class="w-full">

                    <x-input-text id="numero_exterior_propietario" wire:model="numero_exterior_propietario" />

                </x-input-group>

                <x-input-group for="numero_interior_propietario" label="Número interior" :error="$errors->first('numero_interior_propietario')" class="w-full">

                    <x-input-text id="numero_interior_propietario" wire:model="numero_interior_propietario" />

                </x-input-group>

                <x-input-group for="colonia" label="Colonia" :error="$errors->first('colonia')" class="w-full">

                    <x-input-text id="colonia" wire:model="colonia" />

                </x-input-group>

                <x-input-group for="cp" label="Código postal" :error="$errors->first('cp')" class="w-full">

                    <x-input-text type="number" id="cp" wire:model="cp" />

                </x-input-group>

                <x-input-group for="entidad" label="Entidad" :error="$errors->first('entidad')" class="w-full">

                    <x-input-text id="entidad" wire:model="entidad" />

                </x-input-group>

                <x-input-group for="municipio_propietario" label="Municipio" :error="$errors->first('municipio_propietario')" class="w-full">

                    <x-input-text id="municipio_propietario" wire:model="municipio_propietario" />

                </x-input-group>

            </div>

        </x-slot>

        <x-slot name="footer">

            <div class="flex gap-3">

                @if($crear)

                    <x-button-blue
                        wire:click="guardarPropietario"
                        wire:loading.attr="disabled"
                        wire:target="guardarPropietario">

                        <img wire:loading wire:target="guardarPropietario" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        <span>Guardar</span>
                    </x-button-blue>

                @elseif($editar)

                    <x-button-blue
                        wire:click="actualizarPropietario"
                        wire:loading.attr="disabled"
                        wire:target="actualizarPropietario">

                        <img wire:loading wire:target="actualizarPropietario" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        <span>Actualizar</span>
                    </x-button-blue>

                @endif

                <x-button-red
                    wire:click="$toggle('modal')"
                    wire:loading.attr="disabled"
                    wire:target="$toggle('modal')"
                    type="button">
                    Cerrar
                </x-button-red>

            </div>

        </x-slot>

    </x-dialog-modal>

</div>
