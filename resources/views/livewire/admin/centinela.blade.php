<div>

    <x-header>Centinela</x-header>

    <div class="flex justify-between mb-5">

        <div class="flex">

            <input type="number" placeholder="Folio real" min="1" class="bg-white rounded-l w-24 text-sm focus:ring-0 @error('folio') border-red-500 @enderror " wire:model="folio">

            <button
                wire:click="buscarFolioReal"
                wire:loading.attr="disabled"
                wire:target="buscarFolioReal"
                type="button"
                class="relative bg-blue-400 hover:shadow-lg text-white font-bold px-4 rounded-r text-sm hover:bg-blue-700 focus:outline-blue-400 focus:outline-offset-2">

                {{-- <img wire:loading wire:target="buscarFolioReal" class="mx-auto h-5 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading"> --}}

                <div wire:loading.flex class="flex absolute top-2 right-2 items-center">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>

                <svg wire:loading.remove xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>

            </button>

        </div>

    </div>

    <div>

        @if($folioReal)

            <div class="bg-white p-4 rounded-lg mb-3 flex gap-3 items-center justify-between shadow-lg">

                <h4 class="text-xl">Folio real: {{ $folioReal->folio }} <span class="text-sm tracking-widest capitalize">({{ $folioReal->estado }}) @if($folioReal->matriz) matriz @endif</span></h4>

                <x-button-red
                    wire:click="abrirModal"
                    wire:target="abrirModal"
                    wire:loading.attr="disabled">

                    Bloquear / Desbloquear

                </x-button-red>

            </div>

            @if($folioReal->bloqueos->count())

                <div class="bg-white p-4 rounded-lg mb-3 gap-3 items-center justify-between shadow-lg">

                    <span class="flex items-center justify-center text-lg text-gray-700 mb-5">Bloqueos</span>

                    <x-table>

                        <x-slot name="head">
                            <x-table.heading >Tipo</x-table.heading>
                            <x-table.heading >Observaciones bloqueo</x-table.heading>
                            <x-table.heading >Observaciones desbloqueo</x-table.heading>
                            <x-table.heading >Registro</x-table.heading>
                            <x-table.heading >Actualizado</x-table.heading>
                        </x-slot>

                        <x-slot name="body">

                            @foreach ($folioReal->bloqueos->reverse() as $bloqueo)

                                <x-table.row >

                                    <x-table.cell class="capitalize">{{ $bloqueo->tipo }}</x-table.cell>
                                    <x-table.cell>{{ $bloqueo->observaciones }}</x-table.cell>
                                    <x-table.cell>{{ $bloqueo->observaciones_desbloqueo ?? 'N/A' }}</x-table.cell>
                                    <x-table.cell>
                                        <span class="font-semibold">@if($bloqueo->creadoPor != null)Bloqueado por: {{$bloqueo->creadoPor->name}} @else Bloqueado: @endif</span> <br>

                                        {{ $bloqueo->created_at }}
                                    </x-table.cell>
                                    <x-table.cell>
                                        <span class="font-semibold">@if($bloqueo->actualizadoPor != null)Desbloqueado por: {{$bloqueo->actualizadoPor->name}} @else Desbloqueado: @endif</span> <br>

                                        {{ $bloqueo->updated_at }}
                                    </x-table.cell>

                                </x-table.row>

                            @endforeach

                        </x-slot>

                        <x-slot name="tfoot"></x-slot>

                    </x-table>

                </div>

            @endif

            @includeIf('livewire.consulta.descripcion')

            @includeIf('livewire.consulta.ubicacion')

            @includeIf('livewire.consulta.propietarios')

            <x-dialog-modal wire:model="modal" maxWidth="sm">

                <x-slot name="title">

                    Bloquear / Desbloquear

                </x-slot>

                <x-slot name="content">

                    <div class="space-y-2">

                        @if($folioReal->estado == 'activo')

                            <x-input-group for="tipo" label="Tipo" :error="$errors->first('tipo')" class="w-full">

                                <x-input-select id="tipo" wire:model="tipo" class="w-full">

                                    <option value="">Seleccione una opción</option>
                                    <option value="centinela">Centinela</option>
                                    <option value="bloqueado">Normal</option>

                                </x-input-select>

                            </x-input-group>

                        @endif

                        <x-input-group for="observaciones" label="Observaciones" :error="$errors->first('observaciones')" class="w-full">

                            <textarea autofocus="false" class="bg-white rounded text-xs w-full " rows="4" wire:model="observaciones" placeholder="Se lo mas especifico posible acerca del bloqueo o desbloqueo."></textarea>

                        </x-input-group>

                    </div>

                </x-slot>

                <x-slot name="footer">

                    <div class="flex gap-3">

                        @if($folioReal->estado == 'activo')

                            <x-button-blue
                                wire:click="procesar"
                                wire:loading.attr="disabled"
                                wire:target="procesar">

                                <img wire:loading wire:target="procesar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                                <span>Bloquear</span>
                            </x-button-blue>

                        @elseif(in_array($folioReal->estado, ['bloqueado', 'centinela']))

                            <x-button-blue
                                wire:click="procesar"
                                wire:loading.attr="disabled"
                                wire:target="procesar">

                                <img wire:loading wire:target="procesar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                                <span>Desbloquear</span>
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

        @endif

    </div>

</div>
