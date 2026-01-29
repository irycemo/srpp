<div>

    <x-header>Pase a folio simplificado<span class="tracking-widest text-sm">(Folio real: {{ $movimientoRegistral->folioReal?->folio }})</span></x-header>

        <div x-data="{ activeTab:  0 }">

            <div class="flex px-4 gap-4 lg:justify-center lg:items-center mb-5 overflow-auto">

                <x-button-pill  @click="activeTab = 0" x-bind:class="{ 'bg-gray-300 bg-opacity-5 text-black ': activeTab === 0 }">Documento de entrada</x-button-pill>

                <x-button-pill  @click="activeTab = 2" x-bind:class="{ 'bg-gray-300 bg-opacity-5 text-black ': activeTab === 2 }">Descripción del predio</x-button-pill>

                <x-button-pill  @click="activeTab = 1" x-bind:class="{ 'bg-gray-300 bg-opacity-5 text-black ': activeTab === 1 }">Ubicación del predio</x-button-pill>

                <x-button-pill  @click="activeTab = 3" x-bind:class="{ 'bg-gray-300 bg-opacity-5 text-black ': activeTab === 3 }">Propietarios</x-button-pill>

            </div>

            <div class="tab-panel rounded-lg" :class="{ 'active ': activeTab === 0 }" x-show.transition.in.opacity.duration.800="activeTab === 0">

                @include('livewire.pase-folio.pase-folio-simplificado.documento-entrada-simplificado')

            </div>

            <div class="tab-panel rounded-lg" :class="{ 'active': activeTab === 1 }" x-show.transition.in.opacity.duration.800="activeTab === 1" x-cloak>

                @livewire('pase-folio.ubicacion-predio', ['movimientoRegistral' => $this->movimientoRegistral, 'simplificado' => true, 'propiedadOld' => $this->propiedadOld])

            </div>

            <div class="tab-panel rounded-lg" :class="{ 'active': activeTab === 2 }" x-show.transition.in.opacity.duration.800="activeTab === 2" x-cloak>

                @livewire('pase-folio.descripcion-predio', ['movimientoRegistral' => $this->movimientoRegistral, 'simplificado' => true, 'propiedadOld' => $this->propiedadOld])

            </div>

            <div class="tab-panel rounded-lg" :class="{ 'active': activeTab === 3 }" x-show.transition.in.opacity.duration.800="activeTab === 3" x-cloak>

                @livewire('pase-folio.propietarios', ['movimientoRegistral' => $this->movimientoRegistral, 'simplificado' => true, 'propiedadOld' => $this->propiedadOld])

            </div>

        </div>

    @filepondScripts

    @include('livewire.comun.inscripciones.modal-guardar_documento_entrada_pdf')

</div>