<div>

    <div class="grid grid-cols-3 gap-3">

        <div class=" gap-3 mb-3 col-span-2 bg-white rounded-lg p-3">

            <span class="flex items-center justify-center text-lg text-gray-700 mb-5">Gravamenes</span>

            <div class="flex justify-end mb-2">

                <x-button-gray
                        wire:click="agregarGravamen"
                        wire:loading.attr="disabled"
                        wire:target="agregarGravamen">

                        <img wire:loading wire:target="agregarGravamen" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                        Agregar gravamen
                </x-button-gray>

            </div>

            <x-table>

                <x-slot name="head">
                    <x-table.heading >Acto contenido</x-table.heading>
                    <x-table.heading >Tomo</x-table.heading>
                    <x-table.heading >Registro</x-table.heading>
                    <x-table.heading >Tipo</x-table.heading>
                    <x-table.heading ></x-table.heading>
                </x-slot>

                <x-slot name="body">

                    @if($gravamenes)

                        @foreach ($gravamenes as $gravamen)

                            <x-table.row >

                                <x-table.cell>{{ $gravamen->acto_contenido }}</x-table.cell>
                                <x-table.cell>{{ $gravamen->tomo }}</x-table.cell>
                                <x-table.cell>{{ $gravamen->registro }}</x-table.cell>
                                <x-table.cell>{{ $gravamen->tipo }}</x-table.cell>
                                <x-table.cell>
                                    <div class="flex items-center gap-3">
                                        <x-button-blue
                                            wire:click="editarGravamen({{ $gravamen->id }})"
                                            wire:loading.attr="disabled"
                                        >
                                            Editar
                                        </x-button-blue>
                                        <x-button-red
                                            wire:click="borrarGravamen({{ $gravamen->id }})"
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

    </div>

    <x-dialog-modal wire:model="modal">

        <x-slot name="title">

            @if($crear)
                Nuevo Gravamen
            @elseif($editar)
                Editar Gravamen
            @endif

        </x-slot>

        <x-slot name="content">

            {{ $errors }}

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3  col-span-2 rounded-lg mb-3">

                <x-input-group for="acto_contenido" label="Tipo de propietario" :error="$errors->first('acto_contenido')" class="w-full">

                    <x-input-select id="acto_contenido" wire:model="acto_contenido" class="w-full">

                        <option value="">Seleccione una opción</option>

                        @foreach ($actos as $acto)

                            <option value="{{ $acto }}">{{ $acto }}</option>

                        @endforeach

                    </x-input-select>

                </x-input-group>

                <x-input-group for="tomo" label="Tomo" :error="$errors->first('tomo')" class="w-full">

                    <x-input-text type="number" id="tomo" wire:model="tomo" />

                </x-input-group>

                <x-input-group for="registro" label="Registro" :error="$errors->first('registro')" class="w-full">

                    <x-input-text id="registro" wire:model="registro" />

                </x-input-group>

                <x-input-group for="distrito" label="Tipo de propietario" :error="$errors->first('distrito')" class="w-full">

                    <x-input-select id="distrito" wire:model="distrito" class="w-full">

                        <option value="">Seleccione una opción</option>

                        @foreach ($distritos as $key => $distrito)

                            <option value="{{ $key }}">{{ $distrito }}</option>

                        @endforeach

                    </x-input-select>

                </x-input-group>

                <x-input-group for="tipo" label="Tipo" :error="$errors->first('tipo')" class="w-full">

                    <x-input-text id="tipo" wire:model="tipo" />

                </x-input-group>

                <x-input-group for="tipo_deudor" label="Tipo de deudor" :error="$errors->first('tipo_deudor')" class="w-full">

                    <x-input-text id="tipo_deudor" wire:model="tipo_deudor" />

                </x-input-group>

                <x-input-group for="nombre_deudor" label="Nombre del deudor" :error="$errors->first('nombre_deudor')" class="w-full">

                    <x-input-text id="nombre_deudor" wire:model="nombre_deudor" />

                </x-input-group>

                <x-input-group for="nombre_acredor" label="Nombre del acredor" :error="$errors->first('nombre_acredor')" class="w-full">

                    <x-input-text id="nombre_acredor" wire:model="nombre_acredor" />

                </x-input-group>

                <x-input-group for="valor_gravamen" label="Valor del gravamen" :error="$errors->first('valor_gravamen')" class="w-full">

                    <x-input-text type="number" id="valor_gravamen" wire:model="valor_gravamen" />

                </x-input-group>

                <x-input-group for="fecha_nacimiento" label="Fecha de nacimiento" :error="$errors->first('fecha_nacimiento')" class="w-full">

                    <x-input-text type="date" id="fecha_nacimiento" wire:model="fecha_nacimiento" />

                </x-input-group>

                <x-input-group for="divisa" label="Divisa" :error="$errors->first('divisa')" class="w-full">

                    <x-input-text id="divisa" wire:model="divisa" />

                </x-input-group>

                <x-input-group for="fecha_inscripcion" label="Fecha de inscripción" :error="$errors->first('fecha_inscripcion')" class="w-full">

                    <x-input-text type="date" id="fecha_inscripcion" wire:model="fecha_inscripcion" />

                </x-input-group>

                <x-input-group for="escritura_observaciones" label="Observaciones" :error="$errors->first('escritura_observaciones')" class="col-span-3">

                    <textarea rows="3" class="w-full bg-white rounded" wire:model="escritura_observaciones"></textarea>

                </x-input-group>

            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3  col-span-2 rounded-lg mb-3">

                <span class="flex items-center justify-center text-lg text-gray-700 sm:col-span-2 lg:col-span-3">Escritura</span>

                <x-input-group for="tomo" label="Tomo" :error="$errors->first('tomo')" class="w-full">

                    <x-input-text type="number" id="tomo" wire:model="tomo" />

                </x-input-group>

                <x-input-group for="registro" label="Registro" :error="$errors->first('registro')" class="w-full">

                    <x-input-text id="registro" wire:model="registro" />

                </x-input-group>

                <x-input-group for="distrito" label="Distrito" :error="$errors->first('distrito')" class="w-full">

                    <x-input-select id="distrito" wire:model="distrito" class="w-full">

                        <option value="">Seleccione una opción</option>

                        @foreach ($distritos as $key => $distrito)

                            <option value="{{ $key }}">{{ $distrito }}</option>

                        @endforeach

                    </x-input-select>

                </x-input-group>

            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3  col-span-2 rounded-lg">

                <span class="flex items-center justify-center text-lg text-gray-700 sm:col-span-2 lg:col-span-3">Documento de entrada</span>

                <x-input-group for="tipo_documento" label="Tipo de documento" :error="$errors->first('tipo_documento')" class="w-full">

                    <x-input-text id="tipo_documento" wire:model="tipo_documento" />

                </x-input-group>

                <x-input-group for="autoridad_cargo" label="Autoridad cargo" :error="$errors->first('autoridad_cargo')" class="w-full">

                    <x-input-text id="autoridad_cargo" wire:model="autoridad_cargo" />

                </x-input-group>

                <x-input-group for="autoridad_nombre" label="Nombre de la autoridad" :error="$errors->first('autoridad_nombre')" class="w-full">

                    <x-input-text id="autoridad_nombre" wire:model="autoridad_nombre" />

                </x-input-group>

                <x-input-group for="numero_documento" label="Número de documento" :error="$errors->first('numero_documento')" class="w-full">

                    <x-input-text id="numero_documento" wire:model="numero_documento" />

                </x-input-group>

                <x-input-group for="fecha_emision" label="Fecha de emisión" :error="$errors->first('fecha_emision')" class="w-full">

                    <x-input-text type="date" id="fecha_emision" wire:model="fecha_emision" />

                </x-input-group>

                <x-input-group for="fecha_inscripcion" label="Fecha de inscripción" :error="$errors->first('fecha_inscripcion')" class="w-full">

                    <x-input-text type="date" id="fecha_inscripcion" wire:model="fecha_inscripcion" />

                </x-input-group>

                <x-input-group for="procedencia" label="Procedencia" :error="$errors->first('procedencia')" class="w-full">

                    <x-input-text id="procedencia" wire:model="procedencia" />

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
