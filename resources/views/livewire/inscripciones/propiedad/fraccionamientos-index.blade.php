<div>

    <div class="mb-2 lg:mb-5">

        <x-header>Fraccionamientos</x-header>

        <div class="flex justify-between items-center">

            @include('livewire.comun.filtros-inscripciones')

            @if(auth()->user()->ubicacion === 'Regional 4')

                <div class="">

                    <button wire:click="$toggle('modal_reasignarme_movimiento_registral')" class="bg-gray-500 hover:shadow-lg hover:bg-gray-700 text-sm py-2 px-4 text-white rounded-full hidden md:block items-center justify-center focus:outline-gray-400 focus:outline-offset-2">

                        <img wire:loading wire:target="$toggle('modal_reasignarme_movimiento_registral')" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                        Asignarme trámite

                    </button>

                    <button wire:click="$toggle('modal_reasignarme_movimiento_registral')" class="bg-gray-500 hover:shadow-lg hover:bg-gray-700 float-right text-sm py-2 px-4 text-white rounded-full md:hidden focus:outline-gray-400 focus:outline-offset-2">+</button>

                </div>

            @endif

        </div>

    </div>

    <div class="overflow-x-auto rounded-lg shadow-xl border-t-2 border-t-gray-500">

        @include('livewire.comun.inscripciones.tabla-inscripciones')

    </div>

    @include('livewire.comun.inscripciones.modal-finalizar')

    @include('livewire.comun.inscripciones.modal-rechazar')

    @include('livewire.comun.inscripciones.modal-correccion')

    @include('livewire.comun.inscripciones.modal-concluir')

    @include('livewire.comun.inscripciones.modal-reasignar-usuario')

    @include('livewire.comun.inscripciones.modal-recibir-documento')

    @include('livewire.comun.inscripciones.modal-reasignarme-movimiento-registral')

</div>
