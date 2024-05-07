@push('styles')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endpush

<div>

    <x-header>Inscripción de gravamen</x-header>

    <div class="p-4 bg-white shadow-xl rounded-xl mb-5">

        <span class="flex items-center justify-center  text-gray-700">Antecedente</span>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto">

            <x-input-group for="antecente_tomo" label="Tomo" :error="$errors->first('antecente_tomo')" class="w-full">

                <x-input-text type="number" id="antecente_tomo" wire:model="antecente_tomo" />

            </x-input-group>

            <x-input-group for="antecente_registro" label="Registro" :error="$errors->first('antecente_registro')" class="w-full">

                <x-input-text type="number" id="antecente_registro" wire:model="antecente_registro" />

            </x-input-group>

            <x-input-group for="antecente_distrito" label="Distrito" :error="$errors->first('antecente_distrito')" class="w-full">

                <x-input-select id="antecente_distrito" wire:model="antecente_distrito" class="w-full">

                    <option value="">Seleccione una opción</option>

                    @foreach ($distritos as $key => $distrito)

                        <option value="{{ $key }}">{{ $distrito }}</option>

                    @endforeach

                </x-input-select>

            </x-input-group>

        </div>

    </div>

    <div class="p-4 bg-white shadow-xl rounded-xl mb-5">

        <span class="flex items-center justify-center ext-gray-700">Datos del gravamen</span>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto mb-4">

            <x-input-group for="gravamen.acto_contenido" label="Acto contenido" :error="$errors->first('gravamen.acto_contenido')" class="w-full">

                <x-input-select id="gravamen.acto_contenido" wire:model.live="gravamen.acto_contenido" class="w-full">

                    <option value="">Seleccione una opción</option>

                    @foreach ($actos as $acto)

                        <option value="{{ $acto }}">{{ $acto }}</option>

                    @endforeach

                </x-input-select>

            </x-input-group>

            <x-input-group for="gravamen.tipo" label="Tipo" :error="$errors->first('gravamen.tipo')" class="w-full col-span-2">

                <x-input-text id="gravamen.tipo" wire:model="gravamen.tipo" />

            </x-input-group>

        </div>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto mb-4">

            <x-input-group for="gravamen.valor_gravamen" label="Valor del gravamen" :error="$errors->first('gravamen.valor_gravamen')" class="w-full relative">

                <x-input-text type="number" id="gravamen.valor_gravamen" wire:model="gravamen.valor_gravamen" />

                <div class="absolute right-0 top-6">

                    <x-input-select id="divisa" wire:model="gravamen.divisa">

                        @foreach ($divisas as $divisa)

                            <option value="{{ $divisa }}">{{ $divisa }}</option>

                        @endforeach

                    </x-input-select>

                </div>

            </x-input-group>

            <x-input-group for="gravamen.fecha_inscripcion" label="Fecha de inscripción" :error="$errors->first('gravamen.fecha_inscripcion')" class="w-full">

                <x-input-text type="date" id="gravamen.fecha_inscripcion" wire:model="gravamen.fecha_inscripcion" />

            </x-input-group>

        </div>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto">

            <x-input-group for="gravamen.comentario" label="Comentario del gravámen" :error="$errors->first('gravamen.comentario')" class="w-full">

                <textarea rows="3" class="w-full bg-white rounded" wire:model="gravamen.comentario"></textarea>

            </x-input-group>

        </div>

    </div>

    <div class="p-4 bg-white shadow-xl rounded-xl mb-5"  x-data>

        <span class="flex items-center justify-center text-gray-700 col-span-3">Deudores</span>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto mb-4">

            <x-input-group for="tipo_deudor" label="Tipo de deudor" :error="$errors->first('tipo_deudor')" class="w-full">

                <x-input-select id="tipo_deudor" wire:model.live="tipo_deudor" class="w-full">

                    <option value="">Seleccione una opción</option>

                    @foreach ($tipo_deudores as $deudor)

                        <option value="{{ $deudor }}">{{ $deudor }}</option>

                    @endforeach

                </x-input-select>

            </x-input-group>

            <div x-show="$wire.tipo_deudor === 'I-DEUDOR ÚNICO'" >

                <x-input-group for="propietario" label="Propietarios" :error="$errors->first('propietario')" class="w-full">

                    <select class="bg-white rounded text-sm w-full" wire:model.live="propietario">

                        <option value="" selected>Seleccione una opción</option>

                        @if($propiedad)

                            @foreach ($propiedad->propietarios() as $propietario)

                                <option value="{{ $propietario->id }}">{{ $propietario->persona->nombre }} {{ $propietario->persona->ap_paterno }} {{ $propietario->persona->ap_materno }} {{ $propietario->persona->razon_social }}</option>

                            @endforeach

                        @endif

                    </select>

                </x-input-group>

            </div>

        </div>

        <div x-show="$wire.tipo_deudor === 'D-GARANTE(S) HIPOTECARIO(S)'" class="w-full lg:w-1/2 mx-auto mb-4">

            <div class="mb-2 flex justify-end">

                <x-button-blue
                    wire:click="abrirModalCrear('Garante hipotecario')">

                    <img wire:loading wire:target="abrirModalCrear('Garante')" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                    Agregar deudor
                </x-button-blue>

            </div>

            <div class="">

                <x-table>

                    <x-slot name="head">
                        <x-table.heading >Deudores</x-table.heading>
                        <x-table.heading ></x-table.heading>
                    </x-slot>

                    <x-slot name="body">

                        @foreach ($gravamen->garantesHipotecarios as $garante)

                            <x-table.row >

                                <x-table.cell>{{ $garante->persona->nombre }} {{ $garante->persona->ap_paterno }} {{ $garante->persona->ap_materno }} {{ $garante->persona->razon_social }}</x-table.cell>
                                <x-table.cell>
                                    <div class="flex items-center gap-3">
                                        <x-button-blue
                                            wire:click="abrirModalEditar('Garante hipotecario', '{{ $garante->id }}')"
                                            wire:loading.attr="disabled"
                                        >
                                            Editar
                                        </x-button-blue>
                                        <x-button-red
                                            wire:click="borrarDeudor({{ $garante->id }})"
                                            wire:loading.attr="disabled">
                                            Borrar
                                        </x-button-red>
                                    </div>
                                </x-table.cell>

                            </x-table.row>

                        @endforeach

                    </x-slot>

                    <x-slot name="tfoot"></x-slot>

                </x-table>

            </div>

        </div>

        <div x-show="$wire.tipo_deudor === 'P-PARTE ALICUOTA'" class="w-full lg:w-1/2 mx-auto mb-4">

            <x-input-group for="propietario" label="Propietarios" :error="$errors->first('propietarios_alicuotas')" class="w-full">

                <div
                    x-data = "{ model: @entangle('propietarios_alicuotas') }"
                    x-init =
                    "
                        select2 = $($refs.select)
                            .select2({
                                placeholder: 'Propietarios',
                                width: '100%',
                                multiple: true,
                            })

                        select2.on('change', function(){
                            $wire.set('propietarios_alicuotas', $(this).val())
                        })

                        select2.on('select2:unselect', function(e){
                            $wire.borrarDeudor(e.params.data.id)
                        })

                        select2.on('keyup', function(e) {
                            if (e.keyCode === 13){
                                $wire.set('propietarios_alicuotas', $('.select2').val())
                            }
                        });

                        Livewire.on('recargar', function(e) {

                            $($refs.select).trigger('change');

                        });
                    "
                    x-on:reload.window="x-init"
                    wire:ignore>

                    <select
                        class="bg-white rounded text-sm w-full z-50"
                        wire:model.live="propietarios_alicuotas"
                        x-ref="select"
                        multiple="multiple">

                        @if($propiedad)

                            @foreach ($propiedad->propietarios() as $propietario)

                                <option value="{{ $propietario->id }}">{{ $propietario->persona->nombre }} {{ $propietario->persona->ap_paterno }} {{ $propietario->persona->ap_materno }} {{ $propietario->persona->razon_social }}</option>

                            @endforeach

                        @endif

                    </select>

                </div>

            </x-input-group>

        </div>

        <div x-show="$wire.tipo_deudor === 'G-GARANTES EN COOPROPIEDAD'" class="w-full lg:w-1/2 mx-auto mb-4">

            <x-input-group for="propietario" label="Propietarios" :error="$errors->first('propietarios_garantes_coopropiedad')" class="w-full mb-3">

                <div
                    x-data = "{ model: @entangle('propietarios_garantes_coopropiedad') }"
                    x-init =
                    "
                        select2 = $($refs.select)
                            .select2({
                                placeholder: 'Propietarios',
                                width: '100%',
                            })

                        select2.on('change', function(){
                            $wire.set('propietarios_garantes_coopropiedad', $(this).val())
                        })

                        select2.on('select2:unselect', function(e){
                            $wire.borrarDeudor(e.params.data.id)
                        })

                        select2.on('keyup', function(e) {
                            if (e.keyCode === 13){
                                $wire.set('propietarios_garantes_coopropiedad', $('.select2').val())
                            }
                        });

                        $watch('model', (value) => {
                            select2.val(value).trigger('change');
                        });
                    "
                    x-on:reload.window="x-init"
                    wire:ignore>

                    <select
                        class="bg-white rounded text-sm w-full z-50"
                        wire:model.live="propietarios_garantes_coopropiedad"
                        x-ref="select"
                        multiple="multiple">

                        @if($propiedad)

                            @foreach ($propiedad->propietarios() as $propietario)

                                <option value="{{ $propietario->id }}">{{ $propietario->persona->nombre }} {{ $propietario->persona->ap_paterno }} {{ $propietario->persona->ap_materno }} {{ $propietario->persona->razon_social }}</option>

                            @endforeach

                        @endif

                    </select>

                </div>

            </x-input-group>

            <div class="mb-2 flex justify-end">

                <x-button-blue
                    wire:click="abrirModalCrear('Garante en coopropiedad')">

                    <img wire:loading wire:target="abrirModalCrear('Garante en coopropiedad')" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                    Agregar deudor
                </x-button-blue>

            </div>

            <div class="">

                <x-table>

                    <x-slot name="head">
                        <x-table.heading >Deudor</x-table.heading>
                        <x-table.heading ></x-table.heading>
                    </x-slot>

                    <x-slot name="body">

                        @foreach ($gravamen->garantesCoopropiedad as $garante)

                            @if($garante->persona_id)

                                <x-table.row >

                                    <x-table.cell>
                                        @if($garante->persona)

                                            {{ $garante->persona->nombre }} {{ $garante->persona->ap_paterno }} {{ $garante->persona->ap_materno }} {{ $garante->persona->razon_social }}

                                        @endif
                                    </x-table.cell>
                                    <x-table.cell>
                                        <div class="flex items-center gap-3">
                                            @if($garante->persona)

                                                <x-button-blue
                                                    wire:click="abrirModalEditar('Garante en coopropiedad', '{{ $garante->id }}')"
                                                    wire:loading.attr="disabled"
                                                >
                                                    Editar
                                                </x-button-blue>
                                            @endif
                                            <x-button-red
                                                wire:click="borrarDeudor({{ $garante->id }})"
                                                wire:loading.attr="disabled">
                                                Borrar
                                            </x-button-red>
                                        </div>
                                    </x-table.cell>

                                </x-table.row>

                            @endif

                        @endforeach

                    </x-slot>

                    <x-slot name="tfoot"></x-slot>

                </x-table>

            </div>

        </div>

        <div x-show="$wire.tipo_deudor === 'F-FIANZA'" class="w-full lg:w-1/2 mx-auto mb-4">

            <div class="mb-2 flex justify-end">

                <x-button-blue
                    wire:click="abrirModalCrear('Fizanza')">

                    <img wire:loading wire:target="abrirModalCrear('Fizanza')" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                    Agregar fiado
                </x-button-blue>

            </div>

            <div class="">

                <x-table>

                    <x-slot name="head">
                        <x-table.heading >Fiados</x-table.heading>
                        <x-table.heading ></x-table.heading>
                    </x-slot>

                    <x-slot name="body">

                        @foreach ($gravamen->fianza as $deudor)

                            <x-table.row >

                                <x-table.cell>{{ $deudor->persona->nombre }} {{ $deudor->persona->ap_paterno }} {{ $deudor->persona->ap_materno }} {{ $deudor->persona->razon_social }}</x-table.cell>
                                <x-table.cell>
                                    <div class="flex items-center gap-3">
                                        <x-button-blue
                                            wire:click="abrirModalEditar('Fizanza', '{{ $deudor->id }}')"
                                            wire:loading.attr="disabled"
                                        >
                                            Editar
                                        </x-button-blue>
                                        <x-button-red
                                            wire:click="borrarDeudor({{ $deudor->id }})"
                                            wire:loading.attr="disabled">
                                            Borrar
                                        </x-button-red>
                                    </div>
                                </x-table.cell>

                            </x-table.row>

                        @endforeach

                    </x-slot>

                    <x-slot name="tfoot"></x-slot>

                </x-table>

            </div>

        </div>

    </div>

    <div class="p-4 bg-white shadow-xl rounded-xl mb-5">

        <div class="w-full lg:w-1/2 justify-center mx-auto">

            <span class="flex items-center justify-center text-gray-700">Acreedores</span>

            <div class="">

                <div class="mb-2 flex justify-end">

                    <x-button-blue wire:click="abrirModalCrear('Acreedor')">Agregar acreedor</x-button-blue>

                </div>

                <div class=">

                    <x-table>

                        <x-slot name="head">
                            <x-table.heading >Acreedor</x-table.heading>
                            <x-table.heading ></x-table.heading>
                        </x-slot>

                        <x-slot name="body">

                            @foreach ($gravamen->acreedores as $acreedor)

                                <x-table.row >

                                    <x-table.cell>{{ $acreedor->persona->nombre }} {{ $acreedor->persona->ap_paterno }} {{ $acreedor->persona->ap_materno }} {{ $acreedor->persona->razon_social }}</x-table.cell>
                                    <x-table.cell>
                                        <div class="flex items-center gap-3">
                                            <x-button-blue
                                                wire:click="abrirModalEditarAcreedor('Acreedor', '{{ $acreedor->id }}')"
                                                wire:loading.attr="disabled"
                                            >
                                                Editar
                                            </x-button-blue>
                                            <x-button-red
                                                wire:click="borrarAcreedor({{ $acreedor->id }})"
                                                wire:loading.attr="disabled">
                                                Borrar
                                            </x-button-red>
                                        </div>
                                    </x-table.cell>

                                </x-table.row>

                            @endforeach

                        </x-slot>

                        <x-slot name="tfoot"></x-slot>

                    </x-table>

                </div>

            </div>

        </div>

    </div>

    <x-dialog-modal wire:model="modal">

        <x-slot name="title">

            @if($editar)
                Editar {{ $title }}
            @else
                Nuevo {{ $title }}
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

                    @if($crear)

                        <x-input-group for="curp" label="CURP" :error="$errors->first('curp')" class="w-full">

                            <x-input-text id="curp" wire:model="curp" />

                        </x-input-group>

                    @endif

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

                @if($crear)

                    <x-input-group for="rfc" label="RFC" :error="$errors->first('rfc')" class="w-full">

                        <x-input-text id="rfc" wire:model="rfc" />

                    </x-input-group>

                @endif

                <x-input-group for="nacionalidad" label="Nacionalidad" :error="$errors->first('nacionalidad')" class="w-full">

                    <x-input-text id="nacionalidad" wire:model="nacionalidad" />

                </x-input-group>

                <span class="flex items-center justify-center text-lg text-gray-700 md col-span-1 sm:col-span-2">Domicilio</span>

                <x-input-group for="cp" label="Código postal" :error="$errors->first('cp')" class="w-full">

                    <x-input-text type="number" id="cp" wire:model="cp" />

                </x-input-group>

                <x-input-group for="entidad" label="Entidad" :error="$errors->first('entidad')" class="w-full">

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

        </x-slot>

        <x-slot name="footer">

            <div class="flex gap-3">

                @if($crear)

                    @if($title == 'Acreedor')

                        <x-button-blue
                            wire:click="guardarAcreedor"
                            wire:loading.attr="disabled"
                            wire:target="guardarAcreedor">

                            <img wire:loading wire:target="guardarAcreedor" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                            <span>Guardar</span>

                        </x-button-blue>

                    @else
                        <x-button-blue
                            wire:click="guardarDeudor"
                            wire:loading.attr="disabled"
                            wire:target="guardarDeudor">

                            <img wire:loading wire:target="guardarDeudor" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                            <span>Guardar</span>

                        </x-button-blue>

                    @endif

                @elseif($editar)

                    @if($title == 'Acreedor')

                            <x-button-blue
                                wire:click="actualizarAcreedor"
                                wire:loading.attr="disabled"
                                wire:target="actualizarAcreedor">

                                <img wire:loading wire:target="actualizarAcreedor" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                                <span>Actualizar</span>

                            </x-button-blue>

                        @else

                        <x-button-blue
                            wire:click="actualizarDeudor"
                            wire:loading.attr="disabled"
                            wire:target="actualizarDeudor">

                            <img wire:loading wire:target="actualizarDeudor" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                            <span>Actualizar</span>

                        </x-button-blue>

                    @endif

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

    <x-dialog-modal wire:model="modalContraseña" maxWidth="sm">

        <x-slot name="title">

            Ingresa tu contraseña

        </x-slot>

        <x-slot name="content">

            <x-input-group for="contraseña" label="Contraseña" :error="$errors->first('contraseña')" class="w-full">

                <x-input-text type="password" id="contraseña" wire:model="contraseña" />

            </x-input-group>

        </x-slot>

        <x-slot name="footer">

            <div class="flex gap-3">

                <x-button-blue
                    wire:click="inscribir"
                    wire:loading.attr="disabled"
                    wire:target="inscribir">

                    <img wire:loading wire:target="inscribir" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <span>Ingresar contraseña</span>
                </x-button-blue>

                <x-button-red
                    wire:click="$toggle('modalContraseña')"
                    wire:loading.attr="disabled"
                    wire:target="$toggle('modalContraseña')"
                    type="button">
                    Cerrar
                </x-button-red>

            </div>

        </x-slot>

    </x-dialog-modal>

    @if(count($errors) > 0)

        <div class="mb-5 bg-white rounded-lg p-2 shadow-lg flex gap-2 flex-wrap ">

            <ul class="flex gap-2 felx flex-wrap list-disc ml-5">
            @foreach ($errors->all() as $error)

                <li class="text-red-500 text-xs md:text-sm ml-5">
                    {{ $error }}
                </li>

            @endforeach

        </ul>

        </div>

    @endif

    <div class="bg-white rounded-lg p-3 flex justify-end shadow-lg">

        <x-button-green
            wire:click="finalizar"
            wire:loading.attr="disabled"
            wire:target="finalizar">

            <img wire:loading wire:target="finalizar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

            Finalizar inscripción

        </x-button-green>

    </div>

</div>

@push('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@endpush
