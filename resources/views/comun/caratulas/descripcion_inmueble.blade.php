<p class="separador">DESCRIPCIÓN DEL INMUEBLE</p>

<p class="parrafo">

    @if($predio->cp_localidad)
        <strong>Cuenta predial:</strong> {{ $predio->cp_localidad }}-{{ $predio->cp_oficina }}-{{ $predio->cp_tipo_predio }}-{{ $predio->cp_registro }};
    @endif

    @if($predio->cc_region_catastral)
        <strong>Clave catastral:</strong> {{ $predio->cc_estado }}-{{ $predio->cc_region_catastral }}-{{ $predio->cc_municipio }}-{{ $predio->cc_zona_catastral }}-{{ $predio->cc_sector }}-{{ $predio->cc_manzana }}-{{ $predio->cc_predio }}-{{ $predio->cc_edificio }}-{{ $predio->cc_departamento }};
    @endif

    <strong>Superficie de terreno:</strong>  {{ $predio->superficie_terreno }}  {{ $predio->unidad_area }}

    @if ($predio->superficie_construccion)

        <strong>Superficie de construcción:</strong> {{ $predio->superficie_construccion }} Metros cuadrados

    @endif

    <strong>monto de la transacción:</strong> ${{ number_format($predio->monto_transaccion, 2) }} {{ $predio->divisa }};

    @if ($predio->curt)
        <strong>curt:</strong> {{ $predio->curt }};
    @endif

    @if ($predio->superficie_judicial)
        <strong>superficie judicial:</strong>  {{ $predio->superficie_judicial }}  {{ $predio->unidad_area }};
    @endif

    @if ($predio->superficie_notarial)
        <strong>superficie notarial:</strong> {{ $predio->superficie_notarial }}  {{ $predio->unidad_area }};
    @endif

    @if ($predio->area_comun_terreno)
        <strong>área de terreno común:</strong> {{ $predio->area_comun_terreno }} Metros cuadrados;
    @endif

    @if ($predio->area_comun_construccion)
        <strong>área de construcción común:</strong> {{ $predio->area_comun_construccion }} Metros cuadrados;
    @endif

    @if ($predio->valor_terreno_comun)
        <strong>valor de terreno común:</strong> {{ $predio->valor_terreno_comun }} {{ $predio->divisa }};
    @endif

    @if ($predio->valor_construccion_comun)
        <strong>valor de construcción común:</strong> {{ $predio->valor_construccion_comun }} {{ $predio->divisa }};
    @endif

    @if ($predio->valor_catastral)
        <strong>valor de construcción común:</strong> {{ $predio->valor_catastral }} {{ $predio->divisa }};
    @endif

</p>

<p class="parrafo">

    <strong>Descripción:</strong> {{ $predio->descripcion }}.

</p>
