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

                            <div class="w-full text-lg font-semibold">Certificado único de propiedad</div>

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



    @endif

    @if($radio == 'negativo' && $negativo_radio == 'propiedad_sin_registro')

        <div class="bg-white rounded-lg p-4 shadow-lg w-full  mx-auto mb-5">

            <div class="lg:w-1/2 mx-auto mb-5">

            @include('livewire.certificaciones.comun.propietario')

            <button
                wire:click="buscarPropietarioUnico"
                wire:loading.attr="disabled"
                wire:target="buscarPropietarioUnico"
                type="button"
                class="mt-3 bg-blue-400 hover:shadow-lg text-white mx-auto font-bold px-4 py-2 rounded text-xs hover:bg-blue-700 focus:outline-none flex items-center justify-center focus:outline-blue-400 focus:outline-offset-2">

                <img wire:loading wire:target="buscarPropietarioUnico" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                Buscar

            </button>

            </div>

        </div>

        <div class="bg-white rounded-lg p-4 shadow-lg w-full  mx-auto mb-5">

            <div class="lg:w-1/2 mx-auto mb-5">

                <x-input-group for="observaciones" label="Observaciones" :error="$errors->first('observaciones')" class="w-full">

                    <textarea rows="5" class="bg-white rounded text-sm w-full" wire:model="observaciones"></textarea>

                </x-input-group>

            </div>

        </div>

        <div class="bg-white rounded-lg p-3 flex justify-end shadow-lg gap-3">

            <x-button-red
                wire:click="abrirModalRechazar"
                wire:loading.attr="disabled"
                wire:target="abrirModalRechazar">

                <img wire:loading wire:target="abrirModalRechazar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                Rechazar

            </x-button-red>

            @if($flagNegativo)

                <x-button-blue
                    wire:click="generarCertificadoNegativo"
                    wire:loading.attr="disabled"
                    wire:target="generarCertificadoNegativo">

                    <img wire:loading wire:target="generarCertificadoNegativo" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    Generar certificado negativo de propiedad

                </x-button-blue>

            @endif

        </div>

    @endif


    <x-dialog-modal wire:model="modalRechazar" maxWidth="sm">

        <x-slot name="title">

            Rechazar

        </x-slot>

        <x-slot name="content">

            <div class="flex flex-col md:flex-row justify-between md:space-x-3 mb-5">

                <x-input-group for="observaciones" label="Observaciones" :error="$errors->first('observaciones')" class="w-full">

                    <textarea rows="5" class="bg-white rounded text-sm w-full" wire:model="observaciones"></textarea>

                </x-input-group>

            </div>

        </x-slot>

        <x-slot name="footer">

            <div class="flex items-center justify-end space-x-3">

                <x-button-blue
                    wire:click="rechazar"
                    wire:loading.attr="disabled"
                    wire:target="rechazar">

                    <img wire:loading wire:target="rechazar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <span>Rechazar</span>
                </x-button-blue>

                <x-button-red
                    wire:click="$toggle('modalRechazar')"
                    wire:loading.attr="disabled"
                    wire:target="$toggle('modalRechazar')"
                    type="button">
                    <span>Cerrar</span>
                </x-button-red>

            </div>

        </x-slot>

    </x-dialog-modal>

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
