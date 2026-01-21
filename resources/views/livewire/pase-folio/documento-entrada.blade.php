<div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

    <div class="col-span-2">

        @if($movimientoRegistral->inscripcionPropiedad?->servicio != 'D157')

            <div class="grid grid-cols-1 md:grid-cols-3 sm:grid-cols-2 gap-3 mb-3 bg-white rounded-lg p-3 shadow-lg">

                <span class="flex items-center justify-center text-lg text-gray-700 md:col-span-3 col-span-1 sm:col-span-2">Antecedente</span>

                <x-input-group for="folio_real" label="Folio real" class="w-full">

                    <x-input-text id="folio_real" value="{{ $movimientoRegistral->folioReal?->folioRealAntecedente?->folio }}" readonly/>

                </x-input-group>

                <x-input-group for="tomo" label="Tomo" class="w-full">

                    <x-input-text id="tomo" value="{{ $movimientoRegistral->tomo }}" readonly/>

                </x-input-group>

                <x-input-group for="registro" label="Registro" class="w-full">

                    <x-input-text id="registro" value="{{ $movimientoRegistral->registro }}" readonly/>

                </x-input-group>

                <x-input-group for="numero_propiedad" label="Número de propiedad" class="w-full">

                    <x-input-text id="numero_propiedad" value="{{ $movimientoRegistral->numero_propiedad }}" readonly/>

                </x-input-group>

                <x-input-group for="distrito" label="Distrito" class="w-full">

                    <x-input-text id="distrito" value="{{ $movimientoRegistral->distrito }}" readonly/>

                </x-input-group>

                <x-input-group for="seccion" label="Sección" class="w-full">

                    <x-input-text id="seccion" value="Propiedad" readonly/>

                </x-input-group>

            </div >

        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 sm:grid-cols-2 gap-3 mb-3 bg-white rounded-lg p-3 shadow-lg">

            <span class="flex items-center justify-center text-lg text-gray-700 md:col-span-3 col-span-1 sm:col-span-2">Documento de entrada</span>

            <x-input-group for="tipo_documento" label="Tipo de documento" :error="$errors->first('tipo_documento')" class="w-full">

                <x-input-select id="tipo_documento" wire:model.live="tipo_documento" class="w-full">

                    <option value="">Seleccione una opción</option>

                    @foreach ($documentos_de_entrada as $item)

                        <option value="{{ $item }}">{{ $item }}</option>

                    @endforeach

                </x-input-select>

            </x-input-group>

            @if(!in_array($tipo_documento, ['ESCRITURA PÚBLICA', 'ESCRITURA PRIVADA']))

                <x-input-group for="autoridad_cargo" label="Autoridad cargo" :error="$errors->first('autoridad_cargo')" class="w-full">

                    <x-input-select id="autoridad_cargo" wire:model.live="autoridad_cargo" class="w-full">

                        <option value="">Seleccione una opción</option>

                        @foreach ($cargos_autoridad as $item)

                            <option value="{{ $item }}">{{ $item }}</option>

                        @endforeach

                    </x-input-select>

                </x-input-group>

                <x-input-group for="autoridad_nombre" label="Nombre de la autoridad" :error="$errors->first('autoridad_nombre')" class="w-full">

                    <x-input-text id="autoridad_nombre" wire:model="autoridad_nombre" />

                </x-input-group>

                <x-input-group for="autoridad_numero" label="Número de la autoridad" :error="$errors->first('autoridad_numero')" class="w-full">

                    <x-input-text id="autoridad_numero" wire:model="autoridad_numero" />

                </x-input-group>

                <x-input-group for="numero_documento" label="Número de documento / oficio" :error="$errors->first('numero_documento')" class="w-full">

                    <x-input-text id="numero_documento" wire:model="numero_documento" />

                </x-input-group>

                <x-input-group for="fecha_emision" label="Fecha de emisión" :error="$errors->first('fecha_emision')" class="w-full">

                    <x-input-text type="date" id="fecha_emision" wire:model="fecha_emision" />

                </x-input-group>

                <x-input-group for="fecha_inscripcion" label="Fecha de inscripción" :error="$errors->first('fecha_inscripcion')" class="w-full">

                    <x-input-text type="date" id="fecha_inscripcion" wire:model="fecha_inscripcion" />

                </x-input-group>

                <x-input-group for="procedencia" label="Dependencia" :error="$errors->first('procedencia')" class="w-full">

                    <x-input-text id="procedencia" wire:model="procedencia" />

                </x-input-group>

                <x-input-group for="acto_contenido_antecedente" label="Acto contenido" :error="$errors->first('acto_contenido_antecedente')" class="w-full">

                    <x-input-select id="acto_contenido_antecedente" wire:model.live="acto_contenido_antecedente" class="w-full">

                        <option value="">Seleccione una opción</option>

                        @foreach ($actos_contenidos as $acto)

                            <option value="{{ $acto }}">{{ $acto }}</option>

                        @endforeach

                    </x-input-select>

                </x-input-group>

                <x-input-group for="observaciones_antecedente" label="Descripción" :error="$errors->first('observaciones_antecedente')" class="sm:col-span-2 lg:col-span-3">

                    <textarea rows="3" class="w-full bg-white rounded text-sm" wire:model="observaciones_antecedente"></textarea>

                </x-input-group>

            @elseif ($tipo_documento == 'ESCRITURA PÚBLICA' || $tipo_documento == 'ESCRITURA PRIVADA')

                <x-input-group for="escritura_numero" label="Número de escritura" :error="$errors->first('escritura_numero')" class="w-full">

                    <x-input-text type="number" id="escritura_numero" wire:model="escritura_numero" />

                </x-input-group>

                <x-input-group for="escritura_notaria" label="Número de notaría" :error="$errors->first('escritura_notaria')" class="w-full">

                    <x-input-text type="number" id="escritura_notaria" wire:model="escritura_notaria" />

                </x-input-group>

                <x-input-group for="escritura_nombre_notario" label="Nombre del notario" :error="$errors->first('escritura_nombre_notario')" class="w-full">

                    <x-input-text id="escritura_nombre_notario" wire:model="escritura_nombre_notario" />

                </x-input-group>

                <x-input-group for="escritura_estado_notario" label="Estado del notario" :error="$errors->first('escritura_estado_notario')" class="w-full">

                    <x-input-select id="escritura_estado_notario" wire:model.live="escritura_estado_notario" class="w-full">

                        <option value="">Seleccione una opción</option>

                        @foreach ($estados as $estado)

                            <option value="{{ $estado }}">{{ $estado }}</option>

                        @endforeach

                    </x-input-select>

                </x-input-group>

                <x-input-group for="escritura_fecha_inscripcion" label="Fecha de inscripcion" :error="$errors->first('escritura_fecha_inscripcion')" class="w-full">

                    <x-input-text type="date" id="escritura_fecha_inscripcion" wire:model="escritura_fecha_inscripcion" />

                </x-input-group>

                <x-input-group for="escritura_fecha_escritura" label="Fecha de la escritura" :error="$errors->first('escritura_fecha_escritura')" class="w-full">

                    <x-input-text type="date" id="escritura_fecha_escritura" wire:model="escritura_fecha_escritura" />

                </x-input-group>

                <x-input-group for="escritura_numero_hojas" label="Número de hojas" :error="$errors->first('escritura_numero_hojas')" class="w-full">

                    <x-input-text type="number" id="escritura_numero_hojas" wire:model="escritura_numero_hojas" />

                </x-input-group>

                <x-input-group for="escritura_numero_paginas" label="Número de paginas" :error="$errors->first('escritura_numero_paginas')" class="w-full">

                    <x-input-text type="number" id="escritura_numero_paginas" wire:model="escritura_numero_paginas" />

                </x-input-group>

                <x-input-group for="acto_contenido_antecedente" label="Acto contenido" :error="$errors->first('acto_contenido_antecedente')" class="w-full">

                    <x-input-select id="acto_contenido_antecedente" wire:model.live="acto_contenido_antecedente" class="w-full">

                        <option value="">Seleccione una opción</option>

                        @foreach ($actos_contenidos as $acto)

                            <option value="{{ $acto }}">{{ $acto }}</option>

                        @endforeach

                    </x-input-select>

                </x-input-group>

                <x-input-group for="escritura_observaciones" label="Descripción" :error="$errors->first('escritura_observaciones')" class="sm:col-span-2 lg:col-span-3">

                    <textarea rows="3" class="w-full bg-white rounded text-sm" wire:model="escritura_observaciones"></textarea>

                </x-input-group>

            @endif

        </div>

        @if($movimientoRegistral->folioReal)

            <div class="w-full bg-white rounded-lg p-3 shadow-lg mb-3">

                <span class="flex items-center justify-center text-gray-700">Antecedentes a fusionar ó vincular</span>

                <div class="">

                    <div class="mb-2 flex justify-end">

                        <x-button-blue wire:click="abrirModalCrear">Agregar antecedente</x-button-blue>

                    </div>

                    <div>

                        <x-table>

                            <x-slot name="head">
                                <x-table.heading >Folio Real</x-table.heading>
                                <x-table.heading >Tomo</x-table.heading>
                                <x-table.heading >Registro</x-table.heading>
                                <x-table.heading ># Propiedad</x-table.heading>
                                <x-table.heading >Distrito</x-table.heading>
                                <x-table.heading >Sección</x-table.heading>
                                <x-table.heading ></x-table.heading>
                            </x-slot>

                            <x-slot name="body">

                                @foreach ($movimientoRegistral->folioReal->antecedentes as $antecedente)

                                    <x-table.row >

                                        <x-table.cell>{{ $antecedente->folioRealAntecedente->folio ?? 'N/A' }}</x-table.cell>
                                        <x-table.cell>{{ $antecedente->tomo_antecedente  ?? 'N/A'}}</x-table.cell>
                                        <x-table.cell>{{ $antecedente->registro_antecedente  ?? 'N/A'}}</x-table.cell>
                                        <x-table.cell>{{ $antecedente->numero_propiedad_antecedente  ?? 'N/A'}}</x-table.cell>
                                        <x-table.cell>{{ $antecedente->distrito_antecedente }}</x-table.cell>
                                        <x-table.cell>{{ $antecedente->seccion_antecedente }}</x-table.cell>
                                        <x-table.cell>
                                            <div class="flex items-center gap-3">
                                                <x-button-blue
                                                    wire:click="abrirModalEditar({{ $antecedente->id }})"
                                                    wire:loading.attr="disabled"
                                                >
                                                    Editar
                                                </x-button-blue>
                                                <x-button-red
                                                    wire:click="borrarAntecedente({{ $antecedente->id }})"
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

        @endif

    </div>

    @include('livewire.pase-folio.informacion_base_datos')

    <x-dialog-modal wire:model="modal">

        <x-slot name="title">

            @if($editar)
                Editar
            @else
                Nuevo
            @endif

        </x-slot>

        <x-slot name="content">

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 mb-3 col-span-2 rounded-lg p-3">

                <x-input-group for="folio_real_antecedente" label="Folio real" :error="$errors->first('folio_real_antecedente')" class="w-full">

                    <x-input-text type="number" id="folio_real_antecedente" wire:model.lazy="folio_real_antecedente" />

                </x-input-group>

                <x-input-group for="tomo" label="Tomo" :error="$errors->first('tomo')" class="w-full" >

                    <x-input-text type="number" id="tomo" wire:model.lazy="tomo" :readonly="$folio_real_antecedente != null"/>

                </x-input-group>

                <x-input-group for="registro" label="Registro" :error="$errors->first('registro')" class="w-full" >

                    <x-input-text type="number" id="registro" wire:model.lazy="registro" :readonly="$folio_real_antecedente != null"/>

                </x-input-group>

                <x-input-group for="numero_propiedad" label="Número de propiedad" :error="$errors->first('numero_propiedad')" class="w-full" >

                    <x-input-text type="number" id="numero_propiedad" wire:model.lazy="numero_propiedad" :readonly="$folio_real_antecedente != null"/>

                </x-input-group>

            </div>

        </x-slot>

        <x-slot name="footer">

            <div class="flex gap-3">

                @if($crear)

                <x-button-blue
                    wire:click="guardarAntecedente"
                    wire:loading.attr="disabled"
                    wire:target="guardarAntecedente">

                    <img wire:loading wire:target="guardarAntecedente" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <span>Guardar</span>

                </x-button-blue>

                @elseif($editar)

                    <x-button-blue
                        wire:click="actualizarAntecedente"
                        wire:loading.attr="disabled"
                        wire:target="actualizarAntecedente">

                        <img wire:loading wire:target="actualizarAntecedente" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

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

