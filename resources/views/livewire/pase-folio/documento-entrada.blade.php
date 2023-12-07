<div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

    <div class="col-span-2">

        <div class="grid grid-cols-1 md:grid-cols-3 sm:grid-cols-2 gap-3 mb-3 bg-white rounded-lg p-3 shadow-lg">

            <span class="flex items-center justify-center text-lg text-gray-700 md:col-span-3 col-span-1 sm:col-span-2">Antecedente</span>

            <x-input-group for="folio_real" label="Folio real" class="w-full">

                <x-input-text id="folio_real" value="{{ $movimientoRegistral->folioReal?->folio }}" readonly/>

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

                <x-input-text id="seccion" value="{{ $movimientoRegistral->seccion }}" readonly/>

            </x-input-group>

        </div >

        <div class="grid grid-cols-1 md:grid-cols-3 sm:grid-cols-2 gap-3 mb-3 bg-white rounded-lg p-3 shadow-lg">

            <span class="flex items-center justify-center text-lg text-gray-700 md:col-span-3 col-span-1 sm:col-span-2">Documento de entrada</span>

            <x-input-group for="tipo_documento" label="Tipo de documento" :error="$errors->first('tipo_documento')" class="w-full">

                <x-input-select id="tipo_documento" wire:model.live="tipo_documento" class="w-full">

                    <option value="">Seleccione una opción</option>
                    <option value="escritura">Escritura</option>
                    <option value="oficio">Oficio</option>

                </x-input-select>

            </x-input-group>

            @if($tipo_documento == 'oficio')

            <x-input-group for="autoridad_cargo" label="Autoridad cargo" :error="$errors->first('autoridad_cargo')" class="w-full">

                <x-input-select id="autoridad_cargo" wire:model.live="autoridad_cargo" class="w-full">

                    <option value="">Seleccione una opción</option>
                    <option value="juez">Juez(a)</option>
                    <option value="funcionario">Funcionario</option>

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

            @elseif ($tipo_documento == 'escritura')

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

                <x-input-group for="escritura_observaciones" label="Observaciones" :error="$errors->first('escritura_observaciones')" class="sm:col-span-2 lg:col-span-3">

                    <textarea rows="3" class="w-full bg-white rounded" wire:model="escritura_observaciones"></textarea>

                </x-input-group>


            @endif

        </div>

    </div>

    <div class="bg-white rounded-lg p-2 mb-3 shadow-lg">

        <span class="flex items-center justify-center text-lg text-gray-700">Información de la base de datos</span>

    </div>

</div>

<div class=" flex justify-end items-center bg-white rounded-lg p-2 shadow-lg">

    <x-button-blue
        wire:click="guardarDocumentoEntrada"
        wire:loading.attr="disabled"
        wire:target="guardarDocumentoEntrada">

        <img wire:loading wire:target="guardarDocumentoEntrada" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
        Guardar y continuar
    </x-button-blue>

</div>
