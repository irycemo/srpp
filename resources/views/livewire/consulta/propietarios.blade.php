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
                            <x-table.cell>{{ $propietario->porcentaje_propiedad }}%</x-table.cell>
                            <x-table.cell>{{ $propietario->porcentaje_nuda }}%</x-table.cell>
                            <x-table.cell>{{ $propietario->porcentaje_usufructo }}%</x-table.cell>

                        </x-table.row>

                    @endforeach

                @endif

            </x-slot>

            <x-slot name="tfoot"></x-slot>

        </x-table>

    </div>

    <div class="bg-white p-4 rounded-lg mb-3 flex gap-3 items-center justify-between shadow-lg">

        <div>

            @if($folioReal->documentoEntrada())

                <x-link-blue target="_blank" href="{{ $folioReal->documentoEntrada() }}">Documento de entrada</x-link-blue>

            @endif

        </div>

        <div>

            @if($folioReal->caratula())

                <x-link-blue target="_blank" href="{{ $folioReal->caratula() }}">Caratula</x-link-blue>

            @endif

        </div>

    </div>

</div>
