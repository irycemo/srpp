<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-3 col-span-2 bg-white rounded-lg p-3">

    <span class="flex items-center justify-center text-lg text-gray-700 md:col-span-3 col-span-1 sm:col-span-2">Colindancias</span>

    <div class="mb-5 divide-y md:col-span-3 col-span-1 sm:col-span-2">

        @foreach ($medidas as $index => $medida)

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 items-start mb-2">

                <div class="flex-auto lg:col-span-2">

                    <div>

                        <label class="text-sm" >Viento</label>

                    </div>

                    <div>

                        <select class="bg-white rounded text-xs w-full" wire:model="medidas.{{ $index }}.viento">

                            <option value="" selected>Seleccione una opción</option>

                            @foreach ($vientos as $viento)

                                <option value="{{ $viento }}" selected>{{ $viento }}</option>

                            @endforeach

                        </select>

                    </div>

                    <div>

                        @error('medidas.' . $index . '.viento') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                    </div>

                </div>

                <div class="flex-auto lg:col-span-2">

                    <div>

                        <label class="text-sm" >Longitud (metros)</label>

                    </div>

                    <div>

                        <input type="number" min="0" class="bg-white rounded text-xs w-full" wire:model="medidas.{{ $index }}.longitud">

                    </div>

                    <div>

                        @error('medidas.' . $index . '.longitud') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                    </div>

                </div>

                <div class="flex-auto lg:col-span-7">

                    <div>

                        <label class="text-sm" >Descripción</label>

                    </div>

                    <div>

                        <textarea rows="1" class="bg-white rounded text-xs w-full" wire:model="medidas.{{ $index }}.descripcion"></textarea>

                    </div>

                    <div>

                        @error('medidas.' . $index . '.descripcion') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                    </div>

                </div>

                <div class="flex-auto lg:col-span-1 my-auto">

                    <x-button-red
                        wire:click="borrarColindancia({{ $index }})"
                        wire:loading.attr="disabled"
                    >

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>

                    </x-button-red>

                </div>

            </div>

        @endforeach

    </div>

    <div class="flex justify-end lg:col-span-3">

        <x-button-green
            wire:click="agregarColindancia"
            wire:loading.attr="disabled"
        >

            <img wire:loading wire:target="agregarColindancia" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

            Agregar colindancia

        </x-button-green>

    </div>

</div>
