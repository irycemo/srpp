<div class="">

    <div class="mb-6">

        <x-header>Subdivisión <span class="text-sm tracking-widest">Folio real matriz: {{ $propiedad->movimientoRegistral->folioReal->folio }} - {{ $propiedad->movimientoRegistral->folio }}</span></x-header>

    </div>

    @if($propiedad->movimientoRegistral->estado != 'elaborado')

        <div class="mb-5 bg-white rounded-lg p-2 w-1/3 mx-auto space-y-2">

            <div class="rounded-lg bg-gray-100 py-1 px-2">

                <strong>Folio real matriz</strong>

                <p>{{ $propiedad->movimientoRegistral->folioReal->folio }}</p>

            </div>

            <div class="rounded-lg bg-gray-100 py-1 px-2">

                <strong>Superficie actual</strong>

                <p>{{ $propiedad->movimientoRegistral->folioReal->predio->superficie_terreno_formateada }} {{ $propiedad->movimientoRegistral->folioReal->predio->unidad_area }}</p>

            </div>

            <div class="rounded-lg bg-gray-100 py-1 px-2">

                <strong>Subdivisiones</strong>

                <p>{{ $propiedad->numero_inmuebles }}</p>

            </div>

            <div class="flex justify-between pt-5">

                @if(!$propiedad->movimientoRegistral->documentoEntrada())

                    <x-button-blue
                        wire:click="abrirModalDocumento"
                        wire:loading.attr="disabled"
                        wire:target="abrirModalDocumento">

                        <img wire:loading wire:target="abrirModalDocumento" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        Subir documento de entrada

                    </x-button-blue>

                @else

                    <div class="inline-block">

                        <x-link-blue target="_blank" href="{{ $propiedad->movimientoRegistral->documentoEntrada() }}">Ver documento de entrada</x-link-blue>

                    </div>

                @endif

                <x-button-blue
                    wire:click="subdividir"
                    wire:loading.attr="disabled"
                    wire:target="subdividir">

                    <img wire:loading wire:target="subdividir" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    Hacer subdivisión

                </x-button-blue>

            </div>

        </div>

    @else

        <div class="mb-6">

            <x-h4>Folios reales creados</x-h4>

        </div>

        <div class="relative overflow-x-auto rounded-lg shadow-xl border-t-2 border-t-gray-500">

            <x-table>

                <x-slot name="head">

                    <x-table.heading>Folio real</x-table.heading>
                    <x-table.heading>Estado</x-table.heading>
                    <x-table.heading>Folio Antecedente</x-table.heading>

                </x-slot>

                <x-slot name="body">

                    @forelse ($foliosReales as $folio)

                        <x-table.row wire:loading.class.delaylongest="opacity-50" wire:key="row-{{ $folio->id }}">

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Folio real</span>

                                <span class="whitespace-nowrap">{{ $folio->folio }}</span>

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Estado</span>

                                <span class="bg-{{ $folio->estado_color }} py-1 px-2 rounded-full text-white text-xs">{{ ucfirst($folio->estado) }}</span>

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Folio Antecedente</span>

                                {{ $folio->folioRealAntecedente->folio }}

                            </x-table.cell>

                        </x-table.row>

                    @empty

                        <x-table.row>

                            <x-table.cell colspan="12">

                                <div class="bg-white text-gray-500 text-center p-5 rounded-full text-lg">

                                    No hay resultados.

                                </div>

                            </x-table.cell>

                        </x-table.row>

                    @endforelse

                </x-slot>

                <x-slot name="tfoot">

                    <x-table.row>

                        <x-table.cell colspan="12" class="bg-gray-50">



                        </x-table.cell>

                    </x-table.row>

                </x-slot>

            </x-table>

            <div class="h-full w-full rounded-lg bg-gray-200 bg-opacity-75 absolute top-0 left-0" wire:loading.delay.longer>

                <img class="mx-auto h-16" src="{{ asset('storage/img/loading.svg') }}" alt="">

            </div>

        </div>

    @endif

    <x-dialog-modal wire:model="modalDocumento" maxWidth="sm">

        <x-slot name="title">

            Subir archivo

        </x-slot>

        <x-slot name="content">

            <x-filepond::upload wire:model="documento_entrada" :accepted-file-types="['application/pdf']"/>

            <div>

                @error('documento_entrada') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

            </div>

        </x-slot>

        <x-slot name="footer">

            <div class="flex gap-3">

                <x-button-blue
                    wire:click="guardarDocumento"
                    wire:loading.attr="disabled"
                    wire:target="guardarDocumento">

                    <img wire:loading wire:target="guardarDocumento" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <span>Guardar</span>

                </x-button-blue>

                <x-button-red
                    wire:click="$toggle('modalDocumento')"
                    wire:loading.attr="disabled"
                    wire:target="$toggle('modalDocumento')"
                    type="button">

                    <span>Cerrar</span>

                </x-button-red>

            </div>

        </x-slot>

    </x-dialog-modal>

    @filepondScripts

</div>
