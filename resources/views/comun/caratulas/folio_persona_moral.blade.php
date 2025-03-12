<p class="separador">datos de la persona moral</p>

<p class="parrafo">

    <strong>Denominación o razón social:</strong> {{ $folioReal->denominacion }}

</p>

<p class="parrafo">

    <strong>Fecha de inscripción:</strong> {{ $folioReal->fecha_inscripcion }}; <strong>Fecha de protocolización:</strong> {{ $folioReal->fecha_constitucion }}; <strong>Capital:</strong> ${{ number_format($folioReal->capital, 2) }}; @if($folioReal->duracion) <strong>Duración:</strong> {{ $folioReal->duracion }} años; @endif <strong>Tipo:</strong> {{ str_replace('_', ' ', $folioReal->tipo) }} @if($folioReal->tomo); <strong>Tomo:</strong> {{ $folioReal->tomo }}; <strong>Registro:</strong> {{ $folioReal->registro }} @endif

</p>

<p class="separador">objeto de la persona moral</p>

<p class="parrafo">{{ $folioReal->objeto }}</p>

<p class="separador">domicilio</p>

<p class="parrafo">{{ $folioReal->domicilio }}</p>

@if($folioReal->observaciones)

    <p class="separador">observaciones</p>

    <p class="parrafo">{{ $folioReal->observaciones }}</p>

@endif

<p class="separador">socios</p>

<table>

    <thead>

        <tr>
            <th style="padding-right: 10px;">Nombre / Razón social</th>
            <th style="padding-right: 10px;">tipo</th>
        </tr>

    </thead>

    <tbody>

        @foreach ($folioReal->participantes as $socio)

            <tr>
                <td style="padding-right: 40px;">
                    <p style="margin:0">{{ $socio->nombre }} {{ $socio->ap_paterno }} {{ $socio->ap_materno }} {{ $socio->razon_social }}</p>
                    @if($socio->multiple_nombre)
                        <p style="margin:0">({{ $socio->multiple_nombre }})</p>
                    @endif
                </td>
                <td style="padding-right: 40px;">
                    <p style="margin:0">{{ $socio->tipo_socio }}</p>
                </td>
            </tr>

        @endforeach

    </tbody>

</table>

