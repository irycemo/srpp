<div class="">

    <x-header>Certificado de propiedad o negativo de propiedad</x-header>

    <div class="bg-white rounded-lg p-4 shadow-lg w-full  mx-auto mb-5">

        <div class="lg:w-1/2 mx-auto mb-5">

            <p class="text-center"><strong>Solicitante</strong></p>

            <div class="text-gray-500 text-sm leading-relaxed mx-auto mb-5">

                <p class="text-center">{{ $certificacion->movimientoRegistral->solicitante }}</p>

            </div>

            <p class="text-center"><strong>Observaciones</strong></p>

            <div class="text-gray-500 text-sm leading-relaxed mx-auto mb-5">

                <p class="text-justify">{{ $certificacion->observaciones }}</p>

            </div>

        </div>

    </div>

    <div class="bg-white p-4 rounded-lg shadow-lg mb-5">

        <ul class="grid w-full lg:w-1/2 mx-auto gap-6 md:grid-cols-2">

            <li>

                <input type="radio" id="certificado-propiedad" name="certificado" value="propiedad" class="hidden peer" wire:model.live="radio" required>

                <label for="certificado-propiedad" class="inline-flex items-center justify-between w-full p-3 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">

                    <div class="block">

                        <div class="w-full text-lg font-semibold">Certificado de propiedad</div>

                    </div>

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" />
                    </svg>

                </label>

            </li>

            <li>

                <input type="radio" id="certificado-negativo" name="certificado" value="negativo" class="hidden peer" wire:model.live="radio">

                <label for="certificado-negativo" class="inline-flex items-center justify-between w-full p-3 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">

                    <div class="block">

                        <div class="w-full text-lg font-semibold">Certificado negativo de propiedad</div>

                    </div>

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" />
                    </svg>

                </label>

            </li>

        </ul>

    </div>

    @if($radio == 'propiedad')

        <div class="bg-white p-4 rounded-lg shadow-lg mb-5">

            <ul class="grid w-full lg:w-1/2 mx-auto gap-6 md:grid-cols-2">

                <li>

                    <input type="radio" id="certificado-unico" name="propiedad" value="unico" class="hidden peer" wire:model.live="propiedad_radio" required>

                    <label for="certificado-unico" class="inline-flex items-center justify-between w-full px-3 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">

                        <div class="block">

                            <div class="w-full text-lg font-semibold">Certificado de única propiedad</div>

                        </div>

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" />
                        </svg>

                    </label>

                </li>

                <li>

                    <input type="radio" id="certificado-normal" name="propiedad" value="propieda" class="hidden peer" wire:model.live="propiedad_radio">

                    <label for="certificado-normal" class="inline-flex items-center justify-between w-full px-3 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">

                        <div class="block">

                            <div class="w-full text-lg font-semibold">Certificado de propiedad</div>

                        </div>

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" />
                        </svg>

                    </label>

                </li>

            </ul>

        </div>

    @elseif($radio == 'negativo')

        <div class="bg-white p-4 rounded-lg shadow-lg mb-5">

            <ul class="grid w-full lg:w-1/2 mx-auto gap-6 md:grid-cols-3">

                <li>

                    <input type="radio" id="nombre" name="propiedad" value="nombre" class="hidden peer" wire:model.live="negativo_radio" required>

                    <label for="nombre" class="inline-flex items-center justify-between w-full px-3 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">

                        <div class="block">

                            <div class="w-full text-lg font-semibold">Solo nombre</div>

                        </div>

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" />
                        </svg>

                    </label>

                </li>

                <li>

                    <input type="radio" id="propiedad_registrada" name="propiedad_registrada" value="propiedad_registrada" class="hidden peer" wire:model.live="negativo_radio">

                    <label for="propiedad_registrada" class="inline-flex items-center justify-between w-full px-3 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">

                        <div class="block">

                            <div class="w-full text-lg font-semibold">Propiedad registrada</div>

                        </div>

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" />
                        </svg>

                    </label>

                </li>

                <li>

                    <input type="radio" id="propiedad_sin_registro" name="propiedad_sin_registro" value="propiedad_sin_registro" class="hidden peer" wire:model.live="negativo_radio">

                    <label for="propiedad_sin_registro" class="inline-flex items-center justify-between w-full px-3 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">

                        <div class="block">

                            <div class="w-full text-lg font-semibold">Propiedad no registrada</div>

                        </div>

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" />
                        </svg>

                    </label>

                </li>

            </ul>

        </div>

    @endif

    @if($radio == 'propiedad' && $propiedad_radio == 'unico')

        <livewire:certificaciones.certificado-propiedad.certificado-unico :certificacion="$certificacion"/>

    @endif

    @if($radio == 'propiedad' && $propiedad_radio == 'propieda')

        <livewire:certificaciones.certificado-propiedad.certificado-propiedad :certificacion="$certificacion" :vientos="$vientos"/>

    @endif

    @if($radio == 'negativo' && $negativo_radio == 'nombre')

        <livewire:certificaciones.certificado-negativo.solo-nombre :certificacion="$certificacion" :vientos="$vientos"/>

    @endif

    @if($radio == 'negativo' && $negativo_radio == 'propiedad_registrada')

        <livewire:certificaciones.certificado-negativo.propiedad-registrada :certificacion="$certificacion" :vientos="$vientos"/>

    @endif

    @if($radio == 'negativo' && $negativo_radio == 'propiedad_sin_registro')

        <livewire:certificaciones.certificado-negativo.propiedad-no-registrada :certificacion="$certificacion"/>

    @endif

</div>

@push('scripts')

    <script>

        window.addEventListener('imprimir_negativo_propiedad', event => {

            const documento = event.detail[0].certificacion;

            var url = "{{ route('certificado_negativo_propiedad_pdf', '')}}" + "/" + documento;

            window.open(url, '_blank');

            window.location.href = "{{ route('certificados_propiedad')}}";

        });

        window.addEventListener('imprimir_propiedad', event => {

            const documento = event.detail[0].certificacion;

            var url = "{{ route('certificado_propiedad_pdf', '')}}" + "/" + documento;

            window.open(url, '_blank');

            window.location.href = "{{ route('certificados_propiedad')}}";

        });

        window.addEventListener('imprimir_unico_propiedad', event => {

            const documento = event.detail[0].certificacion;

            var url = "{{ route('certificado_unico_propiedad_pdf', '')}}" + "/" + documento;

            window.open(url, '_blank');

            window.location.href = "{{ route('certificados_propiedad')}}";

        });

        window.addEventListener('imprimir_propiedad_colindancias', event => {

            const documento = event.detail[0].certificacion;

            var url = "{{ route('certificado_propiedad_colindancias_pdf', '')}}" + "/" + documento;

            window.open(url, '_blank');

            window.location.href = "{{ route('certificados_propiedad')}}";

        });

        window.addEventListener('imprimir_negativo', event => {

            const documento = event.detail[0].certificacion;

            var url = "{{ route('certificado_negativo_pdf', '')}}" + "/" + documento;

            window.open(url, '_blank');

            window.location.href = "{{ route('certificados_propiedad')}}";

        });

    </script>

@endpush
