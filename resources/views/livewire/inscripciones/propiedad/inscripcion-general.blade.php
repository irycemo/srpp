@push('styles')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endpush

<div>

    <div class="bg-white rounded-lg p-4 shadow-lg mb-4">

        <x-input-group for="inscripcion.descripcion_acto" label="Descripción del acto" :error="$errors->first('inscripcion.descripcion_acto')" class="w-full lg:w-1/4 mx-auto">

            <textarea class="bg-white rounded text-xs w-full  @error('inscripcion.descripcion_acto') border-1 border-red-500 @enderror" rows="4" wire:model="inscripcion.descripcion_acto"></textarea>

        </x-input-group>

    </div>

    <div class="bg-white rounded-lg p-4 shadow-lg mb-4">

            <div class="space-y-1 sm:col-span-2 lg:col-span-3 mx-auto text-center mb-2">

                <span class="flex items-center justify-center text-gray-700">Cuenta predial</span>

                <input title="Localidad" placeholder="Localidad" type="number" class="bg-white rounded text-xs w-20 @error('inscripcion.cp_localidad') border-1 border-red-500 @enderror" wire:model.lazy="inscripcion.cp_localidad">

                <input title="Oficina" placeholder="Oficina" type="number" class="bg-white rounded text-xs w-20 @error('inscripcion.cp_oficina') border-1 border-red-500 @enderror" wire:model.defer="inscripcion.cp_oficina">

                <input title="Tipo de predio" placeholder="Tipo" type="number" class="bg-white rounded text-xs w-20 @error('inscripcion.cp_tipo_predio') border-1 border-red-500 @enderror" wire:model.defer="inscripcion.cp_tipo_predio">

                <input title="Número de registro" placeholder="Registro" type="number" class="bg-white rounded text-xs w-20 @error('inscripcion.cp_registro') border-1 border-red-500 @enderror" wire:model.lazy="inscripcion.cp_registro">

            </div>

            {{-- <div class="space-y-1 sm:col-span-2 lg:col-span-3 mx-auto text-center">

                <span class="flex items-center justify-center text-gray-700">Clave catastral</span>

                <input placeholder="Estado" type="number" class="bg-white rounded text-xs w-10" title="Estado" value="16">

                <input title="Región catastral" placeholder="Región" type="number" class="bg-white rounded text-xs w-16  @error('inscripcion.cc_region_catastral') border-1 border-red-500 @enderror" wire:model.defer="inscripcion.cc_region_catastral">

                <input title="Municipio" placeholder="Municipio" type="number" class="bg-white rounded text-xs w-20 @error('inscripcion.cc_municipio') border-1 border-red-500 @enderror" wire:model.defer="inscripcion.cc_municipio">

                <input title="Zona" placeholder="Zona" type="number" class="bg-white rounded text-xs w-16 @error('inscripcion.cc_zona_catastral') border-1 border-red-500 @enderror" wire:model.defer="inscripcion.cc_zona_catastral">

                <input title="Localidad" placeholder="Localidad" type="number" class="bg-white rounded text-xs w-20 @error('inscripcion.cp_localidad') border-1 border-red-500 @enderror" wire:model.lazy="inscripcion.cp_localidad">

                <input title="Sector" placeholder="Sector" type="number" class="bg-white rounded text-xs w-20 @error('inscripcion.cc_sector') border-1 border-red-500 @enderror" wire:model.defer="inscripcion.cc_sector">

                <input title="Manzana" placeholder="Manzana" type="number" class="bg-white rounded text-xs w-20 @error('inscripcion.cc_manzana') border-1 border-red-500 @enderror" wire:model.defer="inscripcion.cc_manzana">

                <input title="Predio" placeholder="Predio" type="number" class="bg-white rounded text-xs w-20 @error('inscripcion.cc_predio') border-1 border-red-500 @enderror" wire:model.lazy="inscripcion.cc_predio">

                <input title="Edificio" placeholder="Edificio" type="number" class="bg-white rounded text-xs w-16 @error('inscripcion.cc_edificio') border-1 border-red-500 @enderror" wire:model.defer="inscripcion.cc_edificio">

                <input title="Departamento" placeholder="Departamento" type="number" class="bg-white rounded text-xs w-28 @error('inscripcion.cc_departamento') border-1 border-red-500 @enderror" wire:model.defer="inscripcion.cc_departamento">

            </div> --}}

    </div>

    <div class="p-4 bg-white shadow-xl rounded-xl mb-5">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-3 bg-white rounded-lg p-3">

            <span class="flex items-center justify-center text-lg text-gray-700  md:col-span-3 col-span-1 sm:col-span-2">Descripción del predio</span>

            <x-input-group for="inscripcion.superficie_terreno" label="Superficie de terreno" :error="$errors->first('inscripcion.superficie_terreno')" class="w-full relative">

                <x-input-text type="number" id="inscripcion.superficie_terreno" wire:model="inscripcion.superficie_terreno" />

                <div class="absolute right-0 top-6">

                    <x-input-select id="inscripcion.unidad_area" wire:model="inscripcion.unidad_area">

                        <option value="">-</option>
                        @foreach ($areas as $unidad)

                            <option value="{{ $unidad }}">@if($unidad == 'Metros cuadrados') M<sup>2</sup> @else Has.@endif</option>

                        @endforeach

                    </x-input-select>

                </div>

            </x-input-group>

            <x-input-group for="inscripcion.superficie_construccion" label="Superficie de construcción" :error="$errors->first('inscripcion.superficie_construccion')" class="w-full relative">

                <x-input-text type="number" id="inscripcion.superficie_construccion" wire:model="inscripcion.superficie_construccion" />

                <div class="absolute right-0 top-6">

                    <x-input-select id="inscripcion.unidad_area" wire:model="inscripcion.unidad_area">

                        <option value="">-</option>
                        @foreach ($areas as $unidad)

                            <option value="{{ $unidad }}">@if($unidad == 'Metros cuadrados') M<sup>2</sup> @else Has.@endif</option>

                        @endforeach

                    </x-input-select>

                </div>

            </x-input-group>

            @if(!$nuevoFolio)

                <x-input-group for="inscripcion.monto_transaccion" label="Monto de la transacción" :error="$errors->first('inscripcion.monto_transaccion')" class="w-full relative">

                    <x-input-text type="number" id="inscripcion.monto_transaccion" wire:model="inscripcion.monto_transaccion" />

                    <div class="absolute right-0 top-6">

                        <x-input-select id="inscripcion.divisa" wire:model="inscripcion.divisa">

                            @foreach ($divisas as $divisa)

                                <option value="{{ $divisa }}">{{ $divisa }}</option>

                            @endforeach

                        </x-input-select>

                    </div>

                </x-input-group>

            @endif

            <x-input-group for="inscripcion.descripcion" label="Descripción" :error="$errors->first('inscripcion.descripcion')" class="sm:col-span-2 lg:col-span-3">

                <textarea rows="3" class="w-full bg-white rounded text-sm" wire:model="inscripcion.descripcion"></textarea>

            </x-input-group>

        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 mb-3 bg-white rounded-lg p-3">

            <span class="flex items-center justify-center text-lg text-gray-700 md:col-span-5 col-span-1 sm:col-span-2">Datos Complementarios</span>

            <x-input-group for="inscripcion.superficie_judicial" label="Superficie judicial" :error="$errors->first('inscripcion.superficie_judicial')" class="w-full relative">

                <x-input-text type="number" id="inscripcion.superficie_judicial" wire:model="inscripcion.superficie_judicial" />

                <div class="absolute right-0 top-6">

                    <x-input-select id="inscripcion.unidad_area" wire:model="inscripcion.unidad_area">

                        <option value="">-</option>
                        @foreach ($areas as $unidad)

                            <option value="{{ $unidad }}">@if($unidad == 'Metros cuadrados') M<sup>2</sup> @else Has.@endif</option>

                        @endforeach

                    </x-input-select>

                </div>

            </x-input-group>

            <x-input-group for="inscripcion.superficie_notarial" label="Superficie notarial" :error="$errors->first('inscripcion.superficie_notarial')" class="w-full relative">

                <x-input-text type="number" id="inscripcion.superficie_notarial" wire:model="inscripcion.superficie_notarial" />

                <div class="absolute right-0 top-6">

                    <x-input-select id="inscripcion.unidad_area" wire:model="inscripcion.unidad_area">

                        <option value="">-</option>
                        @foreach ($areas as $unidad)

                            <option value="{{ $unidad }}">@if($unidad == 'Metros cuadrados') M<sup>2</sup> @else Has.@endif</option>

                        @endforeach

                    </x-input-select>

                </div>

            </x-input-group>

            <x-input-group for="inscripcion.area_comun_terreno" label="Área de terreno común" :error="$errors->first('inscripcion.area_comun_terreno')" class="w-full relative">

                <x-input-text type="number" id="inscripcion.area_comun_terreno" wire:model="inscripcion.area_comun_terreno" />

                <div class="absolute right-0 top-6">

                    <x-input-select id="inscripcion.unidad_area" wire:model="inscripcion.unidad_area">

                        <option value="">-</option>
                        @foreach ($areas as $unidad)

                            <option value="{{ $unidad }}">@if($unidad == 'Metros cuadrados') M<sup>2</sup> @else Has.@endif</option>

                        @endforeach

                    </x-input-select>

                </div>

            </x-input-group>

            <x-input-group for="inscripcion.area_comun_construccion" label="Área de contrucción común" :error="$errors->first('inscripcion.area_comun_construccion')" class="w-full relative">

                <x-input-text type="number" id="inscripcion.area_comun_construccion" wire:model="inscripcion.area_comun_construccion" />

                <div class="absolute right-0 top-6">

                    <x-input-select id="inscripcion.unidad_area" wire:model="inscripcion.unidad_area">

                        <option value="">-</option>
                        @foreach ($areas as $unidad)

                            <option value="{{ $unidad }}">@if($unidad == 'Metros cuadrados') M<sup>2</sup> @else Has.@endif</option>

                        @endforeach

                    </x-input-select>

                </div>

            </x-input-group>

            <x-input-group for="inscripcion.valor_terreno_comun" label="Valor de terreno común" :error="$errors->first('inscripcion.valor_terreno_comun')" class="w-full">

                <x-input-text type="number" id="inscripcion.valor_terreno_comun" wire:model="inscripcion.valor_terreno_comun" />

            </x-input-group>

            <x-input-group for="inscripcion.valor_construccion_comun" label="Valor de construcción común" :error="$errors->first('inscripcion.valor_construccion_comun')" class="w-full">

                <x-input-text type="number" id="inscripcion.valor_construccion_comun" wire:model="inscripcion.valor_construccion_comun" />

            </x-input-group>

            <x-input-group for="inscripcion.valor_total_terreno" label="Valor total del terreno" :error="$errors->first('inscripcion.valor_total_terreno')" class="w-full">

                <x-input-text type="number" id="inscripcion.valor_total_terreno" wire:model="valor_total_terreno" />

            </x-input-group>

            <x-input-group for="inscripcion.valor_total_construccion" label="Valor total de la contrucción" :error="$errors->first('inscripcion.valor_total_construccion')" class="w-full">

                <x-input-text type="number" id="inscripcion.valor_total_construccion" wire:model="inscripcion.valor_total_construccion" />

            </x-input-group>

            <x-input-group for="inscripcion.valor_catastral" label="Valor catastral" :error="$errors->first('inscripcion.valor_catastral')" class="w-full">

                <x-input-text type="number" id="inscripcion.valor_catastral" wire:model="inscripcion.valor_catastral" />

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

            <x-input-group for="inscripcion.codigo_postal" label="Código postal" :error="$errors->first('inscripcion.codigo_postal')" class="w-full">

                <x-input-text type="number" id="inscripcion.codigo_postal" wire:model.lazy="inscripcion.codigo_postal" />

            </x-input-group>

            <x-input-group for="inscripcion.nombre_asentamiento" label="Nombre del asentamiento" :error="$errors->first('inscripcion.nombre_asentamiento')" class="w-full">

                <x-input-text id="inscripcion.nombre_asentamiento" wire:model="inscripcion.nombre_asentamiento"/>

            </x-input-group>

            <x-input-group for="inscripcion.municipio" label="Municipio" :error="$errors->first('inscripcion.municipio')" class="w-full">

                <x-input-text id="inscripcion.municipio" wire:model="inscripcion.municipio"/>

            </x-input-group>

            <x-input-group for="inscripcion.ciudad" label="Ciudad" :error="$errors->first('inscripcion.ciudad')" class="w-full">

                <x-input-text id="inscripcion.ciudad" wire:model="inscripcion.ciudad"/>

            </x-input-group>

            <x-input-group for="inscripcion.tipo_asentamiento" label="Tipo de asentamiento" :error="$errors->first('inscripcion.tipo_asentamiento')" class="w-full">

                <x-input-text id="inscripcion.tipo_asentamiento" wire:model="inscripcion.tipo_asentamiento"/>

            </x-input-group>

            <x-input-group for="inscripcion.localidad" label="Localidad" :error="$errors->first('inscripcion.localidad')" class="w-full">

                <x-input-text id="inscripcion.localidad" wire:model="inscripcion.localidad" />

            </x-input-group>

            <x-input-group for="inscripcion.tipo_vialidad" label="Tipo de vialidad" :error="$errors->first('inscripcion.tipo_vialidad')" class="w-full">

                <x-input-select id="inscripcion.tipo_vialidad" wire:model.live="inscripcion.tipo_vialidad" class="w-full">

                    <option value="">Seleccione una opción</option>

                    @foreach ($tipos_vialidades as $vialidad)

                        <option value="{{ $vialidad }}">{{ $vialidad }}</option>

                    @endforeach

                </x-input-select>

            </x-input-group>

            <x-input-group for="inscripcion.nombre_vialidad" label="Nombre de la vialidad" :error="$errors->first('inscripcion.nombre_vialidad')" class="w-full">

                <x-input-text id="inscripcion.nombre_vialidad" wire:model="inscripcion.nombre_vialidad" />

            </x-input-group>

            <x-input-group for="inscripcion.numero_exterior" label="Número exterior" :error="$errors->first('inscripcion.numero_exterior')" class="w-full">

                <x-input-text id="inscripcion.numero_exterior" wire:model="inscripcion.numero_exterior" />

            </x-input-group>

            <x-input-group for="inscripcion.numero_interior" label="Número interior" :error="$errors->first('inscripcion.numero_interior')" class="w-full">

                <x-input-text id="inscripcion.numero_interior" wire:model="inscripcion.numero_interior" />

            </x-input-group>

            <x-input-group for="inscripcion.nombre_edificio" label="Edificio" :error="$errors->first('inscripcion.nombre_edificio')" class="w-full">

                <x-input-text id="inscripcion.nombre_edificio" wire:model="inscripcion.nombre_edificio" />

            </x-input-group>

            <x-input-group for="inscripcion.departamento_edificio" label="Departamento" :error="$errors->first('inscripcion.departamento_edificio')" class="w-full">

                <x-input-text id="inscripcion.departamento_edificio" wire:model="inscripcion.departamento_edificio" />

            </x-input-group>

            <x-input-group for="inscripcion.observaciones" label="Observaciones" :error="$errors->first('inscripcion.observaciones')" class="md:col-span-5 col-span-1 sm:col-span-2">

                <textarea rows="3" class="w-full bg-white rounded" wire:model="inscripcion.observaciones"></textarea>

            </x-input-group>

        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5 mb-3 bg-white rounded-lg p-3">

            <span class="flex items-center justify-center text-lg text-gray-700 md:col-span-5 col-span-1 sm:col-span-2">Datos Complementarios</span>

            <x-input-group for="inscripcion.zona_ubicacion" label="Zona" :error="$errors->first('inscripcion.zona_ubicacion')" class="w-full">

                <x-input-text id="inscripcion.zona_ubicacion" wire:model="inscripcion.zona_ubicacion" />

            </x-input-group>

            <x-input-group for="inscripcion.lote" label="Lote" :error="$errors->first('inscripcion.lote')" class="w-full">

                <x-input-text id="inscripcion.lote" wire:model="inscripcion.lote" />

            </x-input-group>

            <x-input-group for="inscripcion.manzana" label="Manzana" :error="$errors->first('inscripcion.manzana')" class="w-full">

                <x-input-text id="inscripcion.manzana" wire:model="inscripcion.manzana" />

            </x-input-group>

            <x-input-group for="inscripcion.ejido" label="Ejido" :error="$errors->first('inscripcion.ejido')" class="w-full">

                <x-input-text id="inscripcion.ejido" wire:model="inscripcion.ejido" />

            </x-input-group>

            <x-input-group for="inscripcion.parcela" label="Parcela" :error="$errors->first('inscripcion.parcela')" class="w-full">

                <x-input-text id="inscripcion.parcela" wire:model="inscripcion.parcela" />

            </x-input-group>

            <x-input-group for="inscripcion.solar" label="Solar" :error="$errors->first('inscripcion.solar')" class="w-full">

                <x-input-text id="inscripcion.solar" wire:model="inscripcion.solar" />

            </x-input-group>

            <x-input-group for="inscripcion.poblado" label="Poblado" :error="$errors->first('inscripcion.poblado')" class="w-full">

                <x-input-text id="inscripcion.poblado" wire:model="inscripcion.poblado" />

            </x-input-group>

            <x-input-group for="inscripcion.numero_exterior_2" label="Número exterior 2" :error="$errors->first('inscripcion.numero_exterior_2')" class="w-full">

                <x-input-text id="inscripcion.numero_exterior_2" wire:model="inscripcion.numero_exterior_2" />

            </x-input-group>

            <x-input-group for="inscripcion.numero_adicional" label="Número adicional" :error="$errors->first('inscripcion.numero_adicional')" class="w-full">

                <x-input-text id="inscripcion.numero_adicional" wire:model="inscripcion.numero_adicional" />

            </x-input-group>

            <x-input-group for="inscripcion.numero_adicional_2" label="Número adicional 2" :error="$errors->first('inscripcion.numero_adicional_2')" class="w-full">

                <x-input-text id="inscripcion.numero_adicional_2" wire:model="inscripcion.numero_adicional_2" />

            </x-input-group>

            <x-input-group for="inscripcion.lote_fraccionador" label="Lote del fraccionador" :error="$errors->first('inscripcion.lote_fraccionador')" class="w-full">

                <x-input-text id="inscripcion.lote_fraccionador" wire:model="inscripcion.lote_fraccionador" />

            </x-input-group>

            <x-input-group for="inscripcion.manzana_fraccionador" label="Manzana del fraccionador" :error="$errors->first('inscripcion.manzana_fraccionador')" class="w-full">

                <x-input-text id="inscripcion.manzana_fraccionador" wire:model="inscripcion.manzana_fraccionador" />

            </x-input-group>

            <x-input-group for="inscripcion.etapa_fraccionador" label="Etapa del fraccionador" :error="$errors->first('inscripcion.etapa_fraccionador')" class="w-full">

                <x-input-text id="inscripcion.etapa_fraccionador" wire:model="inscripcion.etapa_fraccionador" />

            </x-input-group>

            <x-input-group for="inscripcion.clave_edificio" label="Clave del edificio" :error="$errors->first('inscripcion.clave_edificio')" class="w-full">

                <x-input-text id="inscripcion.clave_edificio" wire:model="inscripcion.clave_edificio" />

            </x-input-group>

        </div>

    </div>

    @if(!$nuevoFolio)

        @if($inscripcion->movimientoRegistral->estado != 'correccion')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-1 mb-2">

                @if($inscripcion)

                    <div class="mb-3 bg-white rounded-lg p-3 shadow-lg">

                        <span class="flex items-center justify-center text-lg text-gray-700 mb-5">Transmitentes ({{ $inscripcion->transmitentes()->count() }})</span>

                        <div class="flex justify-end mb-2">

                            <x-button-gray
                                    wire:click="agregarTransmitente"
                                    wire:loading.attr="disabled"
                                    wire:target="agregarTransmitente">

                                    <img wire:loading wire:target="agregarTransmitente" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                                    Agregar transmitente
                            </x-button-gray>

                        </div>

                        <x-table>

                            <x-slot name="head">
                                <x-table.heading >Nombre / Razón social</x-table.heading>
                                <x-table.heading >% propiedad</x-table.heading>
                                <x-table.heading >% nuda</x-table.heading>
                                <x-table.heading >% usufructo</x-table.heading>
                                <x-table.heading ></x-table.heading>
                            </x-slot>

                            <x-slot name="body">

                                @foreach ($inscripcion->transmitentes() as $transmitente)

                                    <x-table.row >

                                        <x-table.cell>{{ $transmitente->persona->nombre }} {{ $transmitente->persona->ap_paterno }} {{ $transmitente->persona->ap_materno }} {{ $transmitente->persona->razon_social }}</x-table.cell>
                                        <x-table.cell>{{ $transmitente->porcentaje_propiedad }}</x-table.cell>
                                        <x-table.cell>{{ $transmitente->porcentaje_nuda }}</x-table.cell>
                                        <x-table.cell>{{ $transmitente->porcentaje_usufructo }}</x-table.cell>
                                        <x-table.cell>
                                            <div class="flex flex-col items-center gap-3">
                                                <x-button-red
                                                    wire:click="borrarActor({{ $transmitente->id }})"
                                                    wire:loading.attr="disabled">

                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>

                                                </x-button-red>
                                            </div>
                                        </x-table.cell>

                                    </x-table.row>

                                @endforeach

                            </x-slot>

                            <x-slot name="tfoot"></x-slot>

                        </x-table>

                    </div>

                    <div class="mb-3 bg-white rounded-lg p-3 shadow-lg">

                        <span class="flex items-center justify-center text-lg text-gray-700 mb-5">Adquirientes ({{ $inscripcion->propietarios()->count() }})</span>

                        <div class="flex justify-end mb-2">

                            <x-button-gray
                                    wire:click="agregarPropietario"
                                    wire:loading.attr="disabled"
                                    wire:target="agregarPropietario">

                                    <img wire:loading wire:target="agregarPropietario" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                                    Agregar adquiriente
                            </x-button-gray>

                        </div>

                        <x-table>

                            <x-slot name="head">
                                <x-table.heading >Nombre / Razón social</x-table.heading>
                                <x-table.heading >% propiedad</x-table.heading>
                                <x-table.heading >% nuda</x-table.heading>
                                <x-table.heading >% usufructo</x-table.heading>
                                <x-table.heading ></x-table.heading>
                            </x-slot>

                            <x-slot name="body">

                                @foreach ($inscripcion->propietarios() as $propietario)

                                    <x-table.row >

                                        <x-table.cell>{{ $propietario->persona->nombre }} {{ $propietario->persona->ap_paterno }} {{ $propietario->persona->ap_materno }} {{ $propietario->persona->razon_social }}</x-table.cell>
                                        <x-table.cell>{{ $propietario->porcentaje_propiedad }}%</x-table.cell>
                                        <x-table.cell>{{ $propietario->porcentaje_nuda }}%</x-table.cell>
                                        <x-table.cell>{{ $propietario->porcentaje_usufructo }}%</x-table.cell>
                                        <x-table.cell>
                                            <div class="flex flex-col items-center gap-3">
                                                <x-button-blue
                                                    wire:click="editarActor({{ $propietario->id }}, 'propietario')"
                                                    wire:traget="editarActor({{ $propietario->id }}, 'propietario')"
                                                    wire:loading.attr="disabled"
                                                >

                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                    </svg>

                                                </x-button-blue>
                                                <x-button-red
                                                    wire:click="borrarActor({{ $propietario->id }})"
                                                    wire:loading.attr="disabled">

                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>

                                                </x-button-red>
                                            </div>
                                        </x-table.cell>

                                    </x-table.row>

                                @endforeach

                            </x-slot>

                            <x-slot name="tfoot"></x-slot>

                        </x-table>

                    </div>

                    <div class="mb-3 bg-white rounded-lg p-3 shadow-lg">

                        <span class="flex items-center justify-center text-lg text-gray-700 mb-5">Representantes ({{ $inscripcion->representantes()->count() }})</span>

                        <div class="flex justify-end mb-2">

                            <x-button-gray
                                    wire:click="agregarRepresentante"
                                    wire:loading.attr="disabled"
                                    wire:target="agregarRepresentante">

                                    <img wire:loading wire:target="agregarRepresentante" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                                    Agregar representante
                            </x-button-gray>

                        </div>

                        <x-table>

                            <x-slot name="head">
                                <x-table.heading >Nombre / Razón social</x-table.heading>
                                <x-table.heading >Representados</x-table.heading>
                                <x-table.heading ></x-table.heading>
                            </x-slot>

                            <x-slot name="body">

                                @foreach ($inscripcion->representantes() as $representante)

                                    <x-table.row >

                                        <x-table.cell>{{ $representante->persona->nombre }} {{ $representante->persona->ap_paterno }} {{ $representante->persona->ap_materno }} {{ $representante->persona->razon_social }}</x-table.cell>
                                        <x-table.cell>

                                            @foreach ($representante->representados as $representado)

                                                <p>{{ $representado->persona->nombre }} {{ $representado->persona->ap_paterno }} {{ $representado->persona->ap_materno }} {{ $representado->persona->razon_social }}</p>

                                            @endforeach

                                        </x-table.cell>
                                        <x-table.cell>
                                            <div class="flex flex-col items-center gap-3">
                                                <x-button-blue
                                                    wire:click="editarActor({{ $representante->id }}, 'representante')"
                                                    wire:loading.attr="disabled"
                                                >

                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                    </svg>

                                                </x-button-blue>
                                                <x-button-red
                                                    wire:click="borrarActor({{ $representante->id }})"
                                                    wire:loading.attr="disabled">

                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>

                                                </x-button-red>
                                            </div>
                                        </x-table.cell>

                                    </x-table.row>

                                @endforeach

                            </x-slot>

                            <x-slot name="tfoot"></x-slot>

                        </x-table>

                    </div>

                @endif

            </div>

            <div class="bg-white rounded-lg p-3 shadow-lg mb-4">

                <div>

                    <h4 class="text-lg mb-1 text-center">Distribuición de porcentajes</h4>

                </div>

                <table class="mx-auto">

                    <thead class="border-b border-gray-300 ">

                        <tr class="text-sm text-gray-500 text-left traling-wider whitespace-nowrap">

                            <th class="px-2">Nombre / Razón social</th>
                            <th class="px-2">% Propiedad</th>
                            <th class="px-2">% Nuda</th>
                            <th class="px-2">% Usufructo</th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-200">

                        @foreach ($transmitentes as $key => $transmitente)

                            <tr class="text-gray-500 text-sm leading-relaxed">
                                <td class=" p-2">(Tra.) {{ $transmitente['nombre'] }} {{ $transmitente['ap_paterno'] }} {{ $transmitente['ap_materno'] }} {{ $transmitente['razon_social'] }}</td>
                                <td class=" p-2">
                                    <input wire:model.live="transmitentes.{{ $key }}.porcentaje_propiedad" type="number" class="bg-white text-sm w-full rounded-md p-2 border border-gray-500 outline-none ring-blue-600 focus:ring-1 focus:border-blue-600">
                                </td>
                                <td class=" p-2">
                                    <input wire:model.live="transmitentes.{{ $key }}.porcentaje_nuda" type="number" class="bg-white text-sm w-full rounded-md p-2 border border-gray-500 outline-none ring-blue-600 focus:ring-1 focus:border-blue-600">
                                </td>
                                <td class=" p-2">
                                    <input wire:model.live="transmitentes.{{ $key }}.porcentaje_usufructo" type="number" class="bg-white text-sm w-full rounded-md p-2 border border-gray-500 outline-none ring-blue-600 focus:ring-1 focus:border-blue-600">
                                </td>
                            </tr>

                        @endforeach

                        @foreach ($this->inscripcion->propietarios() as $adquiriente)

                            <tr class="text-gray-500 text-sm leading-relaxed">
                                <td class=" px-2">(Adq.){{ $adquiriente->persona->nombre }} {{ $adquiriente->persona->ap_paterno }} {{ $adquiriente->persona->ap_materno }} {{ $adquiriente->persona->razon_social }}</td>
                                <td class=" px-2">{{ $adquiriente->porcentaje_propiedad ?? '0' }}</td>
                                <td class=" px-2">{{ $adquiriente->porcentaje_nuda ?? '0' }} </td>
                                <td class=" px-2">{{ $adquiriente->porcentaje_usufructo ?? '0' }}</td>
                            </tr>

                        @endforeach

                        <tr class="text-gray-500 text-sm leading-relaxed">
                            <td class=" px-2">Totales</td>
                            <td class=" px-2">{{ collect($transmitentes)->sum('porcentaje_propiedad') + $this->inscripcion->propietarios()->sum('porcentaje_propiedad') }}</td>
                            <td class=" px-2">{{ collect($transmitentes)->sum('porcentaje_nuda') + $this->inscripcion->propietarios()->sum('porcentaje_nuda') }}</td>
                            <td class=" px-2">{{ collect($transmitentes)->sum('porcentaje_usufructo') + $this->inscripcion->propietarios()->sum('porcentaje_usufructo') }}</td>
                        </tr>

                    </tbody>

                </table>

            </div>

        @else

            <div>

                <div class="p-4 bg-white shadow-xl rounded-xl mb-5">

                    <div class="mb-3 bg-white rounded-lg p-3">

                        <span class="flex items-center justify-center text-lg text-gray-700 mb-5">Propietarios</span>

                        <x-table>

                            <x-slot name="head">
                                <x-table.heading >Nombre / Razón social</x-table.heading>
                                <x-table.heading >Porcentaje propiedad</x-table.heading>
                                <x-table.heading >Porcentaje nuda</x-table.heading>
                                <x-table.heading >Porcentaje usufructo</x-table.heading>
                                <x-table.heading ></x-table.heading>
                            </x-slot>

                            <x-slot name="body">

                                @foreach ($inscripcion->movimientoRegistral->folioReal->predio->propietarios() as $propietario)

                                    <x-table.row >

                                        <x-table.cell>{{ $propietario->persona->nombre }} {{ $propietario->persona->ap_paterno }} {{ $propietario->persona->ap_materno }} {{ $propietario->persona->razon_social }}</x-table.cell>
                                        <x-table.cell>{{ $propietario->porcentaje_propiedad }}%</x-table.cell>
                                        <x-table.cell>{{ $propietario->porcentaje_nuda }}%</x-table.cell>
                                        <x-table.cell>{{ $propietario->porcentaje_usufructo }}%</x-table.cell>
                                        <x-table.cell>
                                            <div class="flex items-center gap-3">
                                                <x-button-blue
                                                    wire:click="editarActor({{ $propietario->id }}, 'propietario')"
                                                    wire:traget="editarActor({{ $propietario->id }}, 'propietario')"
                                                    wire:loading.attr="disabled"
                                                >
                                                    Editar
                                                </x-button-blue>
                                                {{-- <x-button-red
                                                    wire:click="borrarActor({{ $propietario->id }})"
                                                    wire:loading.attr="disabled">
                                                    Borrar
                                                </x-button-red> --}}
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

    @endif

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

    <div class="bg-white rounded-lg p-3  justify-between shadow-lg flex">

        <div>

            @if(in_array($inscripcion->servicio, ['D114', 'D113', 'D116', 'D115']) && $inscripcion->estado != 'correccion')

                <x-input-group for="nuevoFolio" label="Esta inscripción genera nuevo folio real" :error="$errors->first('nuevoFolio')" class="flex gap-3 items-center">

                    <x-checkbox wire:model.live="nuevoFolio"/>

                </x-input-group>

            @endif

        </div>

        <div class="flex  gap-3">

            @if(!$inscripcion->movimientoRegistral->documentoEntrada())

                <x-button-blue
                    wire:click="abrirModalDocumento"
                    wire:loading.attr="disabled"
                    wire:target="abrirModalDocumento">

                    <img wire:loading wire:target="abrirModalDocumento" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    Subir documento de entrada

                </x-button-blue>

            @else

                <div class="inline-block">

                    <x-link-blue target="_blank" href="{{ $inscripcion->movimientoRegistral->documentoEntrada() }}">Ver documento de entrada</x-link-blue>

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

    </div>

    <x-dialog-modal wire:model="modalPropietario">

        <x-slot name="title">

            @if($crear)
                Nuevo Adquiriente
            @elseif($editar)
                Editar Adquiriente
            @endif

        </x-slot>

        <x-slot name="content">

            <x-input-group for="tipo_persona" label="Tipo de persona" :error="$errors->first('tipo_persona')" class="w-full p-3">

                <x-input-select id="tipo_persona" wire:model.live="tipo_persona" class="w-full">

                    <option value="">Seleccione una opción</option>
                    <option value="MORAL">MORAL</option>
                    <option value="FISICA">FISICA</option>

                </x-input-select>

            </x-input-group>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 mb-3 col-span-2 rounded-lg p-3">

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

                    <div class=" col-span-3 rounded-lg">

                        <x-input-group for="multiple_nombre" label="Nombre multiple (Opcional)" :error="$errors->first('multiple_nombre')" class="sm:col-span-2 lg:col-span-3">

                            <textarea rows="3" class="w-full bg-white rounded text-sm" wire:model="multiple_nombre"></textarea>

                        </x-input-group>

                    </div>

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

                <x-input-group for="porcentaje_usufructo" label="Porcentaje sufructo" :error="$errors->first('porcentaje_usufructo')" class="w-full">

                    <x-input-text type="number" id="porcentaje_usufructo" wire:model.lazy="porcentaje_usufructo" />

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

    <x-dialog-modal wire:model="modalTransmitente" maxWidth="sm">

        <x-slot name="title">

            Agregar transmitente

        </x-slot>

        <x-slot name="content">

            <x-input-group for="propietario" label="Propietario" :error="$errors->first('propietario')" class="w-full p-3">

                <x-input-select id="propietario" wire:model.live="propietario" class="w-full">

                    <option value="">Seleccione una opción</option>

                    @foreach ($inscripcion->movimientoRegistral->folioReal->predio->propietarios() as $propietario)

                        <option value="{{ $propietario->id }}">{{ $propietario->persona->nombre }} {{ $propietario->persona->ap_paterno }} {{ $propietario->persona->ap_materno }} {{ $propietario->persona->razon_social }}</option>

                    @endforeach

                </x-input-select>

            </x-input-group>

        </x-slot>

        <x-slot name="footer">

            <div class="flex gap-3">

                <x-button-blue
                    wire:click="guardarTransmitente"
                    wire:loading.attr="disabled"
                    wire:target="guardarTransmitente">

                    <img wire:loading wire:target="guardarTransmitente" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <span>Agregar transmitente</span>
                </x-button-blue>

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

    <x-dialog-modal wire:model="modalRepresentante">

        <x-slot name="title">

            @if($crear)
                Nuevo Representante
            @elseif($editar)
                Editar Representante
            @endif

        </x-slot>

        <x-slot name="content">

            <x-input-group for="tipo_persona" label="Tipo de persona" :error="$errors->first('tipo_persona')" class="w-full p-3">

                <x-input-select id="tipo_persona" wire:model.live="tipo_persona" class="w-full">

                    <option value="">Seleccione una opción</option>
                    <option value="MORAL">MORAL</option>
                    <option value="FISICA">FISICA</option>

                </x-input-select>

            </x-input-group>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 mb-3 col-span-2 rounded-lg p-3">

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

                    <div class=" col-span-3 rounded-lg">

                        <x-input-group for="multiple_nombre" label="Nombre multiple (Opcional)" :error="$errors->first('multiple_nombre')" class="sm:col-span-2 lg:col-span-3">

                            <textarea rows="3" class="w-full bg-white rounded text-sm" wire:model="multiple_nombre"></textarea>

                        </x-input-group>

                    </div>

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

                <span class="flex items-center justify-center text-lg text-gray-700 md:col-span-3 col-span-1 sm:col-span-2" >Representados</span>

                <div class="md:col-span-3 col-span-1 sm:col-span-2">

                    <div class="flex space-x-4 items-center">

                        <Label>Seleccione los representados</Label>

                    </div>

                    <div
                        x-data = "{ model: @entangle('representados') }"
                        x-init =
                        "
                            select2 = $($refs.select)
                                .select2({
                                    placeholder: 'Propietarios y transmitentes',
                                    width: '100%',
                                })

                            select2.on('change', function(){
                                $wire.set('representados', $(this).val())
                            })

                            select2.on('keyup', function(e) {
                                if (e.keyCode === 13){
                                    $wire.set('representados', $('.select2').val())
                                }
                            });

                            $watch('model', (value) => {
                                select2.val(value).trigger('change');
                            });

                            Livewire.on('recargar', function(e) {

                                var newOption = new Option(e[0].description, e[0].id, false, false);

                                $($refs.select).append(newOption).trigger('change');

                            });

                        "
                        wire:ignore>

                        <select
                            class="bg-white rounded text-sm w-full z-50"
                            wire:model.live="representados"
                            x-ref="select"
                            multiple="multiple">

                            @if($inscripcion)

                                @foreach ($inscripcion->propietarios() as $propietario)

                                    <option value="{{ $propietario->id }}">{{ $propietario->persona->nombre }} {{ $propietario->persona->ap_paterno }} {{ $propietario->persona->ap_materno }} {{ $propietario->persona->razon_social }}</option>

                                @endforeach

                                @foreach ($inscripcion->transmitentes() as $transmitente)

                                    <option value="{{ $transmitente->id }}">{{ $transmitente->persona->nombre }} {{ $transmitente->persona->ap_paterno }} {{ $transmitente->persona->ap_materno }} {{ $transmitente->persona->razon_social }}</option>

                                @endforeach

                            @endif

                        </select>

                    </div>

                    <div>

                        @error('representados') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                    </div>

                </div>

            </div>

        </x-slot>

        <x-slot name="footer">

            <div class="flex gap-3">

                @if($crear)

                    <x-button-blue
                        wire:click="guardarRepresentante"
                        wire:loading.attr="disabled"
                        wire:target="guardarRepresentante">

                        <img wire:loading wire:target="guardarRepresentante" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

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

    <x-dialog-modal wire:model="modalContraseña">

        <x-slot name="title">

            Finalizar inscripción

        </x-slot>

        <x-slot name="content">

            <div class="grid grid-cols-2 gap-2">

                <div class="bg-gray-100 p-2 rounded-lg">

                    <p><strong>Cuenta predial:</strong> {{ $inscripcion->cp_localidad }}-{{ $inscripcion->cp_oficina }}-{{ $inscripcion->cp_tipo_predio }}-{{ $inscripcion->cp_registro }}</p>

                </div>

                <div class="bg-gray-100 p-2 rounded-lg">

                    <p><strong>Superficie de terreno:</strong> {{ $inscripcion->superficie_terreno }} {{ $inscripcion->unidad_area }}</p>

                </div>

                <div class="bg-gray-100 p-2 rounded-lg">

                    <p><strong>Superficie de construcción:</strong> {{ $inscripcion->superficie_construccion }} {{ $inscripcion->unidad_area }}</p>

                </div>

                <div class="bg-gray-100 p-2 rounded-lg">

                    <p><strong>Monto de la transacción:</strong> {{ $inscripcion->monto_transaccion }} {{ $inscripcion->divisa }}</p>

                </div>

                <div class="bg-gray-100 p-2 rounded-lg">

                    <p><strong>Monto de la transacción:</strong> {{ $inscripcion->monto_transaccion }} {{ $inscripcion->divisa }}</p>

                </div>

                @if ($inscripcion->superficie_judicial)

                    <div class="bg-gray-100 p-2 rounded-lg">

                        <p><strong>Superficie judicial:</strong> {{ $inscripcion->superficie_judicial }} {{ $inscripcion->unidad_area }}</p>

                    </div>

                @endif

                @if ($inscripcion->superficie_notarial)

                    <div class="bg-gray-100 p-2 rounded-lg">

                        <p><strong>Superficie notarial:</strong> {{ $inscripcion->superficie_notarial }} {{ $inscripcion->unidad_area }}</p>

                    </div>

                @endif

                @if ($inscripcion->area_comun_terreno)

                    <div class="bg-gray-100 p-2 rounded-lg">

                        <p><strong>Área de terreno común:</strong> {{ $inscripcion->area_comun_terreno }} {{ $inscripcion->unidad_area }}</p>

                    </div>

                @endif

                @if ($inscripcion->area_comun_construccion)

                    <div class="bg-gray-100 p-2 rounded-lg">

                        <p><strong>Área de contrucción común:</strong> {{ $inscripcion->area_comun_construccion }} {{ $inscripcion->unidad_area }}</p>

                    </div>

                @endif

                @if ($inscripcion->valor_terreno_comun)

                    <div class="bg-gray-100 p-2 rounded-lg">

                        <p><strong>Valor de terreno común:</strong> {{ $inscripcion->valor_terreno_comun }}</p>

                    </div>

                @endif

                @if ($inscripcion->valor_construccion_comun)

                    <div class="bg-gray-100 p-2 rounded-lg">

                        <p><strong>Valor de construcción común:</strong> {{ $inscripcion->valor_construccion_comun }}</p>

                    </div>

                @endif

                @if ($inscripcion->valor_total_terreno)

                    <div class="bg-gray-100 p-2 rounded-lg">

                        <p><strong>Valor total del terreno:</strong> {{ $inscripcion->valor_total_terreno }}</p>

                    </div>

                @endif

                @if ($inscripcion->valor_total_construccion)

                    <div class="bg-gray-100 p-2 rounded-lg">

                        <p><strong>Valor total de la contrucción:</strong> {{ $inscripcion->valor_total_construccion }}</p>

                    </div>

                @endif

                <div class="col-span-2">

                    <table class="min-w-full">
                        <thead class="border-b border-gray-300 bg-gray-50">
                            <tr class="text-gray-500 text-left">
                                <th class="px-2">Viento</th>
                                <th class="px-2">Longitud</th>
                                <th class="px-2">Descripción</th>

                            </tr>
                        </thead>
                        <tbody class="bg-gray-100 divide-y">
                            @foreach ($medidas as $index => $medida)
                                <tr>
                                    <td class="px-2">{{ $medida['viento'] }}</td>
                                    <td class="px-2">{{ $medida['longitud'] }}</td>
                                    <td class="px-2">{{ $medida['descripcion'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>

                @if ($inscripcion->codigo_postal)

                    <div class="bg-gray-100 p-2 rounded-lg">

                        <p><strong>Código postal:</strong> {{ $inscripcion->codigo_postal }}</p>

                    </div>

                @endif

                @if ($inscripcion->nombre_asentamiento)

                    <div class="bg-gray-100 p-2 rounded-lg">

                        <p><strong>Nombre del asentamiento:</strong> {{ $inscripcion->nombre_asentamiento }}</p>

                    </div>

                @endif

                @if ($inscripcion->municipio)

                    <div class="bg-gray-100 p-2 rounded-lg">

                        <p><strong>Municipio:</strong> {{ $inscripcion->municipio }}</p>

                    </div>

                @endif

                @if ($inscripcion->ciudad)

                    <div class="bg-gray-100 p-2 rounded-lg">

                        <p><strong>Ciudad:</strong> {{ $inscripcion->ciudad }}</p>

                    </div>

                @endif

                @if ($inscripcion->tipo_asentamiento)

                    <div class="bg-gray-100 p-2 rounded-lg">

                        <p><strong>Tipo de asentamiento:</strong> {{ $inscripcion->tipo_asentamiento }}</p>

                    </div>

                @endif

                @if ($inscripcion->localidad)

                    <div class="bg-gray-100 p-2 rounded-lg">

                        <p><strong>Localidad:</strong> {{ $inscripcion->localidad }}</p>

                    </div>

                @endif

                @if ($inscripcion->tipo_vialidad)

                    <div class="bg-gray-100 p-2 rounded-lg">

                        <p><strong>Tipo de vialidad:</strong> {{ $inscripcion->tipo_vialidad }}</p>

                    </div>

                @endif

                @if ($inscripcion->nombre_vialidad)

                    <div class="bg-gray-100 p-2 rounded-lg">

                        <p><strong>Nombre de la vialidad:</strong> {{ $inscripcion->nombre_vialidad }}</p>

                    </div>

                @endif

                @if ($inscripcion->numero_exterior)

                    <div class="bg-gray-100 p-2 rounded-lg">

                        <p><strong>Número exterior:</strong> {{ $inscripcion->numero_exterior }}</p>

                    </div>

                @endif

                @if ($inscripcion->numero_interior)

                    <div class="bg-gray-100 p-2 rounded-lg">

                        <p><strong>Número interior:</strong> {{ $inscripcion->numero_interior }}</p>

                    </div>

                @endif

                @if ($inscripcion->nombre_edificio)

                    <div class="bg-gray-100 p-2 rounded-lg">

                        <p><strong>Edificio:</strong> {{ $inscripcion->nombre_edificio }}</p>

                    </div>

                @endif

                @if ($inscripcion->departamento_edificio)

                    <div class="bg-gray-100 p-2 rounded-lg">

                        <p><strong>Departamento:</strong> {{ $inscripcion->departamento_edificio }}</p>

                    </div>

                @endif

                @if ($inscripcion->observaciones)

                    <div class="bg-gray-100 p-2 rounded-lg">

                        <p><strong>Observaciones:</strong> {{ $inscripcion->observaciones }}</p>

                    </div>

                @endif

                @if ($inscripcion->lote)

                    <div class="bg-gray-100 p-2 rounded-lg">

                        <p><strong>Lote:</strong> {{ $inscripcion->lote }}</p>

                    </div>

                @endif

                @if ($inscripcion->manzana)

                    <div class="bg-gray-100 p-2 rounded-lg">

                        <p><strong>Manzana:</strong> {{ $inscripcion->manzana }}</p>

                    </div>

                @endif

                @if ($inscripcion->ejido)

                    <div class="bg-gray-100 p-2 rounded-lg">

                        <p><strong>Ejido:</strong> {{ $inscripcion->ejido }}</p>

                    </div>

                @endif

                @if ($inscripcion->parcela)

                    <div class="bg-gray-100 p-2 rounded-lg">

                        <p><strong>Parcela:</strong> {{ $inscripcion->parcela }}</p>

                    </div>

                @endif

                @if ($inscripcion->solar)

                    <div class="bg-gray-100 p-2 rounded-lg">

                        <p><strong>Solar:</strong> {{ $inscripcion->solar }}</p>

                    </div>

                @endif

                @if ($inscripcion->poblado)

                    <div class="bg-gray-100 p-2 rounded-lg">

                        <p><strong>Poblado:</strong> {{ $inscripcion->poblado }}</p>

                    </div>

                @endif

                @if ($inscripcion->numero_exterior_2)

                    <div class="bg-gray-100 p-2 rounded-lg">

                        <p><strong>Número exterior 2:</strong> {{ $inscripcion->numero_exterior_2 }}</p>

                    </div>

                @endif

                @if ($inscripcion->numero_adicional)

                    <div class="bg-gray-100 p-2 rounded-lg">

                        <p><strong>Número adicional:</strong> {{ $inscripcion->numero_adicional }}</p>

                    </div>

                @endif

                @if ($inscripcion->numero_adicional_2)

                    <div class="bg-gray-100 p-2 rounded-lg">

                        <p><strong>Número adicional 2:</strong> {{ $inscripcion->numero_adicional_2 }}</p>

                    </div>

                @endif

                @if ($inscripcion->lote_fraccionador)

                    <div class="bg-gray-100 p-2 rounded-lg">

                        <p><strong>Lote del fraccionador:</strong> {{ $inscripcion->lote_fraccionador }}</p>

                    </div>

                @endif

                @if ($inscripcion->manzana_fraccionador)

                    <div class="bg-gray-100 p-2 rounded-lg">

                        <p><strong>Manzana del fraccionador:</strong> {{ $inscripcion->manzana_fraccionador }}</p>

                    </div>

                @endif

                @if ($inscripcion->etapa_fraccionador)

                    <div class="bg-gray-100 p-2 rounded-lg">

                        <p><strong>Etapa del fraccionador:</strong> {{ $inscripcion->etapa_fraccionador }}</p>

                    </div>

                @endif

                @if ($inscripcion->clave_edificio)

                    <div class="bg-gray-100 p-2 rounded-lg">

                        <p><strong>Clave del edificio:</strong> {{ $inscripcion->clave_edificio }}</p>

                    </div>

                @endif

                <div class="col-span-2">

                    <table class="min-w-full">
                        <thead class="border-b border-gray-300 bg-gray-50">
                            <tr class="text-gray-500 text-left">
                                <th class="px-2">Nombre/Razón social</th>
                                <th class="px-2">% propiedad</th>
                                <th class="px-2">% nuda</th>
                                <th class="px-2">% usufructo</th>

                            </tr>
                        </thead>
                        <tbody class="bg-gray-100 divide-y">
                            @foreach ($inscripcion->propietarios() as $propietario)
                                <tr>
                                    <td class="px-2">{{ $propietario->persona->nombre }} {{ $propietario->persona->ap_paterno }} {{ $propietario->persona->ap_materno }} {{ $propietario->persona->razon_social }}</td>
                                    <td class="px-2">{{ $propietario->porcentaje_propiedad }}</td>
                                    <td class="px-2">{{ $propietario->porcentaje_nuda }}</td>
                                    <td class="px-2">{{ $propietario->porcentaje_usufructo }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>

            </div>

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
                    wire:click="resetear"
                    wire:loading.attr="disabled"
                    wire:target="resetear"
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

            {{-- <x-filepond wire:model.live="documento" accept="['application/pdf']"/> --}}

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

@push('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@endpush
