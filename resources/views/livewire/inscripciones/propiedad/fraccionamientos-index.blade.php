<div>

    <div class="mb-2 lg:mb-5">

        <x-header>Fraccionamientos</x-header>

        <div class="flex justify-between items-center">

            @include('livewire.comun.filtros-inscripciones')

            @include('livewire.comun.inscripciones.asignarme-tramite')

        </div>

    </div>

    <div class="overflow-x-auto rounded-lg shadow-xl border-t-2 border-t-gray-500">

        @include('livewire.comun.inscripciones.tabla-inscripciones')

    </div>

    @include('livewire.comun.inscripciones.modal-finalizar')

    @include('livewire.comun.inscripciones.modal-rechazar')

    @include('livewire.comun.inscripciones.modal-rechazos')

    @include('livewire.comun.inscripciones.modal-correccion')

    @include('livewire.comun.inscripciones.modal-concluir')

    @include('livewire.comun.inscripciones.modal-reasignar-usuario')

    @include('livewire.comun.inscripciones.modal-recibir-documento')

    @include('livewire.comun.inscripciones.modal-reasignarme-movimiento-registral')

    @include('livewire.comun.inscripciones.modal-cambiar-antecedente')

</div>
