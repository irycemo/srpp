<div class="">

    @if($certificacion->servicio == 'DL10')

        <x-header>Certificado de propiedad o negativo de propiedad</x-header>

        <div class="bg-white rounded-lg p-4 shadow-lg w-full  mx-auto mb-5">

            <div class="lg:w-1/2 mx-auto mb-5">

                <p class="text-center"><strong>Solicitante</strong></p>

                <div class="text-gray-500 text-sm leading-relaxed mx-auto mb-5">

                    <p class="text-center">{{ $certificacion->movimientoRegistral->solicitante }}</p>

                </div>

                <p class="text-center"><strong>Observaciones</strong></p>

                <div class="text-gray-500 text-sm leading-relaxed mx-auto mb-5">

                    <p class="text-justify">{{ $certificacion->observaciones }}</p>

                </div>

            </div>

        </div>

        <div class="bg-white p-4 rounded-lg shadow-lg mb-5">

            <ul class="grid w-full lg:w-1/2 mx-auto gap-6 md:grid-cols-2">

                <li>

                    <input type="radio" id="certificado-propiedad" name="certificado" value="propiedad" class="hidden peer" wire:model.live="radio" required>

                    <label for="certificado-propiedad" class="inline-flex items-center justify-between w-full p-3 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">

                        <div class="block">

                            <div class="w-full text-lg font-semibold">Certificado de propiedad</div>

                        </div>

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" />
                        </svg>

                    </label>

                </li>

                <li>

                    <input type="radio" id="certificado-negativo" name="certificado" value="negativo" class="hidden peer" wire:model.live="radio">

                    <label for="certificado-negativo" class="inline-flex items-center justify-between w-full p-3 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">

                        <div class="block">

                            <div class="w-full text-lg font-semibold">Certificado negativo de propiedad</div>

                        </div>

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" />
                        </svg>

                    </label>

                </li>

            </ul>

        </div>

        @if($radio == 'propiedad')

            <div class="bg-white p-4 rounded-lg shadow-lg mb-5">

                <ul class="grid w-full lg:w-1/2 mx-auto gap-6 md:grid-cols-2">

                    <li>

                        <input type="radio" id="certificado-unico" name="propiedad" value="unico" class="hidden peer" wire:model.live="propiedad_radio" required>

                        <label for="certificado-unico" class="inline-flex items-center justify-between w-full px-3 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">

                            <div class="block">

                                <div class="w-full text-lg font-semibold">Certificado único de propiedad</div>

                            </div>

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" />
                            </svg>

                        </label>

                    </li>

                    <li>

                        <input type="radio" id="certificado-normal" name="propiedad" value="propieda" class="hidden peer" wire:model.live="propiedad_radio">

                        <label for="certificado-normal" class="inline-flex items-center justify-between w-full px-3 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">

                            <div class="block">

                                <div class="w-full text-lg font-semibold">Certificado de propiedad</div>

                            </div>

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" />
                            </svg>

                        </label>

                    </li>

                </ul>

            </div>

        @elseif($radio == 'negativo')

            <div class="bg-white p-4 rounded-lg shadow-lg mb-5">

                <ul class="grid w-full lg:w-1/2 mx-auto gap-6 md:grid-cols-3">

                    <li>

                        <input type="radio" id="nombre" name="propiedad" value="nombre" class="hidden peer" wire:model.live="negativo_radio" required>

                        <label for="nombre" class="inline-flex items-center justify-between w-full px-3 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">

                            <div class="block">

                                <div class="w-full text-lg font-semibold">Solo nombre</div>

                            </div>

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" />
                            </svg>

                        </label>

                    </li>

                    <li>

                        <input type="radio" id="propiedad_registrada" name="propiedad_registrada" value="propiedad_registrada" class="hidden peer" wire:model.live="negativo_radio">

                        <label for="propiedad_registrada" class="inline-flex items-center justify-between w-full px-3 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">

                            <div class="block">

                                <div class="w-full text-lg font-semibold">Propiedad registrada</div>

                            </div>

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" />
                            </svg>

                        </label>

                    </li>

                    <li>

                        <input type="radio" id="propiedad_sin_registro" name="propiedad_sin_registro" value="propiedad_sin_registro" class="hidden peer" wire:model.live="negativo_radio">

                        <label for="propiedad_sin_registro" class="inline-flex items-center justify-between w-full px-3 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">

                            <div class="block">

                                <div class="w-full text-lg font-semibold">Propiedad no registrada</div>

                            </div>

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" />
                            </svg>

                        </label>

                    </li>

                </ul>

            </div>

        @endif

        @if($radio == 'propiedad' && $propiedad_radio == 'unico')

            <div class="bg-white rounded-lg p-4 shadow-lg w-full  mx-auto mb-5">

                <div class="lg:w-1/2 mx-auto mb-5">

                @include('livewire.certificaciones.comun.propietario')

                <button
                    wire:click="buscarPropietarioUnico"
                    wire:loading.attr="disabled"
                    wire:target="buscarPropietarioUnico"
                    type="button"
                    class="mt-3 bg-blue-400 hover:shadow-lg text-white mx-auto font-bold px-4 py-2 rounded text-xs hover:bg-blue-700 focus:outline-none flex items-center justify-center focus:outline-blue-400 focus:outline-offset-2">

                    <img wire:loading wire:target="buscarPropietarioUnico" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    Buscar

                </button>

                </div>

            </div>

            <p class="text-center bg-white rounded-lg p-4 shadow-lg w-full  mx-auto mb-5"><strong>Propiedades encontradas en el distrito {{ $certificacion->movimientoRegistral->distrito }} ({{ count($predios) + count($prediosOld) }})</strong></p>

            @include('livewire.certificaciones.comun.predios')

            <div class="bg-white rounded-lg p-3 flex justify-end shadow-lg gap-3">

                <x-button-red
                    wire:click="abrirModalRechazar"
                    wire:loading.attr="disabled"
                    wire:target="abrirModalRechazar">

                    <img wire:loading wire:target="abrirModalRechazar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    Rechazar

                </x-button-red>

                @if($flagUnico)

                    <x-button-blue
                        wire:click="generarCertificadoPropiedadUnico"
                        wire:loading.attr="disabled"
                        wire:target="generarCertificadoPropiedadUnico">

                        <img wire:loading wire:target="generarCertificadoPropiedadUnico" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        Generar certificado único de propiedad

                    </x-button-blue>

                @endif

            </div>

        @endif

        @if($radio == 'propiedad' && $propiedad_radio == 'propieda')

            {{-- <p class="text-center bg-white rounded-lg p-4 shadow-lg w-full  mx-auto mb-5"><strong>Cantidad solicitada {{ $certificacion->numero_paginas }}</strong></p> --}}

            @include('livewire.certificaciones.comun.propiedad')

            <div class="bg-white rounded-lg p-3 flex justify-end shadow-lg gap-3">

                <x-button-red
                    wire:click="abrirModalRechazar"
                    wire:loading.attr="disabled"
                    wire:target="abrirModalRechazar">

                    <img wire:loading wire:target="abrirModalRechazar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    Rechazar

                </x-button-red>

                <x-button-blue
                    wire:click="generarCertificadoPropiedad"
                    wire:loading.attr="disabled"
                    wire:target="generarCertificadoPropiedad">

                    <img wire:loading wire:target="generarCertificadoPropiedad" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    Generar certificado de propiedad

                </x-button-blue>

            </div>

        @endif

        @if($radio == 'negativo' && $negativo_radio == 'nombre')

            <div class="bg-white rounded-lg p-4 shadow-lg w-full  mx-auto mb-5">

                <div class="lg:w-1/2 mx-auto mb-5">

                @include('livewire.certificaciones.comun.propietario')

                <button
                    wire:click="buscarPropietarioUnico"
                    wire:loading.attr="disabled"
                    wire:target="buscarPropietarioUnico"
                    type="button"
                    class="mt-3 bg-blue-400 hover:shadow-lg text-white mx-auto font-bold px-4 py-2 rounded text-xs hover:bg-blue-700 focus:outline-none flex items-center justify-center focus:outline-blue-400 focus:outline-offset-2">

                    <img wire:loading wire:target="buscarPropietarioUnico" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    Buscar

                </button>

                </div>

            </div>

            @include('livewire.certificaciones.comun.predios')

            <div class="bg-white rounded-lg p-3 flex justify-end shadow-lg gap-3">

                <x-button-red
                    wire:click="abrirModalRechazar"
                    wire:loading.attr="disabled"
                    wire:target="abrirModalRechazar">

                    <img wire:loading wire:target="abrirModalRechazar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    Rechazar

                </x-button-red>

                @if($flagNegativo)

                    <x-button-blue
                        wire:click="generarCertificadoNegativo"
                        wire:loading.attr="disabled"
                        wire:target="generarCertificadoNegativo">

                        <img wire:loading wire:target="generarCertificadoNegativo" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        Generar certificado negativo de propiedad

                    </x-button-blue>

                @endif

            </div>

        @endif

        @if($radio == 'negativo' && $negativo_radio == 'propiedad_registrada')

            <div class="bg-white rounded-lg p-4 shadow-lg w-full  mx-auto mb-5">

                <div class="lg:w-1/2 mx-auto mb-5">

                @include('livewire.certificaciones.comun.propietario')

                @if($certificacion->movimientoRegistral->folioReal)

                    <button
                        wire:click="buscarProppietariosEnFolio"
                        wire:loading.attr="disabled"
                        wire:target="buscarProppietariosEnFolio"
                        type="button"
                        class="mt-3 bg-blue-400 hover:shadow-lg text-white mx-auto font-bold px-4 py-2 rounded text-xs hover:bg-blue-700 focus:outline-none flex items-center justify-center focus:outline-blue-400 focus:outline-offset-2">

                        <img wire:loading wire:target="buscarProppietariosEnFolio" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        Buscar

                    </button>

                @endif

                </div>

            </div>

            @include('livewire.certificaciones.comun.propiedad')

            <div class="bg-white rounded-lg p-3 flex justify-end shadow-lg gap-3">

                <x-button-red
                    wire:click="abrirModalRechazar"
                    wire:loading.attr="disabled"
                    wire:target="abrirModalRechazar">

                    <img wire:loading wire:target="abrirModalRechazar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    Rechazar

                </x-button-red>

                @if($flagNegativo)

                    <x-button-blue
                        wire:click="generarCertificadoNegativoPropiedad"
                        wire:loading.attr="disabled"
                        wire:target="generarCertificadoNegativoPropiedad">

                        <img wire:loading wire:target="generarCertificadoNegativoPropiedad" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        Generar certificado negativo de propiedad

                    </x-button-blue>

                @endif

            </div>

        @endif

        @if($radio == 'negativo' && $negativo_radio == 'propiedad_sin_registro')

            <div class="bg-white rounded-lg p-4 shadow-lg w-full  mx-auto mb-5">

                <div class="lg:w-1/2 mx-auto mb-5">

                @include('livewire.certificaciones.comun.propietario')

                <button
                    wire:click="buscarPropietarioUnico"
                    wire:loading.attr="disabled"
                    wire:target="buscarPropietarioUnico"
                    type="button"
                    class="mt-3 bg-blue-400 hover:shadow-lg text-white mx-auto font-bold px-4 py-2 rounded text-xs hover:bg-blue-700 focus:outline-none flex items-center justify-center focus:outline-blue-400 focus:outline-offset-2">

                    <img wire:loading wire:target="buscarPropietarioUnico" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    Buscar

                </button>

                </div>

            </div>

            <div class="bg-white rounded-lg p-4 shadow-lg w-full  mx-auto mb-5">

                <div class="lg:w-1/2 mx-auto mb-5">

                    <x-input-group for="observaciones" label="Observaciones" :error="$errors->first('observaciones')" class="w-full">

                        <textarea rows="5" class="bg-white rounded text-sm w-full" wire:model="observaciones"></textarea>

                    </x-input-group>

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

                @if($flagNegativo)

                    <x-button-blue
                        wire:click="generarCertificadoNegativo"
                        wire:loading.attr="disabled"
                        wire:target="generarCertificadoNegativo">

                        <img wire:loading wire:target="generarCertificadoNegativo" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        Generar certificado negativo de propiedad

                    </x-button-blue>

                @endif

            </div>

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
                wire:click="generarCertificadoColindancias"
                wire:loading.attr="disabled"
                wire:target="generarCertificadoColindancias">

                <img wire:loading wire:target="generarCertificadoColindancias" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

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

                <x-input-group for="observaciones" label="Observaciones" :error="$errors->first('observaciones')" class="w-full">

                    <textarea rows="5" class="bg-white rounded text-sm w-full" wire:model="observaciones"></textarea>

                </x-input-group>

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
                    wire:click="$toggle('modalRechazar')"
                    wire:loading.attr="disabled"
                    wire:target="$toggle('modalRechazar')"
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
