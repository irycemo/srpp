<div>

    <div class="mb-2 flex justify-end">

        <x-button-blue wire:click="abrirModal">
            @if(!$actor->getKey())
                Agregar participante
            @else
                Editar
            @endif
        </x-button-blue>

    </div>

    <x-dialog-modal wire:model="modal">

        <x-slot name="title">

            @if($crear)
                Nuevo participante
            @elseif($editar)
                Editar participante
            @endif

        </x-slot>

        <x-slot name="content">

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 mb-3 col-span-2 rounded-lg p-3">

                <x-input-group for="nombre" label="Nombre(s)" :error="$errors->first('nombre')" class="w-full">

                    <x-input-text id="nombre" wire:model="nombre" :readonly="$editar && $actor->persona->nombre" />

                </x-input-group>

                <x-input-group for="ap_paterno" label="Apellido paterno" :error="$errors->first('ap_paterno')" class="w-full">

                    <x-input-text id="ap_paterno" wire:model="ap_paterno" :readonly="$editar && $actor->persona->ap_paterno" />

                </x-input-group>

                <x-input-group for="ap_materno" label="Apellido materno" :error="$errors->first('ap_materno')" class="w-full">

                    <x-input-text id="ap_materno" wire:model="ap_materno" :readonly="$editar && $actor->persona->ap_materno" />

                </x-input-group>

                <x-input-group for="rfc" label="RFC" :error="$errors->first('rfc')" class="w-full">

                    <x-input-text id="rfc" wire:model="rfc"/>

                </x-input-group>

                <x-input-group for="curp" label="CURP" :error="$errors->first('curp')" class="w-full">

                    <x-input-text id="curp" wire:model="curp"/>

                </x-input-group>

                <x-input-group for="razon_social" label="Razon social" :error="$errors->first('razon_social')" class="w-full">

                    <x-input-text id="razon_social" wire:model="razon_social" :readonly="$editar && $actor->persona->razon_social" />

                </x-input-group>

            </div>

            @include('livewire.comun.actores.modal-content')

        </x-slot>

        <x-slot name="footer">

            <div class="flex gap-3">

                @if($crear)

                    <x-button-blue
                        wire:click="guardar"
                        wire:loading.attr="disabled"
                        wire:target="guardar">

                        <img wire:loading wire:target="guardar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        <span>Guardar</span>
                    </x-button-blue>

                @elseif($editar)

                    <x-button-blue
                        wire:click="actualizar"
                        wire:loading.attr="disabled"
                        wire:target="actualizar">

                        <img wire:loading wire:target="actualizar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        <span>Actualizar</span>
                    </x-button-blue>

                @endif

                <x-button-red
                    wire:click="$toggle('modal')"
                    wire:loading.attr="disabled"
                    wire:target="$toggle('modal')"
                    type="button">
                    Cerrar
                </x-button-red>

            </div>

        </x-slot>

    </x-dialog-modal>

</div>
