<div class="">

    @if($certificacion->servicio == 'DL10')

        <x-header>Certificado de propiedad o negativo de propiedad</x-header>

        <div class="bg-white rounded-lg p-4 shadow-lg w-full  mx-auto mb-5">

            <div class="lg:w-1/2 mx-auto mb-5">

                <p class="text-center"><strong>Solicitante</strong></p>

                <div class="text-gray-500 text-sm leading-relaxed mx-auto mb-5">

                    <p class="text-center">{{ $certificacion->movimientoRegistral->solicitante }}</p>

                    <p class="text-justify">Observaciones: {{ $certificacion->observaciones }}</p>

                </div>

            </div>

        </div>

        @if($certificacion->movimientoRegistral->folio_real)

            <div class="bg-white rounded-lg p-4 shadow-lg w-full  mx-auto mb-5">

                <div class="lg:w-1/2 mx-auto mb-5">

                    <p class="text-center"><strong>Propietarios</strong></p>

                    @foreach ($propietarios as $index => $propietario)

                        <div class="flex gap-3 justify-center items-center">

                            <x-input-group for="nombre" label="Nombre" :error="$errors->first('propietarios.{{ $index }}.nombre')" class="w-full">

                                <x-input-text id="nombre" wire:model.live.debounce="propietarios.{{ $index }}.nombre" />

                            </x-input-group>

                            <x-input-group for="ap_paterno" label="Apellido paterno" :error="$errors->first('propietarios.{{ $index }}.ap_paterno')" class="w-full">

                                <x-input-text id="ap_paterno" wire:model.live.debounce="propietarios.{{ $index }}.ap_paterno" />

                            </x-input-group>

                            <x-input-group for="ap_materno" label="Apellido materno" :error="$errors->first('propietarios.{{ $index }}.ap_materno')" class="w-full">

                                <x-input-text id="ap_materno" wire:model.live.debounce="propietarios.{{ $index }}.ap_materno" />

                            </x-input-group>

                        </div>

                    @endforeach

                </div>

            </div>

            <div class="bg-white rounded-lg p-4 shadow-lg w-full  mx-auto mb-5">

                <p class="text-center"><strong>Ubicación del inmueble</strong></p>

                <div class="text-gray-500 text-sm leading-relaxed lg:w-1/2 mx-auto mb-5">

                    <p class=" text-justify">

                        @if ($certificacion->movimientoRegistral->folioReal->predio->codigo_postal)
                            <strong class="capitalize">Cóidigo postal:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->codigo_postal }};
                        @endif

                        @if ($certificacion->movimientoRegistral->folioReal->predio->tipo_asentamiento)
                            <strong class="capitalize">Tipo de asentamiento:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->tipo_asentamiento }};
                        @endif

                        @if ($certificacion->movimientoRegistral->folioReal->predio->nombre_asentamiento)
                            <strong class="capitalize">Nombre del asentamiento:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->nombre_asentamiento }};
                        @endif

                        @if ($certificacion->movimientoRegistral->folioReal->predio->municipio)
                            <strong class="capitalize">Municipio:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->municipio }};
                        @endif

                        @if ($certificacion->movimientoRegistral->folioReal->predio->ciudad)
                            <strong class="capitalize">Ciudad:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->ciudad }};
                        @endif

                        @if ($certificacion->movimientoRegistral->folioReal->predio->localidad)
                            <strong class="capitalize">Localidad:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->localidad }};
                        @endif

                        @if ($certificacion->movimientoRegistral->folioReal->predio->tipo_vialidad)
                            <strong class="capitalize">Tipo de vialidad:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->tipo_vialidad }};
                        @endif

                        @if ($certificacion->movimientoRegistral->folioReal->predio->nombre_vialidad)
                            <strong class="capitalize">Nombre de la vialidad:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->nombre_vialidad }};
                        @endif

                        @if ($certificacion->movimientoRegistral->folioReal->predio->numero_exterior)
                            <strong class="capitalize">Número exterior:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->numero_exterior ?? 'SN' }};
                        @endif

                        @if ($certificacion->movimientoRegistral->folioReal->predio->numero_interior)
                            <strong class="capitalize">Número interior:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->numero_interior ?? 'SN' }};
                        @endif

                        @if ($certificacion->movimientoRegistral->folioReal->predio->nombre_edificio)
                            <strong class="capitalize">Edificio:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->nombre_edificio }};
                        @endif

                        @if ($certificacion->movimientoRegistral->folioReal->predio->clave_edificio)
                            <strong class="capitalize">clave del edificio:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->clave_edificio }};
                        @endif

                        @if ($certificacion->movimientoRegistral->folioReal->predio->departamento_edificio)
                            <strong class="capitalize">Departamento:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->departamento_edificio }};
                        @endif

                        @if ($certificacion->movimientoRegistral->folioReal->predio->lote)
                            <strong class="capitalize">Lote:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->lote }};
                        @endif

                        @if ($certificacion->movimientoRegistral->folioReal->predio->manzana)
                            <strong class="capitalize">Manzana:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->manzana }};
                        @endif

                        @if ($certificacion->movimientoRegistral->folioReal->predio->ejido)
                            <strong class="capitalize">ejido:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->ejido }};
                        @endif

                        @if ($certificacion->movimientoRegistral->folioReal->predio->parcela)
                            <strong class="capitalize">parcela:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->parcela }};
                        @endif

                        @if ($certificacion->movimientoRegistral->folioReal->predio->solar)
                            <strong class="capitalize">solar:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->solar }};
                        @endif

                        @if ($certificacion->movimientoRegistral->folioReal->predio->poblado)
                            <strong class="capitalize">poblado:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->poblado }};
                        @endif

                        @if ($certificacion->movimientoRegistral->folioReal->predio->numero_exterior)
                            <strong class="capitalize">número exterior:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->numero_exterior }};
                        @endif

                        @if ($certificacion->movimientoRegistral->folioReal->predio->numero_exterior_2)
                            <strong class="capitalize">número exterior 2:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->numero_exterior_2 }};
                        @endif

                        @if ($certificacion->movimientoRegistral->folioReal->predio->numero_adicional)
                            <strong class="capitalize">número adicional:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->numero_adicional }};
                        @endif

                        @if ($certificacion->movimientoRegistral->folioReal->predio->numero_adicional_2)
                            <strong class="capitalize">número adicional 2:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->numero_adicional_2 }};
                        @endif

                        @if ($certificacion->movimientoRegistral->folioReal->predio->lote_fraccionador)
                            <strong class="capitalize">lote del fraccionador:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->lote_fraccionador }};
                        @endif

                        @if ($certificacion->movimientoRegistral->folioReal->predio->manzana_fraccionador)
                            <strong class="capitalize">manzana del fraccionador:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->manzana_fraccionador }};
                        @endif

                        @if ($certificacion->movimientoRegistral->folioReal->predio->etapa_fraccionador)
                            <strong class="capitalize">etapa del fraccionador:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->etapa_fraccionador }};
                        @endif

                        @if ($certificacion->movimientoRegistral->folioReal->predio->observaciones)
                            <strong class="capitalize">Observaciones:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->observaciones }}.
                        @endif

                    </p>

                </div>

                {{-- <p class="text-center"><strong>Colindancias</strong></p>

                <div class="text-gray-500 text-sm leading-relaxed lg:w-1/2 mb-5 mx-auto">

                    <table class="mx-auto">

                        <thead>

                            <tr>
                                <th>Viento</th>
                                <th>Longitud (mts.)</th>
                                <th>Descripción</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach ($certificacion->movimientoRegistral->folioReal->predio->colindancias as $colindancia)

                                <tr>
                                    <td style="padding-right: 40px;">
                                        {{ $colindancia->viento }}
                                    </td>
                                    <td style="padding-right: 40px;">
                                        {{ number_format($colindancia->longitud, 2) }}
                                    </td>
                                    <td style="padding-right: 40px;">
                                        {{ $colindancia->descripcion }}
                                    </td>
                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div> --}}

                <p class="text-center"><strong>Descripción del inmueble</strong></p>

                <div class="text-gray-500 text-sm leading-relaxed lg:w-1/2 mx-auto mb-5">

                    <p class=" text-justify">

                        <strong class="capitalize">Folio real:</strong> {{ $certificacion->movimientoRegistral->folioReal->folio }};

                        @if($certificacion->movimientoRegistral->folioReal->predio->cp_localidad)
                            <strong class="capitalize">Cuenta predial:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->cp_localidad }}-{{ $certificacion->movimientoRegistral->folioReal->predio->cp_oficina }}-{{ $certificacion->movimientoRegistral->folioReal->predio->cp_tipo_predio }}-{{ $certificacion->movimientoRegistral->folioReal->predio->cp_registro }};
                        @endif

                        @if($certificacion->movimientoRegistral->folioReal->predio->cc_region_catastral)
                            <strong class="capitalize">Clave catastral:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->cc_estado }}-{{ $certificacion->movimientoRegistral->folioReal->predio->cc_region_catastral }}-{{ $certificacion->movimientoRegistral->folioReal->predio->cc_municipio }}-{{ $certificacion->movimientoRegistral->folioReal->predio->cc_zona_catastral }}-{{ $certificacion->movimientoRegistral->folioReal->predio->cc_sector }}-{{ $certificacion->movimientoRegistral->folioReal->predio->cc_manzana }}-{{ $certificacion->movimientoRegistral->folioReal->predio->cc_predio }}-{{ $certificacion->movimientoRegistral->folioReal->predio->cc_edificio }}-{{ $certificacion->movimientoRegistral->folioReal->predio->cc_departamento }};
                        @endif

                        <strong class="capitalize">Superficie de terreno:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->superficie_terreno }} {{ $certificacion->movimientoRegistral->folioReal->predio->unidad_area }} <strong class="capitalize">Superficie de construcción:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->superficie_construccion }} {{ $certificacion->movimientoRegistral->folioReal->predio->unidad_area }} <strong class="capitalize">monto de la transacción:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->monto_transaccion }} {{ $certificacion->movimientoRegistral->folioReal->predio->divisa }};

                        @if ($certificacion->movimientoRegistral->folioReal->predio->curt)
                            <strong class="capitalize">curt:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->curt }};
                        @endif

                        @if ($certificacion->movimientoRegistral->folioReal->predio->superficie_judicial)
                            <strong class="capitalize">superficie judicial:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->superficie_judicial }} {{ $certificacion->movimientoRegistral->folioReal->predio->unidad_area }};
                        @endif

                        @if ($certificacion->movimientoRegistral->folioReal->predio->superficie_notarial)
                            <strong class="capitalize">superficie notarial:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->superficie_notarial }} {{ $certificacion->movimientoRegistral->folioReal->predio->unidad_area }};
                        @endif

                        @if ($certificacion->movimientoRegistral->folioReal->predio->area_comun_terreno)
                            <strong class="capitalize">área de terreno común:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->area_comun_terreno }} {{ $certificacion->movimientoRegistral->folioReal->predio->unidad_area }};
                        @endif

                        @if ($certificacion->movimientoRegistral->folioReal->predio->area_comun_construccion)
                            <strong class="capitalize">área de construcción común:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->area_comun_construccion }} {{ $certificacion->movimientoRegistral->folioReal->predio->unidad_area }};
                        @endif

                        @if ($certificacion->movimientoRegistral->folioReal->predio->valor_terreno_comun)
                            <strong class="capitalize">valor de terreno común:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->valor_terreno_comun }} {{ $certificacion->movimientoRegistral->folioReal->predio->divisa }};
                        @endif

                        @if ($certificacion->movimientoRegistral->folioReal->predio->valor_construccion_comun)
                            <strong class="capitalize">valor de construcción común:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->valor_construccion_comun }} {{ $certificacion->movimientoRegistral->folioReal->predio->divisa }};
                        @endif

                        @if ($certificacion->movimientoRegistral->folioReal->predio->valor_catastral)
                            <strong class="capitalize">valor de construcción común:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->valor_catastral }} {{ $certificacion->movimientoRegistral->folioReal->predio->divisa }};
                        @endif

                        <strong class="capitalize">Descripción:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->descripcion }}.

                    </p>

                </div>

                <p class="text-center"><strong>Propietarios</strong></p>

                <div class="text-gray-500 text-sm leading-relaxed lg:w-1/2 mb-5 mx-auto">

                    <table class="mx-auto">

                        <thead>

                            <tr>
                                <th style="padding-right: 10px;">Nombre / Razón social</th>
                                <th style="padding-right: 10px;">% de propiedad</th>
                                <th style="padding-right: 10px;">% de nuda</th>
                                <th style="padding-right: 10px;">% de usufructo</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach ($certificacion->movimientoRegistral->folioReal->predio->propietarios() as $propietario)

                                <tr>
                                    <td style="padding-right: 40px;">
                                        {{ $propietario->persona->nombre }} {{ $propietario->persona->ap_paterno }} {{ $propietario->persona->ap_materno }} {{ $propietario->persona->razon_social }}
                                    </td>
                                    <td style="padding-right: 40px;">
                                        {{ $propietario->porcentaje_propiedad ?? '0.00' }} %
                                    </td>
                                    <td style="padding-right: 40px;">
                                        {{ $propietario->porcentaje_nuda ?? '0.00' }} %
                                    </td>
                                    <td style="padding-right: 40px;">
                                        {{ $propietario->porcentaje_usufructo ?? '0.00' }} %
                                    </td>
                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

            <div class="bg-white rounded-lg p-3 flex justify-end shadow-lg gap-3">

                <x-button-red
                    wire:click="abrirModalRechazar"
                    wire:loading.attr="disabled"
                    wire:target="abrirModalRechazar">

                    <img wire:loading wire:target="abrirModalRechazar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    Rechazar

                </x-button-red>

                <x-button-blue
                    wire:click="generarCertificado(1)"
                    wire:loading.attr="disabled"
                    wire:target="generarCertificado(1)">

                    <img wire:loading wire:target="generarCertificado(1)" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    Generar certificado negativo de propiedad

                </x-button-blue>

                <x-button-blue
                    wire:click="generarCertificado(2)"
                    wire:loading.attr="disabled"
                    wire:target="generarCertificado(2)">

                    <img wire:loading wire:target="generarCertificado(2)" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    Generar certificado de propiedad

                </x-button-blue>

                @if($certificacion->movimientoRegistral->folioReal->predio->propietarios()->count() == 1 && $certificacion->numero_paginas == 1)

                    <x-button-blue
                        wire:click="generarCertificado(3)"
                        wire:loading.attr="disabled"
                        wire:target="generarCertificado(3)">

                        <img wire:loading wire:target="generarCertificado(3)" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        Generar certificado único de propiedad

                    </x-button-blue>

                @endif

            </div>

        @else

            @if($certificacion->numero_paginas > 1)

                <div class="bg-white rounded-lg p-4 shadow-lg w-full  mx-auto mb-5">

                    <div class="lg:w-1/2 mx-auto mb-5">

                        <p class="text-center"><strong>Propietarios</strong></p>

                        @foreach ($propietarios as $index => $propietario)

                            <div class="flex gap-3 justify-center items-center">

                                <x-input-group for="nombre" label="Nombre" :error="$errors->first('propietarios.{{ $index }}.nombre')" class="w-full">

                                    <x-input-text id="nombre" wire:model.live.debounce="propietarios.{{ $index }}.nombre" />

                                </x-input-group>

                                <x-input-group for="ap_paterno" label="Apellido paterno" :error="$errors->first('propietarios.{{ $index }}.ap_paterno')" class="w-full">

                                    <x-input-text id="ap_paterno" wire:model.live.debounce="propietarios.{{ $index }}.ap_paterno" />

                                </x-input-group>

                                <x-input-group for="ap_materno" label="Apellido materno" :error="$errors->first('propietarios.{{ $index }}.ap_materno')" class="w-full">

                                    <x-input-text id="ap_materno" wire:model.live.debounce="propietarios.{{ $index }}.ap_materno" />

                                </x-input-group>

                            </div>

                        @endforeach

                    </div>

                </div>

            @else

                <div class="bg-white rounded-lg p-4 shadow-lg w-full  mx-auto mb-5">

                    <div class="flex flex-col lg:flex-row gap-3 mb-5 lg:w-1/2 mx-auto">

                        <x-input-group for="nombre" label="Nombre" :error="$errors->first('nombre')" class="w-full">

                            <x-input-text id="nombre" wire:model.live.debounce="nombre" />

                        </x-input-group>

                        <x-input-group for="ap_paterno" label="Apellido paterno" :error="$errors->first('ap_paterno')" class="w-full">

                            <x-input-text id="ap_paterno" wire:model.live.debounce="ap_paterno" />

                        </x-input-group>

                        <x-input-group for="ap_materno" label="Apellido materno" :error="$errors->first('ap_materno')" class="w-full">

                            <x-input-text id="ap_materno" wire:model.live.debounce="ap_materno" />

                        </x-input-group>

                        {{-- <x-input-group for="razon_social" label="Razón social" :error="$errors->first('razon_social')" class="w-full">

                            <x-input-text id="razon_social" wire:model.live.debounce="razon_social" />

                        </x-input-group> --}}

                    </div>

                    <button
                        wire:click="buscarPropietario"
                        wire:loading.attr="disabled"
                        wire:target="buscarPropietario"
                        type="button"
                        class="bg-blue-400 hover:shadow-lg text-white mx-auto font-bold px-4 py-2 rounded text-xs hover:bg-blue-700 focus:outline-none flex items-center justify-center focus:outline-blue-400 focus:outline-offset-2">

                        <img wire:loading wire:target="buscarPropietario" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        Buscar

                    </button>

                </div>

            @endif

            @if($propietario)

                <div class="bg-white rounded-lg p-4 shadow-lg w-full  mx-auto mb-5">

                    <p class="text-center"><strong>Ubicación del inmueble</strong></p>

                    <div class="text-gray-500 text-sm leading-relaxed lg:w-1/2 mx-auto mb-5">

                        <p class=" text-justify">

                            @if ($predio->codigo_postal)
                                <strong class="capitalize">Cóidigo postal:</strong> {{ $predio->codigo_postal }};
                            @endif

                            @if ($predio->tipo_asentamiento)
                                <strong class="capitalize">Tipo de asentamiento:</strong> {{ $predio->tipo_asentamiento }};
                            @endif

                            @if ($predio->nombre_asentamiento)
                                <strong class="capitalize">Nombre del asentamiento:</strong> {{ $predio->nombre_asentamiento }};
                            @endif

                            @if ($predio->municipio)
                                <strong class="capitalize">Municipio:</strong> {{ $predio->municipio }};
                            @endif

                            @if ($predio->ciudad)
                                <strong class="capitalize">Ciudad:</strong> {{ $predio->ciudad }};
                            @endif

                            @if ($predio->localidad)
                                <strong class="capitalize">Localidad:</strong> {{ $predio->localidad }};
                            @endif

                            @if ($predio->tipo_vialidad)
                                <strong class="capitalize">Tipo de vialidad:</strong> {{ $predio->tipo_vialidad }};
                            @endif

                            @if ($predio->nombre_vialidad)
                                <strong class="capitalize">Nombre de la vialidad:</strong> {{ $predio->nombre_vialidad }};
                            @endif

                            @if ($predio->numero_exterior)
                                <strong class="capitalize">Número exterior:</strong> {{ $predio->numero_exterior ?? 'SN' }};
                            @endif

                            @if ($predio->numero_interior)
                                <strong class="capitalize">Número interior:</strong> {{ $predio->numero_interior ?? 'SN' }};
                            @endif

                            @if ($predio->nombre_edificio)
                                <strong class="capitalize">Edificio:</strong> {{ $predio->nombre_edificio }};
                            @endif

                            @if ($predio->clave_edificio)
                                <strong class="capitalize">clave del edificio:</strong> {{ $predio->clave_edificio }};
                            @endif

                            @if ($predio->departamento_edificio)
                                <strong class="capitalize">Departamento:</strong> {{ $predio->departamento_edificio }};
                            @endif

                            @if ($predio->lote)
                                <strong class="capitalize">Lote:</strong> {{ $predio->lote }};
                            @endif

                            @if ($predio->manzana)
                                <strong class="capitalize">Manzana:</strong> {{ $predio->manzana }};
                            @endif

                            @if ($predio->ejido)
                                <strong class="capitalize">ejido:</strong> {{ $predio->ejido }};
                            @endif

                            @if ($predio->parcela)
                                <strong class="capitalize">parcela:</strong> {{ $predio->parcela }};
                            @endif

                            @if ($predio->solar)
                                <strong class="capitalize">solar:</strong> {{ $predio->solar }};
                            @endif

                            @if ($predio->poblado)
                                <strong class="capitalize">poblado:</strong> {{ $predio->poblado }};
                            @endif

                            @if ($predio->numero_exterior)
                                <strong class="capitalize">número exterior:</strong> {{ $predio->numero_exterior }};
                            @endif

                            @if ($predio->numero_exterior_2)
                                <strong class="capitalize">número exterior 2:</strong> {{ $predio->numero_exterior_2 }};
                            @endif

                            @if ($predio->numero_adicional)
                                <strong class="capitalize">número adicional:</strong> {{ $predio->numero_adicional }};
                            @endif

                            @if ($predio->numero_adicional_2)
                                <strong class="capitalize">número adicional 2:</strong> {{ $predio->numero_adicional_2 }};
                            @endif

                            @if ($predio->lote_fraccionador)
                                <strong class="capitalize">lote del fraccionador:</strong> {{ $predio->lote_fraccionador }};
                            @endif

                            @if ($predio->manzana_fraccionador)
                                <strong class="capitalize">manzana del fraccionador:</strong> {{ $predio->manzana_fraccionador }};
                            @endif

                            @if ($predio->etapa_fraccionador)
                                <strong class="capitalize">etapa del fraccionador:</strong> {{ $predio->etapa_fraccionador }};
                            @endif

                            @if ($predio->observaciones)
                                <strong class="capitalize">Observaciones:</strong> {{ $predio->observaciones }}.
                            @endif

                        </p>

                    </div>

                    <p class="text-center"><strong>Propietarios</strong></p>

                    <div class="text-gray-500 text-sm leading-relaxed lg:w-1/2 mb-5 mx-auto">

                        <table class="mx-auto">

                            <thead>

                                <tr>
                                    <th style="padding-right: 10px;">Nombre / Razón social</th>
                                    <th style="padding-right: 10px;">% de propiedad</th>
                                    <th style="padding-right: 10px;">% de nuda</th>
                                    <th style="padding-right: 10px;">% de usufructo</th>
                                </tr>

                            </thead>

                            <tbody>

                                @foreach ($predio->propietarios() as $propietario)

                                    <tr>
                                        <td style="padding-right: 40px;">
                                            {{ $propietario->persona->nombre }} {{ $propietario->persona->ap_paterno }} {{ $propietario->persona->ap_materno }} {{ $propietario->persona->razon_social }}
                                        </td>
                                        <td style="padding-right: 40px;">
                                            {{ $propietario->porcentaje_propiedad ?? '0.00' }} %
                                        </td>
                                        <td style="padding-right: 40px;">
                                            {{ $propietario->porcentaje_nuda ?? '0.00' }} %
                                        </td>
                                        <td style="padding-right: 40px;">
                                            {{ $propietario->porcentaje_usufructo ?? '0.00' }} %
                                        </td>
                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

                <div class="bg-white rounded-lg p-3 flex justify-end shadow-lg gap-3">

                    <x-button-red
                        wire:click="abrirModalRechazar"
                        wire:loading.attr="disabled"
                        wire:target="abrirModalRechazar">

                        <img wire:loading wire:target="abrirModalRechazar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        Rechazar

                    </x-button-red>

                </div>

            @elseif($propietarioOld)

                <div class="bg-white rounded-lg p-4 shadow-lg w-full  mx-auto mb-5">

                    <p class="text-center"><strong>Descripción del inmueble</strong></p>

                    <div class="text-gray-500 text-sm leading-relaxed lg:w-1/2 mx-auto mb-5">

                        <p class=" text-justify">

                            @if ($predioOld->distrito)
                                <strong class="capitalize">Distrito:</strong> {{ $predioOld->distrito }};
                            @endif

                            @if ($predioOld->registro)
                                <strong class="capitalize">Registro:</strong> {{ $predioOld->registro }};
                            @endif

                            @if ($predioOld->noprop)
                                <strong class="capitalize">Número de propiedad:</strong> {{ $predioOld->noprop }};
                            @endif

                            @if ($predioOld->superficie)
                                <strong class="capitalize">Superficie:</strong> {{ $predioOld->superficie }};
                            @endif

                            @if ($predioOld->monto)
                                <strong class="capitalize">Monto:</strong> {{ $predioOld->monto }};
                            @endif

                            @if ($predioOld->ubicacion)
                                <strong class="capitalize">Ubicación:</strong> {{ $predioOld->ubicacion }};
                            @endif

                            @if ($predioOld->propietarios)
                                <strong class="capitalize">Propietarios:</strong> {{ $predioOld->propietarios }};
                            @endif
                        </p>

                    </div>

                </div>

                <div class="bg-white rounded-lg p-3 flex justify-end shadow-lg gap-3">

                    <x-button-red
                        wire:click="abrirModalRechazar"
                        wire:loading.attr="disabled"
                        wire:target="abrirModalRechazar">

                        <img wire:loading wire:target="abrirModalRechazar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        Rechazar

                    </x-button-red>

                </div>

            @elseif($flagPropietario)

                <div class="bg-white rounded-lg p-4 shadow-lg w-full  mx-auto mb-5 text-center">

                    <span class="">No se encontraron propiedades a nombre de algun propietario con los campos ingresados</span>

                </div>

                <div class="bg-white rounded-lg p-3 flex justify-end shadow-lg gap-3">

                    <x-button-blue
                        wire:click="generarCertificado(5)"
                        wire:loading.attr="disabled"
                        wire:target="generarCertificado(5)">

                        <img wire:loading wire:target="generarCertificado(5)" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        Generar certificado

                    </x-button-blue>

                </div>

            @endif

        @endif

    @elseif($certificacion->servicio == 'DL11')

        <x-header>Certificado con medidas y linderos</x-header>

        <div class="bg-white rounded-lg p-4 shadow-lg w-full  mx-auto mb-5">

            <div class="lg:w-1/2 mx-auto mb-5">

                <p class="text-center"><strong>Solicitante</strong></p>

                <div class="text-gray-500 text-sm leading-relaxed lg:w-1/2 mx-auto mb-5">

                    <p class="text-center">{{ $certificacion->movimientoRegistral->solicitante }}</p>

                    <p class="text-justify">Observaciones: {{ $certificacion->observaciones }}</p>

                </div>

            </div>

        </div>

        <div class="bg-white rounded-lg p-4 shadow-lg w-full  mx-auto mb-5">

            <p class="text-center"><strong>Ubicación del inmueble</strong></p>

            <div class="text-gray-500 text-sm leading-relaxed lg:w-1/2 mx-auto mb-5">

                <p class=" text-justify">

                    @if ($certificacion->movimientoRegistral->folioReal->predio->codigo_postal)
                        <strong class="capitalize">Cóidigo postal:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->codigo_postal }};
                    @endif

                    @if ($certificacion->movimientoRegistral->folioReal->predio->tipo_asentamiento)
                        <strong class="capitalize">Tipo de asentamiento:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->tipo_asentamiento }};
                    @endif

                    @if ($certificacion->movimientoRegistral->folioReal->predio->nombre_asentamiento)
                        <strong class="capitalize">Nombre del asentamiento:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->nombre_asentamiento }};
                    @endif

                    @if ($certificacion->movimientoRegistral->folioReal->predio->municipio)
                        <strong class="capitalize">Municipio:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->municipio }};
                    @endif

                    @if ($certificacion->movimientoRegistral->folioReal->predio->ciudad)
                        <strong class="capitalize">Ciudad:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->ciudad }};
                    @endif

                    @if ($certificacion->movimientoRegistral->folioReal->predio->localidad)
                        <strong class="capitalize">Localidad:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->localidad }};
                    @endif

                    @if ($certificacion->movimientoRegistral->folioReal->predio->tipo_vialidad)
                        <strong class="capitalize">Tipo de vialidad:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->tipo_vialidad }};
                    @endif

                    @if ($certificacion->movimientoRegistral->folioReal->predio->nombre_vialidad)
                        <strong class="capitalize">Nombre de la vialidad:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->nombre_vialidad }};
                    @endif

                    @if ($certificacion->movimientoRegistral->folioReal->predio->numero_exterior)
                        <strong class="capitalize">Número exterior:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->numero_exterior ?? 'SN' }};
                    @endif

                    @if ($certificacion->movimientoRegistral->folioReal->predio->numero_interior)
                        <strong class="capitalize">Número interior:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->numero_interior ?? 'SN' }};
                    @endif

                    @if ($certificacion->movimientoRegistral->folioReal->predio->nombre_edificio)
                        <strong class="capitalize">Edificio:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->nombre_edificio }};
                    @endif

                    @if ($certificacion->movimientoRegistral->folioReal->predio->clave_edificio)
                        <strong class="capitalize">clave del edificio:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->clave_edificio }};
                    @endif

                    @if ($certificacion->movimientoRegistral->folioReal->predio->departamento_edificio)
                        <strong class="capitalize">Departamento:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->departamento_edificio }};
                    @endif

                    @if ($certificacion->movimientoRegistral->folioReal->predio->lote)
                        <strong class="capitalize">Lote:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->lote }};
                    @endif

                    @if ($certificacion->movimientoRegistral->folioReal->predio->manzana)
                        <strong class="capitalize">Manzana:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->manzana }};
                    @endif

                    @if ($certificacion->movimientoRegistral->folioReal->predio->ejido)
                        <strong class="capitalize">ejido:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->ejido }};
                    @endif

                    @if ($certificacion->movimientoRegistral->folioReal->predio->parcela)
                        <strong class="capitalize">parcela:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->parcela }};
                    @endif

                    @if ($certificacion->movimientoRegistral->folioReal->predio->solar)
                        <strong class="capitalize">solar:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->solar }};
                    @endif

                    @if ($certificacion->movimientoRegistral->folioReal->predio->poblado)
                        <strong class="capitalize">poblado:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->poblado }};
                    @endif

                    @if ($certificacion->movimientoRegistral->folioReal->predio->numero_exterior)
                        <strong class="capitalize">número exterior:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->numero_exterior }};
                    @endif

                    @if ($certificacion->movimientoRegistral->folioReal->predio->numero_exterior_2)
                        <strong class="capitalize">número exterior 2:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->numero_exterior_2 }};
                    @endif

                    @if ($certificacion->movimientoRegistral->folioReal->predio->numero_adicional)
                        <strong class="capitalize">número adicional:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->numero_adicional }};
                    @endif

                    @if ($certificacion->movimientoRegistral->folioReal->predio->numero_adicional_2)
                        <strong class="capitalize">número adicional 2:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->numero_adicional_2 }};
                    @endif

                    @if ($certificacion->movimientoRegistral->folioReal->predio->lote_fraccionador)
                        <strong class="capitalize">lote del fraccionador:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->lote_fraccionador }};
                    @endif

                    @if ($certificacion->movimientoRegistral->folioReal->predio->manzana_fraccionador)
                        <strong class="capitalize">manzana del fraccionador:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->manzana_fraccionador }};
                    @endif

                    @if ($certificacion->movimientoRegistral->folioReal->predio->etapa_fraccionador)
                        <strong class="capitalize">etapa del fraccionador:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->etapa_fraccionador }};
                    @endif

                    @if ($certificacion->movimientoRegistral->folioReal->predio->observaciones)
                        <strong class="capitalize">Observaciones:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->observaciones }}.
                    @endif

                </p>

            </div>

            <p class="text-center"><strong>Colindancias</strong></p>

            <div class="text-gray-500 text-sm leading-relaxed lg:w-1/2 mb-5 mx-auto">

                <table class="mx-auto">

                    <thead>

                        <tr>
                            <th>Viento</th>
                            <th>Longitud (mts.)</th>
                            <th>Descripción</th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($certificacion->movimientoRegistral->folioReal->predio->colindancias as $colindancia)

                            <tr>
                                <td style="padding-right: 40px;">
                                    {{ $colindancia->viento }}
                                </td>
                                <td style="padding-right: 40px;">
                                    {{ number_format($colindancia->longitud, 2) }}
                                </td>
                                <td style="padding-right: 40px;">
                                    {{ $colindancia->descripcion }}
                                </td>
                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

            <p class="text-center"><strong>Descripción del inmueble</strong></p>

            <div class="text-gray-500 text-sm leading-relaxed lg:w-1/2 mx-auto mb-5">

                <p class=" text-justify">

                    <strong class="capitalize">Folio real:</strong> {{ $certificacion->movimientoRegistral->folioReal->folio }};

                    @if($certificacion->movimientoRegistral->folioReal->predio->cp_localidad)
                        <strong class="capitalize">Cuenta predial:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->cp_localidad }}-{{ $certificacion->movimientoRegistral->folioReal->predio->cp_oficina }}-{{ $certificacion->movimientoRegistral->folioReal->predio->cp_tipo_predio }}-{{ $certificacion->movimientoRegistral->folioReal->predio->cp_registro }};
                    @endif

                    @if($certificacion->movimientoRegistral->folioReal->predio->cc_region_catastral)
                        <strong class="capitalize">Clave catastral:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->cc_estado }}-{{ $certificacion->movimientoRegistral->folioReal->predio->cc_region_catastral }}-{{ $certificacion->movimientoRegistral->folioReal->predio->cc_municipio }}-{{ $certificacion->movimientoRegistral->folioReal->predio->cc_zona_catastral }}-{{ $certificacion->movimientoRegistral->folioReal->predio->cc_sector }}-{{ $certificacion->movimientoRegistral->folioReal->predio->cc_manzana }}-{{ $certificacion->movimientoRegistral->folioReal->predio->cc_predio }}-{{ $certificacion->movimientoRegistral->folioReal->predio->cc_edificio }}-{{ $certificacion->movimientoRegistral->folioReal->predio->cc_departamento }};
                    @endif

                    <strong class="capitalize">Superficie de terreno:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->superficie_terreno }} {{ $certificacion->movimientoRegistral->folioReal->predio->unidad_area }} <strong class="capitalize">Superficie de construcción:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->superficie_construccion }} {{ $certificacion->movimientoRegistral->folioReal->predio->unidad_area }} <strong class="capitalize">monto de la transacción:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->monto_transaccion }} {{ $certificacion->movimientoRegistral->folioReal->predio->divisa }};

                    @if ($certificacion->movimientoRegistral->folioReal->predio->curt)
                        <strong class="capitalize">curt:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->curt }};
                    @endif

                    @if ($certificacion->movimientoRegistral->folioReal->predio->superficie_judicial)
                        <strong class="capitalize">superficie judicial:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->superficie_judicial }} {{ $certificacion->movimientoRegistral->folioReal->predio->unidad_area }};
                    @endif

                    @if ($certificacion->movimientoRegistral->folioReal->predio->superficie_notarial)
                        <strong class="capitalize">superficie notarial:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->superficie_notarial }} {{ $certificacion->movimientoRegistral->folioReal->predio->unidad_area }};
                    @endif

                    @if ($certificacion->movimientoRegistral->folioReal->predio->area_comun_terreno)
                        <strong class="capitalize">área de terreno común:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->area_comun_terreno }} {{ $certificacion->movimientoRegistral->folioReal->predio->unidad_area }};
                    @endif

                    @if ($certificacion->movimientoRegistral->folioReal->predio->area_comun_construccion)
                        <strong class="capitalize">área de construcción común:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->area_comun_construccion }} {{ $certificacion->movimientoRegistral->folioReal->predio->unidad_area }};
                    @endif

                    @if ($certificacion->movimientoRegistral->folioReal->predio->valor_terreno_comun)
                        <strong class="capitalize">valor de terreno común:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->valor_terreno_comun }} {{ $certificacion->movimientoRegistral->folioReal->predio->divisa }};
                    @endif

                    @if ($certificacion->movimientoRegistral->folioReal->predio->valor_construccion_comun)
                        <strong class="capitalize">valor de construcción común:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->valor_construccion_comun }} {{ $certificacion->movimientoRegistral->folioReal->predio->divisa }};
                    @endif

                    @if ($certificacion->movimientoRegistral->folioReal->predio->valor_catastral)
                        <strong class="capitalize">valor de construcción común:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->valor_catastral }} {{ $certificacion->movimientoRegistral->folioReal->predio->divisa }};
                    @endif

                    <strong class="capitalize">Descripción:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->descripcion }}.

                </p>

            </div>

            <p class="text-center"><strong>Propietarios</strong></p>

            <div class="text-gray-500 text-sm leading-relaxed lg:w-1/2 mb-5 mx-auto">

                <table class="mx-auto">

                    <thead>

                        <tr>
                            <th style="padding-right: 10px;">Nombre / Razón social</th>
                            <th style="padding-right: 10px;">% de propiedad</th>
                            <th style="padding-right: 10px;">% de nuda</th>
                            <th style="padding-right: 10px;">% de usufructo</th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($certificacion->movimientoRegistral->folioReal->predio->propietarios() as $propietario)

                            <tr>
                                <td style="padding-right: 40px;">
                                    {{ $propietario->persona->nombre }} {{ $propietario->persona->ap_paterno }} {{ $propietario->persona->ap_materno }} {{ $propietario->persona->razon_social }}
                                </td>
                                <td style="padding-right: 40px;">
                                    {{ $propietario->porcentaje_propiedad ?? '0.00' }} %
                                </td>
                                <td style="padding-right: 40px;">
                                    {{ $propietario->porcentaje_nuda ?? '0.00' }} %
                                </td>
                                <td style="padding-right: 40px;">
                                    {{ $propietario->porcentaje_usufructo ?? '0.00' }} %
                                </td>
                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

        <div class="bg-white rounded-lg p-3 flex justify-end shadow-lg gap-3">

            <x-button-red
                wire:click="abrirModalRechazar"
                wire:loading.attr="disabled"
                wire:target="abrirModalRechazar">

                <img wire:loading wire:target="abrirModalRechazar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                Rechazar

            </x-button-red>

            <x-button-blue
                wire:click="generarCertificado(4)"
                wire:loading.attr="disabled"
                wire:target="generarCertificado(4)">

                <img wire:loading wire:target="generarCertificado(4)" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                Generar certificado

            </x-button-blue>

        </div>

    @endif

    <x-dialog-modal wire:model="modalRechazar" maxWidth="sm">

        <x-slot name="title">

            Rechazar

        </x-slot>

        <x-slot name="content">

            <div class="flex flex-col md:flex-row justify-between md:space-x-3 mb-5">

                <div class="flex-auto ">

                    <div>

                        <Label>Observaciones</Label>
                    </div>

                    <div>

                        <textarea rows="5" class="bg-white rounded text-sm w-full" wire:model="observaciones"></textarea>

                    </div>

                    <div>

                        @error('observaciones') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                    </div>

                </div>

            </div>

        </x-slot>

        <x-slot name="footer">

            <div class="flex items-center justify-end space-x-3">

                <x-button-blue
                    wire:click="rechazar"
                    wire:loading.attr="disabled"
                    wire:target="rechazar">

                    <img wire:loading wire:target="rechazar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <span>Rechazar</span>
                </x-button-blue>

                <x-button-red
                    wire:click="resetearTodo"
                    wire:loading.attr="disabled"
                    wire:target="resetearTodo"
                    type="button">
                    <span>Cerrar</span>
                </x-button-red>

            </div>

        </x-slot>

    </x-dialog-modal>

