<div class="">

    @if($certificacion->servicio == 'DL10')

        <x-header>Certificado de propiedad o negativo de propiedad</x-header>

        {{ $certificacion->numero_paginas }}

        <div class="bg-white p-4 rounded-lg shadow-lg mb-5">

            <ul class="grid w-full  mx-auto gap-6 md:grid-cols-3">

                <li>

                    <input type="radio" id="propiedad" name="hosting" value="propiedad" class="hidden peer" wire:model.live="radio" >

                    <label for="propiedad" class="inline-flex items-center justify-between w-full p-1 px-3 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">

                        <div class="block">

                            <div class="w-full font-semibold">Certificado de propiedad</div>

                        </div>

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" />
                        </svg>

                    </label>

                </li>

                <li>

                    <input type="radio" id="negativo" name="hosting" value="negativo" class="hidden peer" wire:model.live="radio">

                    <label for="negativo" class="inline-flex items-center justify-between w-full p-1 px-3 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">

                        <div class="block">

                            <div class="w-full font-semibold">Certificado negativo propiedad</div>

                        </div>

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" />
                        </svg>

                    </label>

                </li>

                <li>

                    <input type="radio" id="unico" name="hosting" value="unico" class="hidden peer" wire:model.live="radio">

                    <label for="unico" class="inline-flex items-center justify-between w-full p-1 px-3 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">

                        <div class="block">

                            <div class="w-full font-semibold">Certificado único de  propiedad</div>

                        </div>

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" />
                        </svg>

                    </label>

                </li>

            </ul>

        </div>

        <div class="bg-white rounded-lg p-4 shadow-lg w-full" id="propietarios-div">

            <div class="lg:w-1/2 mx-auto mb-5">

                <p>Solicitante: {{ $certificacion->movimientoRegistral->solicitante }}</p>

                <p>Tomo: {{ $certificacion->movimientoRegistral->tomo }}</p>

                <p>Registro: {{ $certificacion->movimientoRegistral->registro }}</p>

                <p>Número de propiedad: {{ $certificacion->movimientoRegistral->numero_propiedad }}</p>

                <p>Distrito: {{ $certificacion->movimientoRegistral->distrito }}</p>

                <div class="flex flex-col lg:flex-row gap-3 mb-5">

                    <x-input-group for="nombre" label="Nombre" :error="$errors->first('nombre')" class="w-full">

                        <x-input-text id="nombre" wire:model="nombre" />

                    </x-input-group>

                    <x-input-group for="ap_paterno" label="Apellido paterno" :error="$errors->first('ap_paterno')" class="w-full">

                        <x-input-text id="ap_paterno" wire:model="ap_paterno" />

                    </x-input-group>

                    <x-input-group for="ap_materno" label="Apellido materno" :error="$errors->first('ap_materno')" class="w-full">

                        <x-input-text id="ap_materno" wire:model="ap_materno" />

                    </x-input-group>

                </div>

                <button
                    wire:click="buscarPropietario"
                    wire:loading.attr="disabled"
                    wire:target="buscarPropietario"
                    type="button"
                    class="bg-blue-400 hover:shadow-lg text-white mx-auto font-bold px-4 py-2 rounded text-xs hover:bg-blue-700 focus:outline-none flex items-center justify-center focus:outline-blue-400 focus:outline-offset-2">

                    <img wire:loading wire:target="buscarPropietario" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    Buscar

                </button>

            </div>

        </div>


        <div>

            @if($radio == 'propiedad')

            @elseif($radio == 'negativo')


            @elseif($radio == 'unico')

            @endif


            <div>

                Propietario old

                {{ $propietarioOld }}

            </div>

            <div>

                Predio Old

                {{ $predioOld }}

            </div>

        </div>

    @elseif($certificacion->servicio == 'DL11')

        <x-header>Certificado con medidas y linderos</x-header>

    @endif

</div>

@push('scripts')

    <script>

        window.addEventListener('imprimir_documento', event => {

            const documento = event.detail[0].gravamen;

            var url = "{{ route('certificado_propiedad_pdf', '')}}" + "/" + documento;

            window.open(url, '_blank');

        });

    </script>

@endpush
