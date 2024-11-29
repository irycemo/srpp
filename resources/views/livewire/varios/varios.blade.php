@push('styles')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endpush

<div>

    <x-header>Varios</x-header>

    @if($vario->acto_contenido == 'PERSONAS MORALES')

        @livewire('varios.personas-morales', ['vario' => $this->vario])

    @elseif($vario->acto_contenido == 'DONACIÓN / VENTA DE USUFRUCTO')

        @livewire('varios.donacion-usufructo', ['vario' => $this->vario])

    {{-- Aclaración administrativa --}}
    @elseif($vario->servicio == 'D112')

        @livewire('varios.aclaracion-administrativa', ['vario' => $this->vario])

    {{-- Consolidación de usufructo --}}
    @elseif($vario->servicio == 'D128')

        @livewire('varios.consolidacion-usufructo', ['vario' => $this->vario])

    {{-- Primer aviso preventivo --}}
    @elseif($vario->servicio == 'D146')

        @livewire('varios.primer-aviso-preventivo', ['vario' => $this->vario])

    {{-- Fideicomiso --}}
    @elseif($vario->servicio == 'D149')

        @livewire('varios.fideicomiso', ['vario' => $this->vario])

    @else

        <div class="p-4 bg-white shadow-xl rounded-xl mb-5">

            <span class="flex items-center justify-center ext-gray-700">Datos del movimiento</span>

            <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto mb-4">

                <x-input-group for="vario.acto_contenido" label="Acto contenido" :error="$errors->first('vario.acto_contenido')" class="w-full">

                    <x-input-select id="vario.acto_contenido" wire:model.live="vario.acto_contenido" class="w-full">

                        <option value="">Seleccione una opción</option>

                        @foreach ($actos as $acto)

                            <option value="{{ $acto }}">{{ $acto }}</option>

                        @endforeach

                    </x-input-select>

                </x-input-group>

            </div>

            <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto">

                <x-input-group for="vario.descripcion" label="Comentario del movimiento" :error="$errors->first('vario.descripcion')" class="w-full">

                    <textarea rows="3" class="w-full bg-white rounded" wire:model="vario.descripcion"></textarea>

                </x-input-group>

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

            @if(!$vario->movimientoRegistral->documentoEntrada())

                <x-button-blue
                    wire:click="abrirModalFinalizar"
                    wire:loading.attr="disabled"
                    wire:target="abrirModalFinalizar">

                    <img wire:loading wire:target="abrirModalFinalizar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    Subir documento de entrada

                </x-button-blue>

            @else

                <div class="inline-block">

                    <x-link-blue target="_blank" href="{{ $vario->movimientoRegistral->documentoEntrada() }}">Documento de entrada</x-link-blue>

                </div>

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

    @endif

</div>

@push('scripts')

    <script>

        window.addEventListener('imprimir_documento', event => {

            const documento = event.detail[0].vario;

            var url = "{{ route('varios.inscripcion.acto', '')}}" + "/" + documento;

            window.open(url, '_blank');

            window.location.href = "{{ route('varios')}}";

        });

        window.addEventListener('ver_documento', event => {

            const documento = event.detail[0].url;

            window.open(documento, '_blank');

        });

    </script>

@endpush
