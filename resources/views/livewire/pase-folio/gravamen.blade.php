<div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

        <div class=" gap-3 mb-3 col-span-2 bg-white rounded-lg p-3 shadow-lg">

            <span class="flex items-center justify-center text-lg text-gray-700 mb-5">Gravamenes</span>

            <div class="flex justify-end mb-2">

                <x-button-gray
                        wire:click="agregarGravamen"
                        wire:loading.attr="disabled"
                        wire:target="agregarGravamen">

                        <img wire:loading wire:target="agregarGravamen" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                        Agregar gravamen
                </x-button-gray>

            </div>

            <x-table>

                <x-slot name="head">
                    <x-table.heading >Estado</x-table.heading>
                    <x-table.heading >Acto contenido</x-table.heading>
                    <x-table.heading >Tomo</x-table.heading>
                    <x-table.heading >Registro</x-table.heading>
                    <x-table.heading >Distrito</x-table.heading>
                    <x-table.heading ></x-table.heading>
                </x-slot>

                <x-slot name="body">

                    @if($gravamenes)

                        @foreach ($gravamenes as $gravamen)

                            <x-table.row >

                                <x-table.cell>
                                    @if($gravamen->estado == 'activo')

                                        <span class="bg-green-400 py-1 px-2 rounded-full text-white text-xs">{{ ucfirst($gravamen->estado) }}</span>

                                    @elseif(!$gravamen->acreedores()->count())

                                        <span class="bg-yellow-400 py-1 px-2 rounded-full text-white text-xs">Incompleto</span>

                                    @elseif($gravamen->estado == 'inactivo')

                                        <span class="bg-yellow-400 py-1 px-2 rounded-full text-white text-xs">{{ ucfirst($gravamen->estado) }}</span>

                                    @elseif($gravamen->estado == 'cancelado')

                                        <span class="bg-red-400 py-1 px-2 rounded-full text-white text-xs">{{ ucfirst($gravamen->estado) }}</span>

                                    @elseif($gravamen->estado == 'parcial')

                                        <span class="bg-yellow-400 py-1 px-2 rounded-full text-white text-xs">{{ ucfirst($gravamen->estado) }}</span>

                                    @endif
                                </x-table.cell>
                                <x-table.cell>{{ $gravamen->acto_contenido }}</x-table.cell>
                                <x-table.cell>{{ $gravamen->movimientoRegistral->tomo_gravamen }}</x-table.cell>
                                <x-table.cell>{{ $gravamen->movimientoRegistral->registro_gravamen }}</x-table.cell>
                                <x-table.cell>{{ $gravamen->movimientoRegistral->distrito }}</x-table.cell>
                                <x-table.cell>
                                    <div class="flex items-center gap-3">
                                        <x-button-blue
                                            wire:click="$dispatch('openModal', { component: 'pase-folio.modal-gravamen', arguments: { editar: true, gravamen: {{ $gravamen->id }}, movimientoRegistral: {{ $gravamen->movimientoRegistral->id }} } } )"
                                            wire:loading.attr="disabled"
                                        >
                                            Editar
                                        </x-button-blue>

                                        @if(auth()->user()->ubicacion == 'Regional 4')

                                            <x-button-red
                                                    wire:click="abrirModalBorrar({{ $gravamen->id }})"
                                                    wire:loading.attr="disabled"
                                                >
                                                    Eliminar
                                            </x-button-red>

                                        @endif

                                        @if($gravamen->acreedores()->count() && !in_array($gravamen->estado, ['cancelado', 'parcial']))

                                            <x-button-red
                                                wire:click="abrirModalCancelar({{ $gravamen->id }})"
                                                wire:loading.attr="disabled"
                                            >
                                                Cancelar
                                            </x-button-red>

                                            <x-button-blue
                                                wire:click="abrirModalInactivar({{ $gravamen->id }})"
                                                wire:loading.attr="disabled"
                                            >
                                                Sin afectación
                                            </x-button-blue>

                                        @endif
                                    </div>
                                </x-table.cell>

                            </x-table.row>

                        @endforeach

                    @endif

                </x-slot>

                <x-slot name="tfoot"></x-slot>

            </x-table>

        </div>

        @include('livewire.pase-folio.informacion_base_datos')

    </div>

    <div class=" flex justify-end items-center bg-white rounded-lg p-2 shadow-lg gap-3">

        <x-button-red
            wire:click="$parent.finalizarPaseAFolio"
            wire:loading.attr="disabled">

            <img wire:loading class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
            Finalizar pase a folio

        </x-button-red>

    </div>

    <x-dialog-modal wire:model="modalCancelacion" maxWidth="sm">

        <x-slot name="title">

            Cancelar gravamen

        </x-slot>

        <x-slot name="content">

            <x-input-group for="tomo_cancelacion" label="Tomo de cancelación" :error="$errors->first('tomo_cancelacion')" class="w-full">

                <x-input-text type="number"  id="tomo_cancelacion" wire:model="tomo_cancelacion" />

            </x-input-group>

            <x-input-group for="folio_cancelacion" label="Registro de cancelación" :error="$errors->first('folio_cancelacion')" class="w-full">

                <x-input-text  type="number" id="folio_cancelacion" wire:model="folio_cancelacion" />

            </x-input-group>

            <x-input-group for="tipo_cancelacion" label="Tipo de cancelación" :error="$errors->first('tipo_cancelacion')" class="w-full">

                <x-input-select id="tipo_cancelacion" wire:model="tipo_cancelacion" class="w-full">

                    <option value="">Seleccione una opción</option>
                    <option value="total">Total</option>
                    <option value="parcial">Parcial</option>

                </x-input-select>

            </x-input-group>

        </x-slot>

        <x-slot name="footer">

            <div class="flex gap-3">

                <x-button-blue
                    wire:click="cancelar"
                    wire:loading.attr="disabled"
                    wire:target="cancelar">

                    <img wire:loading wire:target="cancelar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <span>Cancelar</span>
                </x-button-blue>

                <x-button-red
                    wire:click="$toggle('modalCancelacion')"
                    wire:target="$toggle('modalCancelacion')"
                    type="button">
                    Cerrar
                </x-button-red>

            </div>

        </x-slot>

    </x-dialog-modal>

    <x-confirmation-modal wire:model="modalBorrar" maxWidth="sm">

        <x-slot name="title">
            Eliminar gravamen
        </x-slot>

        <x-slot name="content">
            ¿Esta seguro que desea eliminar el gravamen? No sera posible recuperar la información.
        </x-slot>

        <x-slot name="footer">

            <x-secondary-button
                wire:click="$toggle('modalBorrar')"
                wire:loading.attr="disabled"
            >
                No
            </x-secondary-button>

            <x-danger-button
                class="ml-2"
                wire:click="borrar"
                wire:loading.attr="disabled"
                wire:target="borrar"
            >

                <img wire:loading wire:target="borrar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                Borrar
            </x-danger-button>

        </x-slot>

    </x-confirmation-modal>

    <x-dialog-modal wire:model="modalInactivar" maxWidth="sm">

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
                    wire:click="inactivar"
                    wire:loading.attr="disabled"
                    wire:target="inactivar">

                    <img wire:loading wire:target="inactivar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <span>Ingresar contraseña</span>
                </x-button-blue>

                <x-button-red
                    wire:click="$toggle('modalInactivar')"
                    wire:loading.attr="disabled"
                    wire:target="$toggle('modalInactivar')"
                    type="button">
                    Cerrar
                </x-button-red>

            </div>

        </x-slot>

    </x-dialog-modal>

</div>
