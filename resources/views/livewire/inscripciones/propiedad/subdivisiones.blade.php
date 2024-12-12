<div class="">

    <div class="mb-6">

        <x-header>Subdivisión <span class="text-sm tracking-widest">Folio real matriz: {{ $propiedad->movimientoRegistral->folioReal->folio }} - {{ $propiedad->movimientoRegistral->folio }}</span></x-header>

    </div>

    @if($propiedad->movimientoRegistral->estado != 'concluido')

        <div class="space-y-2 mb-5 bg-white rounded-lg p-2">

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

        </div>

        <div class="space-y-2 mb-5 bg-white rounded-lg p-2">

            <div class="md:w-1/2 lg:w-1/4 mx-auto items-center text-center">

                <div class="mb-5">

                    <x-filepond::upload wire:model="documento" :accepted-file-types="['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', '.xlsx']"/>

                </div>

                <div>

                    @error('documento') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                </div>

                @if($documento)

                    <button
                        class="bg-blue-400 hover:shadow-lg w-full justify-center text-white text-xs md:text-sm px-3 py-1 items-center rounded-full mr-2 hover:bg-blue-700 flex focus:outline-none"
                        wire:click="procesar"
                        wire:loading.attr="disabled"
                        wire:target="procesar">

                        <img wire:loading wire:target="procesar" class="h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        Procesar
                    </button>

                @else

                    <button
                        class="bg-green-400 hover:shadow-lg w-full justify-center text-white text-xs md:text-sm px-3 py-1 items-center rounded-full mr-2 hover:bg-green-700 flex focus:outline-none"
                        wire:click="descargarFicha"
                        wire:loading.attr="disabled"
                        wire:target="descargarFicha">

                        <div wire:loading.flex wire:target="descargarFicha" class="flex absolute top-1 right-1 items-center">
                            <svg class="animate-spin h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>

                        Descargar ficha técnica
                    </button>

                @endif

            </div>

        </div>

    @endif

    @if ($data != null)

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

                    @forelse ($data as $folio)

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

    @else

        <div class="space-y-2 mb-5 bg-white rounded-lg p-4">

            <div class="space-y-2">

                <h4 class="text-xl font-semibold">Colindancias</h4>

                <p class="">Las colindancias deben tener la siguiente estrctura: <strong>VIENTO:LONGITUD:DESCRIPCION</strong>. Cada elemento separado por el carácter '<strong>:</strong>'. Debe usar el carácter '<strong>|</strong>' para separar colindancias.</p>

                <p class="">Valores permitidos para VIENTO:</p>

                <ul class="ml-10 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6">

                    @foreach ($vientos as $viento)

                        <li class="list-disc">{{ $viento }}</li>

                    @endforeach

                </ul>

                <p class="">Ejemplo para una colindancia: <strong>NORTE:100:Colinda 100 metros al norte</strong></p>

                <p class="">Ejemplo para más de una colindancia: <strong>NORTE:100:Colinda 100 metros al norte|SUR:50:Colinda 50 metros al sur|ESTE:10:colinda 10 metros al este</strong></p>

            </div>

        </div>

        <div class="space-y-2 mb-5 bg-white rounded-lg p-4">

            <div class="space-y-2">

                <h4 class="text-xl font-semibold">Propietarios, Actores gravamen, Acreedores</h4>

                <p class="">Para personas fisicas: <strong>TIPO:NOMBRE:APELLIDO PATERNO:APELLIDO MATERNO</strong>. Cada elemento separado por el carácter '<strong>:</strong>'. Debe usar el carácter '<strong>|</strong>' para separarlos.</p>

                <p class="">Valores permitidos para TIPO:</p>

                <ul class="ml-10 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6">

                        <li class="list-disc">FISICA</li>
                        <li class="list-disc">MORAL</li>

                </ul>

                <p class="">Ejemplo para una persona fisica: <strong>FISICA:MARIA:MORALES:AVILA</strong></p>

                <p class="">Ejemplo para más de una persona fisica: <strong>FISICA:MARIA:MORALES:AVILA|FISICA:MARIO:DUARTE:DIAZ|FISICA:JUAN:JUAREZ:RUIZ</strong></p>

                <p class="">Para personas morales: <strong>TIPO:RAZON SOCIAL</strong>. Cada elemento separado por el carácter '<strong>:</strong>'. Debe usar el carácter '<strong>|</strong>' para separarlos.</p>

                <p class="">Ejemplo para una persona fisica: <strong>MORAL:GOBIERNO DEL ESTADO DE MICHOACAN DE OCAMPO</strong></p>

                <p class="">Ejemplo para más de una persona fisica: <strong>MORAL:GOBIERNO DEL ESTADO DE MICHOACAN DE OCAMPO|MORAL:H. AYUNTAMIENTO DE MORELIA|MORAL:SERVICIO POSTAL MEXICANO</strong></p>

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
