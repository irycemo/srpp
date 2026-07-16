<p class="separador">documento de entrada</p>

<p class="parrafo">

    @if ($datos_control->documento_entrada->tipo_documento)
        <strong>tipo de documento:</strong> {{ $datos_control->documento_entrada->tipo_documento }};
    @endif

    @if ($datos_control->documento_entrada->numero_documento)
        <strong>número de documento:</strong> {{ $datos_control->documento_entrada->numero_documento }};
    @endif

    @if ($datos_control->documento_entrada->fecha_emision)
        <strong>Fecha de emisión:</strong> {{ $datos_control->documento_entrada->fecha_emision }};
    @endif

    @if ($datos_control->documento_entrada->autoridad_cargo)
        <strong>cargo de la autoridad:</strong> {{ $datos_control->documento_entrada->autoridad_cargo }};
    @endif

    @if ($datos_control->documento_entrada->autoridad_nombre)
        <strong>Nombre de la autoridad:</strong> {{ $datos_control->documento_entrada->autoridad_nombre }};
    @endif

    @if ($datos_control->documento_entrada->autoridad_numero)
        <strong>Número de la autoridad:</strong> {{ $datos_control->documento_entrada->autoridad_numero }};
    @endif

</p>
