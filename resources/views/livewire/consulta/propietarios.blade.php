<div class="mb-3">

    <div class="mb-3 bg-white rounded-lg p-3 shadow-lg">

        <span class="flex items-center justify-center text-lg text-gray-700 mb-5">Propietarios</span>

        <x-table>

            <x-slot name="head">
                <x-table.heading >Nombre / Razón social</x-table.heading>
                <x-table.heading >Porcentaje propiedad</x-table.heading>
                <x-table.heading >Porcentaje nuda</x-table.heading>
                <x-table.heading >Porcentaje usufructo</x-table.heading>
            </x-slot>

            <x-slot name="body">

                @if($folioReal->predio->propietarios()->count() > 0)

                    @foreach ($folioReal->predio->propietarios() as $propietario)

                        <x-table.row >

                            <x-table.cell>{{ $propietario->persona->nombre }} {{ $propietario->persona->ap_paterno }} {{ $propietario->persona->ap_materno }} {{ $propietario->persona->razon_social }}</x-table.cell>
                            <x-table.cell>{{ number_format($propietario->porcentaje_propiedad, 2) }}%</x-table.cell>
                            <x-table.cell>{{ number_format($propietario->porcentaje_nuda, 2) }}%</x-table.cell>
                            <x-table.cell>{{ number_format($propietario->porcentaje_usufructo, 2) }}%</x-table.cell>

                        </x-table.row>

                    @endforeach

                @endif

            </x-slot>

            <x-slot name="tfoot"></x-slot>

        </x-table>

    </div>

    <div class="bg-white p-4 rounded-lg mb-3 flex gap-3 items-center justify-end">

        <x-link-blue target="_blank" href="{{ $folioReal->caratula() }}">Documento de entrada</x-link-blue>

    </div>

    {{-- <div class="mb-3 bg-white rounded-lg p-3 shadow-lg">

        <span class="flex items-center justify-center text-lg text-gray-700 mb-5">Transmitentes</span>

        <x-table>

            <x-slot name="head">
                <x-table.heading >Nombre / Razón social</x-table.heading>
            </x-slot>

            <x-slot name="body">

                @if($folioReal->predio->transmitentes()->count() > 0)

                    @foreach ($folioReal->predio->transmitentes() as $transmitente)

                        <x-table.row >

                            <x-table.cell>{{ $transmitente->persona->nombre }} {{ $transmitente->persona->ap_paterno }} {{ $transmitente->persona->ap_materno }} {{ $transmitente->persona->razon_social }}</x-table.cell>

                        </x-table.row>

                    @endforeach

                @endif

            </x-slot>

            <x-slot name="tfoot"></x-slot>

        </x-table>

    </div>

    @if($folioReal->predio->representantes()->count() > 0)

        <div class="mb-3 bg-white rounded-lg p-3 shadow-lg">

            <span class="flex items-center justify-center text-lg text-gray-700 mb-5">Representantes</span>

            <x-table>

                <x-slot name="head">
                    <x-table.heading >Nombre / Razón social</x-table.heading>
                    <x-table.heading >Representados</x-table.heading>
                </x-slot>

                <x-slot name="body">

                    @foreach ($folioReal->predio->representantes() as $representante)

                        <x-table.row >

                            <x-table.cell>{{ $representante->persona->nombre }} {{ $representante->persona->ap_paterno }} {{ $representante->persona->ap_materno }} {{ $representante->persona->razon_social }}</x-table.cell>
                            <x-table.cell>

                                @foreach ($representante->representados as $representado)

                                    <p>{{ $representado->persona->nombre }} {{ $representado->persona->ap_paterno }} {{ $representado->persona->ap_materno }} {{ $representante->persona->razon_social }}</p>

                                @endforeach

                            </x-table.cell>

                        </x-table.row>

                    @endforeach

                </x-slot>

                <x-slot name="tfoot"></x-slot>

            </x-table>

        </div>

    @endif --}}

</div>
