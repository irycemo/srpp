<x-dialog-modal wire:model="modalReasignarUsuario" maxWidth="sm">

    <x-slot name="title">

        Reasignar usuario

    </x-slot>

    <x-slot name="content">

        <div class="flex flex-col md:flex-row justify-between gap-3 mb-3">

            <x-input-group for="modelo_editar.usuario_asignado" label="Área" :error="$errors->first('modelo_editar.usuario_asignado')" class="w-full">

                <x-input-select id="modelo_editar.usuario_asignado" wire:model="modelo_editar.usuario_asignado" class="w-full">

                    <option value="">Seleccione una opción</option>

                    @foreach ($usuarios as $usuario)

                        <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>

                    @endforeach

                </x-input-select>

            </x-input-group>

        </div>

    </x-slot>

    <x-slot name="footer">

        <div class="flex gap-3">

            <x-button-blue
                wire:click="reasignarUsuario"
                wire:loading.attr="disabled"
                wire:target="reasignarUsuario">

                <img wire:loading wire:target="reasignarUsuario" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                <span>Reasignar</span>
            </x-button-blue>

            <x-button-blue
                wire:click="reasignarUsuarioAleatoriamente"
                wire:loading.attr="disabled"
                wire:target="reasignarUsuarioAleatoriamente">

                <img wire:loading wire:target="reasignarUsuarioAleatoriamente" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                <span>Aleatorio</span>
            </x-button-blue>

            <x-button-red
                wire:click="$toggle('modalReasignarUsuario')"
                wire:loading.attr="disabled"
                wire:target="$toggle('modalReasignarUsuario')"
                type="button">
                Cerrar
            </x-button-red>

        </div>

    </x-slot>

</x-dialog-modal>