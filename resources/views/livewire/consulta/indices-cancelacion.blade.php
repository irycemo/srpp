<div>

    <x-header>Indices Cancelaciones</x-header>

    <div class="bg-white p-4 rounded-lg mb-5 shadow-xl">

        <div class="w-fit mx-auto mb-5">

            <x-input-group for="tomo" label="Tomo" :error="$errors->first('tomo')" class="">

                <x-input-text id="tomo" wire:model="tomo"/>

            </x-input-group>

        </div>

        <div class="felx justify-center">

            <x-button-blue
                wire:click="buscarCancelacion"
                wire:target="buscarCancelacion"
                wire:loading.attr="disabled"
                class="mx-auto">

                <img wire:loading wire:target="buscarCancelacion" class="h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                Buscar
            </x-button-blue>

        </div>

    </div>

    @if($cancelaciones)

        <div class="bg-white p-4 rounded-lg mb-5 shadow-xl">

            <div class="lg:w-1/2 mx-auto">

                <x-table>

                    <x-slot name="head">

                        <x-table.heading >Tomo</x-table.heading>
                        <x-table.heading >Registro</x-table.heading>
                        <x-table.heading >Archivo</x-table.heading>

                    </x-slot>

                    <x-slot name="body">

                        @forelse ($cancelaciones as $cancelacion)

                            <x-table.row wire:loading.class.delaylongest="opacity-50" wire:key="row-{{$loop->index }}">

                                <x-table.cell title="Tomo">

                                    {{$tomo }}

                                </x-table.cell>

                                <x-table.cell title="Registro">

                                    {{ str_replace('.pdf', '', $cancelacion['name']) }}

                                </x-table.cell>

                                <x-table.cell title="Archivo">

                                    <a
                                        href="{{ Storage::disk('s3')->temporaryUrl($cancelacion['route'], now()->addMinutes(10)) }}"
                                        target="_blank"
                                        class="bg-green-400 hover:shadow-lg text-white text-xs px-3 py-1 rounded-full hover:bg-green-700 focus:outline-green-900 w-auto"
                                    >
                                        Ver archivo
                                    </a>

                                </x-table.cell>

                            </x-table.row>

                        @empty

                            <x-table.row>

                                <x-table.cell colspan="9">

                                    <div class="bg-white text-gray-500 text-center p-5 rounded-full text-lg">

                                        No hay resultados.

                                    </div>

                                </x-table.cell>

                            </x-table.row>

                        @endforelse

                    </x-slot>

                    <x-slot name="tfoot">
                    </x-slot>

                </x-table>

            </div>

        </div>

    @endif

</div>