</div>

@push('scripts')

    <script>

        window.addEventListener('imprimir_negativo_propiedad', event => {

            const documento = event.detail[0].certificacion;

            var url = "{{ route('certificado_negativo_propiedad_pdf', '')}}" + "/" + documento;

            window.open(url, '_blank');

            window.location.href = "{{ route('certificados_propiedad')}}";

        });

        window.addEventListener('imprimir_propiedad', event => {

            const documento = event.detail[0].certificacion;

            var url = "{{ route('certificado_propiedad_pdf', '')}}" + "/" + documento;

            window.open(url, '_blank');

            window.location.href = "{{ route('certificados_propiedad')}}";

        });

        window.addEventListener('imprimir_unico_propiedad', event => {

            const documento = event.detail[0].certificacion;

            var url = "{{ route('certificado_unico_propiedad_pdf', '')}}" + "/" + documento;

            window.open(url, '_blank');

            window.location.href = "{{ route('certificados_propiedad')}}";

        });

        window.addEventListener('imprimir_propiedad_colindancias', event => {

            const documento = event.detail[0].certificacion;

            var url = "{{ route('certificado_propiedad_colindancias_pdf', '')}}" + "/" + documento;

            window.open(url, '_blank');

            window.location.href = "{{ route('certificados_propiedad')}}";

        });

        window.addEventListener('imprimir_negativo', event => {

            const documento = event.detail[0].certificacion;

            var url = "{{ route('certificado_negativo_pdf', '')}}" + "/" + documento;

            window.open(url, '_blank');

            window.location.href = "{{ route('certificados_propiedad')}}";

        });

    </script>

@endpush
