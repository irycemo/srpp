<div>

    <x-header>Cancelación de gravamen</x-header>

    <div class="p-4 bg-white shadow-xl rounded-xl mb-5">

        <div class="inline-block">

            <x-button-blue
                wire:click="consultarArchivo"
                wire:loading.attr="disabled"
                wire:target="consultarArchivo">

                <img wire:loading wire:target="consultarArchivo" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                Ver documento de entrada
            </x-button-blue>

        </div>

        <span class="flex items-center justify-center ext-gray-700">Datos del movimiento</span>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto mb-4">

            <x-input-group for="cancelacion.acto_contenido" label="Acto contenido" :error="$errors->first('cancelacion.acto_contenido')" class="w-full">

                <x-input-select id="cancelacion.acto_contenido" wire:model.live="cancelacion.acto_contenido" class="w-full">

                    <option value="">Seleccione una opción</option>
                    <option value="PARCIAL">Parcial</option>
                    <option value="TOTAL">Total</option>

                </x-input-select>

            </x-input-group>

            <x-input-group for="cancelacion.tipo" label="Tipo" :error="$errors->first('cancelacion.tipo')" class="w-full col-span-2">

                <x-input-text id="cancelacion.tipo" wire:model="cancelacion.tipo" />

            </x-input-group>

        </div>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto">

            <x-input-group for="cancelacion.observaciones" label="Comentario del movimiento" :error="$errors->first('cancelacion.observaciones')" class="w-full">

                <textarea rows="3" class="w-full bg-white rounded" wire:model="cancelacion.observaciones"></textarea>

            </x-input-group>

        </div>

    </div>

    <div class="p-4 bg-white shadow-xl rounded-xl mb-5">

        <div class="w-full  justify-center mx-auto">

            @if($gravamenCancelarMovimiento)

                <span class="flex items-center justify-center ext-gray-700 mb-4">Gravamen a cancelar ({{ $cancelacion->movimientoRegistral->folioReal->folio }}-{{ $gravamenCancelarMovimiento->folio }})</span>

                <div class="lg:w-1/2 mx-auto">

                    <div class="flex gap-3 items-center justify-center mx-auto mb-4">

                        <input type="text" value="{{ $gravamenCancelarMovimiento->gravamen->acto_contenido }}" class="bg-white rounded text-sm w-full" readonly>

                        <input type="text" value="{{ $gravamenCancelarMovimiento->gravamen->tipo }}" class="bg-white rounded text-sm w-full" readonly>

                    </div>

                    <div class="flex gap-3 items-center justify-center mx-auto mb-4">

                        <input type="text" value="{{ $gravamenCancelarMovimiento->gravamen->valor_gravamen }}" class="bg-white rounded text-sm w-full" readonly>

                        <input type="text" value="{{ $gravamenCancelarMovimiento->gravamen->fecha_inscripcion }}" class="bg-white rounded text-sm w-full" readonly>

                    </div>

                    <textarea class="bg-white rounded text-sm w-full" readonly>{{ $gravamenCancelarMovimiento->gravamen->observaciones }}</textarea>

                </div>

                @if($cancelacion->acto_contenido == 'PARCIAL')

                    <div class="gap-3 items-center justify-center mx-auto mb-4 w-min">

                        <Label class="text-base tracking-widest rounded-xl border-gray-500">Parcialidad del valor</Label>

                        <input type="number" class="bg-white rounded text-sm w-full" wire:model="valor">

                    </div>

                @endif

            @endif

        </div>

    </div>

    <x-dialog-modal wire:model="modalContraseña" maxWidth="sm">

        <x-slot name="title">

            Ingresa tu contraseña

        </x-slot>

        <x-slot name="content">

            <x-input-group for="contraseña" label="Contraseña" :error="$errors->first('contraseña')" class="w-full">

                <x-input-text type="password" id="contraseña" wire:model="contraseña" />

            </x-input-group>

        </x-slot>

        <x-slot name="footer">

            <div class="flex gap-3">

                <x-button-blue
                    wire:click="inscribir"
                    wire:loading.attr="disabled"
                    wire:target="inscribir">

                    <img wire:loading wire:target="inscribir" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <span>Ingresar contraseña</span>
                </x-button-blue>

                <x-button-red
                    wire:click="$toggle('modalContraseña')"
                    wire:loading.attr="disabled"
                    wire:target="$toggle('modalContraseña')"
                    type="button">
                    Cerrar
                </x-button-red>

            </div>

        </x-slot>

    </x-dialog-modal>

    @if(count($errors) > 0)

        <div class="mb-5 bg-white rounded-lg p-2 shadow-lg flex gap-2 flex-wrap ">

            <ul class="flex gap-2 felx flex-wrap list-disc ml-5">
            @foreach ($errors->all() as $error)

                <li class="text-red-500 text-xs md:text-sm ml-5">
                    {{ $error }}
                </li>

            @endforeach

        </ul>

        </div>

    @endif

    <div class="bg-white rounded-lg p-3 flex justify-end shadow-lg gap-3">

        <x-button-blue
            wire:click="guardar"
            wire:loading.attr="disabled"
            wire:target="guardar">

            <img wire:loading wire:target="guardar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

            Guardar y continuar

        </x-button-blue>

        <x-button-green
            wire:click="finalizar"
            wire:loading.attr="disabled"
            wire:target="finalizar">

            <img wire:loading wire:target="finalizar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

            Finalizar inscripción

        </x-button-green>

    </div>

</div>

@push('scripts')

    <script>

        window.addEventListener('imprimir_documento', event => {

            const documento = event.detail[0].cancelacion;

            var url = "{{ route('cancelacion.inscripcion.acto', '')}}" + "/" + documento;

            window.open(url, '_blank');

            window.location.href = "{{ route('cancelacion')}}";

        });

        window.addEventListener('ver_documento', event => {

            const documento = event.detail[0].url;

            window.open(documento, '_blank');

        });

    </script>

@endpush
