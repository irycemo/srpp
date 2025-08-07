<p class="parrafo">
    A SOLICITUD DE:
    <strong>{{ $datos_control->solicitante }}</strong>
    se EXPiDe EL PRESENTE EN LA CIUDAD DE
    @if($datos_control->distrito == '02 Uruapan' )
        uruapan
    @elseif (isset($datos_control->nombre_regional))
        {{ $datos_control->ciudad_regional }}
    @else
        MORELIA
    @endif
    , MICHOACÁN, A LAS {{ $datos_control->elaborado_en }}.
</p>