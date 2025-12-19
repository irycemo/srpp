<div>

    @if($gravamen->movimientoRegistral?->estado == 'carga_parcial')

        <x-input-group for="comentario" label="Comentarios del gravamen" :error="$errors->first('comentario')" class="w-full">

            <textarea rows="5" class="w-full bg-white rounded" wire:model="comentario" readonly></textarea>

        </x-input-group>

    @endif

    @if($antecedente)

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3  col-span-2 rounded-lg mb-3 w-full lg:w-2/3 mx-auto" x-transition:enter.duration.500ms x-transition:leave.duration.500ms>

            <span class="flex items-center justify-center  text-gray-700 md:col-span-3 col-span-1 sm:col-span-2">Antecedente</span>

            <x-input-group for="antecente_tomo" label="Tomo" :error="$errors->first('antecente_tomo')" class="w-full">

                <x-input-text type="number" id="antecente_tomo" wire:model="antecente_tomo" />

            </x-input-group>

            <x-input-group for="antecente_registro" label="Registro" :error="$errors->first('antecente_registro')" class="w-full">

                <x-input-text type="number" id="antecente_registro" wire:model="antecente_registro" />

            </x-input-group>

            <div class="flex justify-between items-center w-full col-span-3">

                <x-input-select id="acto_sin_antecedente" wire:model.live="acto_sin_antecedente" class="w-full" >

                    <option value="">Selecciona una opción</option>
                    <option value="RESERVA DE DOMINIO">Reserva de dominio</option>
                    <option value="ANOTACIONES MARGINALES">Anotaciones marginales</option>

                </x-input-select>

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

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3  col-span-2 rounded-lg mb-3 w-full lg:w-2/3 mx-auto" x-transition:enter.duration.500ms x-transition:leave.duration.500ms>

            <span class="flex items-center justify-center text-gray-700 md:col-span-3 col-span-1 sm:col-span-2">Documento de entrada</span>

            <x-input-group for="tipo_documento" label="Tipo de documento" :error="$errors->first('tipo_documento')" class="w-full">

                <x-input-select id="tipo_documento" wire:model.live="tipo_documento" class="w-full" >

                    <option value="">Seleccione una opción</option>
                    <option value="escritura">Escritura</option>
                    <option value="oficio">Oficio</option>
                    <option value="contrato">Contrato</option>
                    <option value="acta de embargo">Acta de embargo</option>
                    <option value="convenio">Convenio</option>
                    <option value="fianza">Fianza</option>
                    <option value="exhorto">Exhorto</option>

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

            <div class="flex justify-between items-center w-full col-span-3 rounded-full bg-slate-100">

                <x-button-gray
                    wire:click="regresarA('antecedente')"
                    wire:loading.attr="disabled"
                    wire:target="regresarA('antecedente')">

                    <img wire:loading wire:target="regresarA('antecedente')" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
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

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3  col-span-2 rounded-lg mb-3 w-full lg:w-2/3 mx-auto" x-transition:enter.duration.500ms x-transition:leave.duration.500ms>

            <span class="flex items-center justify-center ext-gray-700 col-span-3">Datos del gravámen</span>

            <x-input-group for="acto_contenido" label="Acto contenido" :error="$errors->first('acto_contenido')" class="w-full">

                <x-input-select id="acto_contenido" wire:model="acto_contenido" class="w-full" :disabled="isset($this->acto_sin_antecedente)">

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

                @if($errors->first('divisa'))

                    <div class="text-red-500 text-sm mt-1"> {{ $errors->first('divisa') }} </div>

                @endif

            </x-input-group>

            <x-input-group for="fecha_inscripcion" label="Fecha de inscripción" :error="$errors->first('fecha_inscripcion')" class="w-full">

                <x-input-text type="date" id="fecha_inscripcion" wire:model="fecha_inscripcion" />

            </x-input-group>

            @if(in_array($gravamen->movimientoRegistral->estado, ['pase_folio']))

                <x-input-group for="comentario" label="Comentario del gravámen" :error="$errors->first('comentario')" class="col-span-3">

                    <textarea rows="3" class="w-full bg-white rounded" wire:model="comentario"></textarea>

                </x-input-group>

            @endif

            <div class="flex justify-between items-center w-full col-span-3 rounded-full bg-slate-100">

                <x-button-gray
                    wire:click="regresarA('documento_entrada')"
                    wire:loading.attr="disabled"
                    wire:target="regresarA('documento_entrada')">

                    <img wire:loading wire:target="regresarA('documento_entrada')" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
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

        <div class="w-full lg:w-2/3 mx-auto" x-transition:enter.duration.500ms x-transition:leave.duration.500ms>

            <span class="flex items-center justify-center text-gray-700 md:col-span-3 col-span-1 sm:col-span-2">Actores</span>

            <div class="flex justify-end mb-3">

                @livewire('comun.actores.deudor-crear', ['sub_tipos' => $actores, 'modelo' => $gravamen, 'visible' => true])

            </div>

            <div>

                <x-table>

                    <x-slot name="head">
                        <x-table.heading >Actor</x-table.heading>
                        <x-table.heading >Tipo</x-table.heading>
                        <x-table.heading ></x-table.heading>
                    </x-slot>

                    <x-slot name="body">

                        @foreach ($gravamen->deudores as $participante)

                            <x-table.row wire:key="row-{{ $participante->id }}">

                                <x-table.cell>{{ $participante->persona->nombre }} {{ $participante->persona->ap_paterno }} {{ $participante->persona->ap_materno }} {{ $participante->persona->razon_social }}</x-table.cell>
                                <x-table.cell>{{ $participante->tipo_deudor }}</x-table.cell>
                                <x-table.cell>
                                    <div class="flex justify-end items-center gap-3">

                                        <div>

                                            <livewire:comun.actores.deudor-actualizar :sub_tipos="$actores" :actor="$participante" wire:key="button-deudor-{{ $participante->id }}" />

                                        </div>

                                        <x-button-red
                                            wire:click="eliminarActor({{ $participante->id }})"
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

            <div class="flex justify-between items-center w-full col-span-3 mt-5 rounded-full bg-slate-100">

                <x-button-gray
                    wire:click="regresarA('datos_gravamen')"
                    wire:loading.attr="disabled"
                    wire:target="regresarA('datos_gravamen')">

                    <img wire:loading wire:target="regresarA('datos_gravamen')" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
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

        <div class="w-full lg:w-2/3 mx-auto" x-transition:enter.duration.500ms x-transition:leave.duration.500ms>

            <span class="flex items-center justify-center text-gray-700 col-span-3">Acreedores</span>

            <div class="flex justify-end mb-3">

                @livewire('comun.actores.acreedor-crear', ['modelo' => $gravamen, 'visible' => true])

            </div>

            <div>

                <x-table>

                    <x-slot name="head">
                        <x-table.heading >Acreedor</x-table.heading>
                        <x-table.heading ></x-table.heading>
                    </x-slot>

                    <x-slot name="body">

                        @foreach ($gravamen->acreedores as $participante)

                            <x-table.row wire:key="row-{{ $participante->id }}">

                                <x-table.cell>{{ $participante->persona->nombre }} {{ $participante->persona->ap_paterno }} {{ $participante->persona->ap_materno }} {{ $participante->persona->razon_social }}</x-table.cell>
                                <x-table.cell>
                                    <div class="flex justify-end items-center gap-3">

                                        <div>

                                            <livewire:comun.actores.acreedor-actualizar :actor="$participante" wire:key="button-acreedor-{{ $participante->id }}" />

                                        </div>

                                        <x-button-red
                                            wire:click="eliminarActor({{ $participante->id }})"
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

            <div class="flex justify-start items-center w-full col-span-3 mt-5 rounded-full bg-slate-100">

                <x-button-gray
                    wire:click="regresarA('deudores')"
                    wire:loading.attr="disabled"
                    wire:target="regresarA('deudores')">

                    <img wire:loading wire:target="regresarA('deudores')" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                    Anterior
                </x-button-gray>

            </div>

        </div>

    @endif

    <div class="flex justify-end gap-3 mt-2">

        @if($acreedores)

            <x-button-red
                wire:click="cerrar"
                wire:loading.attr="disabled"
                wire:target="cerrar"
                type="button">
                Regresar
            </x-button-red>

            <x-button-blue
                wire:click="finalizar"
                wire:loading.attr="disabled"
                wire:target="finalizar">

                <img wire:loading wire:target="finalizar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                <span>Finalizar</span>
            </x-button-blue>

        @else

            <x-button-red
                wire:click="cerrar"
                wire:loading.attr="disabled"
                wire:target="cerrar"
                type="button">
                Regresar
            </x-button-red>

        @endif

    </div>

</div>
