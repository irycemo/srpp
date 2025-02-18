@if($certificacion->movimientoRegistral->folioReal)

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

                <strong class="capitalize">Superficie de terreno:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->superficie_terreno_formateada }} {{ $certificacion->movimientoRegistral->folioReal->predio->unidad_area }} <strong class="capitalize">Superficie de construcción:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->superficie_construccion_formateada }} {{ $certificacion->movimientoRegistral->folioReal->predio->unidad_area }} <strong class="capitalize">monto de la transacción:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->monto_transaccion }} {{ $certificacion->movimientoRegistral->folioReal->predio->divisa }};

                @if ($certificacion->movimientoRegistral->folioReal->predio->curt)
                    <strong class="capitalize">curt:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->curt }};
                @endif

                @if ($certificacion->movimientoRegistral->folioReal->predio->superficie_judicial)
                    <strong class="capitalize">superficie judicial:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->superficie_judicial_formateada }} {{ $certificacion->movimientoRegistral->folioReal->predio->unidad_area }};
                @endif

                @if ($certificacion->movimientoRegistral->folioReal->predio->superficie_notarial)
                    <strong class="capitalize">superficie notarial:</strong> {{ $certificacion->movimientoRegistral->folioReal->predio->superficie_notarial_formateada }} {{ $certificacion->movimientoRegistral->folioReal->predio->unidad_area }};
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

    <div class="bg-white rounded-lg p-4 shadow-lg w-full mb-5 md:col-span-3 col-span-1 sm:col-span-2 ">

        @include('comun.inscripciones.colindancias')

    </div>

@endif
