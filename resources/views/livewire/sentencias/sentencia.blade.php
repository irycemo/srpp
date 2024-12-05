<div>

    <x-header>Sentencia <span class="text-sm tracking-widest">Folio real: {{ $sentencia->movimientoRegistral->folioReal->folio }}</span></x-header>

    @if($sentencia->acto_contenido == 'SENTENCIA RECTIFICACTORIA')

        @livewire('sentencias.rectificatoria', ['sentencia' => $this->sentencia])

    @elseif($sentencia->acto_contenido == 'CANCELACIÓN DE SENTENCIA')

        @livewire('sentencias.cancelatoria', ['sentencia' => $this->sentencia])

    @elseif(in_array($sentencia->acto_contenido, ['RESOLUCIÓN', 'DEMANDA', 'PROVIDENCIA PRECAUTORIA']))

        @livewire('sentencias.bloqueadora', ['sentencia' => $this->sentencia])

    @else

        <div class="p-4 bg-white shadow-xl rounded-xl mb-5">

            <span class="flex items-center justify-center ext-gray-700">Datos del movimiento</span>

            <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto mb-4">

                <x-input-group for="sentencia.acto_contenido" label="Acto contenido" :error="$errors->first('sentencia.acto_contenido')" class="w-full">

                    <x-input-select id="sentencia.acto_contenido" wire:model.live="sentencia.acto_contenido" class="w-full">

                        <option value="">Seleccione una opción</option>

                        @foreach ($actos as $acto)

                            <option value="{{ $acto }}">{{ $acto }}</option>

                        @endforeach

                    </x-input-select>

                </x-input-group>

            </div>

        </div>

    @endif

    <x-dialog-modal wire:model="modalDocumento" maxWidth="sm">

        <x-slot name="title">

            Subir archivo

        </x-slot>

        <x-slot name="content">

            <x-filepond wire:model.live="documento" accept="['application/pdf']"/>

            <div>

                @error('documento') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

            </div>

        </x-slot>

        <x-slot name="footer">

            <div class="flex gap-3">

                <x-button-blue
                    wire:click="guardarDocumento"
                    wire:loading.attr="disabled"
                    wire:target="guardarDocumento">

                    <img wire:loading wire:target="guardarDocumento" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <span>Guardar</span>

                </x-button-blue>

                <x-button-red
                    wire:click="$toggle('modalDocumento')"
                    wire:loading.attr="disabled"
                    wire:target="$toggle('modalDocumento')"
                    type="button">

                    <span>Cerrar</span>

                </x-button-red>

            </div>

        </x-slot>

    </x-dialog-modal>

</div>
