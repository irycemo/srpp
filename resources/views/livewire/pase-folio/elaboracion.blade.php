<div>

    <x-header>Pase a folio</x-header>

    <div x-data="{ activeTab: 4 }">

        <div class="flex px-4 gap-4 justify-center items-center">

            <x-button-pill @click="activeTab = 4" x-bind:class="{ 'bg-slate-200 ': activeTab === 4 }">Propiedad</x-button-pill>

            <x-button-pill @click="activeTab = 5" x-bind:class="{ 'bg-slate-200 ': activeTab === 5 }">Gravamen</x-button-pill>

            <x-button-pill @click="activeTab = 6" x-bind:class="{ 'bg-slate-200 ': activeTab === 6 }">Sentencias</x-button-pill>

            <x-button-pill @click="activeTab = 7" x-bind:class="{ 'bg-slate-200 ': activeTab === 7 }">Varios</x-button-pill>

        </div>

        <div class="tab-panel" :class="{ 'active': activeTab === 4 }" x-show.transition.in.opacity.duration.800="activeTab === 4">

            <div x-data="{ activeTab:  0 }">

                <div class="flex px-4 gap-4 justify-center items-center  p-3 rounded-lg">

                    <x-button-pill  @click="activeTab = 0" x-bind:class="{ 'bg-slate-200 ': activeTab === 0 }">Documento de entrada</x-button-pill>

                    <x-button-pill  @click="activeTab = 2" x-bind:class="{ 'bg-slate-200 ': activeTab === 2 }">Descripción del predio</x-button-pill>

                    <x-button-pill  @click="activeTab = 1" x-bind:class="{ 'bg-slate-200 ': activeTab === 1 }">Ubicación del predio</x-button-pill>

                    <x-button-pill  @click="activeTab = 3" x-bind:class="{ 'bg-slate-200 ': activeTab === 3 }">Propietarios</x-button-pill>

                </div>

                <div class="tab-panel rounded-lg" :class="{ 'active ': activeTab === 0 }" x-show.transition.in.opacity.duration.800="activeTab === 0">

                    @include('livewire.pase-folio.documento-entrada')

                </div>

                <div class="tab-panel rounded-lg" :class="{ 'active': activeTab === 1 }" x-show.transition.in.opacity.duration.800="activeTab === 1">

                    @livewire('pase-folio.ubicacion-predio', ['movimientoRegistral' => $this->movimientoRegistral])

                </div>

                <div class="tab-panel rounded-lg" :class="{ 'active': activeTab === 2 }" x-show.transition.in.opacity.duration.800="activeTab === 2">

                    @livewire('pase-folio.descripcion-predio', ['movimientoRegistral' => $this->movimientoRegistral])

                </div>

                <div class="tab-panel rounded-lg" :class="{ 'active': activeTab === 3 }" x-show.transition.in.opacity.duration.800="activeTab === 3">

                    @include('livewire.pase-folio.propietarios')

                </div>

            </div>

        </div>

        <div class="tab-panel rounded-lg" :class="{ 'active': activeTab === 5 }" x-show.transition.in.opacity.duration.800="activeTab === 5">

            @livewire('pase-folio.gravamen', ['movimientoRegistral' =>  $this->movimientoRegistral])

        </div>

        <div class="tab-panel rounded-lg" :class="{ 'active': activeTab === 6 }" x-show.transition.in.opacity.duration.800="activeTab === 6">

            <span class="text-center text-lg text-gray-700">Sentencias</span>

        </div>

        <div class="tab-panel rounded-lg" :class="{ 'active': activeTab === 7 }" x-show.transition.in.opacity.duration.800="activeTab === 7">

            <span class="text-center text-lg text-gray-700">Varios</span>

        </div>

    </div>

</div>
