<div>

    <x-header>Ordenar movimientos</x-header>

    <div class="bg-white rounded-lg p-4 shadow-lg mb-5">

        <div class=" lg:w-1/2 mx-auto mb-5">

            <x-input-group for="folio_real" label="Folio real" :error="$errors->first('folio_real')" class="w-full lg:w-fit mx-auto mb-5">

                <x-input-text type="number" id="folio_real" wire:model.lazy="folio_real" />

            </x-input-group>

            <div class="lg:flex gap-4">

                <x-input-group for="distrito" label="Distrito" :error="$errors->first('distrito')" class="w-full lg:w-fit">

                    <x-input-select id="distrito" wire:model="distrito" class="w-full">

                        <option value="">Distrito</option>

                        @foreach ($distritos as $key => $distrito)

                            <option value="{{ $key }}">{{ $distrito }}</option>

                        @endforeach

                    </x-input-select>

                </x-input-group>

                <x-input-group for="tomo" label="Tomo" :error="$errors->first('tomo')" class="w-full lg:w-fit">

                    <x-input-text type="number" id="tomo" wire:model.lazy="tomo" />

                </x-input-group>

                <x-input-group for="registro" label="Registro" :error="$errors->first('registro')" class="w-full lg:w-fit">

                    <x-input-text type="number" id="registro" wire:model.lazy="registro" />

                </x-input-group>

                <x-input-group for="numero_propiedad" label="Número de propiedad" :error="$errors->first('numero_propiedad')" class="w-full lg:w-fit">

                    <x-input-text type="number" id="numero_propiedad" wire:model.lazy="numero_propiedad" />

                </x-input-group>

            </div>

        </div>

        <x-button-blue
            class="mx-auto"
            wire:click="buscar"
            wire:loading.attr="disabled"
            wire:target="buscar">

            <img wire:loading wire:target="buscar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

            Buscar

        </x-button-blue>

    </div>

    <div class="bg-white p-4 shadow-xl rounded-lg col-span-4 lg:w-1/2 mx-auto">

        <x-h4>Movimientos registrales</x-h4>

        <ul drag-root class="text-sm space-y-3 rounded-md" wire:loading.class.delay.longest="opacity-50">

            @if($movimientos)

                @foreach ($movimientos->sortBy('folio') as $movimiento)

                    <li
                        drag-item
                        draggable="true"
                        wire:sortable.item="{{ $movimiento->id }}"
                        wire:sortable.handle
                        wire:key="{{ $movimiento->id }}"
                        class="rounded-lg bg-gray-100 p-2 flex gap-4 items-center cursor-pointer">

                        Movimiento {{ $movimiento->folio }} ({{ ucfirst($movimiento->estado) }}): {{ $movimiento->servicio_nombre }}

                    </li>

                @endforeach

            @endif

        </ul>

    </div>

    @push('scripts')

        <script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v1.x.x/dist/livewire-sortable.js"></script>

        <script>

            document.addEventListener('livewire:init', () => {

                Livewire.on('cargar_ordenamiento', (event) => {

                    let root = document.querySelector('[drag-root]')

                    root.querySelectorAll('[drag-item]').forEach(el => {

                        el.addEventListener('dragstart', e => {

                            e.target.setAttribute('dragging', true);

                        })

                        el.addEventListener('drop', e => {

                            e.target.closest('li').classList.remove('bg-gray-300')

                            let dragging = root.querySelector('[dragging]')

                            Livewire.first().reaordenarMovimientos(dragging.getAttribute('wire:key'), e.target.getAttribute('wire:key'))

                        })

                        el.addEventListener('dragenter', e => {

                            e.target.closest('li').classList.add('bg-gray-300')

                            e.preventDefault()

                        })

                        el.addEventListener('dragover', e => e.preventDefault())

                        el.addEventListener('dragleave', e => {

                            e.target.closest('li').classList.remove('bg-gray-300')

                        })

                        el.addEventListener('dragend', e => {

                            e.target.removeAttribute('dragging');

                        })

                    })

                });

            });



        </script>

    @endpush

</div>
