<div>

    <x-header>Sentencia  <span class="text-sm tracking-widest">Folio real: {{ $sentencia->movimientoRegistral->folioReal->folio }} - {{ $sentencia->movimientoRegistral->folio }}</span></x-header>

    @if($sentencia->acto_contenido == 'SENTENCIA RECTIFICATORIA')

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

    @include('livewire.comun.inscripciones.modal-guardar_documento_entrada_pdf')

    @filepondScripts

</div>
