<p>
    A SOLICITUD DE:
    <strong>{{ $datos_control->solicitante }}</strong>
    se expide EL PRESENTE CERTIFICADO EN LA CIUDAD DE
    @if($folioReal->distrito == '02 Uruapan' )
        URUAPAN,
    @elseif (isset($datos_control->nombre_regional))
        {{ $datos_control->ciudad_regional }}
    @else
        MORELIA,
    @endif
        MICHOACÁN, A LAS {{ $datos_control->elaborado_en }}.
</p>