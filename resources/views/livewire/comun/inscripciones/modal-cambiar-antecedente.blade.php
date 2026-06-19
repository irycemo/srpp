<x-dialog-modal wire:model="modal_cambiar_antecedente" maxWidth="sm">

    <x-slot name="title">
        Cambiar antecedente
    </x-slot>

    <x-slot name="content">

        <div class="flex flex-col md:flex-row justify-between gap-3 mb-3">

            <x-input-group for="folio_real_cambiar_atecendente" label="Folio real" :error="$errors->first('folio_real_cambiar_atecendente')" class="w-full">

                <x-input-text type="number" id="folio_real_cambiar_atecendente" wire:model.lazy="folio_real_cambiar_atecendente" />

            </x-input-group>

            <x-input-group for="tomo_cambiar_atecendente" label="Tomo" :error="$errors->first('tomo_cambiar_atecendente')" class="w-full">

                <x-input-text type="number" id="tomo_cambiar_atecendente" wire:model.lazy="tomo_cambiar_atecendente" />

            </x-input-group>

            <x-input-group for="registro_cambiar_atecendente" label="Registro" :error="$errors->first('registro_cambiar_atecendente')" class="w-full">

                <x-input-text type="number" id="registro_cambiar_atecendente" wire:model.lazy="registro_cambiar_atecendente" />

            </x-input-group>

        </div>

        <div class="flex flex-col md:flex-row justify-between gap-3 mb-3">

            <x-input-group for="numero_propiedad_cambiar_atecendente" label="Número de propiedad" :error="$errors->first('numero_propiedad_cambiar_atecendente')" class="w-full">

                <x-input-text type="number" id="numero_propiedad_cambiar_atecendente" wire:model.lazy="numero_propiedad_cambiar_atecendente" />

            </x-input-group>

            <x-input-group for="distrito_cambiar_atecendente" label="Distrito" :error="$errors->first('distrito_cambiar_atecendente')" class="w-full">

                <x-input-select id="distrito_cambiar_atecendente" wire:model.live="distrito_cambiar_atecendente" class="w-full">

                    <option value="">Seleccione una opción</option>

                    @foreach ($distritos as $key => $distrito)
                        <option value="{{ $key }}">{{ $distrito }}</option>
                    @endforeach

                </x-input-select>

            </x-input-group>

        </div>

    </x-slot>

    <x-slot name="footer">

        <div class="flex gap-3">

            <x-button-blue
                wire:click="cambiarAntecedente()"
                wire:loading.attr="disabled"
                wire:target="cambiarAntecedente()">

                <img wire:loading wire:target="cambiarAntecedente()" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                <span>Cambiar antecedente</span>
            </x-button-blue>

            <x-button-red
                wire:click="$toggle('modal_cambiar_antecedente')"
                wire:loading.attr="disabled"
                wire:target="$toggle('modal_cambiar_antecedente')"
                type="button">
                Cerrar
            </x-button-red>

        </div>

    </x-slot>

</x-dialog-modal>