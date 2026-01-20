<div class="bg-white rounded-lg p-4 shadow-xl grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 text-sm mb-3">

    <span class="flex items-center justify-center text-lg text-gray-700 md:col-span-3 col-span-1 sm:col-span-2 lg:col-span-6">Ubicación del predio</span>

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <strong>Municipio</strong>

        <p>{{ $folioReal->predio->municipio }}</p>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <strong>Ciudad</strong>

        <p>{{ $folioReal->predio->ciudad }}</p>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <strong>Localidad</strong>

        <p>{{ $folioReal->predio->localidad }}</p>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <strong>Código postal</strong>

        <p>{{ $folioReal->predio->codigo_postal }}</p>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <strong>Tipo de asentamiento</strong>

        <p>{{ $folioReal->predio->tipo_asentamiento }}</p>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <strong>Nombre del asentamiento</strong>

        <p>{{ $folioReal->predio->nombre_asentamiento }}</p>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <strong>Tipo de vialidad</strong>

        <p>{{ $folioReal->predio->tipo_vialidad }}</p>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <strong>Nombre de la vialidad</strong>

        <p>{{ $folioReal->predio->nombre_vialidad }}</p>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <strong>Número exterior</strong>

        <p>{{ $folioReal->predio->numero_exterior }}</p>

    </div>

    @if($folioReal->predio->numero_interior)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Número interior</strong>

            <p>{{ $folioReal->predio->numero_interior }}</p>

        </div>

    @endif

    @if($folioReal->predio->nombre_edificio)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Edificio</strong>

            <p>{{ $folioReal->predio->nombre_edificio }}</p>

        </div>

    @endif

    @if($folioReal->predio->departamento_edificio)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Departamento</strong>

            <p>{{ $folioReal->predio->departamento_edificio }}</p>

        </div>

    @endif

    @if($folioReal->predio->lote)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Lote</strong>

            <p>{{ $folioReal->predio->lote }}</p>

        </div>

    @endif

    @if($folioReal->predio->manzana)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Manzana</strong>

            <p>{{ $folioReal->predio->manzana }}</p>

        </div>

    @endif

    @if($folioReal->predio->ejido)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Ejido</strong>

            <p>{{ $folioReal->predio->ejido }}</p>

        </div>

    @endif

    @if($folioReal->predio->parcela)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Parcela</strong>

            <p>{{ $folioReal->predio->parcela }}</p>

        </div>

    @endif

    @if($folioReal->predio->solar)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Solar</strong>

            <p>{{ $folioReal->predio->solar }}</p>

        </div>

    @endif

    @if($folioReal->predio->poblado)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Poblado</strong>

            <p>{{ $folioReal->predio->poblado }}</p>

        </div>

    @endif

    @if($folioReal->predio->numero_exterior_2)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Número exterior 2</strong>

            <p>{{ $folioReal->predio->numero_exterior_2 }}</p>

        </div>

    @endif

    @if($folioReal->predio->numero_adicional)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Número adicional</strong>

            <p>{{ $folioReal->predio->numero_adicional }}</p>

        </div>

    @endif

    @if($folioReal->predio->numero_adicional_2)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Número adicional 2</strong>

            <p>{{ $folioReal->predio->numero_adicional_2 }}</p>

        </div>

    @endif

    @if($folioReal->predio->lote_fraccionador)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Lote del fraccionador</strong>

            <p>{{ $folioReal->predio->lote_fraccionador }}</p>

        </div>

    @endif

    @if($folioReal->predio->manzana_fraccionador)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Manzana del fraccionador</strong>

            <p>{{ $folioReal->predio->manzana_fraccionador }}</p>

        </div>

    @endif

    @if($folioReal->predio->etapa_fraccionador)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Etapa del fraccionador</strong>

            <p>{{ $folioReal->predio->etapa_fraccionador }}</p>

        </div>

    @endif

    @if($folioReal->predio->clave_edificio)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Clave del edificio</strong>

            <p>{{ $folioReal->predio->clave_edificio }}</p>

        </div>

    @endif

    @if($folioReal->predio->observaciones)

        <div class="rounded-lg bg-gray-100 py-1 px-2 md:col-span-3 col-span-1 sm:col-span-2 lg:col-span-6">

            <strong>Observaciones</strong>

            <p>{{ $folioReal->predio->observaciones }}</p>

        </div>

    @endif

</div>