<div class=" flex justify-end items-center bg-white rounded-lg p-2 shadow-lg gap-3">

    <x-input-group for="folio_matriz" label="Folio matriz" :error="$errors->first('folio_matriz')" class="flex gap-3 items-center mr-auto">

        <x-checkbox wire:model.live="folio_matriz"/>

    </x-input-group>

    @if ($movimientoRegistral->folioReal)

        @if(! $movimientoRegistral->documentoEntrada())

            <x-button-blue
                wire:click="abrirModalDocumentoEntrada"
                wire:loading.attr="disabled"
                wire:target="abrirModalDocumentoEntrada">

                <img wire:loading wire:target="abrirModalDocumentoEntrada" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                Subir documento de entrada

            </x-button-blue>

        @else

            <div class="inline-block">

                <x-link-blue target="_blank" href="{{ $movimientoRegistral->folioReal->documentoEntrada() }}">Documento de entrada</x-link-blue>

            </div>

            <x-button-red
                wire:click="eliminarDocumentoEntradaPDF"
                wire:confirm="¿Esta seguro que desea eliminar el documento de entrada?"
                wire:loading.attr="disabled"
                wire:target="eliminarDocumentoEntradaPDF">

                <img wire:loading wire:target="eliminarDocumentoEntradaPDF" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                Eliminar documento de entrada

            </x-button-red>

        @endif

    @endif

    <x-button-blue
        wire:click="guardarDocumentoEntrada"
        wire:loading.attr="disabled"
        wire:target="guardarDocumentoEntrada">

        <img wire:loading wire:target="guardarDocumentoEntrada" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
        Guardar y continuar
    </x-button-blue>

    <x-button-red
        wire:click="finalizarPaseAFolio"
        wire:loading.attr="disabled"
        wire:target="finalizarPaseAFolio">

        <img wire:loading wire:target="finalizarPaseAFolio" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
        Finalizar pase a folio

    </x-button-red>

</div>

@include('livewire.comun.inscripciones.modal-guardar_documento_entrada_pdf')
