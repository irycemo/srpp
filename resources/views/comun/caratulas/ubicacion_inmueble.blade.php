<p class="separador">UBICACIÓN DEL INMUEBLE</p>

<p class="parrafo">

    @if ($folioReal->predio->codigo_postal)
        <strong>CÓDIGO POSTAL:</strong> {{ $folioReal->predio->codigo_postal }};
    @endif

    @if ($folioReal->predio->tipo_asentamiento)
        <strong>TIPO DE ASENTAMIENTO:</strong> {{ $folioReal->predio->tipo_asentamiento }};
    @endif

    @if ($folioReal->predio->nombre_asentamiento)
        <strong>NOMBRE DEL ASENTAMIENTO:</strong> {{ $folioReal->predio->nombre_asentamiento }};
    @endif

    @if ($folioReal->predio->municipio)
        <strong>MUNICIPIO:</strong> {{ $folioReal->predio->municipio }};
    @endif

    @if ($folioReal->predio->ciudad)
        <strong>CIUDAD:</strong> {{ $folioReal->predio->ciudad }};
    @endif

    @if ($folioReal->predio->localidad)
        <strong>LOCALIDAD:</strong> {{ $folioReal->predio->localidad }};
    @endif

    @if ($folioReal->predio->tipo_vialidad)
        <strong>TIPO DE VIALIDAD:</strong> {{ $folioReal->predio->tipo_vialidad }};
    @endif

    @if ($folioReal->predio->nombre_vialidad)
        <strong>NOMBRE DE LA VIALIDAD:</strong> {{ $folioReal->predio->nombre_vialidad }};
    @endif

    @if ($folioReal->predio->numero_exterior)
        <strong>NÚMERO EXTERIOR:</strong> {{ $folioReal->predio->numero_exterior ?? 'SN' }};
    @endif

    @if ($folioReal->predio->numero_interior)
        <strong>NÚMERO INTERIOR:</strong> {{ $folioReal->predio->numero_interior ?? 'SN' }};
    @endif

    @if ($folioReal->predio->nombre_edificio)
        <strong>EDIFICIO:</strong> {{ $folioReal->predio->nombre_edificio }};
    @endif

    @if ($folioReal->predio->clave_edificio)
        <strong>clave del edificio:</strong> {{ $folioReal->predio->clave_edificio }};
    @endif

    @if ($folioReal->predio->departamento_edificio)
        <strong>DEPARTAMENTO:</strong> {{ $folioReal->predio->departamento_edificio }};
    @endif

    @if ($folioReal->predio->lote)
        <strong>LOTE:</strong> {{ $folioReal->predio->lote }};
    @endif

    @if ($folioReal->predio->manzana)
        <strong>MANZANA:</strong> {{ $folioReal->predio->manzana }};
    @endif

    @if ($folioReal->predio->ejido)
        <strong>ejido:</strong> {{ $folioReal->predio->ejido }};
    @endif

    @if ($folioReal->predio->parcela)
        <strong>parcela:</strong> {{ $folioReal->predio->parcela }};
    @endif

    @if ($folioReal->predio->solar)
        <strong>solar:</strong> {{ $folioReal->predio->solar }};
    @endif

    @if ($folioReal->predio->poblado)
        <strong>poblado:</strong> {{ $folioReal->predio->poblado }};
    @endif

    @if ($folioReal->predio->numero_exterior)
        <strong>número exterior:</strong> {{ $folioReal->predio->numero_exterior }};
    @endif

    @if ($folioReal->predio->numero_exterior_2)
        <strong>número exterior 2:</strong> {{ $folioReal->predio->numero_exterior_2 }};
    @endif

    @if ($folioReal->predio->numero_adicional)
        <strong>número adicional:</strong> {{ $folioReal->predio->numero_adicional }};
    @endif

    @if ($folioReal->predio->numero_adicional_2)
        <strong>número adicional 2:</strong> {{ $folioReal->predio->numero_adicional_2 }};
    @endif

    @if ($folioReal->predio->lote_fraccionador)
        <strong>lote del fraccionador:</strong> {{ $folioReal->predio->lote_fraccionador }};
    @endif

    @if ($folioReal->predio->manzana_fraccionador)
        <strong>manzana del fraccionador:</strong> {{ $folioReal->predio->manzana_fraccionador }};
    @endif

    @if ($folioReal->predio->etapa_fraccionador)
        <strong>etapa del fraccionador:</strong> {{ $folioReal->predio->etapa_fraccionador }};
    @endif

    @if ($folioReal->predio->observaciones)
        <strong>OBSERVACIONES:</strong> {{ $folioReal->predio->observaciones }}.
    @endif

</p>
