<div>

    <x-header>Asignación de folio real de persona moral</x-header>

    <div class="p-4 bg-white shadow-xl rounded-xl mb-5 space-y-3">

        <span class="flex items-center justify-center text-lg text-gray-700">Antecedente</span>

        <div class="flex gap-3 items-center w-full lg:w-1/4 justify-center mx-auto">

            <x-input-group for="tomo" label="Tomo" :error="$errors->first('tomo')" class="w-full">

                <x-input-text type="number" id="tomo" wire:model="tomo"/>

            </x-input-group>

            <x-input-group for="registro" label="Registro" :error="$errors->first('registro')" class="w-full">

                <x-input-text type="number" id="registro" wire:model="registro" />

            </x-input-group>

        </div>

    </div>

    <div class="p-4 bg-white shadow-xl rounded-xl mb-5 space-y-3">

        <span class="flex items-center justify-center text-lg text-gray-700">Escritura</span>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto">

            <x-input-group for="numero_escritura" label="Número de escritura" :error="$errors->first('numero_escritura')" class="w-full">

                <x-input-text type="number" id="numero_escritura" wire:model="numero_escritura" />

            </x-input-group>

            <x-input-group for="notaria" label="Número de notaría" :error="$errors->first('notaria')" class="w-full">

                <x-input-text type="number" id="notaria" wire:model="notaria" />

            </x-input-group>

            <x-input-group for="nombre_notario" label="Nombre del notario" :error="$errors->first('nombre_notario')" class="w-full">

                <x-input-text id="nombre_notario" wire:model="nombre_notario" />

            </x-input-group>

        </div>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto">

            <x-input-group for="escritura_fecha_inscripcion" label="Fecha de inscripcion" :error="$errors->first('escritura_fecha_inscripcion')" class="w-full">

                <x-input-text type="date" id="escritura_fecha_inscripcion" wire:model="escritura_fecha_inscripcion" />

            </x-input-group>

            <x-input-group for="escritura_fecha_escritura" label="Fecha de la escritura" :error="$errors->first('escritura_fecha_escritura')" class="w-full">

                <x-input-text type="date" id="escritura_fecha_escritura" wire:model="escritura_fecha_escritura" />

            </x-input-group>

            <x-input-group for="numero_hojas" label="Número de hojas" :error="$errors->first('numero_hojas')" class="w-full">

                <x-input-text type="number" id="numero_hojas" wire:model="numero_hojas" />

            </x-input-group>

            <x-input-group for="numero_paginas" label="Número de paginas" :error="$errors->first('numero_paginas')" class="w-full">

                <x-input-text type="number" id="numero_paginas" wire:model="numero_paginas" />

            </x-input-group>

        </div>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto">

            <x-input-group for="observaciones_escritura" label="Observaciones" :error="$errors->first('observaciones_escritura')" class="w-full">

                <textarea rows="3" class="w-full bg-white rounded" wire:model="observaciones_escritura"></textarea>

            </x-input-group>

        </div>

    </div>

    <div class="p-4 bg-white shadow-xl rounded-xl mb-5 space-y-3">

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto">

            <x-input-group for="denominacion" label="Denominación ó razón social" :error="$errors->first('denominacion')" class="w-full">

                <x-input-text id="denominacion" wire:model="denominacion" />

            </x-input-group>

        </div>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto">

            <x-input-group for="fecha_constitucion" label="Fecha de constitución" :error="$errors->first('fecha_constitucion')" class="w-full">

                <x-input-text type="date" id="fecha_constitucion" wire:model="fecha_constitucion" />

            </x-input-group>

            <x-input-group for="capital" label="Capital" :error="$errors->first('capital')" class="w-full">

                <x-input-text type="number" id="capital" wire:model="capital" />

            </x-input-group>

            <x-input-group for="duracion" label="Duración" :error="$errors->first('duracion')" class="w-full">

                <x-input-text type="number" id="duracion" wire:model="duracion" />

            </x-input-group>

            <x-input-group for="distrito" label="Distrito" :error="$errors->first('distrito')" class="w-full">

                <x-input-select id="distrito" wire:model="distrito" class="w-full">

                    <option value="">Seleccione una opción</option>

                    @foreach ($distritos as $key => $distrito)

                        <option value="{{ $key }}">{{ $distrito }}</option>

                    @endforeach

                </x-input-select>

            </x-input-group>

            <x-input-group for="tipo" label="Tipo" :error="$errors->first('tipo')" class="w-full">

                <x-input-select id="tipo" wire:model="tipo" class="w-full">

                    <option value="">Seleccione una opción</option>
                    <option value="lucrativa">Lucrativa</option>
                    <option value="no_lucrativa">No lucrativa</option>

                </x-input-select>

            </x-input-group>

        </div>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto">

            <x-input-group for="objeto" label="Objeto" :error="$errors->first('objeto')" class="w-full">

                <textarea rows="3" class="w-full bg-white rounded" wire:model="objeto"></textarea>

            </x-input-group>

        </div>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto">

            <x-input-group for="domicilio" label="Domicilio" :error="$errors->first('domicilio')" class="w-full">

                <textarea rows="3" class="w-full bg-white rounded" wire:model="domicilio"></textarea>

            </x-input-group>

        </div>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto">

            <x-input-group for="observaciones" label="Observaciones" :error="$errors->first('observaciones')" class="w-full">

                <textarea rows="3" class="w-full bg-white rounded" wire:model="observaciones"></textarea>

            </x-input-group>

        </div>

    </div>

    <div class="p-4 bg-white shadow-xl rounded-xl mb-5">

        <div class="w-full lg:w-1/2 justify-center mx-auto">

            <span class="flex items-center justify-center text-gray-700">Participantes</span>

            <div class="">

                @livewire('comun.actores.socio-crear', ['sub_tipos' => $actores, 'modelo' => $movimientoRegistral->folioRealPersona])

                <div>

                    @if($movimientoRegistral?->folioRealPersona)

                        <x-table>

                            <x-slot name="head">
                                <x-table.heading >Participante</x-table.heading>
                                <x-table.heading >Tipo</x-table.heading>
                                <x-table.heading ></x-table.heading>
                            </x-slot>

                            <x-slot name="body">

                                @foreach ($movimientoRegistral->folioRealPersona->actores as $participante)

                                    <x-table.row wire:key="row-{{ $participante->id }}">

                                        <x-table.cell>{{ $participante->persona->nombre }} {{ $participante->persona->ap_paterno }} {{ $participante->persona->ap_materno }} {{ $participante->persona->razon_social }}</x-table.cell>
                                        <x-table.cell>{{ $participante->tipo_socio }}</x-table.cell>
                                        <x-table.cell>
                                            <div class="flex items-center gap-3">

                                                <div class="flex w-full">
                                                    <livewire:comun.actores.socio-actualizar :sub_tipos="$actores" :modelo="$movimientoRegistral->folioRealPersona" :actor="$participante" wire:key="button-{{ $participante->id }}" />
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

                    @endif

                </div>

            </div>

        </div>

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

    <div class="bg-white rounded-lg p-3 flex justify-end shadow-lg gap-3">

        @if($movimientoRegistral->folioRealPersona)

            @if(!$movimientoRegistral->folioRealPersona->documentoEntrada())

                @livewire('comun.documento-entrada', ['folioRealPersonaMoral' => $movimientoRegistral->folioRealPersona])

            @else

                <div class="inline-block">

                    <x-link-blue target="_blank" href="{{ $movimientoRegistral->folioRealPersona->documentoEntrada() }}">Documento de entrada</x-link-blue>

                </div>

            @endif

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

    @filepondScripts

</div>
