<p class="separador">DESCRIPCIÓN DEL INMUEBLE</p>

<p class="parrafo">
    @if($folioReal->predio->cp_localidad)
        <strong>Cuenta predial:</strong> {{ $folioReal->predio->cp_localidad }}-{{ $folioReal->predio->cp_oficina }}-{{ $folioReal->predio->cp_tipo_predio }}-{{ $folioReal->predio->cp_registro }};
    @endif

    @if($folioReal->predio->cc_region_catastral)
        <strong>Clave catastral:</strong> {{ $folioReal->predio->cc_estado }}-{{ $folioReal->predio->cc_region_catastral }}-{{ $folioReal->predio->cc_municipio }}-{{ $folioReal->predio->cc_zona_catastral }}-{{ $folioReal->predio->cc_sector }}-{{ $folioReal->predio->cc_manzana }}-{{ $folioReal->predio->cc_predio }}-{{ $folioReal->predio->cc_edificio }}-{{ $folioReal->predio->cc_departamento }};
    @endif

    <strong>Superficie de terreno:</strong> {{ $superficie_terreno }} {{ $folioReal->predio->unidad_area }} <strong>Superficie de construcción:</strong> {{ $superficie_construccion }} {{ $folioReal->predio->unidad_area }} <strong>monto de la transacción:</strong> {{ $monto_transaccion }} {{ $folioReal->predio->divisa }};

    @if ($folioReal->predio->curt)
        <strong>curt:</strong> {{ $folioReal->predio->curt }};
    @endif

    @if ($folioReal->predio->superficie_judicial)
        <strong>superficie judicial:</strong> {{ $folioReal->predio->superficie_judicial }} {{ $folioReal->predio->unidad_area }};
    @endif

    @if ($folioReal->predio->superficie_notarial)
        <strong>superficie notarial:</strong> {{ $folioReal->predio->superficie_notarial }} {{ $folioReal->predio->unidad_area }};
    @endif

    @if ($folioReal->predio->area_comun_terreno)
        <strong>área de terreno común:</strong> {{ $folioReal->predio->area_comun_terreno }} {{ $folioReal->predio->unidad_area }};
    @endif

    @if ($folioReal->predio->area_comun_construccion)
        <strong>área de construcción común:</strong> {{ $folioReal->predio->area_comun_construccion }} {{ $folioReal->predio->unidad_area }};
    @endif

    @if ($folioReal->predio->valor_terreno_comun)
        <strong>valor de terreno común:</strong> {{ $folioReal->predio->valor_terreno_comun }} {{ $folioReal->predio->divisa }};
    @endif

    @if ($folioReal->predio->valor_construccion_comun)
        <strong>valor de construcción común:</strong> {{ $folioReal->predio->valor_construccion_comun }} {{ $folioReal->predio->divisa }};
    @endif

    @if ($folioReal->predio->valor_catastral)
        <strong>valor de construcción común:</strong> {{ $folioReal->predio->valor_catastral }} {{ $folioReal->predio->divisa }};
    @endif

</p>

<p class="parrafo">

    <strong>Descripción:</strong> {{ $folioReal->predio->descripcion }}.

</p>
