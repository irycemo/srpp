<div>

    <x-header>Folio Real Inmobiliario ({{ $folioReal->folio }})</x-header>

    <div class="flex justify-end mb-5 relative" x-data="{ open_drop_down:false }">

        <div>

            <button x-on:click="open_drop_down=true" type="button" class="border-gray-500 border-2 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>

            </button>

        </div>

        <div x-cloak x-show="open_drop_down" x-on:click="open_drop_down=false" x-on:click.away="open_drop_down=false" class="z-50 origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu">

            <button
                wire:click="agregarAclaracionAdministrativa"
                wire:confirm="¿Esta seguro que desea agregar un nuevo movimiento registral?"
                wire:loading.attr="disabled"
                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                role="menuitem">
                Agregar aclaración administrativa
            </button>

            <button
                wire:click="quitarPartesIguales"
                wire:loading.attr="disabled"
                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                role="menuitem">
                Quitar partes iguales
            </button>

        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">

        <div class="bg-white p-4 shadow-xl rounded-lg col-span-4">

            <x-h4>Folio real</x-h4>

            <div class="my-5 text-sm space-y-3">

                <div class="rounded-lg bg-gray-100 py-1 px-2">

                    <p><strong>Estado:</strong> {{ ucfirst($folioReal->estado) }}</p>

                </div>

                <div class="rounded-lg bg-gray-100 py-1 px-2">

                    <p><strong>Matriz:</strong> {{ $folioReal->matriz ? 'Si' : 'No' }}</p>

                </div>

                @if($folioReal->folioRealAntecedente)

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Folio real antecedente:</strong> {{ $folioReal->folioRealAntecedente->folio }}</p>

                    </div>

                @else

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Tomo antecedente:</strong> {{ $folioReal->tomo_antecedente }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Registro antecedente:</strong> {{ $folioReal->registro_antecedente }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Número de propiedad antecedente:</strong> {{ $folioReal->numero_propiedad_antecedente }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Distrito antecedente:</strong> {{ $folioReal->distrito_antecedente }}</p>

                    </div>

                @endif

                <div class="rounded-lg bg-gray-100 py-1 px-2">

                    <p><strong>Tipo de documento:</strong> {{ $folioReal->tipo_documento }}</p>

                </div>

                <div class="rounded-lg bg-gray-100 py-1 px-2">

                    <p><strong>Númemro de documento:</strong> {{ $folioReal->numero_documento }}</p>

                </div>

                <div class="rounded-lg bg-gray-100 py-1 px-2">

                    <p><strong>Autoridad a cargo:</strong> {{ $folioReal->autoridad_cargo }}</p>

                </div>

                <div class="rounded-lg bg-gray-100 py-1 px-2">

                    <p><strong>Nombre de la autoridad a cargo:</strong> {{ $folioReal->autoridad_nombre }}</p>

                </div>

                <div class="rounded-lg bg-gray-100 py-1 px-2">

                    <p><strong>Número de la autoridad a cargo:</strong> {{ $folioReal->autoridad_numero }}</p>

                </div>

                <div class="rounded-lg bg-gray-100 py-1 px-2">

                    <p><strong>Fecha de emisión:</strong> {{ $folioReal->fecha_emision }}</p>

                </div>

                <div class="rounded-lg bg-gray-100 py-1 px-2">

                    <p><strong>Fecha de inscripción:</strong> {{ $folioReal->fecha_inscripcion }}</p>

                </div>

                <div class="rounded-lg bg-gray-100 py-1 px-2">

                    <p><strong>Procedencia:</strong> {{ $folioReal->procedencia }}</p>

                </div>

                <div class="rounded-lg bg-gray-100 py-1 px-2">

                    <p><strong>Acto contenido en el antecedente:</strong> {{ $folioReal->acto_contenido_antecedente }}</p>

                </div>

                <div class="rounded-lg bg-gray-100 py-1 px-2">

                    <p><strong>Observaciones del antecedente:</strong> {{ $folioReal->observaciones_antecedente }}</p>

                </div>

                <div class="rounded-lg bg-gray-100 py-1 px-2">

                    <p><strong>Asignado por:</strong> {{ $folioReal->asignado_por }}</p>

                </div>

            </div>

            <div x-data="{ activeTab: 0 }" class="tab-wrapper">

                <x-h4
                    @click="activeTab =  activeTab == 1 ? 0 : 1"
                    class="cursor-pointer">
                    Predio
                </x-h4>

                <div class="my-5 text-sm space-y-3 tab-panel"  :class="{ 'active': activeTab === 1 }" x-show.transition.in.opacity.duration.800="activeTab === 1"  wire:key="tab-1">

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Partes iguales:</strong> {{ $folioReal->predio->partes_iguales ? 'Si' : ' No' }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Cuenta predial:</strong> {{ $folioReal->predio->cuentaPredial() }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Clave catastral:</strong> {{ $folioReal->predio->claveCatastral() }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Superficie de terreno:</strong> {{ $folioReal->predio->superficie_terreno }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Superficie de construcción:</strong> {{ $folioReal->predio->superficie_construccion }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Superficie judicial:</strong> {{ $folioReal->predio->superficie_judicial }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Superficie notarial:</strong> {{ $folioReal->predio->superficie_notarial }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Área común de terreno:</strong> {{ $folioReal->predio->area_comun_terreno }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Área común de construcción:</strong> {{ $folioReal->predio->area_comun_construccion }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Valor de terreno común:</strong> {{ $folioReal->predio->valor_terreno_comun }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Valor de construcción común:</strong> {{ $folioReal->predio->valor_construccion_comun }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Valor total de terreno:</strong> {{ $folioReal->predio->valor_total_terreno }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Valor total de construcción:</strong> {{ $folioReal->predio->valor_total_construccion }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Valor catastral:</strong> {{ $folioReal->predio->valor_catastral }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Monto de transacción:</strong> {{ $folioReal->predio->monto_transaccion }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Divisa:</strong> {{ $folioReal->predio->divisa }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Tipo de vialidad:</strong> {{ $folioReal->predio->tipo_vialidad }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Tipo de asentamiento:</strong> {{ $folioReal->predio->tipo_asentamiento }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Nombre de la vialidad:</strong> {{ $folioReal->predio->nombre_vialidad }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Nombre del asentamiento:</strong> {{ $folioReal->predio->nombre_asentamiento }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Número exterior:</strong> {{ $folioReal->predio->numero_exterior }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Número exterior 2:</strong> {{ $folioReal->predio->numero_exterior_2 }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Número adicional:</strong> {{ $folioReal->predio->numero_adicional }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Número adicional 2:</strong> {{ $folioReal->predio->numero_adicional_2 }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Número interior:</strong> {{ $folioReal->predio->numero_interior }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Lote:</strong> {{ $folioReal->predio->lote }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Manzana:</strong> {{ $folioReal->predio->manzana }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Código postal:</strong> {{ $folioReal->predio->codigo_postal }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Lote de fraccionador:</strong> {{ $folioReal->predio->lote_fraccionador }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Manzana de fraccionador:</strong> {{ $folioReal->predio->manzana_fraccionador }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Etapa de fraccionador:</strong> {{ $folioReal->predio->etapa_fraccionador }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Nombre del edificio:</strong> {{ $folioReal->predio->nombre_edificio }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Clave del edificio:</strong> {{ $folioReal->predio->clave_edificio }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Departamento:</strong> {{ $folioReal->predio->departamento_edificio }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Entre vialidades:</strong> {{ $folioReal->predio->entre_vialidades }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Nombre del predio:</strong> {{ $folioReal->predio->nombre_predio }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Estado:</strong> {{ $folioReal->predio->estado }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Municipio:</strong> {{ $folioReal->predio->municipio }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Ciudad:</strong> {{ $folioReal->predio->ciudad }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Localidad:</strong> {{ $folioReal->predio->localidad }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Poblado:</strong> {{ $folioReal->predio->poblado }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Ejido:</strong> {{ $folioReal->predio->ejido }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Parcela:</strong> {{ $folioReal->predio->parcela }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Solar:</strong> {{ $folioReal->predio->solar }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Zona:</strong> {{ $folioReal->predio->zona_ubicacion }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Uso de suelo:</strong> {{ $folioReal->predio->uso_suelo }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>XUTM:</strong> {{ $folioReal->predio->xutm }}</p>

                        <p><strong>YUTM:</strong> {{ $folioReal->predio->yutm }}</p>

                        <p><strong>ZUTM:</strong> {{ $folioReal->predio->zutm }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Lon:</strong> {{ $folioReal->predio->lon }}</p>

                        <p><strong>Lat:</strong> {{ $folioReal->predio->lat }}</p>

                    </div>

                </div>

                <x-h4
                    @click="activeTab = activeTab == 2 ? 0 : 2"
                    class="cursor-pointer">
                    Colindancias
                </x-h4>

                <div class="my-5 text-sm space-y-3 overflow-auto tab-panel"  :class="{ 'active': activeTab === 2 }" x-show.transition.in.opacity.duration.800="activeTab === 2"  wire:key="tab-2">

                    <table class="w-full">

                        <thead class="border-b border-gray-300 ">

                            <tr class="text-sm text-gray-500 text-left traling-wider whitespace-nowrap">

                                <th class="px-2">Viento</th>
                                <th class="px-2">Longitud</th>
                                <th class="px-2">Descripcion</th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-gray-200">

                            @foreach ($folioReal->predio->colindancias as $colindancia)

                                <tr class="text-gray-500 text-sm leading-relaxed">
                                    <td class=" px-2 w-min ">{{ $colindancia->viento }}</td>
                                    <td class=" px-2 w-min ">{{ $colindancia->longitud }}</td>
                                    <td class=" px-2 w-full ">{{ $colindancia->descripcion }}</td>
                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                <x-h4
                    @click="activeTab = activeTab == 3 ? 0 : 3"
                    class="cursor-pointer">
                    Propietarios
                </x-h4>

                <div class="my-5 text-sm space-y-3 overflow-auto tab-panel"  :class="{ 'active': activeTab === 3 }" x-show.transition.in.opacity.duration.800="activeTab === 3"  wire:key="tab-3">

                    <table class="w-full">

                        <thead class="border-b border-gray-300 ">

                            <tr class="text-sm text-gray-500 text-left traling-wider whitespace-nowrap">

                                <th class="px-2">Nombre / Razón social</th>
                                <th class="px-2">% Porpiedad</th>
                                <th class="px-2">% Nuda</th>
                                <th class="px-2">% Usufructo</th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-gray-200">

                            @foreach ($folioReal->predio->propietarios()->sortBy('persona.nombre') as $propietario)

                                <tr class="text-gray-500 text-sm leading-relaxed">
                                    <td class=" px-2 w-full">{{ $propietario->persona->nombre }} {{ $propietario->persona->ap_paterno }} {{ $propietario->persona->ap_materno }} {{ $propietario->persona->razon_social }}</td>
                                    <td class=" px-2 w-min">{{ $propietario->porcentaje_propiedad ?? '0' }}</td>
                                    <td class=" px-2 w-min">{{ $propietario->porcentaje_nuda ?? '0' }}</td>
                                    <td class=" px-2 w-min">{{ $propietario->porcentaje_usufructo ?? '0' }}</td>
                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                @if($folioReal->predio->escritura)

                    <x-h4
                        @click="activeTab = activeTab == 4 ? 0 : 4"
                        class="cursor-pointer">
                        Escritura
                    </x-h4>

                    <div class="my-5 text-sm space-y-3 tab-panel"  :class="{ 'active': activeTab === 4 }" x-show.transition.in.opacity.duration.800="activeTab === 4"  wire:key="tab-4">

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Número:</strong> {{ $folioReal->predio->escritura->numero }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Fecha de inscripción:</strong> {{ $folioReal->predio->escritura->fecha_inscripcion }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Fecha de escritura:</strong> {{ $folioReal->predio->escritura->fecha_escritura }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Número de hojas:</strong> {{ $folioReal->predio->escritura->numero_hojas }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Número de paginas:</strong> {{ $folioReal->predio->escritura->numero_paginas }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Notaria:</strong> {{ $folioReal->predio->escritura->notaria }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Nombre del notario:</strong> {{ $folioReal->predio->escritura->nombre_notario }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Estado del notario:</strong> {{ $folioReal->predio->escritura->estado_notario }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Comentario:</strong> {{ $folioReal->predio->escritura->comentario }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Acto contenido:</strong> {{ $folioReal->predio->escritura->acto_contenido_antecedente }}</p>

                        </div>

                    </div>

                @endif

            </div>

        </div>

        <div class="bg-white p-4 shadow-xl rounded-lg col-span-4">

            <x-h4>Movimientos registrales</x-h4>

            <ul drag-root class="text-sm space-y-3 rounded-md" wire:loading.class.delay.longest="opacity-50">

                @foreach ($folioReal->movimientosRegistrales->sortBy('folio') as $movimiento)

                    <li
                        drag-item
                        draggable="true"
                        wire:click="verMovimientoRegistral({{ $movimiento->id }})"
                        wire:loading.attr="disabled"
                        wire:target="verMovimientoRegistral({{ $movimiento->id }})"
                        class="rounded-lg bg-gray-100 p-2 flex gap-4 items-center cursor-pointer"
                        wire:key="{{ $movimiento->id }}">

                        Movimiento {{ $movimiento->folio }} ({{ ucfirst($movimiento->estado) }}): {{ $movimiento->servicio_nombre }}

                    </li>

                @endforeach

                </ul>

        </div>

        <div class="bg-white p-4 shadow-xl rounded-lg col-span-4" x-data="{ activeTab: 1 }" class="tab-wrapper">

            @if($movimiento_registral)

                <x-h4
                    @click="activeTab =  activeTab == 1 ? 0 : 1"
                    class="cursor-pointer">
                    Descripción del movimiento registral
                </x-h4>

                <div class="mt-5 text-sm space-y-3 tab-panel" :class="{ 'active': activeTab === 1 }" x-show.transition.in.opacity.duration.800="activeTab === 1"  wire:key="tab-1">

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Folio:</strong> {{ $movimiento_registral->folio }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Estado:</strong> {{ ucfirst($movimiento_registral->estado) }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Trámite:</strong> {{ $movimiento_registral->año }}-{{ $movimiento_registral->tramite }}-{{ $movimiento_registral->usuario }}</p>

                        @if($movimiento_registral->usuario_tramites_linea_id) Trámite en línea @endif

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Servicio:</strong> {{ $movimiento_registral->servicio_nombre }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Monto:</strong> ${{ number_format($movimiento_registral->monto, 2) }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Fecha de prelación:</strong> {{ $movimiento_registral->fecha_prelacion }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Fecha de entrega:</strong> {{ $movimiento_registral->fecha_entrega }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Fecha de pago:</strong> {{ $movimiento_registral->fecha_pago }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Tipo de servicio:</strong> {{ $movimiento_registral->tipo_servicio }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Solicitante:</strong> {{ $movimiento_registral->solicitante }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Tomo:</strong> {{ $movimiento_registral->tomo }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Registro:</strong> {{ $movimiento_registral->registro }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Número de propiedad:</strong> {{ $movimiento_registral->numero_propiedad }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Distrito:</strong> {{ $movimiento_registral->distrito }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Sección:</strong> {{ $movimiento_registral->seccion }}</p>

                    </div>

                    @if($movimiento_registral->tomo_gravamen)

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Tomo de gravamen:</strong> {{ $movimiento_registral->tomo_gravamen }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Registro de gravamen:</strong> {{ $movimiento_registral->registro_gravamen }}</p>

                        </div>

                    @endif

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Tipo de documento:</strong> {{ $movimiento_registral->tipo_documento }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Número de documento:</strong> {{ $movimiento_registral->numero_documento }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Autoridad a cargo:</strong> {{ $movimiento_registral->autoridad_cargo }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Nombre de la autoridad a cargo:</strong> {{ $movimiento_registral->autoridad_nombre }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Número de la autoridad a cargo:</strong> {{ $movimiento_registral->autoridad_numero }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Fecha de emisión:</strong> {{ $movimiento_registral->fecha_emision }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Fecha de inscripción:</strong> {{ $movimiento_registral->fecha_inscripcion }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Procedencia:</strong> {{ $movimiento_registral->procedencia }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Número de oficio:</strong> {{ $movimiento_registral->numero_oficio }}</p>

                    </div>

                    <div class="rounded-lg bg-gray-100 py-1 px-2">

                        <p><strong>Usuario asignado:</strong> {{ $movimiento_registral->asignadoA?->name }}</p>

                    </div>

                </div>

                @if ($movimiento_registral->cancelacion)

                    <x-h4
                        @click="activeTab =  activeTab == 2 ? 0 : 2"
                        class="cursor-pointer">
                        Cancelación
                    </x-h4>

                    <div class="my-5 text-sm space-y-3 tab-panel" :class="{ 'active': activeTab === 2 }" x-show.transition.in.opacity.duration.800="activeTab === 2"  wire:key="tab-2">

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Acto contenido:</strong> {{ $movimiento_registral->cancelacion->acto_contenido }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Estado:</strong> {{ $movimiento_registral->cancelacion->estado }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Tipo:</strong> {{ $movimiento_registral->cancelacion->tipo }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Fecha de inscripción:</strong> {{ $movimiento_registral->cancelacion->fecha_inscripcion }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Observaciones:</strong> {{ $movimiento_registral->cancelacion->observaciones }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Folio del movimiento registral cancelado:</strong> {{ $movimiento_registral->cancelacion->gravamenCancelado?->folio }}</p>

                        </div>

                    </div>

                @elseif ($movimiento_registral->certificacion)

                    <x-h4
                        @click="activeTab =  activeTab == 2 ? 0 : 2"
                        class="cursor-pointer">
                        Certificación
                    </x-h4>

                    <div class="my-5 text-sm space-y-3 tab-panel" :class="{ 'active': activeTab === 2 }" x-show.transition.in.opacity.duration.800="activeTab === 2"  wire:key="tab-2">

                        @if(in_array($movimiento_registral->certificacion->servicio, ['DL13', 'DL14']))

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Número de paginas:</strong> {{ $movimiento_registral->certificacion->numero_paginas }}</p>

                            </div>

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Folio de carpeta:</strong> {{ $movimiento_registral->certificacion->folio_carpeta_copias }}</p>

                            </div>

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Reimpreso en:</strong> {{ $movimiento_registral->certificacion->reimpreso_en }}</p>

                            </div>

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Observaciones:</strong> {{ $movimiento_registral->certificacion->observaciones }}</p>

                            </div>

                        @else

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Observaciones:</strong> {{ $movimiento_registral->certificacion->observaciones_certificado }}</p>

                            </div>

                        @endif

                    </div>

                @elseif ($movimiento_registral->gravamen)

                    <x-h4
                        @click="activeTab =  activeTab == 2 ? 0 : 2"
                        class="cursor-pointer">
                        Gravamen
                    </x-h4>

                    <div class="my-5 text-sm space-y-3 tab-panel" :class="{ 'active': activeTab === 2 }" x-show.transition.in.opacity.duration.800="activeTab === 2"  wire:key="tab-2">

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Acto contenido:</strong> {{ $movimiento_registral->gravamen->acto_contenido }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Estado:</strong> {{ $movimiento_registral->gravamen->estado }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Expediente:</strong> {{ $movimiento_registral->gravamen->expediente }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Tipo:</strong> {{ $movimiento_registral->gravamen->tipo }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Valor del gravamen:</strong> {{ $movimiento_registral->gravamen->valor_gravamen }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Divisa:</strong> {{ $movimiento_registral->gravamen->divisa }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Fecha de inscripción:</strong> {{ $movimiento_registral->gravamen->fecha_inscripcion }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Obervaciones:</strong> {{ $movimiento_registral->gravamen->observaciones }}</p>

                        </div>

                        <div class="overflow-auto">

                            <table class="w-full">

                                <thead class="border-b border-gray-300 ">

                                    <tr class="text-sm text-gray-500 text-left traling-wider whitespace-nowrap">

                                        <th class="px-2">Nombre / Razón social (Acreedor)</th>
                                        <th class="px-2">% Porpiedad</th>
                                        <th class="px-2">% Nuda</th>
                                        <th class="px-2">% Usufructo</th>

                                    </tr>

                                </thead>

                                <tbody class="divide-y divide-gray-200">

                                    @foreach ($movimiento_registral->gravamen->acreedores() as $acreedor)

                                        <tr class="text-gray-500 text-sm leading-relaxed">
                                            <td class=" px-2 w-full">{{ $acreedor->persona->nombre }} {{ $acreedor->persona->ap_paterno }} {{ $acreedor->persona->ap_materno }} {{ $acreedor->persona->razon_social }}</td>
                                            <td class=" px-2 w-min">{{ $acreedor->porcentaje_propiedad ?? '0' }}</td>
                                            <td class=" px-2 w-min">{{ $acreedor->porcentaje_nuda ?? '0' }}</td>
                                            <td class=" px-2 w-min">{{ $acreedor->porcentaje_usufructo ?? '0' }}</td>
                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                        <div class="overflow-auto">

                            <table class="w-full">

                                <thead class="border-b border-gray-300 ">

                                    <tr class="text-sm text-gray-500 text-left traling-wider whitespace-nowrap">

                                        <th class="px-2">Nombre / Razón social (Deudor)</th>
                                        <th class="px-2">% Porpiedad</th>
                                        <th class="px-2">% Nuda</th>
                                        <th class="px-2">% Usufructo</th>

                                    </tr>

                                </thead>

                                <tbody class="divide-y divide-gray-200">

                                    @foreach ($movimiento_registral->gravamen->deudores() as $deudor)

                                        <tr class="text-gray-500 text-sm leading-relaxed">
                                            <td class=" px-2 w-full">{{ $deudor->persona->nombre }} {{ $deudor->persona->ap_paterno }} {{ $deudor->persona->ap_materno }} {{ $deudor->persona->razon_social }}</td>
                                            <td class=" px-2 w-min">{{ $deudor->porcentaje_propiedad ?? '0' }}</td>
                                            <td class=" px-2 w-min">{{ $deudor->porcentaje_nuda ?? '0' }}</td>
                                            <td class=" px-2 w-min">{{ $deudor->porcentaje_usufructo ?? '0' }}</td>
                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                @elseif ($movimiento_registral->inscripcionPropiedad)

                    <x-h4
                        @click="activeTab =  activeTab == 2 ? 0 : 2"
                        class="cursor-pointer">
                        Inscripción de propiedad
                    </x-h4>

                    <div class="my-5 text-sm space-y-3 tab-panel" :class="{ 'active': activeTab === 2 }" x-show.transition.in.opacity.duration.800="activeTab === 2"  wire:key="tab-2">

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Acto contenido:</strong> {{ $movimiento_registral->inscripcionPropiedad->acto_contenido }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Descripción del acto:</strong> {{ $movimiento_registral->inscripcionPropiedad->descripcion_acto }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Número de inmuebles:</strong> {{ $movimiento_registral->inscripcionPropiedad->numero_inmuebles }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Valor de la propiedad:</strong> {{ $movimiento_registral->inscripcionPropiedad->valor_propiedad }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Número de la propiedad:</strong> {{ $movimiento_registral->inscripcionPropiedad->numero_propiedad }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Fecha de inscripción:</strong> {{ $movimiento_registral->inscripcionPropiedad->fecha_inscripcion }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Superficie de terreno:</strong> {{ $movimiento_registral->inscripcionPropiedad->superficie_terreno }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Superficie de construcción:</strong> {{ $movimiento_registral->inscripcionPropiedad->superficie_construccion }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Superficie judicial:</strong> {{ $movimiento_registral->inscripcionPropiedad->superficie_judicial }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Superficie notarial:</strong> {{ $movimiento_registral->inscripcionPropiedad->superficie_notarial }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Área común de terreno:</strong> {{ $movimiento_registral->inscripcionPropiedad->area_comun_terreno }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Área común de construcción:</strong> {{ $movimiento_registral->inscripcionPropiedad->area_comun_construccion }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Valor de terreno común:</strong> ${{ number_format($movimiento_registral->inscripcionPropiedad->valor_terreno_comun, 2) }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Valor de construcción común:</strong> ${{ number_format($movimiento_registral->inscripcionPropiedad->valor_construccion_comun, 2) }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Valor total de terreno:</strong> ${{ number_format($movimiento_registral->inscripcionPropiedad->valor_total_terreno, 2) }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Valor total de construcción:</strong> ${{ number_format($movimiento_registral->inscripcionPropiedad->valor_total_construccion, 2) }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Valor catastral:</strong> ${{ number_format($movimiento_registral->inscripcionPropiedad->valor_catastral, 2) }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Monto de transacción:</strong> ${{ number_format($movimiento_registral->inscripcionPropiedad->monto_transaccion, 2) }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Divisa:</strong> {{ $movimiento_registral->inscripcionPropiedad->divisa }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Tipo de vialidad:</strong> {{ $movimiento_registral->inscripcionPropiedad->tipo_vialidad }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Tipo de asentamiento:</strong> {{ $movimiento_registral->inscripcionPropiedad->tipo_asentamiento }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Nombre de la vialidad:</strong> {{ $movimiento_registral->inscripcionPropiedad->nombre_vialidad }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Nombre del asentamiento:</strong> {{ $movimiento_registral->inscripcionPropiedad->nombre_asentamiento }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Número exterior:</strong> {{ $movimiento_registral->inscripcionPropiedad->numero_exterior }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Número exterior 2:</strong> {{ $movimiento_registral->inscripcionPropiedad->numero_exterior_2 }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Número adicional:</strong> {{ $movimiento_registral->inscripcionPropiedad->numero_adicional }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Número adicional 2:</strong> {{ $movimiento_registral->inscripcionPropiedad->numero_adicional_2 }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Número interior:</strong> {{ $movimiento_registral->inscripcionPropiedad->numero_interior }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Lote:</strong> {{ $movimiento_registral->inscripcionPropiedad->lote }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Manzana:</strong> {{ $movimiento_registral->inscripcionPropiedad->manzana }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Código postal:</strong> {{ $movimiento_registral->inscripcionPropiedad->codigo_postal }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Lote de fraccionador:</strong> {{ $movimiento_registral->inscripcionPropiedad->lote_fraccionador }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Manzana de fraccionador:</strong> {{ $movimiento_registral->inscripcionPropiedad->manzana_fraccionador }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Etapa de fraccionador:</strong> {{ $movimiento_registral->inscripcionPropiedad->etapa_fraccionador }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Nombre del edificio:</strong> {{ $movimiento_registral->inscripcionPropiedad->nombre_edificio }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Clave del edificio:</strong> {{ $movimiento_registral->inscripcionPropiedad->clave_edificio }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Departamento:</strong> {{ $movimiento_registral->inscripcionPropiedad->departamento_edificio }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Entre vialidades:</strong> {{ $movimiento_registral->inscripcionPropiedad->entre_vialidades }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Nombre del predio:</strong> {{ $movimiento_registral->inscripcionPropiedad->nombre_predio }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Estado:</strong> {{ $movimiento_registral->inscripcionPropiedad->estado }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Municipio:</strong> {{ $movimiento_registral->inscripcionPropiedad->municipio }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Ciudad:</strong> {{ $movimiento_registral->inscripcionPropiedad->ciudad }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Localidad:</strong> {{ $movimiento_registral->inscripcionPropiedad->localidad }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Poblado:</strong> {{ $movimiento_registral->inscripcionPropiedad->poblado }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Ejido:</strong> {{ $movimiento_registral->inscripcionPropiedad->ejido }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Parcela:</strong> {{ $movimiento_registral->inscripcionPropiedad->parcela }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Solar:</strong> {{ $movimiento_registral->inscripcionPropiedad->solar }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Zona:</strong> {{ $movimiento_registral->inscripcionPropiedad->zona_ubicacion }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Descripción:</strong> {{ $movimiento_registral->inscripcionPropiedad->descripcion }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Observaciones:</strong> {{ $movimiento_registral->inscripcionPropiedad->observaciones }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Partes iguales:</strong> {{ $movimiento_registral->inscripcionPropiedad->partes_iguales ? 'Si' : 'No' }}</p>

                        </div>

                        <div class="overflow-auto">

                            <table class="w-full">

                                <thead class="border-b border-gray-300 ">

                                    <tr class="text-sm text-gray-500 text-left traling-wider whitespace-nowrap">

                                        <th class="px-2">Nombre / Razón social (Propietario)</th>
                                        <th class="px-2">% Porpiedad</th>
                                        <th class="px-2">% Nuda</th>
                                        <th class="px-2">% Usufructo</th>

                                    </tr>

                                </thead>

                                <tbody class="divide-y divide-gray-200">

                                    @foreach ($movimiento_registral->inscripcionPropiedad->propietarios() as $propietario)

                                        <tr class="text-gray-500 text-sm leading-relaxed">
                                            <td class=" px-2 w-full">{{ $propietario->persona->nombre }} {{ $propietario->persona->ap_paterno }} {{ $propietario->persona->ap_materno }} {{ $propietario->persona->razon_social }}</td>
                                            <td class=" px-2 w-min">{{ $propietario->porcentaje_propiedad ?? '0' }}</td>
                                            <td class=" px-2 w-min">{{ $propietario->porcentaje_nuda ?? '0' }}</td>
                                            <td class=" px-2 w-min">{{ $propietario->porcentaje_usufructo ?? '0' }}</td>
                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                        <div class="overflow-auto">

                            <table class="w-full">

                                <thead class="border-b border-gray-300 ">

                                    <tr class="text-sm text-gray-500 text-left traling-wider whitespace-nowrap">

                                        <th class="px-2">Nombre / Razón social (Transmitente)</th>
                                        <th class="px-2">% Porpiedad</th>
                                        <th class="px-2">% Nuda</th>
                                        <th class="px-2">% Usufructo</th>

                                    </tr>

                                </thead>

                                <tbody class="divide-y divide-gray-200">

                                    @foreach ($movimiento_registral->inscripcionPropiedad->transmitentes() as $transmitente)

                                        <tr class="text-gray-500 text-sm leading-relaxed">
                                            <td class=" px-2 w-full">{{ $transmitente->persona->nombre }} {{ $transmitente->persona->ap_paterno }} {{ $transmitente->persona->ap_materno }} {{ $transmitente->persona->razon_social }}</td>
                                            <td class=" px-2 w-min">{{ $transmitente->porcentaje_propiedad ?? '0' }}</td>
                                            <td class=" px-2 w-min">{{ $transmitente->porcentaje_nuda ?? '0' }}</td>
                                            <td class=" px-2 w-min">{{ $transmitente->porcentaje_usufructo ?? '0' }}</td>
                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                        <div class="overflow-auto">

                            <table class="w-full">

                                <thead class="border-b border-gray-300 ">

                                    <tr class="text-sm text-gray-500 text-left traling-wider whitespace-nowrap">

                                        <th class="px-2">Nombre / Razón social (Transmitente)</th>
                                        <th class="px-2">% Porpiedad</th>
                                        <th class="px-2">% Nuda</th>
                                        <th class="px-2">% Usufructo</th>

                                    </tr>

                                </thead>

                                <tbody class="divide-y divide-gray-200">

                                    @foreach ($movimiento_registral->inscripcionPropiedad->representantes() as $representante)

                                        <tr class="text-gray-500 text-sm leading-relaxed">
                                            <td class=" px-2 w-full">{{ $representante->persona->nombre }} {{ $representante->persona->ap_paterno }} {{ $representante->persona->ap_materno }} {{ $representante->persona->razon_social }}</td>
                                            <td class=" px-2 w-min">{{ $representante->porcentaje_propiedad ?? '0' }}</td>
                                            <td class=" px-2 w-min">{{ $representante->porcentaje_nuda ?? '0' }}</td>
                                            <td class=" px-2 w-min">{{ $representante->porcentaje_usufructo ?? '0' }}</td>
                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                @elseif ($movimiento_registral->sentencia)

                    <x-h4
                        @click="activeTab =  activeTab == 2 ? 0 : 2"
                        class="cursor-pointer">
                        Sentencia
                    </x-h4>

                    <div class="my-5 text-sm space-y-3 tab-panel" :class="{ 'active': activeTab === 2 }" x-show.transition.in.opacity.duration.800="activeTab === 2"  wire:key="tab-2">

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Acto contenido:</strong> {{ $movimiento_registral->sentencia->acto_contenido }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Descripción del acto:</strong> {{ $movimiento_registral->sentencia->descripcion }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Tipo:</strong> {{ $movimiento_registral->sentencia->tipo }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Estado:</strong> {{ $movimiento_registral->sentencia->estado }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Hojas:</strong> {{ $movimiento_registral->sentencia->hojas }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Expediente:</strong> {{ $movimiento_registral->sentencia->expediente }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Fecha de inscripción:</strong> {{ $movimiento_registral->sentencia->fecha_inscripcion }}</p>

                        </div>

                    </div>

                @elseif ($movimiento_registral->vario)

                    <x-h4
                        @click="activeTab =  activeTab == 2 ? 0 : 2"
                        class="cursor-pointer">
                        Varios
                    </x-h4>

                    <div class="my-5 text-sm space-y-3 tab-panel" :class="{ 'active': activeTab === 2 }" x-show.transition.in.opacity.duration.800="activeTab === 2"  wire:key="tab-2">

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Acto contenido:</strong> {{ $movimiento_registral->vario->acto_contenido }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Descripción del acto:</strong> {{ $movimiento_registral->vario->descripcion }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Estado:</strong> {{ $movimiento_registral->vario->estado }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Fecha de inscripción:</strong> {{ $movimiento_registral->vario->fecha_inscripcion }}</p>

                        </div>

                    </div>

                @endif

            @endif

        </div>

    </div>

    @push('scripts')

        <script>

            let root = document.querySelector('[drag-root]')

            root.querySelectorAll('[drag-item]').forEach(el => {

                el.addEventListener('dragstart', e => {

                    e.target.setAttribute('dragging', true);

                })

                el.addEventListener('drop', e => {

                    e.target.closest('li').classList.remove('bg-gray-300')

                    let dragging = root.querySelector('[dragging]')

                    Livewire.first().reaordenarMovimientos(dragging.getAttribute('wire:key'), e.target.getAttribute('wire:key'))

                })

                el.addEventListener('dragenter', e => {

                    e.target.closest('li').classList.add('bg-gray-300')

                    e.preventDefault()

                })

                el.addEventListener('dragover', e => e.preventDefault())

                el.addEventListener('dragleave', e => {

                    e.target.closest('li').classList.remove('bg-gray-300')

                })

                el.addEventListener('dragend', e => {

                    e.target.removeAttribute('dragging');

                })

            })

        </script>

    @endpush

</div>
