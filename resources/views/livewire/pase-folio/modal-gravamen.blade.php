<div class="p-4 sm:max-w-md md:max-w-xl lg:max-w-2xl">

    <div class="text-lg text-gray-700 mb-3">

        @if($editar)
            Editar Gravamen
        @else
            Nuevo Gravamen
        @endif

    </div>

    <div>

        @if($antecedente)

            @if($gravamen->movimientoRegistral?->estado == 'concluido')

                <x-input-group for="comentario" label="Comentarios del gravamen" :error="$errors->first('comentario')" class="w-full">

                    <textarea rows="5" class="w-full bg-white rounded" wire:model="comentario" readonly></textarea>

                </x-input-group>

            @endif

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

            @if($gravamen->movimientoRegistral?->estado == 'concluido')

                <x-input-group for="comentario" label="Comentarios del gravamen" :error="$errors->first('comentario')" class="w-full">

                    <textarea rows="5" class="w-full bg-white rounded" wire:model="comentario" readonly></textarea>

                </x-input-group>

            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3  col-span-2 rounded-lg mb-3" x-transition:enter.duration.500ms x-transition:leave.duration.500ms>

                <span class="flex items-center justify-center text-gray-700 md:col-span-3 col-span-1 sm:col-span-2">Documento de entrada</span>

                <x-input-group for="tipo_documento" label="Tipo de documento" :error="$errors->first('tipo_documento')" class="w-full">

                    <x-input-select id="tipo_documento" wire:model.live="tipo_documento" class="w-full">

                        <option value="">Seleccione una opción</option>
                        <option value="escritura">Escritura</option>
                        <option value="oficio">Oficio</option>
                        <option value="contrato">Contrato</option>
                        <option value="acta de embargo">Acta de embargo</option>
                        <option value="convenio">Convenio</option>
                        <option value="fianza">Fianza</option>

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

                <x-input-group for="procedencia" label="Procedencia" :error="$errors->first('procedencia')" class="w-full">

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
                        wire:click="cambiar('datos_gravamen')"
                        wire:loading.attr="disabled"
                        wire:target="cambiar('datos_gravamen')">

                        <img wire:loading wire:target="cambiar('datos_gravamen')" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                        Siguiente
                    </x-button-gray>

                </div>

            </div>

        @endif

        @if($datos_gravamen)

            @if($gravamen->movimientoRegistral?->estado == 'concluido')

                <x-input-group for="comentario" label="Comentarios del gravamen" :error="$errors->first('comentario')" class="w-full">

                    <textarea rows="5" class="w-full bg-white rounded" wire:model="comentario" readonly></textarea>

                </x-input-group>

            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3  col-span-2 rounded-lg mb-3" x-transition:enter.duration.500ms x-transition:leave.duration.500ms>

                <span class="flex items-center justify-center ext-gray-700 col-span-3">Datos del gravámen</span>

                <x-input-group for="acto_contenido" label="Acto contenido" :error="$errors->first('acto_contenido')" class="w-full">

                    <x-input-select id="acto_contenido" wire:model="acto_contenido" class="w-full">

                        <option value="">Seleccione una opción</option>

                        @foreach ($actos as $acto)

                            <option value="{{ $acto}}">{{ $acto }}</option>

                        @endforeach

                    </x-input-select>

                </x-input-group>

                <x-input-group for="tipo" label="Tipo" :error="$errors->first('tipo')" class="w-full col-span-2">

                    <x-input-text id="tipo" wire:model="tipo" />

                </x-input-group>

                <x-input-group for="valor_gravamen" label="Valor del gravamen" :error="$errors->first('valor_gravamen')" class="w-full relative">

                    <x-input-text type="number" id="valor_gravamen" wire:model="valor_gravamen" />

                    <div class="absolute right-0 top-6">

                        <x-input-select id="divisa" wire:model="divisa">

                            <option value="">-</option>

                            @foreach ($divisas as $divisa)

                                <option value="{{ $divisa }}">{{ $divisa }}</option>

                            @endforeach

                        </x-input-select>

                    </div>

                </x-input-group>

                <x-input-group for="fecha_inscripcion" label="Fecha de inscripción" :error="$errors->first('fecha_inscripcion')" class="w-full">

                    <x-input-text type="date" id="fecha_inscripcion" wire:model="fecha_inscripcion" />

                </x-input-group>

                {{-- <x-input-group for="estado" label="Estado" :error="$errors->first('estado')" class="w-full">

                    <x-input-select id="estado" wire:model="estado" class="w-full">

                        <option value="">Seleccione una opción</option>
                        <option value="activo">Activo</option>
                        <option value="cancelado">Cancelado</option>
                        <option value="parcial">Parcial</option>
                        <option value="reserva">Reserva de dominio</option>

                    </x-input-select>

                </x-input-group> --}}

                @if($gravamen->movimientoRegistral->estado == 'nuevo')

                    <x-input-group for="comentario" label="Comentario del gravámen" :error="$errors->first('comentario')" class="col-span-3">

                        <textarea rows="3" class="w-full bg-white rounded" wire:model="comentario"></textarea>

                    </x-input-group>

                @endif

                <div class="flex justify-between items-center w-full col-span-3">

                    <x-button-gray
                        wire:click="cambiar('documento_entrada')"
                        wire:loading.attr="disabled"
                        wire:target="cambiar('documento_entrada')">

                        <img wire:loading wire:target="cambiar('documento_entrada')" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                        Anterior
                    </x-button-gray>

                    <x-button-gray
                        wire:click="cambiar('deudores')"
                        wire:loading.attr="disabled"
                        wire:target="cambiar('deudores')">

                        <img wire:loading wire:target="cambiar('deudores')" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                        Siguiente
                    </x-button-gray>

                </div>

            </div>

        @endif

        @if($deudores)

            @if($gravamen->movimientoRegistral?->estado == 'concluido')

                <x-input-group for="comentario" label="Comentarios del gravamen" :error="$errors->first('comentario')" class="w-full">

                    <textarea rows="5" class="w-full bg-white rounded" wire:model="comentario" readonly></textarea>

                </x-input-group>

            @endif

            <div x-transition:enter.duration.500ms x-transition:leave.duration.500ms>

                <span class="flex items-center justify-center text-gray-700 md:col-span-3 col-span-1 sm:col-span-2">Actores</span>

                {{--  --}}

                <div class="flex justify-between items-center w-full col-span-3 mb-3">

                    <x-button-gray
                        wire:click="cambiar('datos_gravamen')"
                        wire:loading.attr="disabled"
                        wire:target="cambiar('datos_gravamen')">

                        <img wire:loading wire:target="cambiar('datos_gravamen')" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                        Anterior
                    </x-button-gray>

                    <x-button-gray
                        wire:click="cambiar('acreedores')"
                        wire:loading.attr="disabled"
                        wire:target="cambiar('acreedores')">

                        <img wire:loading wire:target="cambiar('acreedores')" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                        Siguiente
                    </x-button-gray>

                </div>

            </div>

        @endif

        @if($acreedores)

            @if($gravamen->movimientoRegistral?->estado == 'concluido')

                <x-input-group for="comentario" label="Comentarios del gravamen" :error="$errors->first('comentario')" class="w-full">

                    <textarea rows="5" class="w-full bg-white rounded" wire:model="comentario" readonly></textarea>

                </x-input-group>

            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3  col-span-2 rounded-lg mb-3" x-transition:enter.duration.500ms x-transition:leave.duration.500ms>

                <span class="flex items-center justify-center text-gray-700 col-span-3">Acreedores</span>

                <div class="col-span-3 mb-4">

                    <div class="mb-2 flex justify-end">

                        <x-button-blue wire:click="$dispatch('openModal', { component: 'modals.crear-persona', arguments: { crear: true, title: 'Acreedor' } } )">Agregar acreedor</x-button-blue>

                    </div>

                    <div class="col-span-3">

                        @if($gravamen)

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
                                                        wire:click="$dispatch('openModal', { component: 'modals.crear-persona', arguments: { editar: true, title: 'Acreedor', id:{{ $acreedor->persona->id }} } } )"
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

                        @endif

                    </div>

                </div>

                <div class="flex justify-start items-center w-full col-span-3">

                    <x-button-gray
                        wire:click="cambiar('deudores')"
                        wire:loading.attr="disabled"
                        wire:target="cambiar('deudores')">

                        <img wire:loading wire:target="cambiar('deudores')" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                        Anterior
                    </x-button-gray>

                </div>

            </div>

        @endif

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
                    wire:click="guardar"
                    wire:loading.attr="disabled"
                    wire:target="guardar">

                    <img wire:loading wire:target="guardar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <span>Actualizar</span>
                </x-button-blue>

            @endif

            <x-button-red
                wire:click="cerrar"
                wire:loading.attr="disabled"
                wire:target="cerrar"
                type="button">
                Cerrar
            </x-button-red>

        </div>

    </div>

    <x-confirmation-modal wire:model="modalD" maxWidth="sm">

        <x-slot name="title">
            Eliminar gravamen
        </x-slot>

        <x-slot name="content">
            ¿Esta seguro que desea eliminar el gravamen? No sera posible recuperar la información.
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

