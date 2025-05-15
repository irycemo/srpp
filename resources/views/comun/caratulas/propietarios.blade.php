<div class="no-break">

    <p class="separador">propietarios</p>

    <table>

        <thead>

            <tr>
                <th style="padding-right: 10px;">Nombre / Razón social</th>
                <th style="padding-right: 10px;">% de propiedad</th>
                <th style="padding-right: 10px;">% de nuda</th>
                <th style="padding-right: 10px;">% de usufructo</th>
            </tr>

        </thead>

        <tbody>

            @foreach ($predio->propietarios as $propietario)

                <tr>
                    <td style="padding-right: 40px;">
                        <p style="margin:0">{{ $propietario->nombre }} {{ $propietario->ap_paterno }} {{ $propietario->ap_materno }} {{ $propietario->razon_social }}</p>
                        @if($propietario->multiple_nombre)
                            <p style="margin:0">({{ $propietario->multiple_nombre }})</p>
                        @endif
                    </td>
                    <td style="padding-right: 40px;">
                        <p style="margin:0">
                            @if(isset($predio->partes_iguales))

                                @if($predio->partes_iguales)

                                    Partes iguales

                                @else

                                    {{ $propietario->porcentaje_propiedad ?? '0.00' }}%

                                @endif

                            @else

                                {{ $propietario->porcentaje_propiedad ?? '0.00' }}%

                            @endif
                        </p>
                    </td>
                    <td style="padding-right: 40px;">
                        <p style="margin:0">{{ $propietario->porcentaje_nuda ?? '0.00' }} %</p>
                    </td>
                    <td style="padding-right: 40px;">
                        <p style="margin:0">{{ $propietario->porcentaje_usufructo ?? '0.00' }} %</p>
                    </td>
                </tr>

            @endforeach

        </tbody>

    </table>

</div>
