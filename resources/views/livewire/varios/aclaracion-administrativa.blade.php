<div>

    <div class="p-4 bg-white shadow-xl rounded-xl mb-5">

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto">

            <span>Aclaración administrativa</span>

        </div>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto">

            <x-input-group for="vario.descripcion" label="Comentario del movimiento" :error="$errors->first('vario.descripcion')" class="w-full">

                <textarea rows="3" class="w-full bg-white rounded" wire:model="vario.descripcion"></textarea>

            </x-input-group>

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

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-3 col-span-2 bg-white rounded-lg p-3">

            <span class="flex items-center justify-center text-lg text-gray-700 md:col-span-3 col-span-1 sm:col-span-2">Colindancias</span>

            <div class="mb-5 divide-y md:col-span-3 col-span-1 sm:col-span-2">

                @foreach ($medidas as $index => $medida)

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 items-start mb-2">

                        <div class="flex-auto lg:col-span-2">

                            <div>

                                <label class="text-sm" >Viento</label>

                            </div>

                            <div>

                                <select class="bg-white rounded text-xs w-full" wire:model.defer="medidas.{{ $index }}.viento">

                                    <option value="" selected>Seleccione una opción</option>

                                    @foreach ($vientos as $viento)

                                        <option value="{{ $viento }}" selected>{{ $viento }}</option>

                                    @endforeach

                                </select>

                            </div>

                            <div>

                                @error('medidas.' . $index . '.viento') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                            </div>

                        </div>

                        <div class="flex-auto lg:col-span-2">

                            <div>

                                <label class="text-sm" >Longitud (metros)</label>

                            </div>

                            <div>

                                <input type="number" min="0" class="bg-white rounded text-xs w-full" wire:model.defer="medidas.{{ $index }}.longitud">

                            </div>

                            <div>

                                @error('medidas.' . $index . '.longitud') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                            </div>

                        </div>

                        <div class="flex-auto lg:col-span-7">

                            <div>

                                <label class="text-sm" >Descripción</label>

                            </div>

                            <div>

                                <textarea rows="1" class="bg-white rounded text-xs w-full" wire:model.defer="medidas.{{ $index }}.descripcion"></textarea>

                            </div>

                            <div>

                                @error('medidas.' . $index . '.descripcion') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                            </div>

                        </div>

                        <div class="flex-auto lg:col-span-1 my-auto">

                            <x-button-red
                                wire:click="borrarColindancia({{ $index }})"
                                wire:loading.attr="disabled"
                            >

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>

                            </x-button-red>

                        </div>

                    </div>

                @endforeach

            </div>

            <div class="flex justify-end lg:col-span-3">

                <x-button-green
                    wire:click="agregarColindancia"
                    wire:loading.attr="disabled"
                >

                    <img wire:loading wire:target="agregarColindancia" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    Agregar colindancia

                </x-button-green>

            </div>

        </div>

    </div>

    <div class="p-4 bg-white shadow-xl rounded-xl mb-5">

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3 mb-3 bg-white rounded-lg p-3">

            <span class="flex items-center justify-center text-lg text-gray-700 md:col-span-5 col-span-1 sm:col-span-2">Ubicación del predio</span>

            <x-input-group for="vario.predio.codigo_postal" label="Código postal" :error="$errors->first('vario.predio.codigo_postal')" class="w-full">

                <x-input-text type="number" id="vario.predio.codigo_postal" wire:model.lazy="vario.predio.codigo_postal" />

            </x-input-group>

            <x-input-group for="vario.predio.nombre_asentamiento" label="Nombre del asentamiento" :error="$errors->first('vario.predio.nombre_asentamiento')" class="w-full">

                <x-input-text id="vario.predio.nombre_asentamiento" wire:model="vario.predio.nombre_asentamiento" readonly/>

            </x-input-group>

            <x-input-group for="vario.predio.municipio" label="Municipio" :error="$errors->first('vario.predio.municipio')" class="w-full">

                <x-input-text id="vario.predio.municipio" wire:model="vario.predio.municipio" readonly/>

            </x-input-group>

            <x-input-group for="vario.predio.ciudad" label="Ciudad" :error="$errors->first('vario.predio.ciudad')" class="w-full">

                <x-input-text id="vario.predio.ciudad" wire:model="vario.predio.ciudad"/>

            </x-input-group>

            <x-input-group for="vario.predio.tipo_asentamiento" label="Tipo de asentamiento" :error="$errors->first('vario.predio.tipo_asentamiento')" class="w-full">

                <x-input-text id="vario.predio.tipo_asentamiento" wire:model="vario.predio.tipo_asentamiento" readonly/>

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

                <x-button-gray
                        wire:click="agregarPropietario"
                        wire:loading.attr="disabled"
                        wire:target="agregarPropietario">

                        <img wire:loading wire:target="agregarPropietario" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                        Agregar propietario
                </x-button-gray>

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

                    @if($predio)

                        @foreach ($predio->propietarios() as $propietario)

                            <x-table.row >

                                <x-table.cell>{{ $propietario->persona->nombre }} {{ $propietario->persona->ap_paterno }} {{ $propietario->persona->ap_materno }} {{ $propietario->persona->razon_social }}</x-table.cell>
                                <x-table.cell>{{ number_format($propietario->porcentaje_propiedad, 2) }}%</x-table.cell>
                                <x-table.cell>{{ number_format($propietario->porcentaje_nuda, 2) }}%</x-table.cell>
                                <x-table.cell>{{ number_format($propietario->porcentaje_usufructo, 2) }}%</x-table.cell>
                                <x-table.cell>
                                    <div class="flex items-center gap-3">
                                        <x-button-blue
                                            wire:click="editarActor({{ $propietario->id }}, 'propietario')"
                                            wire:traget="editarActor({{ $propietario->id }}, 'propietario')"
                                            wire:loading.attr="disabled"
                                        >
                                            Editar
                                        </x-button-blue>
                                        <x-button-red
                                            wire:click="borrarActor({{ $propietario->id }})"
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

    <x-dialog-modal wire:model="modalPersona">

        <x-slot name="title">

            Editar porcentajes

        </x-slot>

        <x-slot name="content">

            <div class="flex flex-col md:flex-row justify-between gap-3 mb-3">

                <x-input-group for="porcentaje_propiedad" label="% Propiedad" :error="$errors->first('porcentaje_propiedad')" class="w-full">

                    <x-input-text id="porcentaje_propiedad" wire:model="porcentaje_propiedad" />

                </x-input-group>

                <x-input-group for="porcentaje_nuda" label="% Nuda" :error="$errors->first('porcentaje_nuda')" class="w-full">

                    <x-input-text id="porcentaje_nuda" wire:model="porcentaje_nuda" />

                </x-input-group>

                <x-input-group for="porcentaje_usufructo" label="% Usufructo" :error="$errors->first('porcentaje_usufructo')" class="w-full">

                    <x-input-text id="porcentaje_usufructo" wire:model="porcentaje_usufructo" />

                </x-input-group>

            </div>

        </x-slot>

        <x-slot name="footer">

            <div class="flex gap-3">

                <x-button-blue
                    wire:click="actualizarPorcentajes"
                    wire:loading.attr="disabled"
                    wire:target="actualizarPorcentajes">

                    <img wire:loading wire:target="actualizarPorcentajes" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <span>Actualizar</span>

                </x-button-blue>

                <x-button-red
                    wire:click="$toggle('modalPersona')"
                    wire:loading.attr="disabled"
                    wire:target="$toggle('modalPersona')"
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

    <x-dialog-modal wire:model="modalPropietario">

        <x-slot name="title">

            @if($crear)
                Nuevo Propietario
            @elseif($editar)
                Editar Propietario
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

                    <x-input-group for="curp" label="CURP" :error="$errors->first('curp')" class="w-full">

                        <x-input-text id="curp" wire:model="curp" />

                    </x-input-group>

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

                <x-input-group for="rfc" label="RFC" :error="$errors->first('rfc')" class="w-full">

                    <x-input-text id="rfc" wire:model="rfc" />

                </x-input-group>

                <x-input-group for="nacionalidad" label="Nacionalidad" :error="$errors->first('nacionalidad')" class="w-full">

                    <x-input-text id="nacionalidad" wire:model="nacionalidad" />

                </x-input-group>

                <span class="flex items-center justify-center text-lg text-gray-700 md:col-span-3 col-span-1 sm:col-span-2">Domicilio</span>

                <x-input-group for="cp" label="Código postal" :error="$errors->first('cp')" class="w-full">

                    <x-input-text type="number" id="cp" wire:model="cp" />

                </x-input-group>

                <x-input-group for="entidad" label="Estado" :error="$errors->first('entidad')" class="w-full">

                    <x-input-text id="entidad" wire:model="entidad" />

                </x-input-group>

                <x-input-group for="municipio_propietario" label="Municipio" :error="$errors->first('municipio_propietario')" class="w-full">

                    <x-input-text id="municipio_propietario" wire:model="municipio_propietario" />

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

                <x-input-group for="numero_exterior_propietario" label="Número exterior" :error="$errors->first('numero_exterior_propietario')" class="w-full">

                    <x-input-text id="numero_exterior_propietario" wire:model="numero_exterior_propietario" />

                </x-input-group>

                <x-input-group for="numero_interior_propietario" label="Número interior" :error="$errors->first('numero_interior_propietario')" class="w-full">

                    <x-input-text id="numero_interior_propietario" wire:model="numero_interior_propietario" />

                </x-input-group>

                <span class="flex items-center justify-center text-lg text-gray-700 md:col-span-3 col-span-1 sm:col-span-2">Porcentajes</span>

                <x-input-group for="porcentaje_propiedad" label="Porcentaje propiedad" :error="$errors->first('porcentaje_propiedad')" class="w-full">

                    <x-input-text type="number" id="porcentaje_propiedad" wire:model.lazy="porcentaje_propiedad" />

                </x-input-group>

                <x-input-group for="porcentaje_nuda" label="Porcentaje nuda" :error="$errors->first('porcentaje_nuda')" class="w-full">

                    <x-input-text type="number" id="porcentaje_nuda" wire:model.lazy="porcentaje_nuda" />

                </x-input-group>

                <x-input-group for="porcentaje_usufructo" label="Porcentaje usufructo" :error="$errors->first('porcentaje_usufructo')" class="w-full">

                    <x-input-text type="number" id="porcentaje_usufructo" wire:model.lazy="porcentaje_usufructo" />

                </x-input-group>

                {{-- <x-input-group for="partes_iguales" label="Partes iguales" :error="$errors->first('partes_iguales')" class="w-full">

                    <input wire:model="partes_iguales" type="checkbox" class="rounded">

                </x-input-group> --}}

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
                        wire:click="actualizarActor"
                        wire:loading.attr="disabled"
                        wire:target="actualizarActor">

                        <img wire:loading wire:target="actualizarActor" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        <span>Actualizar</span>
                    </x-button-blue>

                @endif

                <x-button-red
                    wire:click="resetear"
                    wire:loading.attr="disabled"
                    wire:target="resetear"
                    type="button">
                    Cerrar
                </x-button-red>

            </div>

        </x-slot>

    </x-dialog-modal>

</div>
