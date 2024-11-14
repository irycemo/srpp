@push('styles')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endpush

<div>

    <x-header>Inscripción de gravamen</x-header>

    <div class="p-4 bg-white shadow-xl rounded-xl mb-5">

        <span class="flex items-center justify-center text-gray-700">Datos del gravamen</span>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto mb-4">

            <x-input-group for="gravamen.acto_contenido" label="Acto contenido" :error="$errors->first('gravamen.acto_contenido')" class="w-full">

                <x-input-select id="gravamen.acto_contenido" wire:model.live="gravamen.acto_contenido" class="w-full">

                    <option value="">Seleccione una opción</option>

                    @foreach ($actos as $acto)

                        <option value="{{ $acto }}">{{ $acto }}</option>

                    @endforeach

                </x-input-select>

            </x-input-group>

        </div>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto mb-4">

            <x-input-group for="gravamen.tipo" label="Tipo" :error="$errors->first('gravamen.tipo')" class="w-full col-span-2">

                <x-input-text id="gravamen.tipo" wire:model="gravamen.tipo" />

            </x-input-group>

        </div>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto mb-4">

            <x-input-group for="gravamen.valor_gravamen" label="Valor del gravamen" :error="$errors->first('gravamen.valor_gravamen')" class="w-full relative">

                <x-input-text type="number" id="gravamen.valor_gravamen" wire:model="gravamen.valor_gravamen" />

                <div class="absolute right-0 top-6">

                    <x-input-select id="divisa" wire:model="gravamen.divisa">

                        <option value="" selected>Divisa</option>

                        @foreach ($divisas as $divisa)

                            <option value="{{ $divisa }}">{{ $divisa }}</option>

                        @endforeach

                    </x-input-select>

                </div>

            </x-input-group>

        </div>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto">

            <x-input-group for="gravamen.observaciones" label="Comentario del gravámen" :error="$errors->first('gravamen.observaciones')" class="w-full">

                <textarea rows="3" class="w-full bg-white rounded" wire:model="gravamen.observaciones"></textarea>

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

                                        @if(!$propiedad->propietarios()->where('persona_id', $garante->persona_id)->first())
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
                                        @endif
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

                                <option value="{{ $propietario->persona_id }}">{{ $propietario->persona->nombre }} {{ $propietario->persona->ap_paterno }} {{ $propietario->persona->ap_materno }} {{ $propietario->persona->razon_social }}</option>

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

                                <option value="{{ $propietario->persona_id }}">{{ $propietario->persona->nombre }} {{ $propietario->persona->ap_paterno }} {{ $propietario->persona->ap_materno }} {{ $propietario->persona->razon_social }}</option>

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

                                            @if(!$propiedad->propietarios()->where('persona_id', $garante->persona_id)->first())

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

                                            @endif
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

                <div>

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

    {{-- @if($gravamen->acto_contenido == 'DIVISIÓN DE HIPOTECA')

        <div class="p-4 bg-white shadow-xl rounded-xl mb-5">

            <div class="w-full  justify-center mx-auto">

                <div class="flex-auto text-center mb-3 lg:w-1/2 mx-auto">

                    <div >

                        <Label class="text-base tracking-widest rounded-xl border-gray-500">Folio del gravmen</Label>

                    </div>

                    <div class="inline-flex">

                        <input type="number" class="bg-white text-sm w-20 rounded-l focus:ring-0 @error('folio') border-red-500 @enderror" value="{{ $gravamen->movimientoRegistral->folioReal->folio }}" readonly>

                        <input type="number" class="bg-white text-sm w-20 border-l-0 rounded-r focus:ring-0 @error('folio_gravamen') border-red-500 @enderror" wire:model="folio_gravamen">

                    </div>

                    <button
                        wire:click="buscarGravamen"
                        wire:loading.attr="disabled"
                        wire:target="buscarGravamen"
                        type="button"
                        class="bg-blue-400 mx-auto mt-3 hover:shadow-lg text-white font-bold px-4 py-2 rounded text-xs hover:bg-blue-700 focus:outline-none flex items-center justify-center focus:outline-blue-400 focus:outline-offset-2">

                        <img wire:loading wire:target="buscarGravamen" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        Buscar gravamen

                    </button>

                </div>

                @if($gravamenHipoteca)

                    <div class="lg:w-1/2 mx-auto">

                        <div class="flex gap-3 items-center justify-center mx-auto mb-4">

                            <input type="text" value="{{ $gravamenHipoteca->gravamen->acto_contenido }}" class="bg-white rounded text-sm w-full" readonly>

                            <input type="text" value="{{ $gravamenHipoteca->gravamen->tipo }}" class="bg-white rounded text-sm w-full" readonly>

                        </div>

                        <div class="flex gap-3 items-center justify-center mx-auto mb-4">

                            <input type="text" value="{{ $gravamenHipoteca->gravamen->valor_gravamen }}" class="bg-white rounded text-sm w-full" readonly>

                            <input type="text" value="{{ $gravamenHipoteca->gravamen->fecha_inscripcion }}" class="bg-white rounded text-sm w-full" readonly>

                        </div>

                        <textarea class="bg-white rounded text-sm w-full" readonly>{{ $gravamenHipoteca->gravamen->observaciones }}</textarea>

                    </div>

                    <div class="flex-auto text-center mb-3 lg:w-1/2 mx-auto">

                        <div >

                            <Label class="text-base tracking-widest rounded-xl border-gray-500">Folio real</Label>

                        </div>

                        <div class="inline-flex">

                            <input type="number" class="bg-white text-sm w-20 rounded focus:ring-0 @error('folio_real_division') border-red-500 @enderror" wire:model="folio_real_division">

                        </div>

                        <button
                            wire:click="agregarFolioReal"
                            wire:loading.attr="disabled"
                            wire:target="agregarFolioReal"
                            type="button"
                            class="bg-blue-400 mx-auto mt-3 hover:shadow-lg text-white font-bold px-4 py-2 rounded text-xs hover:bg-blue-700 focus:outline-none flex items-center justify-center focus:outline-blue-400 focus:outline-offset-2">

                            <img wire:loading wire:target="agregarFolioReal" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                            Agregar folio real

                        </button>

                    </div>

                    <div class="flex-auto text-center mb-3 mx-auto">

                        @if(count($folios_reales))

                        <table class="mx-auto">

                            <thead class="border-b border-gray-300 ">

                                <tr class="text-sm text-gray-500 text-left traling-wider whitespace-nowrap">

                                    <th class="px-2">Folio</th>
                                    <th class="px-2">Propietario</th>
                                    <th class="px-2">Ubicación</th>
                                    <th class="px-2">Valor de gravamen</th>
                                    <th class="px-2"></th>

                                </tr>

                            </thead>

                            <tbody class="divide-y divide-gray-200">

                                @foreach ($folios_reales as $key => $folio)

                                    <tr class="text-gray-500 text-sm leading-relaxed">
                                        <td class=" p-2">{{ $folio->folio }}</td>
                                        <td class=" p-2">{{ $folio->predio->primerPropietario() }}</td>
                                        <td class=" p-2">{{ $folio->predio->nombre_vialidad }} {{ $folio->predio->numero_exterior }}</td>
                                        <td class=" p-2">${{ number_format($gravamenHipoteca->gravamen->valor_gravamen / count($folios_reales), 2) }}</td>
                                        <td class=" p-2">
                                            <button
                                                wire:click="quitarFolio({{ $key }})"
                                                wire:loading.attr="disabled"
                                                wire:target="quitarFolio({{ $key }})"
                                                class=" bg-red-400 text-white text-xs p-1 items-center rounded-full hover:bg-red-700 flex justify-center focus:outline-none"
                                            >

                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>

                                            </button>
                                        </td>
                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                        @endif

                    </div>

                @endif

            </div>

        </div>

    @endif --}}

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

    <div class="bg-white rounded-lg p-3 flex justify-end shadow-lg gap-3">

        @if(!$gravamen->movimientoRegistral->documentoEntrada())

            <x-button-blue
                wire:click="abrirModalFinalizar"
                wire:loading.attr="disabled"
                wire:target="abrirModalFinalizar">

                <img wire:loading wire:target="abrirModalFinalizar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                Subir documento de entrada

            </x-button-blue>

        @else

            <div class="inline-block">

                <x-link-blue target="_blank" href="{{ $gravamen->movimientoRegistral->documentoEntrada() }}">Documento de entrada</x-link-blue>

            </div>

        @endif

        <x-button-blue
            wire:click="guardar"
            wire:loading.attr="disabled"
            wire:target="guardar">

            <img wire:loading wire:target="guardar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

            Guardar y continuar

        </x-button-blue>

        <x-button-green
            wire:click="finalizar"
            wire:loading.attr="disabled"
            wire:target="finalizar">

            <img wire:loading wire:target="finalizar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

            Finalizar inscripción

        </x-button-green>

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

    <x-dialog-modal wire:model="modalDocumento" maxWidth="sm">

        <x-slot name="title">

            Subir archivo

        </x-slot>

        <x-slot name="content">

            <x-filepond wire:model.live="documento" accept="['application/pdf']"/>

            <div>

                @error('documento') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

            </div>

        </x-slot>

        <x-slot name="footer">

            <div class="flex gap-3">

                <x-button-blue
                    wire:click="guardarDocumento"
                    wire:loading.attr="disabled"
                    wire:target="guardarDocumento">

                    <img wire:loading wire:target="guardarDocumento" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <span>Guardar</span>

                </x-button-blue>

                <x-button-red
                    wire:click="$toggle('modalDocumento')"
                    wire:loading.attr="disabled"
                    wire:target="$toggle('modalDocumento')"
                    type="button">

                    <span>Cerrar</span>

                </x-button-red>

            </div>

        </x-slot>

    </x-dialog-modal>

</div>

@push('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>

        window.addEventListener('imprimir_documento', event => {

            const documento = event.detail[0].gravamen;

            var url = "{{ route('gravamen.inscripcion.acto', '')}}" + "/" + documento;

            window.open(url, '_blank');

            window.location.href = "{{ route('gravamen')}}";

        });

        window.addEventListener('ver_documento', event => {

            const documento = event.detail[0].url;

            window.open(documento, '_blank');

        });

    </script>

@endpush
