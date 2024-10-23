<p class="separador">colindancias</p>

<table>

    <thead>

        <tr>
            <th>Viento</th>
            <th>Longitud (mts.)</th>
            <th>Descripción</th>
        </tr>

    </thead>

    <tbody>

        @foreach ($predio->colindancias as $colindancia)

            <tr>
                <td style="padding-right: 40px;">
                    {{ $colindancia->viento }}
                </td>
                <td style="padding-right: 40px;">
                    {{ $colindancia->longitud }}
                </td>
                <td style="padding-right: 40px;">
                    {{ $colindancia->descripcion }}
                </td>
            </tr>

        @endforeach

    </tbody>

</table>
