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
                    {{ $propietario->nombre }} {{ $propietario->ap_paterno }} {{ $propietario->ap_materno }} {{ $propietario->razon_social }}
                </td>
                <td style="padding-right: 40px;">
                    {{ $propietario->porcentaje_propiedad ?? '0.00' }} %
                </td>
                <td style="padding-right: 40px;">
                    {{ $propietario->porcentaje_nuda ?? '0.00' }} %
                </td>
                <td style="padding-right: 40px;">
                    {{ $propietario->porcentaje_usufructo ?? '0.00' }} %
                </td>
            </tr>

        @endforeach

    </tbody>

</table>
