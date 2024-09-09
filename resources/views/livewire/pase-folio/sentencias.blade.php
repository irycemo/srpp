<div>

    <div class="grid grid-cols-3 gap-3">

        <div class=" gap-3 mb-3 col-span-2 bg-white rounded-lg p-3">

            <span class="flex items-center justify-center text-lg text-gray-700 mb-5">Sentencias</span>

            <div class="flex justify-end mb-2">

                <x-button-gray
                        wire:click="agregarSentencia"
                        wire:loading.attr="disabled"
                        wire:target="agregarSentencia">

                        <img wire:loading wire:target="agregarSentencia" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                        Agregar sentencia
                </x-button-gray>

            </div>

            <x-table>

                <x-slot name="head">
                    <x-table.heading >Acto contenido</x-table.heading>
                    <x-table.heading >Comentario</x-table.heading>
                    <x-table.heading ></x-table.heading>
                </x-slot>

                <x-slot name="body">

                    @if($sentencias)

                        @foreach ($sentencias as $sentencia)

                            <x-table.row >

                                <x-table.cell>{{ $sentencia->acto_contenido }}</x-table.cell>
                                <x-table.cell>{{ $sentencia->descripcion }}</x-table.cell>
                                <x-table.cell>
                                    <div class="flex items-center gap-3">
                                        <x-button-blue
                                            wire:click="actualizarSentencia({{ $sentencia->id }})"
                                            wire:loading.attr="disabled"
                                        >
                                            Editar
                                        </x-button-blue>
                                        <x-button-red
                                            wire:click="abrirModalBorrar({{ $sentencia->id }})"
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

        @include('livewire.pase-folio.informacion_base_datos')

    </div>

    <div class=" flex justify-end items-center bg-white rounded-lg p-2 shadow-lg gap-3">

        <x-button-red
            wire:click="$parent.finalizarPaseAFolio"
            wire:loading.attr="disabled">

            <img wire:loading class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
            Finalizar pase a folio

        </x-button-red>

    </div>

    <x-dialog-modal wire:model="modal">

        <x-slot name="title">

            @if($crear)
                Nueva sentencia
            @elseif($editar)
                Editar sentencia
            @endif

        </x-slot>

        <x-slot name="content">

            @if($antecedente)

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3  col-span-2 rounded-lg mb-3" x-transition:enter.duration.500ms x-transition:leave.duration.500ms>

                    <span class="flex items-center justify-center  text-gray-700 md:col-span-3 col-span-1 sm:col-span-2">Antecedente</span>

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

                    <div class="flex justify-end items-center w-full col-span-3">

                        <x-button-gray
                            wire:click="cambiar('documento_entrada')"
                            wire:loading.attr="disabled"
                            wire:target="cambiar('documento_entrada')">

                            <img wire:loading wire:target="cambiar('documento_entrada')" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                            Siguiente

                        </x-button-gray>

                    </div>

                </div>

            @endif

            @if($documento_entrada)

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3  col-span-2 rounded-lg mb-3" x-transition:enter.duration.500ms x-transition:leave.duration.500ms>

                    <span class="flex items-center justify-center text-gray-700 md:col-span-3 col-span-1 sm:col-span-2">Documento de entrada</span>

                    <x-input-group for="tipo_documento" label="Tipo de documento" :error="$errors->first('tipo_documento')" class="w-full">

                        <x-input-select id="tipo_documento" wire:model.live="tipo_documento" class="w-full">

                            <option value="">Seleccione una opción</option>
                            <option value="escritura">Escritura</option>
                            <option value="oficio">Oficio</option>
                            <option value="contrato">Contrato</option>
                            <option value="embargo">Acto de embargo</option>

                        </x-input-select>

                    </x-input-group>

                    <x-input-group for="autoridad_cargo" label="Cargo de la autoridad" :error="$errors->first('autoridad_cargo')" class="w-full">

                        <x-input-text id="autoridad_cargo" wire:model="autoridad_cargo" />

                    </x-input-group>

                    <x-input-group for="autoridad_nombre" label="Nombre de la autoridad" :error="$errors->first('autoridad_nombre')" class="w-full">

                        <x-input-text id="autoridad_nombre" wire:model="autoridad_nombre" />

                    </x-input-group>

                    <x-input-group for="numero_documento" label="{{ $label_numero_documento }}" :error="$errors->first('numero_documento')" class="w-full">

                        <x-input-text id="numero_documento" wire:model="numero_documento" />

                    </x-input-group>

                    <x-input-group for="fecha_emision" label="Fecha de emisión" :error="$errors->first('fecha_emision')" class="w-full">

                        <x-input-text type="date" id="fecha_emision" wire:model="fecha_emision" />

                    </x-input-group>

                    <x-input-group for="procedencia" label="Dependencia" :error="$errors->first('procedencia')" class="w-full">

                        <x-input-text id="procedencia" wire:model="procedencia" />

                    </x-input-group>

                    <div class="flex justify-between items-center w-full col-span-3">

                        <x-button-gray
                            wire:click="cambiar('antecedente')"
                            wire:loading.attr="disabled"
                            wire:target="cambiar('antecedente')">

                            <img wire:loading wire:target="cambiar('antecedente')" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                            Anterior
                        </x-button-gray>

                        <x-button-gray
                            wire:click="cambiar('datos_sentencia')"
                            wire:loading.attr="disabled"
                            wire:target="cambiar('datos_sentencia')">

                            <img wire:loading wire:target="cambiar('datos_sentencia')" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                            Siguiente
                        </x-button-gray>

                    </div>

                </div>

            @endif

            @if($datos_sentencia)

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3  col-span-2 rounded-lg mb-3" x-transition:enter.duration.500ms x-transition:leave.duration.500ms>

                    <span class="flex items-center justify-center ext-gray-700 col-span-3">Datos de la sentencia</span>

                    <x-input-group for="acto_contenido" label="Acto contenido" :error="$errors->first('acto_contenido')" class="w-full">

                        <x-input-select id="acto_contenido" wire:model="acto_contenido" class="w-full">

                            <option value="">Seleccione una opción</option>

                            <option value="embargo">Embargo</option>

                            @foreach ($actos as $acto)

                                <option value="{{ $acto }}">{{ $acto }}</option>

                            @endforeach

                        </x-input-select>

                    </x-input-group>

                    <x-input-group for="comentario" label="Comentario de la sentencia" :error="$errors->first('comentario')" class="col-span-3">

                        <textarea rows="3" class="w-full bg-white rounded" wire:model="comentario"></textarea>

                    </x-input-group>

                    <div class="flex justify-between items-center w-full col-span-3">

                        <x-button-gray
                            wire:click="cambiar('documento_entrada')"
                            wire:loading.attr="disabled"
                            wire:target="cambiar('documento_entrada')">

                            <img wire:loading wire:target="cambiar('documento_entrada')" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                            Anterior
                        </x-button-gray>

                    </div>

                </div>

            @endif

        </x-slot>

        <x-slot name="footer">

            {{ $errors }}

            <div class="flex gap-3">

                @if($crear)

                    <x-button-blue
                        wire:click="guardarSentencia"
                        wire:loading.attr="disabled"
                        wire:target="guardarSentencia">

                        <img wire:loading wire:target="guardarSentencia" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        <span>Guardar</span>
                    </x-button-blue>

                @elseif($editar)

                    <x-button-blue
                        wire:click="guardarSentencia"
                        wire:loading.attr="disabled"
                        wire:target="guardarSentencia">

                        <img wire:loading wire:target="guardarSentencia" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

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

    <x-confirmation-modal wire:model="modalBorrar" maxWidth="sm">

        <x-slot name="title">
            Eliminar sentencia
        </x-slot>

        <x-slot name="content">
            ¿Esta seguro que desea eliminar la sentencia? No sera posible recuperar la información.
        </x-slot>

        <x-slot name="footer">

            <x-secondary-button
                wire:click="$toggle('modalBorrar')"
                wire:loading.attr="disabled"
            >
                No
            </x-secondary-button>

            <x-danger-button
                class="ml-2"
                wire:click="borrar"
                wire:loading.attr="disabled"
                wire:target="borrar"
            >
                Borrar
            </x-danger-button>

        </x-slot>

    </x-confirmation-modal>

</div>
