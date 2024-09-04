@push('styles')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endpush

<div>

    <x-header>Sentencia</x-header>

    <div class="p-4 bg-white shadow-xl rounded-xl mb-5">

        <span class="flex items-center justify-center ext-gray-700">Datos del movimiento</span>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto mb-4">

            <x-input-group for="sentencia.acto_contenido" label="Acto contenido" :error="$errors->first('sentencia.acto_contenido')" class="w-full">

                <x-input-select id="sentencia.acto_contenido" wire:model.live="sentencia.acto_contenido" class="w-full">

                    <option value="">Seleccione una opción</option>

                    @foreach ($actos as $acto)

                        <option value="{{ $acto }}">{{ $acto }}</option>

                    @endforeach

                </x-input-select>

            </x-input-group>

        </div>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto">

            <x-input-group for="sentencia.descripcion" label="Comentario del movimiento" :error="$errors->first('sentencia.descripcion')" class="w-full">

                <textarea rows="3" class="w-full bg-white rounded" wire:model="sentencia.descripcion"></textarea>

            </x-input-group>

        </div>

    </div>

    @if($sentencia->acto_contenido == 'CANCELACIÓN DE INSCRIPCIÓN')

        <div class="p-4 bg-white shadow-xl rounded-xl mb-5">

            <div class="w-full  justify-center mx-auto">

                <div class="flex-auto text-center mb-3 lg:w-1/2 mx-auto">

                    <div >

                        <Label class="text-base tracking-widest rounded-xl border-gray-500">Folio de la inscripción a cancelar</Label>

                    </div>

                    <div class="inline-flex">

                        <input type="number" class="bg-white text-sm w-20 rounded-l focus:ring-0 @error('folio_real') border-red-500 @enderror" wire:model="folio_real">

                        <input type="number" class="bg-white text-sm w-20 border-l-0 rounded-r focus:ring-0 @error('folio_movimiento') border-red-500 @enderror" wire:model="folio_movimiento">

                    </div>

                    <button
                        wire:click="buscarMovimiento"
                        wire:loading.attr="disabled"
                        wire:target="buscarMovimiento"
                        type="button"
                        class="bg-blue-400 mx-auto mt-3 hover:shadow-lg text-white font-bold px-4 py-2 rounded text-xs hover:bg-blue-700 focus:outline-none flex items-center justify-center focus:outline-blue-400 focus:outline-offset-2">

                        <img wire:loading wire:target="buscarMovimiento" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        Buscar inscripción

                    </button>

                </div>

                @if($movimientoCancelar)

                    <div class="lg:w-1/2 mx-auto">

                        <div class="flex gap-3 items-center justify-center mx-auto mb-4">

                            <input type="text" value="{{ $acto_contenido }}" class="bg-white rounded text-sm w-full" readonly>

                            @if($tipo)

                                <input type="text" value="{{ $tipo }}" class="bg-white rounded text-sm w-full" readonly>

                            @endif

                        </div>

                        <div class="flex gap-3 items-center justify-center mx-auto mb-4">

                            @if($valor_gravamen)

                                <input type="text" value="{{ $valor_gravamen }}" class="bg-white rounded text-sm w-full" readonly>

                            @endif

                            @if($fecha_inscripcion)

                                <input type="text" value="{{ $fecha_inscripcion }}" class="bg-white rounded text-sm w-full" readonly>

                            @endif

                        </div>

                        <textarea class="bg-white rounded text-sm w-full" readonly>{{ $observaciones }}</textarea>

                    </div>

                @endif

            </div>

        </div>

    @endif

    <div class="p-4 bg-white shadow-xl rounded-xl mb-5">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-3 bg-white rounded-lg p-3">

            <span class="flex items-center justify-center text-lg text-gray-700  md:col-span-3 col-span-1 sm:col-span-2">Descripción del predio</span>

            <x-input-group for="sentenciaPredio.superficie_terreno" label="Superficie de terreno" :error="$errors->first('sentenciaPredio.superficie_terreno')" class="w-full relative">

                <x-input-text type="number" id="sentenciaPredio.superficie_terreno" wire:model="sentenciaPredio.superficie_terreno" />

                <div class="absolute right-0 top-6">

                    <x-input-select id="sentenciaPredio.unidad_area" wire:model="sentenciaPredio.unidad_area">

                        @foreach ($areas as $unidad)

                            <option value="{{ $unidad }}">{{ $unidad }}</option>

                        @endforeach

                    </x-input-select>

                </div>

            </x-input-group>

            <x-input-group for="sentenciaPredio.superficie_construccion" label="Superficie de construcción" :error="$errors->first('sentenciaPredio.superficie_construccion')" class="w-full relative">

                <x-input-text type="number" id="sentenciaPredio.superficie_construccion" wire:model="sentenciaPredio.superficie_construccion" />

                <div class="absolute right-0 top-6">

                    <x-input-select id="sentenciaPredio.unidad_area" wire:model="sentenciaPredio.unidad_area">

                        @foreach ($areas as $unidad)

                            <option value="{{ $unidad }}">{{ $unidad }}</option>

                        @endforeach

                    </x-input-select>

                </div>

            </x-input-group>

            <x-input-group for="sentenciaPredio.monto_transaccion" label="Monto de la transacción" :error="$errors->first('sentenciaPredio.monto_transaccion')" class="w-full relative">

                <x-input-text type="number" id="sentenciaPredio.monto_transaccion" wire:model="sentenciaPredio.monto_transaccion" />

                <div class="absolute right-0 top-6">

                    <x-input-select id="sentenciaPredio.divisa" wire:model="sentenciaPredio.divisa">

                        @foreach ($divisas as $divisa)

                            <option value="{{ $divisa }}">{{ $divisa }}</option>

                        @endforeach

                    </x-input-select>

                </div>

            </x-input-group>

            <x-input-group for="sentenciaPredio.descripcion" label="Descripción" :error="$errors->first('sentenciaPredio.descripcion')" class="sm:col-span-2 lg:col-span-3">

                <textarea rows="3" class="w-full bg-white rounded text-sm" wire:model="sentenciaPredio.descripcion"></textarea>

            </x-input-group>

        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 mb-3 bg-white rounded-lg p-3">

            <span class="flex items-center justify-center text-lg text-gray-700 md:col-span-5 col-span-1 sm:col-span-2">Datos Complementarios</span>

            <x-input-group for="sentenciaPredio.curt" label="CURT" :error="$errors->first('sentenciaPredio.curt')" class="w-full">

                <x-input-text id="sentenciaPredio.curt" wire:model="sentenciaPredio.curt" />

            </x-input-group>

            <x-input-group for="sentenciaPredio.superficie_judicial" label="Superficie judicial" :error="$errors->first('sentenciaPredio.superficie_judicial')" class="w-full relative">

                <x-input-text type="number" id="sentenciaPredio.superficie_judicial" wire:model="sentenciaPredio.superficie_judicial" />

                <div class="absolute right-0 top-6">

                    <x-input-select id="sentenciaPredio.unidad_area" wire:model="sentenciaPredio.unidad_area">

                        @foreach ($areas as $unidad)

                            <option value="{{ $unidad }}">{{ $unidad }}</option>

                        @endforeach

                    </x-input-select>

                </div>

            </x-input-group>

            <x-input-group for="sentenciaPredio.superficie_notarial" label="Superficie notarial" :error="$errors->first('sentenciaPredio.superficie_notarial')" class="w-full relative">

                <x-input-text type="number" id="sentenciaPredio.superficie_notarial" wire:model="sentenciaPredio.superficie_notarial" />

                <div class="absolute right-0 top-6">

                    <x-input-select id="sentenciaPredio.unidad_area" wire:model="sentenciaPredio.unidad_area">

                        @foreach ($areas as $unidad)

                            <option value="{{ $unidad }}">{{ $unidad }}</option>

                        @endforeach

                    </x-input-select>

                </div>

            </x-input-group>

            <x-input-group for="sentenciaPredio.area_comun_terreno" label="Área de terreno común" :error="$errors->first('sentenciaPredio.area_comun_terreno')" class="w-full relative">

                <x-input-text type="number" id="sentenciaPredio.area_comun_terreno" wire:model="sentenciaPredio.area_comun_terreno" />

                <div class="absolute right-0 top-6">

                    <x-input-select id="sentenciaPredio.unidad_area" wire:model="sentenciaPredio.unidad_area">

                        @foreach ($areas as $unidad)

                            <option value="{{ $unidad }}">{{ $unidad }}</option>

                        @endforeach

                    </x-input-select>

                </div>

            </x-input-group>

            <x-input-group for="sentenciaPredio.area_comun_construccion" label="Área de contrucción común" :error="$errors->first('sentenciaPredio.area_comun_construccion')" class="w-full relative">

                <x-input-text type="number" id="sentenciaPredio.area_comun_construccion" wire:model="sentenciaPredio.area_comun_construccion" />

                <div class="absolute right-0 top-6">

                    <x-input-select id="sentenciaPredio.unidad_area" wire:model="sentenciaPredio.unidad_area">

                        @foreach ($areas as $unidad)

                            <option value="{{ $unidad }}">{{ $unidad }}</option>

                        @endforeach

                    </x-input-select>

                </div>

            </x-input-group>

            <x-input-group for="sentenciaPredio.valor_terreno_comun" label="Valor de terreno común" :error="$errors->first('sentenciaPredio.valor_terreno_comun')" class="w-full">

                <x-input-text type="number" id="sentenciaPredio.valor_terreno_comun" wire:model="sentenciaPredio.valor_terreno_comun" />

            </x-input-group>

            <x-input-group for="sentenciaPredio.valor_construccion_comun" label="Valor de construcción común" :error="$errors->first('sentenciaPredio.valor_construccion_comun')" class="w-full">

                <x-input-text type="number" id="sentenciaPredio.valor_construccion_comun" wire:model="sentenciaPredio.valor_construccion_comun" />

            </x-input-group>

            <x-input-group for="sentenciaPredio.valor_total_terreno" label="Valor total del terreno" :error="$errors->first('sentenciaPredio.valor_total_terreno')" class="w-full">

                <x-input-text type="number" id="sentenciaPredio.valor_total_terreno" wire:model="valor_total_terreno" />

            </x-input-group>

            <x-input-group for="sentenciaPredio.valor_total_construccion" label="Valor total de la contrucción" :error="$errors->first('sentenciaPredio.valor_total_construccion')" class="w-full">

                <x-input-text type="number" id="sentenciaPredio.valor_total_construccion" wire:model="sentenciaPredio.valor_total_construccion" />

            </x-input-group>

            <x-input-group for="sentenciaPredio.valor_catastral" label="Valor catastral" :error="$errors->first('sentenciaPredio.valor_catastral')" class="w-full">

                <x-input-text type="number" id="sentenciaPredio.valor_catastral" wire:model="sentenciaPredio.valor_catastral" />

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

            <x-input-group for="sentenciaPredio.codigo_postal" label="Código postal" :error="$errors->first('sentenciaPredio.codigo_postal')" class="w-full">

                <x-input-text type="number" id="sentenciaPredio.codigo_postal" wire:model.lazy="sentenciaPredio.codigo_postal" />

            </x-input-group>

            <x-input-group for="sentenciaPredio.nombre_asentamiento" label="Nombre del asentamiento" :error="$errors->first('sentenciaPredio.nombre_asentamiento')" class="w-full">

                <x-input-text id="sentenciaPredio.nombre_asentamiento" wire:model="sentenciaPredio.nombre_asentamiento" readonly/>

            </x-input-group>

            <x-input-group for="sentenciaPredio.municipio" label="Municipio" :error="$errors->first('sentenciaPredio.municipio')" class="w-full">

                <x-input-text id="sentenciaPredio.municipio" wire:model="sentenciaPredio.municipio" readonly/>

            </x-input-group>

            <x-input-group for="sentenciaPredio.ciudad" label="Ciudad" :error="$errors->first('sentenciaPredio.ciudad')" class="w-full">

                <x-input-text id="sentenciaPredio.ciudad" wire:model="sentenciaPredio.ciudad"/>

            </x-input-group>

            <x-input-group for="sentenciaPredio.tipo_asentamiento" label="Tipo de asentamiento" :error="$errors->first('sentenciaPredio.tipo_asentamiento')" class="w-full">

                <x-input-text id="sentenciaPredio.tipo_asentamiento" wire:model="sentenciaPredio.tipo_asentamiento" readonly/>

            </x-input-group>

            <x-input-group for="sentenciaPredio.localidad" label="Localidad" :error="$errors->first('sentenciaPredio.localidad')" class="w-full">

                <x-input-text id="sentenciaPredio.localidad" wire:model="sentenciaPredio.localidad" />

            </x-input-group>

            <x-input-group for="sentenciaPredio.tipo_vialidad" label="Tipo de vialidad" :error="$errors->first('sentenciaPredio.tipo_vialidad')" class="w-full">

                <x-input-select id="sentenciaPredio.tipo_vialidad" wire:model.live="sentenciaPredio.tipo_vialidad" class="w-full">

                    <option value="">Seleccione una opción</option>

                    @foreach ($tipos_vialidades as $vialidad)

                        <option value="{{ $vialidad }}">{{ $vialidad }}</option>

                    @endforeach

                </x-input-select>

            </x-input-group>

            <x-input-group for="sentenciaPredio.nombre_vialidad" label="Nombre de la vialidad" :error="$errors->first('sentenciaPredio.nombre_vialidad')" class="w-full">

                <x-input-text id="sentenciaPredio.nombre_vialidad" wire:model="sentenciaPredio.nombre_vialidad" />

            </x-input-group>

            <x-input-group for="sentenciaPredio.numero_exterior" label="Número exterior" :error="$errors->first('sentenciaPredio.numero_exterior')" class="w-full">

                <x-input-text id="sentenciaPredio.numero_exterior" wire:model="sentenciaPredio.numero_exterior" />

            </x-input-group>

            <x-input-group for="sentenciaPredio.numero_interior" label="Número interior" :error="$errors->first('sentenciaPredio.numero_interior')" class="w-full">

                <x-input-text id="sentenciaPredio.numero_interior" wire:model="sentenciaPredio.numero_interior" />

            </x-input-group>

            <x-input-group for="sentenciaPredio.nombre_edificio" label="Edificio" :error="$errors->first('sentenciaPredio.nombre_edificio')" class="w-full">

                <x-input-text id="sentenciaPredio.nombre_edificio" wire:model="sentenciaPredio.nombre_edificio" />

            </x-input-group>

            <x-input-group for="sentenciaPredio.departamento_edificio" label="Departamento" :error="$errors->first('sentenciaPredio.departamento_edificio')" class="w-full">

                <x-input-text id="sentenciaPredio.departamento_edificio" wire:model="sentenciaPredio.departamento_edificio" />

            </x-input-group>

            <x-input-group for="sentenciaPredio.observaciones" label="Observaciones" :error="$errors->first('sentenciaPredio.observaciones')" class="md:col-span-5 col-span-1 sm:col-span-2">

                <textarea rows="3" class="w-full bg-white rounded" wire:model="sentenciaPredio.observaciones"></textarea>

            </x-input-group>

        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5 mb-3 bg-white rounded-lg p-3">

            <span class="flex items-center justify-center text-lg text-gray-700 md:col-span-5 col-span-1 sm:col-span-2">Datos Complementarios</span>

            <x-input-group for="sentenciaPredio.lote" label="Lote" :error="$errors->first('sentenciaPredio.lote')" class="w-full">

                <x-input-text id="sentenciaPredio.lote" wire:model="sentenciaPredio.lote" />

            </x-input-group>

            <x-input-group for="sentenciaPredio.manzana" label="Manzana" :error="$errors->first('sentenciaPredio.manzana')" class="w-full">

                <x-input-text id="sentenciaPredio.manzana" wire:model="sentenciaPredio.manzana" />

            </x-input-group>

            <x-input-group for="sentenciaPredio.ejido" label="Ejido" :error="$errors->first('sentenciaPredio.ejido')" class="w-full">

                <x-input-text id="sentenciaPredio.ejido" wire:model="sentenciaPredio.ejido" />

            </x-input-group>

            <x-input-group for="sentenciaPredio.parcela" label="Parcela" :error="$errors->first('sentenciaPredio.parcela')" class="w-full">

                <x-input-text id="sentenciaPredio.parcela" wire:model="sentenciaPredio.parcela" />

            </x-input-group>

            <x-input-group for="sentenciaPredio.solar" label="Solar" :error="$errors->first('sentenciaPredio.solar')" class="w-full">

                <x-input-text id="sentenciaPredio.solar" wire:model="sentenciaPredio.solar" />

            </x-input-group>

            <x-input-group for="sentenciaPredio.poblado" label="Poblado" :error="$errors->first('sentenciaPredio.poblado')" class="w-full">

                <x-input-text id="sentenciaPredio.poblado" wire:model="sentenciaPredio.poblado" />

            </x-input-group>

            <x-input-group for="sentenciaPredio.numero_exterior_2" label="Número exterior 2" :error="$errors->first('sentenciaPredio.numero_exterior_2')" class="w-full">

                <x-input-text id="sentenciaPredio.numero_exterior_2" wire:model="sentenciaPredio.numero_exterior_2" />

            </x-input-group>

            <x-input-group for="sentenciaPredio.numero_adicional" label="Número adicional" :error="$errors->first('sentenciaPredio.numero_adicional')" class="w-full">

                <x-input-text id="sentenciaPredio.numero_adicional" wire:model="sentenciaPredio.numero_adicional" />

            </x-input-group>

            <x-input-group for="sentenciaPredio.numero_adicional_2" label="Número adicional 2" :error="$errors->first('sentenciaPredio.numero_adicional_2')" class="w-full">

                <x-input-text id="sentenciaPredio.numero_adicional_2" wire:model="sentenciaPredio.numero_adicional_2" />

            </x-input-group>

            <x-input-group for="sentenciaPredio.lote_fraccionador" label="Lote del fraccionador" :error="$errors->first('sentenciaPredio.lote_fraccionador')" class="w-full">

                <x-input-text id="sentenciaPredio.lote_fraccionador" wire:model="sentenciaPredio.lote_fraccionador" />

            </x-input-group>

            <x-input-group for="sentenciaPredio.manzana_fraccionador" label="Manzana del fraccionador" :error="$errors->first('sentenciaPredio.manzana_fraccionador')" class="w-full">

                <x-input-text id="sentenciaPredio.manzana_fraccionador" wire:model="sentenciaPredio.manzana_fraccionador" />

            </x-input-group>

            <x-input-group for="sentenciaPredio.etapa_fraccionador" label="Etapa del fraccionador" :error="$errors->first('sentenciaPredio.etapa_fraccionador')" class="w-full">

                <x-input-text id="sentenciaPredio.etapa_fraccionador" wire:model="sentenciaPredio.etapa_fraccionador" />

            </x-input-group>

            <x-input-group for="sentenciaPredio.clave_edificio" label="Clave del edificio" :error="$errors->first('sentenciaPredio.clave_edificio')" class="w-full">

                <x-input-text id="sentenciaPredio.clave_edificio" wire:model="sentenciaPredio.clave_edificio" />

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

                    @if($sentenciaPredio)

                        @foreach ($sentenciaPredio->propietarios() as $propietario)

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

        @if(!$sentencia->movimientoRegistral->documentoEntrada())

            <x-button-blue
                wire:click="abrirModalFinalizar"
                wire:loading.attr="disabled"
                wire:target="abrirModalFinalizar">

                <img wire:loading wire:target="abrirModalFinalizar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                Subir documento de entrada

            </x-button-blue>

        @else

            <div class="inline-block">

                <x-link-blue target="_blank" href="{{ $sentencia->movimientoRegistral->documentoEntrada() }}">Documento de entrada</x-link-blue>

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

@push('scripts')

    <script>

        window.addEventListener('imprimir_documento', event => {

            const documento = event.detail[0].sentencia;

            var url = "{{ route('sentencias.inscripcion.acto', '')}}" + "/" + documento;

            window.open(url, '_blank');

            window.location.href = "{{ route('sentencias')}}";

        });

        window.addEventListener('ver_documento', event => {

            const documento = event.detail[0].url;

            window.open(documento, '_blank');

        });

    </script>

@endpush
