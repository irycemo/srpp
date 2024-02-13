<div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

        <div class="col-span-2">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-3 bg-white rounded-lg p-3 shadow-lg">

                <span class="flex items-center justify-center text-lg text-gray-700  md:col-span-3 col-span-1 sm:col-span-2">Descripción del predio</span>

                <div class="space-y-1 sm:col-span-2 lg:col-span-3 mx-auto">

                    <span class="flex items-center justify-center text-gray-700 col-span-2  ">Cuenta predial</span>

                    <input title="Localidad" placeholder="Localidad" type="number" class="bg-white rounded text-xs w-20 @error('localidad') border-1 border-red-500 @enderror" wire:model.lazy="localidad">

                    <input title="Oficina" placeholder="Oficina" type="number" class="bg-white rounded text-xs w-20 @error('oficina') border-1 border-red-500 @enderror" wire:model.defer="oficina">

                    <input title="Tipo de predio" placeholder="Tipo" type="number" class="bg-white rounded text-xs w-20 @error('tipo') border-1 border-red-500 @enderror" wire:model.defer="tipo">

                    <input title="Número de registro" placeholder="Registro" type="number" class="bg-white rounded text-xs w-20 @error('registro') border-1 border-red-500 @enderror" wire:model.lazy="registro">

                </div>

                <div class="space-y-1 sm:col-span-2 lg:col-span-3 mx-auto">

                    <span class="flex items-center justify-center text-gray-700 col-span-2  ">Clave catastral</span>

                    <input placeholder="Estado" type="number" class="bg-white rounded text-xs w-10" title="Estado" value="16" readonly>

                    <input title="Región catastral" placeholder="Región" type="number" class="bg-white rounded text-xs w-16  @error('region') border-1 border-red-500 @enderror" wire:model.defer="region">

                    <input title="Municipio" placeholder="Municipio" type="number" class="bg-white rounded text-xs w-20 @error('municipio') border-1 border-red-500 @enderror" wire:model.defer="municipio">

                    <input title="Zona" placeholder="Zona" type="number" class="bg-white rounded text-xs w-16 @error('zona') border-1 border-red-500 @enderror" wire:model.defer="zona">

                    <input title="Localidad" placeholder="Localidad" type="number" class="bg-white rounded text-xs w-20 @error('localidad') border-1 border-red-500 @enderror" wire:model.lazy="localidad">

                    <input title="Sector" placeholder="Sector" type="number" class="bg-white rounded text-xs w-20 @error('sector') border-1 border-red-500 @enderror" wire:model.defer="sector">

                    <input title="Manzana" placeholder="Manzana" type="number" class="bg-white rounded text-xs w-20 @error('manzana') border-1 border-red-500 @enderror" wire:model.defer="manzana">

                    <input title="Predio" placeholder="Predio" type="number" class="bg-white rounded text-xs w-20 @error('predio') border-1 border-red-500 @enderror" wire:model.lazy="predio">

                    <input title="Edificio" placeholder="Edificio" type="number" class="bg-white rounded text-xs w-16 @error('edificio') border-1 border-red-500 @enderror" wire:model.defer="edificio">

                    <input title="Departamento" placeholder="Departamento" type="number" class="bg-white rounded text-xs w-28 @error('departamento') border-1 border-red-500 @enderror" wire:model.defer="departamento">

                </div>

                <x-input-group for="superficie_terreno" label="Superficie de terreno" :error="$errors->first('superficie_terreno')" class="w-full relative">

                    <x-input-text type="number" id="superficie_terreno" wire:model="superficie_terreno" />

                    <div class="absolute right-0 top-6">

                        <x-input-select id="unidad_area" wire:model="unidad_area">

                            @foreach ($areas as $unidad)

                                <option value="{{ $unidad }}">{{ $unidad }}</option>

                            @endforeach

                        </x-input-select>

                    </div>

                </x-input-group>

                <x-input-group for="superficie_construccion" label="Superficie de construcción" :error="$errors->first('superficie_construccion')" class="w-full relative">

                    <x-input-text type="number" id="superficie_construccion" wire:model="superficie_construccion" />

                    <div class="absolute right-0 top-6">

                        <x-input-select id="unidad_area" wire:model="unidad_area">

                            @foreach ($areas as $unidad)

                                <option value="{{ $unidad }}">{{ $unidad }}</option>

                            @endforeach

                        </x-input-select>

                    </div>

                </x-input-group>

                <x-input-group for="monto_transaccion" label="Monto de la transacción" :error="$errors->first('monto_transaccion')" class="w-full relative">

                    <x-input-text type="number" id="monto_transaccion" wire:model="monto_transaccion" />

                    <div class="absolute right-0 top-6">

                        <x-input-select id="divisa" wire:model="divisa">

                            @foreach ($divisas as $divisa)

                                <option value="{{ $divisa }}">{{ $divisa }}</option>

                            @endforeach

                        </x-input-select>

                    </div>

                </x-input-group>

                <x-input-group for="observaciones" label="Descripción" :error="$errors->first('observaciones')" class="sm:col-span-2 lg:col-span-3">

                    <textarea rows="3" class="w-full bg-white rounded text-sm" wire:model="observaciones"></textarea>

                </x-input-group>

            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-3 bg-white rounded-lg p-3 shadow-lg">

                <span class="flex items-center justify-center text-lg text-gray-700 md:col-span-3 col-span-1 sm:col-span-2">Datos Complementarios</span>

                <x-input-group for="curt" label="CURT" :error="$errors->first('curt')" class="w-full">

                    <x-input-text id="curt" wire:model="curt" />

                </x-input-group>

                <x-input-group for="superficie_judicial" label="Superficie judicial" :error="$errors->first('superficie_judicial')" class="w-full relative">

                    <x-input-text type="number" id="superficie_judicial" wire:model="superficie_judicial" />

                    <div class="absolute right-0 top-6">

                        <x-input-select id="unidad_area" wire:model="unidad_area">

                            @foreach ($areas as $unidad)

                                <option value="{{ $unidad }}">{{ $unidad }}</option>

                            @endforeach

                        </x-input-select>

                    </div>

                </x-input-group>

                <x-input-group for="superficie_notarial" label="Superficie notarial" :error="$errors->first('superficie_notarial')" class="w-full relative">

                    <x-input-text type="number" id="superficie_notarial" wire:model="superficie_notarial" />

                    <div class="absolute right-0 top-6">

                        <x-input-select id="unidad_area" wire:model="unidad_area">

                            @foreach ($areas as $unidad)

                                <option value="{{ $unidad }}">{{ $unidad }}</option>

                            @endforeach

                        </x-input-select>

                    </div>

                </x-input-group>

                <x-input-group for="area_comun_terreno" label="Área de terreno común" :error="$errors->first('area_comun_terreno')" class="w-full relative">

                    <x-input-text type="number" id="area_comun_terreno" wire:model="area_comun_terreno" />

                    <div class="absolute right-0 top-6">

                        <x-input-select id="unidad_area" wire:model="unidad_area">

                            @foreach ($areas as $unidad)

                                <option value="{{ $unidad }}">{{ $unidad }}</option>

                            @endforeach

                        </x-input-select>

                    </div>

                </x-input-group>

                <x-input-group for="area_comun_construccion" label="Área de contrucción común" :error="$errors->first('area_comun_construccion')" class="w-full relative">

                    <x-input-text type="number" id="area_comun_construccion" wire:model="area_comun_construccion" />

                    <div class="absolute right-0 top-6">

                        <x-input-select id="unidad_area" wire:model="unidad_area">

                            @foreach ($areas as $unidad)

                                <option value="{{ $unidad }}">{{ $unidad }}</option>

                            @endforeach

                        </x-input-select>

                    </div>

                </x-input-group>

                <x-input-group for="valor_terreno_comun" label="Valor de terreno común" :error="$errors->first('valor_terreno_comun')" class="w-full">

                    <x-input-text type="number" id="valor_terreno_comun" wire:model="valor_terreno_comun" />

                </x-input-group>

                <x-input-group for="valor_construccion_comun" label="Valor de construcción común" :error="$errors->first('valor_construccion_comun')" class="w-full">

                    <x-input-text type="number" id="valor_construccion_comun" wire:model="valor_construccion_comun" />

                </x-input-group>

                <x-input-group for="valor_total_terreno" label="Valor total del terreno" :error="$errors->first('valor_total_terreno')" class="w-full">

                    <x-input-text type="number" id="valor_total_terreno" wire:model="valor_total_terreno" />

                </x-input-group>

                <x-input-group for="valor_total_construccion" label="Valor total de la contrucción" :error="$errors->first('valor_total_construccion')" class="w-full">

                    <x-input-text type="number" id="valor_total_construccion" wire:model="valor_total_construccion" />

                </x-input-group>

                <x-input-group for="valor_catastral" label="Valor catastral" :error="$errors->first('valor_catastral')" class="w-full">

                    <x-input-text type="number" id="valor_catastral" wire:model="valor_catastral" />

                </x-input-group>

            </div>

        </div>

        <div class="bg-white rounded-lg p-2 mb-3 shadow-lg">

            <span class="flex items-center justify-center text-lg text-gray-700">Información de la base de datos</span>

        </div>

    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-3 col-span-2 bg-white rounded-lg p-3 shadow-lg">

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

        <div class="bg-white rounded-lg p-2 mb-3 shadow-lg">

            <span class="flex items-center justify-center text-lg text-gray-700">Información de la base de datos</span>

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

    <div class=" flex justify-end items-center bg-white rounded-lg p-2 shadow-lg gap-3">


        <x-button-blue
            wire:click="guardarDescripcionPredio"
            wire:loading.attr="disabled"
            wire:target="guardarDescripcionPredio">

            <img wire:loading wire:target="guardarDescripcionPredio" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
            Guardar y continuar
        </x-button-blue>

        <x-button-red
            wire:click="$parent.finalizarPaseAFolio"
            wire:loading.attr="disabled">

            <img wire:loading wire:target="$parent.finalizarPaseAFolio" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
            Finalizar pase a folio

        </x-button-red>

    </div>

</div>
