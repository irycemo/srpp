<div>

    @if($certificacion->movimientoRegistral->folioReal)

        <div class="w-full  mx-auto mb-5 text-center">

            <span class="rounded-lg bg-red-400 text-white px-3 py-1">Se calificó con antecedente de propiedad</span>

        </div>

    @else

        <div class="bg-white rounded-lg p-4 shadow-lg w-full  mx-auto mb-5">

            <div class="lg:w-1/2 mx-auto mb-5">

            @include('livewire.certificaciones.comun.propietarios')

            <button
                wire:click="buscarPropietarios"
                wire:loading.attr="disabled"
                wire:target="buscarPropietarios"
                type="button"
                class="mt-3 bg-blue-400 hover:shadow-lg text-white mx-auto font-bold px-4 py-2 rounded text-xs hover:bg-blue-700 focus:outline-none flex items-center justify-center focus:outline-blue-400 focus:outline-offset-2">

                <img wire:loading wire:target="buscarPropietarios" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                Buscar

            </button>

            </div>

        </div>

        @include('livewire.certificaciones.comun.predios')

        <div class="bg-white rounded-lg p-4 shadow-lg w-full  mx-auto mb-5">

            <div class="lg:w-1/2 mx-auto mb-3">

                <x-input-group for="observaciones" label="Observaciones" :error="$errors->first('observaciones')" class="w-full">

                    <textarea rows="5" class="bg-white rounded text-sm w-full" wire:model="observaciones"></textarea>

                </x-input-group>

            </div>

        </div>

        <div class="bg-white rounded-lg p-3 flex justify-end shadow-lg gap-3">

            @if($flagGenerar)

                <x-button-blue
                    wire:click="generarCertificado"
                    wire:loading.attr="disabled"
                    wire:target="generarCertificado">

                    <img wire:loading wire:target="generarCertificado" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    Generar certificado negativo de propiedad

                </x-button-blue>

            @endif

        </div>

    @endif

    @include('livewire.certificaciones.comun.eliminar-propiedad-modal')

</div>
