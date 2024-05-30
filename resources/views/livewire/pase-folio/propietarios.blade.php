<div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

    <div class=" gap-3  col-span-2">

        <div class="mb-3 bg-white rounded-lg p-3 shadow-lg">

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
                    <x-table.heading >Nombre / Razón social</x-table.heading>
                    <x-table.heading >Porcentaje propiedad</x-table.heading>
                    <x-table.heading >Porcentaje nuda</x-table.heading>
                    <x-table.heading >Porcentaje usufructo</x-table.heading>
                    <x-table.heading ></x-table.heading>
                </x-slot>

                <x-slot name="body">

                    @if($propiedad)

                        @foreach ($propiedad->propietarios() as $propietario)

                            <x-table.row >

                                <x-table.cell>{{ $propietario->persona->nombre }} {{ $propietario->persona->ap_paterno }} {{ $propietario->persona->ap_materno }} {{ $propietario->persona->razon_social }}</x-table.cell>
                                <x-table.cell>{{ number_format($propietario->porcentaje_propiedad, 2) }}%</x-table.cell>
                                <x-table.cell>{{ number_format($propietario->porcentaje_nuda, 2) }}%</x-table.cell>
                                <x-table.cell>{{ number_format($propietario->porcentaje_usufructo, 2) }}%</x-table.cell>
                                <x-table.cell>
                                    <div class="flex items-center gap-3">
                                        <x-button-blue
                                            wire:click="editarActor({{ $propietario->id }}, 'propietario')"
                                            wire:traget="editarActor({{ $propietario->id }}, 'propietario')"
                                            wire:loading.attr="disabled"
                                        >
                                            Editar
                                        </x-button-blue>
                                        <x-button-red
                                            wire:click="borrarActor({{ $propietario->id }})"
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

        <div class="mb-3 bg-white rounded-lg p-3 shadow-lg">

            <span class="flex items-center justify-center text-lg text-gray-700 mb-5">Transmitentes</span>

            <div class="flex justify-end mb-2">

                <x-button-gray
                        wire:click="agregarTransmitente"
                        wire:loading.attr="disabled"
                        wire:target="agregarTransmitente">

                        <img wire:loading wire:target="agregarTransmitente" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                        Agregar transmitente
                </x-button-gray>

            </div>

            <x-table>

                <x-slot name="head">
                    <x-table.heading >Nombre / Razón social</x-table.heading>
                    <x-table.heading ></x-table.heading>
                </x-slot>

                <x-slot name="body">

                    @if($propiedad)

                        @foreach ($propiedad->transmitentes() as $transmitente)

                            <x-table.row >

                                <x-table.cell>{{ $transmitente->persona->nombre }} {{ $transmitente->persona->ap_paterno }} {{ $transmitente->persona->ap_materno }} {{ $transmitente->persona->razon_social }}</x-table.cell>
                                <x-table.cell>
                                    <div class="flex items-center gap-3">
                                        <x-button-blue
                                            wire:click="editarActor({{ $transmitente->id }}, 'transmitente')"
                                            wire:loading.attr="disabled"
                                        >
                                            Editar
                                        </x-button-blue>
                                        <x-button-red
                                            wire:click="borrarActor({{ $transmitente->id }})"
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

        <div class="mb-3 bg-white rounded-lg p-3 shadow-lg">

            <span class="flex items-center justify-center text-lg text-gray-700 mb-5">Representantes</span>

            <div class="flex justify-end mb-2">

                <x-button-gray
                        wire:click="agregarRepresentante"
                        wire:loading.attr="disabled"
                        wire:target="agregarRepresentante">

                        <img wire:loading wire:target="agregarRepresentante" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                        Agregar representante
                </x-button-gray>

            </div>

            <x-table>

                <x-slot name="head">
                    <x-table.heading >Nombre / Razón social</x-table.heading>
                    <x-table.heading >Representados</x-table.heading>
                    <x-table.heading ></x-table.heading>
                </x-slot>

                <x-slot name="body">

                    @if($propiedad)

                        @foreach ($propiedad->representantes() as $representante)

                            <x-table.row >

                                <x-table.cell>{{ $representante->persona->nombre }} {{ $representante->persona->ap_paterno }} {{ $representante->persona->ap_materno }} {{ $representante->persona->razon_social }}</x-table.cell>
                                <x-table.cell>

                                    @foreach ($representante->representados as $representado)

                                        <p>{{ $representado->persona->nombre }} {{ $representado->persona->ap_paterno }} {{ $representado->persona->ap_materno }} {{ $representante->persona->razon_social }}</p>

                                    @endforeach

                                </x-table.cell>
                                <x-table.cell>
                                    <div class="flex items-center gap-3">
                                        <x-button-blue
                                            wire:click="editarActor({{ $representante->id }}, 'representante')"
                                            wire:loading.attr="disabled"
                                        >
                                            Editar
                                        </x-button-blue>
                                        <x-button-red
                                            wire:click="borrarActor({{ $representante->id }})"
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

    </div>

    <div class="bg-white rounded-lg p-2 mb-3 shadow-lg">

        <span class="flex items-center justify-center text-lg text-gray-700">Información de la base de datos</span>

    </div>

    <div class=" flex justify-end items-center bg-white rounded-lg p-2 shadow-lg md:col-span-3 col-span-1 sm:col-span-2">

        <x-button-red
            wire:click="$parent.finalizarPaseAFolio"
            wire:loading.attr="disabled">

            <img wire:loading class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
            Finalizar pase a folio

        </x-button-red>

    </div>

    <x-dialog-modal wire:model="modalPropietario">

        <x-slot name="title">

            @if($crear)
                Nuevo Propietario
            @elseif($editar)
                Editar Propietario
            @endif

        </x-slot>

        <x-slot name="content">

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 mb-3 col-span-2 rounded-lg p-3">

                <x-input-group for="tipo_persona" label="Tipo de persona" :error="$errors->first('tipo_persona')" class="w-full">

                    <x-input-select id="tipo_persona" wire:model.live="tipo_persona" class="w-full">

                        <option value="">Seleccione una opción</option>
                        <option value="MORAL">MORAL</option>
                        <option value="FISICA">FISICA</option>

                    </x-input-select>

                </x-input-group>

                @if($tipo_persona == 'FISICA')

                    <x-input-group for="nombre" label="Nombre(s)" :error="$errors->first('nombre')" class="w-full">

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

                    <x-input-group for="fecha_nacimiento" label="Fecha de nacimiento" :error="$errors->first('fecha_nacimiento')" class="w-full">

                        <x-input-text type="date" id="fecha_nacimiento" wire:model="fecha_nacimiento" />

                    </x-input-group>

                    <x-input-group for="estado_civil" label="Estado civil" :error="$errors->first('estado_civil')" class="w-full">

                        <x-input-text id="estado_civil" wire:model="estado_civil" />

                    </x-input-group>

                @elseif($tipo_persona == 'MORAL')

                    <x-input-group for="razon_social" label="Razon social" :error="$errors->first('razon_social')" class="w-full">

                        <x-input-text id="razon_social" wire:model="razon_social" />

                    </x-input-group>

                @endif

                <x-input-group for="rfc" label="RFC" :error="$errors->first('rfc')" class="w-full">

                    <x-input-text id="rfc" wire:model="rfc" />

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

                <x-input-group for="municipio_propietario" label="Municipio" :error="$errors->first('municipio_propietario')" class="w-full">

                    <x-input-text id="municipio_propietario" wire:model="municipio_propietario" />

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

                <x-input-group for="numero_exterior_propietario" label="Número exterior" :error="$errors->first('numero_exterior_propietario')" class="w-full">

                    <x-input-text id="numero_exterior_propietario" wire:model="numero_exterior_propietario" />

                </x-input-group>

                <x-input-group for="numero_interior_propietario" label="Número interior" :error="$errors->first('numero_interior_propietario')" class="w-full">

                    <x-input-text id="numero_interior_propietario" wire:model="numero_interior_propietario" />

                </x-input-group>

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

                {{-- <x-input-group for="partes_iguales" label="Partes iguales" :error="$errors->first('partes_iguales')" class="w-full">

                    <input wire:model="partes_iguales" type="checkbox" class="rounded">

                </x-input-group> --}}

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
                        wire:click="actualizarActor"
                        wire:loading.attr="disabled"
                        wire:target="actualizarActor">

                        <img wire:loading wire:target="actualizarActor" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        <span>Actualizar</span>
                    </x-button-blue>

                @endif

                <x-button-red
                    wire:click="resetear"
                    wire:loading.attr="disabled"
                    wire:target="resetear"
                    type="button">
                    Cerrar
                </x-button-red>

            </div>

        </x-slot>

    </x-dialog-modal>

    <x-dialog-modal wire:model="modalTransmitente">

        <x-slot name="title">

            @if($crear)
                Nuevo Transmitente
            @elseif($editar)
                Editar Transmitente
            @endif

        </x-slot>

        <x-slot name="content">

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 mb-3 col-span-2 rounded-lg p-3">

                <x-input-group for="tipo_persona" label="Tipo de persona" :error="$errors->first('tipo_persona')" class="w-full">

                    <x-input-select id="tipo_persona" wire:model.live="tipo_persona" class="w-full">

                        <option value="">Seleccione una opción</option>
                        <option value="MORAL">MORAL</option>
                        <option value="FISICA">FISICA</option>

                    </x-input-select>

                </x-input-group>

                @if($tipo_persona == 'FISICA')

                    <x-input-group for="nombre" label="Nombre(s)" :error="$errors->first('nombre')" class="w-full">

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

                    <x-input-group for="fecha_nacimiento" label="Fecha de nacimiento" :error="$errors->first('fecha_nacimiento')" class="w-full">

                        <x-input-text type="date" id="fecha_nacimiento" wire:model="fecha_nacimiento" />

                    </x-input-group>

                    <x-input-group for="estado_civil" label="Estado civil" :error="$errors->first('estado_civil')" class="w-full">

                        <x-input-text id="estado_civil" wire:model="estado_civil" />

                    </x-input-group>

                @elseif($tipo_persona == 'MORAL')

                    <x-input-group for="razon_social" label="Razon social" :error="$errors->first('razon_social')" class="w-full">

                        <x-input-text id="razon_social" wire:model="razon_social" />

                    </x-input-group>

                @endif

                <x-input-group for="rfc" label="RFC" :error="$errors->first('rfc')" class="w-full">

                    <x-input-text id="rfc" wire:model="rfc" />

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

                <x-input-group for="municipio_propietario" label="Municipio" :error="$errors->first('municipio_propietario')" class="w-full">

                    <x-input-text id="municipio_propietario" wire:model="municipio_propietario" />

                </x-input-group>

                <x-input-group for="colonia" label="Colonia" :error="$errors->first('colonia')" class="w-full">

                    <x-input-text id="colonia" wire:model="colonia" />

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

            </div>

        </x-slot>

        <x-slot name="footer">

            <div class="flex gap-3">

                @if($crear)

                    <x-button-blue
                        wire:click="guardarTransmitente"
                        wire:loading.attr="disabled"
                        wire:target="guardarTransmitente">

                        <img wire:loading wire:target="guardarTransmitente" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        <span>Guardar</span>
                    </x-button-blue>

                @elseif($editar)

                    <x-button-blue
                        wire:click="actualizarActor"
                        wire:loading.attr="disabled"
                        wire:target="actualizarActor">

                        <img wire:loading wire:target="actualizarActor" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        <span>Actualizar</span>
                    </x-button-blue>

                @endif

                <x-button-red
                    wire:click="resetear"
                    wire:loading.attr="disabled"
                    wire:target="resetear"
                    type="button">
                    Cerrar
                </x-button-red>

            </div>

        </x-slot>

    </x-dialog-modal>

    <x-dialog-modal wire:model="modalRepresentante">

        <x-slot name="title">

            @if($crear)
                Nuevo Representante
            @elseif($editar)
                Editar Representante
            @endif

        </x-slot>

        <x-slot name="content">

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 mb-3 col-span-2 rounded-lg p-3">

                <x-input-group for="tipo_persona" label="Tipo de persona" :error="$errors->first('tipo_persona')" class="w-full">

                    <x-input-select id="tipo_persona" wire:model.live="tipo_persona" class="w-full">

                        <option value="">Seleccione una opción</option>
                        <option value="MORAL">MORAL</option>
                        <option value="FISICA">FISICA</option>

                    </x-input-select>

                </x-input-group>

                @if($tipo_persona == 'FISICA')

                    <x-input-group for="nombre" label="Nombre(s)" :error="$errors->first('nombre')" class="w-full">

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

                    <x-input-group for="fecha_nacimiento" label="Fecha de nacimiento" :error="$errors->first('fecha_nacimiento')" class="w-full">

                        <x-input-text type="date" id="fecha_nacimiento" wire:model="fecha_nacimiento" />

                    </x-input-group>

                    <x-input-group for="estado_civil" label="Estado civil" :error="$errors->first('estado_civil')" class="w-full">

                        <x-input-text id="estado_civil" wire:model="estado_civil" />

                    </x-input-group>

                @elseif($tipo_persona == 'MORAL')

                    <x-input-group for="razon_social" label="Razon social" :error="$errors->first('razon_social')" class="w-full">

                        <x-input-text id="razon_social" wire:model="razon_social" />

                    </x-input-group>

                @endif

                <x-input-group for="rfc" label="RFC" :error="$errors->first('rfc')" class="w-full">

                    <x-input-text id="rfc" wire:model="rfc" />

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

                <x-input-group for="municipio_propietario" label="Municipio" :error="$errors->first('municipio_propietario')" class="w-full">

                    <x-input-text id="municipio_propietario" wire:model="municipio_propietario" />

                </x-input-group>

                <x-input-group for="colonia" label="Colonia" :error="$errors->first('colonia')" class="w-full">

                    <x-input-text id="colonia" wire:model="colonia" />

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
                                    initSelection : function (element, callback) {
                                        var data = {id: 63, text: 'hola'};
                                        callback(data);
                                    }
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

                            Livewire.on('recargar', function(e) {

                                var newOption = new Option(e[0].description, e[0].id, false, false);

                                $($refs.select).append(newOption).trigger('change');

                            });

                        "
                        wire:ignore>

                        <select
                            class="bg-white rounded text-sm w-full z-50"
                            wire:model.live="representados"
                            x-ref="select"
                            multiple="multiple">

                            @if($propiedad)

                                @foreach ($propiedad->propietarios() as $propietario)

                                    <option value="{{ $propietario->id }}">{{ $propietario->persona->nombre }} {{ $propietario->persona->ap_paterno }} {{ $propietario->persona->ap_materno }} {{ $propietario->persona->razon_social }}</option>

                                @endforeach

                                @foreach ($propiedad->transmitentes() as $transmitente)

                                    <option value="{{ $transmitente->id }}">{{ $transmitente->persona->nombre }} {{ $transmitente->persona->ap_paterno }} {{ $transmitente->persona->ap_materno }} {{ $transmitente->persona->razon_social }}</option>

                                @endforeach

                            @endif

                        </select>

                    </div>

                    <div>

                        @error('representados') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                    </div>

                </div>

            </div>

        </x-slot>

        <x-slot name="footer">

            <div class="flex gap-3">

                @if($crear)

                    <x-button-blue
                        wire:click="guardarRepresentante"
                        wire:loading.attr="disabled"
                        wire:target="guardarRepresentante">

                        <img wire:loading wire:target="guardarRepresentante" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        <span>Guardar</span>
                    </x-button-blue>

                @elseif($editar)

                    <x-button-blue
                        wire:click="actualizarActor"
                        wire:loading.attr="disabled"
                        wire:target="actualizarActor">

                        <img wire:loading wire:target="actualizarActor" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        <span>Actualizar</span>
                    </x-button-blue>

                @endif

                <x-button-red
                    wire:click="resetear"
                    wire:loading.attr="disabled"
                    wire:target="resetear"
                    type="button">
                    Cerrar
                </x-button-red>

            </div>

        </x-slot>

    </x-dialog-modal>

</div>
