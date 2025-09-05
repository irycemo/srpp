@forelse ($predios as $predio)

    <div class="bg-white rounded-lg p-4 shadow-lg w-full  mx-auto mb-5">

        <p class="text-center"><strong>Ubicación del inmueble Folio Real ({{ $predio->folioReal->folio }})</strong></p>

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

@empty

@endforelse

@forelse($prediosOld as $predioOld)

    <div class="bg-white rounded-lg p-4 shadow-lg w-full  mx-auto mb-5">

        <p class="text-center"><strong>Descripción del inmueble</strong></p>

        <div class="text-gray-500 text-sm leading-relaxed lg:w-1/2 mx-auto mb-5">

            <p class=" text-justify">

                @if ($predioOld->distrito)
                    <strong class="capitalize">Distrito:</strong> {{ $predioOld->distrito }};
                @endif

                @if ($predioOld->tomo)
                    <strong class="capitalize">Tomo:</strong> {{ $predioOld->tomo }};
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

@empty

@endforelse
