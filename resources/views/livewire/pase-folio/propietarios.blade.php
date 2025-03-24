<div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

    <div class=" gap-3  col-span-2">

        <div class="mb-3 bg-white rounded-lg p-3 shadow-lg">

            <span class="flex items-center justify-center text-lg text-gray-700 mb-5">Adquirientes</span>

            <div class="flex justify-end mb-2">

                <div class="flex justify-end mb-2">

                    @livewire('comun.actores.propietario-crear', ['modelo' => $propiedad])

                </div>

            </div>

            <x-table>

                <x-slot name="head">
                    <x-table.heading >Nombre / Razón social</x-table.heading>
                    <x-table.heading >Porcentaje propiedad</x-table.heading>
                    <x-table.heading >Porcentaje nuda</x-table.heading>
                    <x-table.heading >Porcentaje usufructo</x-table.heading>
                    <x-table.heading ></x-table.heading>
                </x-slot>

                <x-slot name="body">

                    @if($propiedad)

                        @foreach ($propiedad->propietarios() as $propietario)

                            <x-table.row wire:key="row-{{ $propietario->id }}">

                                <x-table.cell>{{ $propietario->persona->nombre }} {{ $propietario->persona->ap_paterno }} {{ $propietario->persona->ap_materno }} {{ $propietario->persona->razon_social }}</x-table.cell>
                                <x-table.cell>{{ $propietario->porcentaje_propiedad }}%</x-table.cell>
                                <x-table.cell>{{ $propietario->porcentaje_nuda }}%</x-table.cell>
                                <x-table.cell>{{ $propietario->porcentaje_usufructo }}%</x-table.cell>
                                <x-table.cell>
                                    <div class="flex items-center gap-3">
                                        <div>

                                            <livewire:comun.actores.propietario-actualizar :actor="$propietario" :predio="$propiedad" wire:key="button-propietario-{{ $propietario->id }}" />

                                        </div>
                                        <x-button-red
                                            wire:click="borrarActor({{ $propietario->id }})"
                                            wire:loading.attr="disabled">
                                            Borrar
                                        </x-button-red>
                                    </div>
                                </x-table.cell>

                            </x-table.row>

                        @endforeach

                    @endif

                </x-slot>

                <x-slot name="tfoot"></x-slot>

            </x-table>

        </div>

        <div class="mb-3 bg-white rounded-lg p-3 shadow-lg">

            <span class="flex items-center justify-center text-lg text-gray-700 mb-5">Transmitentes</span>

            <div class="flex justify-end mb-2">

                <div class="flex justify-end mb-2">

                    @livewire('comun.actores.transmitente-crear', ['modelo' => $propiedad])

                </div>

            </div>

            <x-table>

                <x-slot name="head">
                    <x-table.heading >Nombre / Razón social</x-table.heading>
                    <x-table.heading ></x-table.heading>
                </x-slot>

                <x-slot name="body">

                    @if($propiedad)

                        @foreach ($propiedad->transmitentes() as $transmitente)

                            <x-table.row wire:key="row-{{ $transmitente->id }}">

                                <x-table.cell>{{ $transmitente->persona->nombre }} {{ $transmitente->persona->ap_paterno }} {{ $transmitente->persona->ap_materno }} {{ $transmitente->persona->razon_social }}</x-table.cell>
                                <x-table.cell>
                                    <div class="flex items-center gap-3">
                                        <div>

                                            <livewire:comun.actores.transmitente-actualizar :actor="$transmitente" :predio="$propiedad" wire:key="button-transmitente-{{ $transmitente->id }}" />

                                        </div>
                                        <x-button-red
                                            wire:click="borrarActor({{ $transmitente->id }})"
                                            wire:loading.attr="disabled">
                                            Borrar
                                        </x-button-red>
                                    </div>
                                </x-table.cell>

                            </x-table.row>

                        @endforeach

                    @endif

                </x-slot>

                <x-slot name="tfoot"></x-slot>

            </x-table>

        </div>

        <div class="mb-3 bg-white rounded-lg p-3 shadow-lg">

            <span class="flex items-center justify-center text-lg text-gray-700 mb-5">Representantes</span>

            <div class="flex justify-end mb-2">

                @livewire('comun.actores.representante-crear', ['predio' => $propiedad, 'modelo' => $propiedad])

            </div>

            <x-table>

                <x-slot name="head">
                    <x-table.heading >Nombre / Razón social</x-table.heading>
                    <x-table.heading >Representados</x-table.heading>
                    <x-table.heading ></x-table.heading>
                </x-slot>

                <x-slot name="body">

                    @if($propiedad)

                        @foreach ($propiedad->representantes() as $representante)

                            <x-table.row wire:key="row-{{ $representante->id }}">

                                <x-table.cell>{{ $representante->persona->nombre }} {{ $representante->persona->ap_paterno }} {{ $representante->persona->ap_materno }} {{ $representante->persona->razon_social }}</x-table.cell>
                                <x-table.cell>

                                    @foreach ($representante->representados as $representado)

                                        <p>{{ $representado->persona->nombre }} {{ $representado->persona->ap_paterno }} {{ $representado->persona->ap_materno }} {{ $representado->persona->razon_social }}</p>

                                    @endforeach

                                </x-table.cell>
                                <x-table.cell>
                                    <div class="flex items-center gap-3">
                                        <div>

                                            <livewire:comun.actores.representante-actualizar :actor="$representante" :predio="$propiedad" wire:key="button-representante-{{ $representante->id }}" />

                                        </div>
                                        <x-button-red
                                            wire:click="borrarActor({{ $representante->id }})"
                                            wire:loading.attr="disabled">
                                            Borrar
                                        </x-button-red>
                                    </div>
                                </x-table.cell>

                            </x-table.row>

                        @endforeach

                    @endif

                </x-slot>

                <x-slot name="tfoot"></x-slot>

            </x-table>

        </div>

    </div>

    @include('livewire.pase-folio.informacion_base_datos')

    <div class=" flex justify-end items-center bg-white rounded-lg p-2 shadow-lg md:col-span-3 col-span-1 sm:col-span-2">

        <x-button-red
            wire:click="$parent.finalizarPaseAFolio"
            wire:loading.attr="disabled">

            <img wire:loading class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
            Finalizar pase a folio

        </x-button-red>

    </div>

</div>
