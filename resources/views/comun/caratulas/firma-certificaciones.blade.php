<div class="firma no-break">

    <p class="atte">
        <strong>A T E N T A M E N T E</strong>
    </p>

    @if(!$firma_electronica)

        @if($folioReal->distrito == '02 Uruapan' )

            <p style="margin-top: 80px;"></p>
            <p class="borde">Lic. SANDRO MEDINA MORALES </p>
            <p style="margin:0;">COORDINADOR REGIONAL 4 PURHÉPECHA (URUAPAN)</p>

        @elseif (isset($datos_control->nombre_regional))

            <p class="borde" style="margin:0;">{{ $datos_control->titular_regional }}</p>
            <p style="margin:0;">{{ $datos_control->nombre_regional }}</p>

        @else

            <p style="margin-top: 80px;"></p>
            <p class="borde" style="margin:0;">{{ $director }}</p>
            <p style="margin:0;">Director del registro público de la propiedad</p>

        @endif

    @else

        <p style="margin:0;">{{ $director }}</p>
        <p style="margin:0;">Director del registro público de la propiedad</p>
        <p style="text-align: center">Firma Electrónica:</p>
        <p class="parrafo" style="overflow-wrap: break-word;">{{ $firma_electronica }}</p>

    @endif

</div>