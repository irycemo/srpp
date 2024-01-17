@push('styles')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endpush

<div>

    <x-header>Pase a folio</x-header>

    <div x-data="{ activeTab: 4 }">

        <div class="flex px-4 gap-4 justify-center items-center">

            <x-button-pill @click="activeTab = 4" x-bind:class="{ 'bg-gray-300 bg-opacity-5 text-black ': activeTab === 4 }">Propiedad</x-button-pill>

            <x-button-pill @click="activeTab = 5" x-bind:class="{ 'bg-gray-300 bg-opacity-5 text-black ': activeTab === 5 }">Gravamen</x-button-pill>

            <x-button-pill @click="activeTab = 6" x-bind:class="{ 'bg-gray-300 bg-opacity-5 text-black ': activeTab === 6 }">Sentencias</x-button-pill>

            <x-button-pill @click="activeTab = 7" x-bind:class="{ 'bg-gray-300 bg-opacity-5 text-black ': activeTab === 7 }">Varios</x-button-pill>

        </div>

        <div class="tab-panel rounded-lg" :class="{ 'active': activeTab === 7 }" x-show.transition.in.opacity.duration.800="activeTab === 7" x-cloak>

            @livewire('pase-folio.varios', ['movimientoRegistral' => $this->movimientoRegistral])

        </div>

        <div class="tab-panel rounded-lg" :class="{ 'active': activeTab === 6 }" x-show.transition.in.opacity.duration.800="activeTab === 6" x-cloak>

            @livewire('pase-folio.sentencias', ['movimientoRegistral' => $this->movimientoRegistral])

        </div>

        <div class="tab-panel" :class="{ 'active': activeTab === 4 }" x-show.transition.in.opacity.duration.800="activeTab === 4">

            <div x-data="{ activeTab:  0 }">

                <div class="flex px-4 gap-4 justify-center items-center  p-3 rounded-lg">

                    <x-button-pill  @click="activeTab = 0" x-bind:class="{ 'bg-gray-300 bg-opacity-5 text-black ': activeTab === 0 }">Documento de entrada</x-button-pill>

                    <x-button-pill  @click="activeTab = 2" x-bind:class="{ 'bg-gray-300 bg-opacity-5 text-black ': activeTab === 2 }">Descripción del predio</x-button-pill>

                    <x-button-pill  @click="activeTab = 1" x-bind:class="{ 'bg-gray-300 bg-opacity-5 text-black ': activeTab === 1 }">Ubicación del predio</x-button-pill>

                    <x-button-pill  @click="activeTab = 3" x-bind:class="{ 'bg-gray-300 bg-opacity-5 text-black ': activeTab === 3 }">Propietarios</x-button-pill>

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

                    @livewire('pase-folio.propietarios', ['movimientoRegistral' => $this->movimientoRegistral])

                </div>

            </div>

        </div>

        <div class="tab-panel rounded-lg" :class="{ 'active': activeTab === 5 }" x-show.transition.in.opacity.duration.800="activeTab === 5" x-cloak>

            @livewire('pase-folio.gravamen', ['movimientoRegistral' => $this->movimientoRegistral])

        </div>

    </div>

</div>

@push('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>

        window.addEventListener('imprimir_documento', event => {

            const documento = event.detail[0].documento;

            var url = "{{ route('pase_folio_caratula', '')}}" + "/" + documento;

            window.open(url, '_blank');

        });

    </script>

@endpush
