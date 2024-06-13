@push('styles')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endpush

<div>

    <x-header>Varios</x-header>

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

    @if($vario->acto_contenido == 'PERSONAS MORALES')

        <div class="p-4 bg-white shadow-xl rounded-xl mb-5">

            <span class="flex items-center justify-center ext-gray-700 mb-5">Datos de la asignación de folio real de persona moral</span>

            <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto">

                <x-input-group for="denominacion" label="Denominación" :error="$errors->first('denominacion')" class="w-full">

                    <x-input-text id="denominacion" wire:model="denominacion" />

                </x-input-group>

                <x-input-group for="fecha_celebracion" label="Fecha de celebarción" :error="$errors->first('fecha_celebracion')" class="w-full">

                    <x-input-text type="date" id="fecha_celebracion" wire:model="fecha_celebracion" />

                </x-input-group>

                <x-input-group for="fecha_inscripcion" label="Fecha de inscripción" :error="$errors->first('fecha_inscripcion')" class="w-full">

                    <x-input-text type="datetime-local" id="fecha_inscripcion" wire:model="fecha_inscripcion" />

                </x-input-group>

            </div>

            <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto">

                <x-input-group for="notaria" label="Número de notaria" :error="$errors->first('notaria')" class="w-full">

                    <x-input-text type="number" id="notaria" wire:model="notaria" />

                </x-input-group>

                <x-input-group for="nombre_notario" label="Nombre del notario" :error="$errors->first('nombre_notario')" class="w-full">

                    <x-input-text id="nombre_notario" wire:model="nombre_notario" />

                </x-input-group>

                <x-input-group for="numero_escritura" label="Número de escritura" :error="$errors->first('numero_escritura')" class="w-full">

                    <x-input-text type="number" id="numero_escritura" wire:model="numero_escritura" />

                </x-input-group>

                <x-input-group for="numero_hojas" label="Número de hojas" :error="$errors->first('numero_hojas')" class="w-full">

                    <x-input-text type="number" id="numero_hojas" wire:model="numero_hojas" />

                </x-input-group>

            </div>

            <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto">

                <x-input-group for="descripcion" label="Descripción" :error="$errors->first('descripcion')" class="w-full">

                    <textarea rows="3" class="w-full bg-white rounded" wire:model="descripcion"></textarea>

                </x-input-group>

            </div>

            <div class="flex gap-3 items-center w-full lg:w-1/2 justify-center mx-auto">

                <x-input-group for="observaciones" label="Observaciones" :error="$errors->first('observaciones')" class="w-full">

                    <textarea rows="3" class="w-full bg-white rounded" wire:model="observaciones"></textarea>

                </x-input-group>

            </div>

        </div>

        <div class="p-4 bg-white shadow-xl rounded-xl mb-5">

            <div class="w-full lg:w-1/2 justify-center mx-auto">

                <span class="flex items-center justify-center text-gray-700">Participantes</span>

                <div class="">

                    <div class="mb-2 flex justify-end">

                        <x-button-blue wire:click="abrirModalCrear">Agregar participante</x-button-blue>

                    </div>

                    <div>

                        <x-table>

                            <x-slot name="head">
                                <x-table.heading >Participante</x-table.heading>
                                <x-table.heading ></x-table.heading>
                            </x-slot>

                            <x-slot name="body">

                                @foreach ($vario->actores as $participante)

                                    <x-table.row >

                                        <x-table.cell>{{ $participante->persona->razon_social }}</x-table.cell>
                                        <x-table.cell>
                                            <div class="flex items-center gap-3">
                                                <x-button-blue
                                                    wire:click="abrirModalEditarActor({{ $participante->id }})"
                                                    wire:loading.attr="disabled"
                                                >
                                                    Editar
                                                </x-button-blue>
                                                <x-button-red
                                                    wire:click="borrarActor({{ $participante->id }})"
                                                    wire:loading.attr="disabled">
                                                    Borrar
                                                </x-button-red>
                                            </div>
                                        </x-table.cell>

                                    </x-table.row>

                                @endforeach

                            </x-slot>

                            <x-slot name="tfoot"></x-slot>

                        </x-table>

                    </div>

                </div>

            </div>

        </div>

    @endif

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

    <x-dialog-modal wire:model="modal">

        <x-slot name="title">

            @if($editar)
                Editar
            @else
                Nuevo
            @endif

        </x-slot>

        <x-slot name="content">

            <div class="flex flex-col md:flex-row justify-between gap-3 mb-3">

                <x-input-group for="razon_social" label="Razon social" :error="$errors->first('razon_social')" class="w-full">

                    <x-input-text id="razon_social" wire:model="razon_social" />

                </x-input-group>

                <x-input-group for="rfc" label="RFC" :error="$errors->first('rfc')" class="w-full">

                    <x-input-text id="rfc" wire:model="rfc" />

                </x-input-group>

            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 mb-3 col-span-2 rounded-lg p-3">

                <span class="flex items-center justify-center text-lg text-gray-700 md col-span-1 sm:col-span-3">Domicilio</span>

                <x-input-group for="nacionalidad" label="Nacionalidad" :error="$errors->first('nacionalidad')" class="w-full">

                    <x-input-text id="nacionalidad" wire:model="nacionalidad" />

                </x-input-group>

                <x-input-group for="cp" label="Código postal" :error="$errors->first('cp')" class="w-full">

                    <x-input-text type="number" id="cp" wire:model="cp" />

                </x-input-group>

                <x-input-group for="entidad" label="Entidad" :error="$errors->first('entidad')" class="w-full">

                    <x-input-text id="entidad" wire:model="entidad" />

                </x-input-group>

                <x-input-group for="municipio" label="Municipio" :error="$errors->first('municipio')" class="w-full">

                    <x-input-text id="municipio" wire:model="municipio" />

                </x-input-group>

                <x-input-group for="ciudad" label="Ciudad" :error="$errors->first('ciudad')" class="w-full">

                    <x-input-text id="ciudad" wire:model="ciudad" />

                </x-input-group>

                <x-input-group for="colonia" label="Colonia" :error="$errors->first('colonia')" class="w-full">

                    <x-input-text id="colonia" wire:model="colonia" />

                </x-input-group>

                <x-input-group for="calle" label="Calle" :error="$errors->first('calle')" class="w-full">

                    <x-input-text id="calle" wire:model="calle" />

                </x-input-group>

                <x-input-group for="numero_exterior" label="Número exterior" :error="$errors->first('numero_exterior')" class="w-full">

                    <x-input-text id="numero_exterior" wire:model="numero_exterior" />

                </x-input-group>

                <x-input-group for="numero_interior" label="Número interior" :error="$errors->first('numero_interior')" class="w-full">

                    <x-input-text id="numero_interior" wire:model="numero_interior" />

                </x-input-group>

            </div>

        </x-slot>

        <x-slot name="footer">

            <div class="flex gap-3">

                @if($crear)

                <x-button-blue
                    wire:click="guardarActor"
                    wire:loading.attr="disabled"
                    wire:target="guardarActor">

                    <img wire:loading wire:target="guardarActor" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <span>Guardar</span>

                </x-button-blue>

                @elseif($editar)

                    <x-button-blue
                        wire:click="actualizarAcreedor"
                        wire:loading.attr="disabled"
                        wire:target="actualizarAcreedor">

                        <img wire:loading wire:target="actualizarAcreedor" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

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

        window.addEventListener('ver_documento', event => {

            const documento = event.detail[0].url;

            window.open(documento, '_blank');

        });

    </script>

@endpush
