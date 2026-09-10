<div>

    <x-header>Candidatos a folio real simplificado</x-header>

    <div class="mb-3 bg-white rounded-lg p-3 shadow-lg">

        <div class="flex gap-4 justify-center mb-5">

            <x-input-group for="distrito" label="Distrito" class="w-fit" :error="$errors->first('distrito')">

                <x-input-select wire:model.live="distrito">

                    <option value="">Distrito</option>

                    @foreach ($distritos as $key => $distrito_item)

                        <option value="{{ $key }}">{{ $distrito_item }}</option>

                    @endforeach

                </x-input-select>

            </x-input-group>

            <x-input-group for="tomo" label="Tomo" class="w-fit" :error="$errors->first('tomo')">

                <x-input-text id="tomo" wire:model="tomo"/>

            </x-input-group>

            <x-input-group for="registro" label="Registro" class="w-fit" :error="$errors->first('registro')">

                <x-input-text id="registro" wire:model="registro"/>

            </x-input-group>

            <x-input-group for="numero_propiedad" label="Número de propiedad" class="w-fit" :error="$errors->first('numero_propiedad')">

                <x-input-text id="numero_propiedad" wire:model="numero_propiedad"/>

            </x-input-group>

        </div>

        <div class="flex gap-4 items-center justify-center  mb-5">

            <div class="flex space-x-4 items-center">

                <x-checkbox wire:model="tomo_bis"></x-checkbox>

                <Label>Tomo bis</Label>

            </div>

            <div class="flex space-x-4 items-center">

                <x-checkbox wire:model="registro_bis"></x-checkbox>

                <Label>Registro bis</Label>

            </div>

        </div>

        <div class="flex gap-4 items-center justify-center">

            <x-button-blue
                wire:click="buscar"
                wire:loading.attr="disabled"
                wire:target="buscar">

                <img wire:loading wire:target="buscar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                Buscar

            </x-button-blue>

        </div>

    </div>

</div>
