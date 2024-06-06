<div>

    <x-header>Cancelación de gravamen</x-header>

    {{-- <div class="p-4 bg-white shadow-xl rounded-xl mb-5">

        <span class="flex items-center justify-center  text-gray-700">Antecedente</span>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto">

            <x-input-group for="antecente_tomo" label="Tomo" :error="$errors->first('antecente_tomo')" class="w-full">

                <x-input-text type="number" id="antecente_tomo" wire:model="antecente_tomo" />

            </x-input-group>

            <x-input-group for="antecente_registro" label="Registro" :error="$errors->first('antecente_registro')" class="w-full">

                <x-input-text type="number" id="antecente_registro" wire:model="antecente_registro" />

            </x-input-group>

            <x-input-group for="antecente_distrito" label="Distrito" :error="$errors->first('antecente_distrito')" class="w-full">

                <x-input-select id="antecente_distrito" wire:model="antecente_distrito" class="w-full">

                    <option value="">Seleccione una opción</option>

                    @foreach ($distritos as $key => $distrito)

                        <option value="{{ $key }}">{{ $distrito }}</option>

                    @endforeach

                </x-input-select>

            </x-input-group>

        </div>

    </div> --}}

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

            <x-input-group for="vario.tipo" label="Tipo" :error="$errors->first('vario.tipo')" class="w-full col-span-2">

                <x-input-text id="vario.tipo" wire:model="vario.tipo" />

            </x-input-group>

        </div>

        <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto">

            <x-input-group for="vario.observaciones" label="Comentario del movimiento" :error="$errors->first('vario.observaciones')" class="w-full">

                <textarea rows="3" class="w-full bg-white rounded" wire:model="vario.observaciones"></textarea>

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

    <div class="bg-white rounded-lg p-3 flex justify-end shadow-lg">

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

            const documento = event.detail[0].vario;

            var url = "{{ route('varios.inscripcion.acto', '')}}" + "/" + documento;

            window.open(url, '_blank');

            window.location.href = "{{ route('varios')}}";

        });

    </script>

@endpush
