<div>

    <div class="bg-white rounded-lg p-4 shadow-lg w-full  mx-auto mb-5">

        @if(!$certificacion->movimientoRegistral->folioReal)

            <div class="text-center  mb-3">

                <span class="rounded-lg bg-red-400 text-white px-3 py-1">Se calificó sin antecedente de propiedad</span>

            </div>

        @else

            <div class="lg:w-1/2 mx-auto mb-5">

                @include('livewire.certificaciones.comun.propietario')

                @if($certificacion->movimientoRegistral->folioReal)

                    <button
                        wire:click="buscarProppietariosEnFolio"
                        wire:loading.attr="disabled"
                        wire:target="buscarProppietariosEnFolio"
                        type="button"
                        class="mt-3 bg-blue-400 hover:shadow-lg text-white mx-auto font-bold px-4 py-2 rounded text-xs hover:bg-blue-700 focus:outline-none flex items-center justify-center focus:outline-blue-400 focus:outline-offset-2">

                        <img wire:loading wire:target="buscarProppietariosEnFolio" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        Buscar

                    </button>

                @endif

            </div>

            @include('livewire.certificaciones.comun.propiedad')

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
                        wire:click="generarCertificadoNegativoPropiedad"
                        wire:loading.attr="disabled"
                        wire:target="generarCertificadoNegativoPropiedad">

                        <img wire:loading wire:target="generarCertificadoNegativoPropiedad" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        Generar certificado negativo de propiedad

                    </x-button-blue>

                @endif

            </div>

        @endif

    </div>

</div>
