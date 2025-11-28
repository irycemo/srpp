<div class="firma no-break">

    <p class="atte" style="margin-bottom: 50px;">
        <strong>A T E N T A M E N T E</strong>
    </p>

    @if(!$firma_electronica)

        @if($datos_control->distrito == '02 Uruapan' )

            <p class="borde">Lic. SANDRO MEDINA MORALES </p>
            <p style="margin:0;">coordinador regional 4 purepecha</p>

        @elseif (isset($datos_control->nombre_regional))

            <p class="borde" style="margin:0;">{{ $datos_control->titular_regional }}</p>
            <p style="margin:0;">{{ $datos_control->nombre_regional }}</p>

        @else

            <p class="borde" style="margin:0;">{{ $datos_control->director }}</p>
            <p style="margin:0;">Director del registro público de la propiedad</p>

        @endif

        <div style="margin-top: 50px;">

            <table class="tabla" >
                <tbody sty>
                    <tr>
                        <td style="padding-right: 40px; text-align:center; width: 50%; vertical-align: bottom; white-space: nowrap;">

                            <p class="borde">{{ $datos_control->registrado_por }}</p>
                            <p style="margin: 0">REGISTRADOR</p>

                        </td>

                        @if($datos_control->distrito != '02 Uruapan' && !isset($datos_control->nombre_regional))

                            <td style="padding-right: 40px; text-align:center; width: 50%; vertical-align: bottom; white-space: nowrap;">

                                <p class="borde">{{ $datos_control->jefe_departamento }}</p>
                                <p style="margin: 0">JEFE DE Departamento de Registro de Inscripciones	</p>
                            </td>

                        @endif

                    </tr>
                </tbody>
            </table>

        </div>

    @else

        <p style="margin:0;">{{ $datos_control->director }}</p>
        <p style="margin:0;">Director del registro público de la propiedad</p>
        <p style="text-align: center">Firma Electrónica:</p>
        <p class="parrafo" style="overflow-wrap: break-word;">{{ $firma_electronica }}</p>

        <p >{{ $datos_control->registrado_por }}</p>
        <p class="borde" style="margin: 0">REGISTRADOR</p>

        <p >{{ $datos_control->jefe_departamento }}</p>
        <p class="borde" style="margin: 0">JEFE DE Departamento de Registro de Inscripciones</p>

    @endif

</div>