<div>

    <div class="p-4 bg-white shadow-xl rounded-xl mb-5 space-y-2">

        <span class="flex items-center justify-center ext-gray-700">Datos del movimiento</span>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto">

            <x-input-group for="sentencia.acto_contenido" label="Acto contenido" :error="$errors->first('sentencia.acto_contenido')" class="w-full">

                <x-input-select id="sentencia.acto_contenido" wire:model.live="sentencia.acto_contenido" class="w-full">

                    <option value="">Seleccione una opción</option>

                    @foreach ($actos as $acto)

                        <option value="{{ $acto }}">{{ $acto }}</option>

                    @endforeach

                </x-input-select>

            </x-input-group>

        </div>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto">

            <x-input-group for="sentencia.tipo" label="Tipo" :error="$errors->first('sentencia.tipo')" class="w-full">

                <x-input-text id="sentencia.tipo" wire:model="sentencia.tipo" />

            </x-input-group>

        </div>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto ">

            <x-input-group for="sentencia.expediente" label="Expediente" :error="$errors->first('sentencia.expediente')" class="w-full">

                <x-input-text id="sentencia.expediente" wire:model="sentencia.expediente" />

            </x-input-group>

            <x-input-group for="sentencia.hojas" label="Hojas" :error="$errors->first('sentencia.hojas')" class="w-full">

                <x-input-text type="number" id="sentencia.hojas" wire:model="sentencia.hojas" />

            </x-input-group>

            <x-input-group for="sentencia.tomo" label="Tomo" :error="$errors->first('sentencia.tomo')" class="w-full">

                <x-input-text id="sentencia.tomo" wire:model="sentencia.tomo" />

            </x-input-group>

            <x-input-group for="sentencia.registro" label="Registro" :error="$errors->first('sentencia.registro')" class="w-full">

                <x-input-text id="sentencia.registro" wire:model="sentencia.registro" />

            </x-input-group>

        </div>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto">

            <x-input-group for="sentencia.descripcion" label="Comentario del movimiento" :error="$errors->first('sentencia.descripcion')" class="w-full">

                <textarea rows="3" class="w-full bg-white rounded" wire:model="sentencia.descripcion"></textarea>

            </x-input-group>

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

        @if(!$sentencia->movimientoRegistral->documentoEntrada())

            <x-button-blue
                wire:click="abrirModalFinalizar"
                wire:loading.attr="disabled"
                wire:target="abrirModalFinalizar">

                <img wire:loading wire:target="abrirModalFinalizar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                Subir documento de entrada

            </x-button-blue>

        @else

            <div class="inline-block">

                <x-link-blue target="_blank" href="{{ $sentencia->movimientoRegistral->documentoEntrada() }}">Documento de entrada</x-link-blue>

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

</div>
