<div>

    <x-header>Cancelación de gravamen  <span class="text-sm tracking-widest">Folio real: {{ $cancelacion->movimientoRegistral->folioReal->folio }} - {{ $cancelacion->movimientoRegistral->folio }}</span></x-header>

    @include('livewire.comun.documento_entrada_campos')

    <div class="p-4 bg-white shadow-xl rounded-xl mb-5">

        <span class="flex items-center justify-center ext-gray-700">Datos del movimiento</span>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto mb-4">

            <x-input-group for="cancelacion.acto_contenido" label="Acto contenido" :error="$errors->first('cancelacion.acto_contenido')" class="w-full">

                <x-input-select id="cancelacion.acto_contenido" wire:model.live="cancelacion.acto_contenido" class="w-full">

                    <option value="">Seleccione una opción</option>
                    @foreach ($actos as $acto)

                        <option value="{{ $acto }}">{{ $acto }}</option>

                    @endforeach

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

                        <x-input-group for="gravamen-acto" label="Acto del gravamen" class="w-full col-span-2">

                            <x-input-text id="gravamen-acto" value="{{ $gravamenCancelarMovimiento->gravamen->acto_contenido }}" readonly/>

                        </x-input-group>

                        <x-input-group for="gravamen-tipo" label="Tipo de gravamen" class="w-full col-span-2">

                            <x-input-text id="gravamen-tipo" value="{{ $gravamenCancelarMovimiento->gravamen->tipo }}" readonly/>

                        </x-input-group>

                    </div>

                    <div class="flex gap-3 items-center justify-center mx-auto mb-4">

                        <x-input-group for="gravamen-valor" label="Valor de gravamen" class="w-full col-span-2">

                            <x-input-text id="gravamen-valor" value="{{ $gravamenCancelarMovimiento->gravamen->valor_gravamen }}" readonly/>

                        </x-input-group>

                        <x-input-group for="gravamen-fecha" label="Fecha de inscripción" class="w-full col-span-2">

                            <x-input-text id="gravamen-fecha" value="{{ $gravamenCancelarMovimiento->gravamen->fecha_inscripcion }}" readonly/>

                        </x-input-group>

                    </div>

                    <x-input-group for="gravamen-observacion" label="Observaciones" class="w-full col-span-2">

                        <textarea rows="5" class="bg-white rounded text-sm w-full" readonly>{{ $gravamenCancelarMovimiento->gravamen->observaciones }}</textarea>

                    </x-input-group>

                </div>

                @if($cancelacion->acto_contenido == 'CANCELACIÓN PARCIAL DE GRAVAMEN')

                    <div class="gap-3 items-center justify-center mx-auto mb-4 w-min">

                        <Label class="text-base tracking-widest rounded-xl border-gray-500">Parcialidad del valor</Label>

                        <input type="number" class="bg-white rounded text-sm w-full" wire:model="valor">

                    </div>

                @endif

            @else

                    <div class="rounded-lg bg-red-200 lg:w-1/2 mx-auto text-center text-red-700 py-3">

                        El folio real no tiene el gravamen con tomo {{ $this->cancelacion->movimientoRegistral->tomo_gravamen }} registro {{ $this->cancelacion->movimientoRegistral->registro_gravamen }}

                    </div>

            @endif

        </div>

    </div>

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

        @if(!$cancelacion->movimientoRegistral->documentoEntrada())

            <x-button-blue
                wire:click="abrirModalDocumentoEntrada"
                wire:loading.attr="disabled"
                wire:target="abrirModalDocumentoEntrada">

                <img wire:loading wire:target="abrirModalDocumentoEntrada" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                Subir documento de entrada

            </x-button-blue>

        @else

            <div class="inline-block">

                <x-link-blue target="_blank" href="{{ $cancelacion->movimientoRegistral->documentoEntrada() }}">Documento de entrada</x-link-blue>

            </div>

            <x-button-red
                wire:click="eliminarDocumentoEntradaPDF"
                wire:confirm="¿Esta seguro que desea eliminar el documento de entrada?"
                wire:loading.attr="disabled"
                wire:target="eliminarDocumentoEntradaPDF">

                <img wire:loading wire:target="eliminarDocumentoEntradaPDF" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                Eliminar documento de entrada

            </x-button-red>

        @endif

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

    @include('livewire.comun.inscripciones.modal-guardar_documento_entrada_pdf')

    @filepondScripts

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
