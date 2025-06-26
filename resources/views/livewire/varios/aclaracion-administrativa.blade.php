<div>

    <div class="p-4 bg-white shadow-xl rounded-xl mb-5">

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto">

            <span>{{ $vario->acto_contenido }}</span>

        </div>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto">

            <x-input-group for="vario.descripcion" label="Comentario del movimiento" :error="$errors->first('vario.descripcion')" class="w-full">

                <textarea rows="3" class="w-full bg-white rounded" wire:model="vario.descripcion"></textarea>

            </x-input-group>

        </div>

    </div>

    <div class="bg-white rounded-lg p-4 shadow-lg mb-4">

        <div class="space-y-1 sm:col-span-2 lg:col-span-3 mx-auto text-center mb-2">

            <span class="flex items-center justify-center text-gray-700">Cuenta predial</span>

            <input title="Localidad" placeholder="Localidad" type="number" class="bg-white rounded text-xs w-20 @error('vario.predio.cp_localidad') border-1 border-red-500 @enderror" wire:model.lazy="vario.predio.cp_localidad">

            <input title="Oficina" placeholder="Oficina" type="number" class="bg-white rounded text-xs w-20 @error('vario.predio.cp_oficina') border-1 border-red-500 @enderror" wire:model.defer="vario.predio.cp_oficina">

            <input title="Tipo de predio" placeholder="Tipo" type="number" class="bg-white rounded text-xs w-20 @error('vario.predio.cp_tipo_predio') border-1 border-red-500 @enderror" wire:model.defer="vario.predio.cp_tipo_predio">

            <input title="Número de registro" placeholder="Registro" type="number" class="bg-white rounded text-xs w-20 @error('vario.predio.cp_registro') border-1 border-red-500 @enderror" wire:model.lazy="vario.predio.cp_registro">

        </div>

    </div>

    <div class="p-4 bg-white shadow-xl rounded-xl mb-5">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-3 bg-white rounded-lg p-3">

            <span class="flex items-center justify-center text-lg text-gray-700  md:col-span-3 col-span-1 sm:col-span-2">Descripción del predio</span>

            <x-input-group for="vario.predio.superficie_terreno" label="Superficie de terreno" :error="$errors->first('vario.predio.superficie_terreno')" class="w-full relative">

                <x-input-text type="number" id="vario.predio.superficie_terreno" wire:model="vario.predio.superficie_terreno" />

                <div class="absolute right-0 top-6">

                    <x-input-select id="vario.predio.unidad_area" wire:model="vario.predio.unidad_area">

                        @foreach ($areas as $unidad)

                            <option value="{{ $unidad }}">{{ $unidad }}</option>

                        @endforeach

                    </x-input-select>

                </div>

            </x-input-group>

            <x-input-group for="vario.predio.superficie_construccion" label="Superficie de construcción" :error="$errors->first('vario.predio.superficie_construccion')" class="w-full relative">

                <x-input-text type="number" id="vario.predio.superficie_construccion" wire:model="vario.predio.superficie_construccion" />

                <div class="absolute right-0 top-6">

                    <x-input-select id="vario.predio.unidad_area" wire:model="vario.predio.unidad_area">

                        @foreach ($areas as $unidad)

                            <option value="{{ $unidad }}">{{ $unidad }}</option>

                        @endforeach

                    </x-input-select>

                </div>

            </x-input-group>

            <x-input-group for="vario.predio.monto_transaccion" label="Monto de la transacción" :error="$errors->first('vario.predio.monto_transaccion')" class="w-full relative">

                <x-input-text type="number" id="vario.predio.monto_transaccion" wire:model="vario.predio.monto_transaccion" />

                <div class="absolute right-0 top-6">

                    <x-input-select id="vario.predio.divisa" wire:model="vario.predio.divisa">

                        <option value="">-</option>

                        @foreach ($divisas as $divisa)

                            <option value="{{ $divisa }}">{{ $divisa }}</option>

                        @endforeach

                    </x-input-select>

                </div>

            </x-input-group>

            <x-input-group for="vario.predio.descripcion" label="Descripción" :error="$errors->first('vario.predio.descripcion')" class="sm:col-span-2 lg:col-span-3">

                <textarea rows="3" class="w-full bg-white rounded text-sm" wire:model="vario.predio.descripcion"></textarea>

            </x-input-group>

        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 mb-3 bg-white rounded-lg p-3">

            <span class="flex items-center justify-center text-lg text-gray-700 md:col-span-5 col-span-1 sm:col-span-2">Datos Complementarios</span>

            <x-input-group for="vario.predio.curt" label="CURT" :error="$errors->first('vario.predio.curt')" class="w-full">

                <x-input-text id="vario.predio.curt" wire:model="vario.predio.curt" />

            </x-input-group>

            <x-input-group for="vario.predio.superficie_judicial" label="Superficie judicial" :error="$errors->first('vario.predio.superficie_judicial')" class="w-full relative">

                <x-input-text type="number" id="vario.predio.superficie_judicial" wire:model="vario.predio.superficie_judicial" />

                <div class="absolute right-0 top-6">

                    <x-input-select id="vario.predio.unidad_area" wire:model="vario.predio.unidad_area">

                        @foreach ($areas as $unidad)

                            <option value="{{ $unidad }}">{{ $unidad }}</option>

                        @endforeach

                    </x-input-select>

                </div>

            </x-input-group>

            <x-input-group for="vario.predio.superficie_notarial" label="Superficie notarial" :error="$errors->first('vario.predio.superficie_notarial')" class="w-full relative">

                <x-input-text type="number" id="vario.predio.superficie_notarial" wire:model="vario.predio.superficie_notarial" />

                <div class="absolute right-0 top-6">

                    <x-input-select id="vario.predio.unidad_area" wire:model="vario.predio.unidad_area">

                        @foreach ($areas as $unidad)

                            <option value="{{ $unidad }}">{{ $unidad }}</option>

                        @endforeach

                    </x-input-select>

                </div>

            </x-input-group>

            <x-input-group for="vario.predio.area_comun_terreno" label="Área de terreno común" :error="$errors->first('vario.predio.area_comun_terreno')" class="w-full relative">

                <x-input-text type="number" id="vario.predio.area_comun_terreno" wire:model="vario.predio.area_comun_terreno" />

                <div class="absolute right-0 top-6">

                    <x-input-select id="vario.predio.unidad_area" wire:model="vario.predio.unidad_area">

                        @foreach ($areas as $unidad)

                            <option value="{{ $unidad }}">{{ $unidad }}</option>

                        @endforeach

                    </x-input-select>

                </div>

            </x-input-group>

            <x-input-group for="vario.predio.area_comun_construccion" label="Área de contrucción común" :error="$errors->first('vario.predio.area_comun_construccion')" class="w-full relative">

                <x-input-text type="number" id="vario.predio.area_comun_construccion" wire:model="vario.predio.area_comun_construccion" />

                <div class="absolute right-0 top-6">

                    <x-input-select id="vario.predio.unidad_area" wire:model="vario.predio.unidad_area">

                        @foreach ($areas as $unidad)

                            <option value="{{ $unidad }}">{{ $unidad }}</option>

                        @endforeach

                    </x-input-select>

                </div>

            </x-input-group>

            <x-input-group for="vario.predio.valor_terreno_comun" label="Valor de terreno común" :error="$errors->first('vario.predio.valor_terreno_comun')" class="w-full">

                <x-input-text type="number" id="vario.predio.valor_terreno_comun" wire:model="vario.predio.valor_terreno_comun" />

            </x-input-group>

            <x-input-group for="vario.predio.valor_construccion_comun" label="Valor de construcción común" :error="$errors->first('vario.predio.valor_construccion_comun')" class="w-full">

                <x-input-text type="number" id="vario.predio.valor_construccion_comun" wire:model="vario.predio.valor_construccion_comun" />

            </x-input-group>

            <x-input-group for="vario.predio.valor_total_terreno" label="Valor total del terreno" :error="$errors->first('vario.predio.valor_total_terreno')" class="w-full">

                <x-input-text type="number" id="vario.predio.valor_total_terreno" wire:model="valor_total_terreno" />

            </x-input-group>

            <x-input-group for="vario.predio.valor_total_construccion" label="Valor total de la contrucción" :error="$errors->first('vario.predio.valor_total_construccion')" class="w-full">

                <x-input-text type="number" id="vario.predio.valor_total_construccion" wire:model="vario.predio.valor_total_construccion" />

            </x-input-group>

            <x-input-group for="vario.predio.valor_catastral" label="Valor catastral" :error="$errors->first('vario.predio.valor_catastral')" class="w-full">

                <x-input-text type="number" id="vario.predio.valor_catastral" wire:model="vario.predio.valor_catastral" />

            </x-input-group>

        </div>

    </div>

    <div class="p-4 bg-white shadow-xl rounded-xl mb-5">

        @include('comun.inscripciones.colindancias')

    </div>

    <div class="p-4 bg-white shadow-xl rounded-xl mb-5">

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3 mb-3 bg-white rounded-lg p-3">

            <span class="flex items-center justify-center text-lg text-gray-700 md:col-span-5 col-span-1 sm:col-span-2">Ubicación del predio</span>

            <x-input-group for="vario.predio.codigo_postal" label="Código postal" :error="$errors->first('vario.predio.codigo_postal')" class="w-full">

                <x-input-text type="number" id="vario.predio.codigo_postal" wire:model.lazy="vario.predio.codigo_postal" />

            </x-input-group>

            <x-input-group for="vario.predio.nombre_asentamiento" label="Nombre del asentamiento" :error="$errors->first('vario.predio.nombre_asentamiento')" class="w-full">

                <x-input-text id="vario.predio.nombre_asentamiento" wire:model="vario.predio.nombre_asentamiento"/>

            </x-input-group>

            <x-input-group for="vario.predio.municipio" label="Municipio" :error="$errors->first('vario.predio.municipio')" class="w-full">

                <x-input-text id="vario.predio.municipio" wire:model="vario.predio.municipio"/>

            </x-input-group>

            <x-input-group for="vario.predio.ciudad" label="Ciudad" :error="$errors->first('vario.predio.ciudad')" class="w-full">

                <x-input-text id="vario.predio.ciudad" wire:model="vario.predio.ciudad"/>

            </x-input-group>

            <x-input-group for="vario.predio.tipo_asentamiento" label="Tipo de asentamiento" :error="$errors->first('vario.predio.tipo_asentamiento')" class="w-full">

                <x-input-text id="vario.predio.tipo_asentamiento" wire:model="vario.predio.tipo_asentamiento"/>

            </x-input-group>

            <x-input-group for="vario.predio.localidad" label="Localidad" :error="$errors->first('vario.predio.localidad')" class="w-full">

                <x-input-text id="vario.predio.localidad" wire:model="vario.predio.localidad" />

            </x-input-group>

            <x-input-group for="vario.predio.tipo_vialidad" label="Tipo de vialidad" :error="$errors->first('vario.predio.tipo_vialidad')" class="w-full">

                <x-input-select id="vario.predio.tipo_vialidad" wire:model.live="vario.predio.tipo_vialidad" class="w-full">

                    <option value="">Seleccione una opción</option>

                    @foreach ($tipos_vialidades as $vialidad)

                        <option value="{{ $vialidad }}">{{ $vialidad }}</option>

                    @endforeach

                </x-input-select>

            </x-input-group>

            <x-input-group for="vario.predio.nombre_vialidad" label="Nombre de la vialidad" :error="$errors->first('vario.predio.nombre_vialidad')" class="w-full">

                <x-input-text id="vario.predio.nombre_vialidad" wire:model="vario.predio.nombre_vialidad" />

            </x-input-group>

            <x-input-group for="vario.predio.numero_exterior" label="Número exterior" :error="$errors->first('vario.predio.numero_exterior')" class="w-full">

                <x-input-text id="vario.predio.numero_exterior" wire:model="vario.predio.numero_exterior" />

            </x-input-group>

            <x-input-group for="vario.predio.numero_interior" label="Número interior" :error="$errors->first('vario.predio.numero_interior')" class="w-full">

                <x-input-text id="vario.predio.numero_interior" wire:model="vario.predio.numero_interior" />

            </x-input-group>

            <x-input-group for="vario.predio.nombre_edificio" label="Edificio" :error="$errors->first('vario.predio.nombre_edificio')" class="w-full">

                <x-input-text id="vario.predio.nombre_edificio" wire:model="vario.predio.nombre_edificio" />

            </x-input-group>

            <x-input-group for="vario.predio.departamento_edificio" label="Departamento" :error="$errors->first('vario.predio.departamento_edificio')" class="w-full">

                <x-input-text id="vario.predio.departamento_edificio" wire:model="vario.predio.departamento_edificio" />

            </x-input-group>

            <x-input-group for="vario.predio.observaciones" label="Observaciones" :error="$errors->first('vario.predio.observaciones')" class="md:col-span-5 col-span-1 sm:col-span-2">

                <textarea rows="3" class="w-full bg-white rounded" wire:model="vario.predio.observaciones"></textarea>

            </x-input-group>

        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5 mb-3 bg-white rounded-lg p-3">

            <span class="flex items-center justify-center text-lg text-gray-700 md:col-span-5 col-span-1 sm:col-span-2">Datos Complementarios</span>

            <x-input-group for="vario.predio.lote" label="Lote" :error="$errors->first('vario.predio.lote')" class="w-full">

                <x-input-text id="vario.predio.lote" wire:model="vario.predio.lote" />

            </x-input-group>

            <x-input-group for="vario.predio.manzana" label="Manzana" :error="$errors->first('vario.predio.manzana')" class="w-full">

                <x-input-text id="vario.predio.manzana" wire:model="vario.predio.manzana" />

            </x-input-group>

            <x-input-group for="vario.predio.ejido" label="Ejido" :error="$errors->first('vario.predio.ejido')" class="w-full">

                <x-input-text id="vario.predio.ejido" wire:model="vario.predio.ejido" />

            </x-input-group>

            <x-input-group for="vario.predio.parcela" label="Parcela" :error="$errors->first('vario.predio.parcela')" class="w-full">

                <x-input-text id="vario.predio.parcela" wire:model="vario.predio.parcela" />

            </x-input-group>

            <x-input-group for="vario.predio.solar" label="Solar" :error="$errors->first('vario.predio.solar')" class="w-full">

                <x-input-text id="vario.predio.solar" wire:model="vario.predio.solar" />

            </x-input-group>

            <x-input-group for="vario.predio.poblado" label="Poblado" :error="$errors->first('vario.predio.poblado')" class="w-full">

                <x-input-text id="vario.predio.poblado" wire:model="vario.predio.poblado" />

            </x-input-group>

            <x-input-group for="vario.predio.numero_exterior_2" label="Número exterior 2" :error="$errors->first('vario.predio.numero_exterior_2')" class="w-full">

                <x-input-text id="vario.predio.numero_exterior_2" wire:model="vario.predio.numero_exterior_2" />

            </x-input-group>

            <x-input-group for="vario.predio.numero_adicional" label="Número adicional" :error="$errors->first('vario.predio.numero_adicional')" class="w-full">

                <x-input-text id="vario.predio.numero_adicional" wire:model="vario.predio.numero_adicional" />

            </x-input-group>

            <x-input-group for="vario.predio.numero_adicional_2" label="Número adicional 2" :error="$errors->first('vario.predio.numero_adicional_2')" class="w-full">

                <x-input-text id="vario.predio.numero_adicional_2" wire:model="vario.predio.numero_adicional_2" />

            </x-input-group>

            <x-input-group for="vario.predio.lote_fraccionador" label="Lote del fraccionador" :error="$errors->first('vario.predio.lote_fraccionador')" class="w-full">

                <x-input-text id="vario.predio.lote_fraccionador" wire:model="vario.predio.lote_fraccionador" />

            </x-input-group>

            <x-input-group for="vario.predio.manzana_fraccionador" label="Manzana del fraccionador" :error="$errors->first('vario.predio.manzana_fraccionador')" class="w-full">

                <x-input-text id="vario.predio.manzana_fraccionador" wire:model="vario.predio.manzana_fraccionador" />

            </x-input-group>

            <x-input-group for="vario.predio.etapa_fraccionador" label="Etapa del fraccionador" :error="$errors->first('vario.predio.etapa_fraccionador')" class="w-full">

                <x-input-text id="vario.predio.etapa_fraccionador" wire:model="vario.predio.etapa_fraccionador" />

            </x-input-group>

            <x-input-group for="vario.predio.clave_edificio" label="Clave del edificio" :error="$errors->first('vario.predio.clave_edificio')" class="w-full">

                <x-input-text id="vario.predio.clave_edificio" wire:model="vario.predio.clave_edificio" />

            </x-input-group>

        </div>

    </div>

    <div class="p-4 bg-white shadow-xl rounded-xl mb-5">

        <div class="mb-3 bg-white rounded-lg p-3">

            <span class="flex items-center justify-center text-lg text-gray-700 mb-5">Propietarios</span>

            <div class="flex justify-end mb-2">

                @livewire('comun.actores.propietario-crear', ['modelo' => $vario->predio, 'partes_iguales_flag'=> true])

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

                    @foreach ($vario->predio->actores as $propietario)

                        <x-table.row wire:key="row-propietario-{{ $propietario->id }}">

                            <x-table.cell>{{ $propietario->persona->nombre }} {{ $propietario->persona->ap_paterno }} {{ $propietario->persona->ap_materno }} {{ $propietario->persona->razon_social }}</x-table.cell>
                            <x-table.cell>{{ $propietario->porcentaje_propiedad }}%</x-table.cell>
                            <x-table.cell>{{ $propietario->porcentaje_nuda }}%</x-table.cell>
                            <x-table.cell>{{ $propietario->porcentaje_usufructo }}%</x-table.cell>
                            <x-table.cell>
                                <div class="flex items-center gap-3">

                                    <div>

                                        <livewire:comun.actores.propietario-actualizar :actor="$propietario" :predio="$vario->predio" :partes_iguales_flag="true" wire:key="button-propietario-{{ $propietario->id }}" />

                                    </div>

                                    <x-button-red
                                        wire:click="borrarActor({{ $propietario->id }})"
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

        @if(!$vario->movimientoRegistral->documentoEntrada())

            <x-button-blue
                wire:click="abrirModalFinalizar"
                wire:loading.attr="disabled"
                wire:target="abrirModalFinalizar">

                <img wire:loading wire:target="abrirModalFinalizar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                Subir documento de entrada

            </x-button-blue>

        @else

            <div class="inline-block">

                <x-link-blue target="_blank" href="{{ $vario->movimientoRegistral->documentoEntrada() }}">Documento de entrada</x-link-blue>

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

            <x-filepond::upload wire:model="documento" :accepted-file-types="['application/pdf']"/>

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

    @filepondScripts

</div>
