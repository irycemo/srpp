<div>

    @include('livewire.certificaciones.comun.propiedad')

    <div class="bg-white rounded-lg p-4 shadow-lg w-full  mx-auto mb-5">

        @if(!$certificacion->movimientoRegistral->folioReal)

            <div class="text-center  my-3">

                <span class="rounded-lg bg-red-400 text-white px-3 py-1">Se calificó sin antecedente de propiedad</span>

            </div>

        @else

            <div class="lg:w-1/2 mx-auto mb-5">

                <x-input-group for="observaciones" label="Observaciones" :error="$errors->first('observaciones')" class="w-full">

                    <textarea rows="5" class="bg-white rounded text-sm w-full" wire:model="observaciones"></textarea>

                </x-input-group>

            </div>

        @endif

    </div>

    <div class="bg-white rounded-lg p-3 flex justify-end shadow-lg gap-3">

        @if($this->certificacion->movimientoRegistral->folioReal)

            <x-button-blue
                wire:click="generarCertificado"
                wire:loading.attr="disabled"
                wire:target="generarCertificado">

                <img wire:loading wire:target="generarCertificado" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                Generar certificado de propiedad

            </x-button-blue>

        @endif

    </div>

</div>
