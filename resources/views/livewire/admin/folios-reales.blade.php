<div class="">

    <div class="mb-6">

        <x-header>Folios reales I</x-header>

        <div class="flex justify-between">

            <div class="flex gap-3">

                <input type="number" wire:model.live.debounce.500ms="filters.folio" placeholder="Folio" class="bg-white rounded-full text-sm">

                <select class="bg-white rounded-full text-sm" wire:model.live="filters.estado">

                    <option value="activo">Activo</option>
                    <option value="elaborado">Elaborado</option>
                    <option value="rechazado">Rechazado</option>
                    <option value="bloqueado">Bloqueado</option>

                </select>

                <input type="number" wire:model.live.debounce.500ms="filters.tomo" placeholder="Tomo" class="bg-white rounded-full text-sm">

                <input type="number" wire:model.live.debounce.500ms="filters.registro" placeholder="Registro" class="bg-white rounded-full text-sm">

                <input type="number" wire:model.live.debounce.500ms="filters.distrito" placeholder="Distrito" class="bg-white rounded-full text-sm">

                <x-input-select class="bg-white rounded-full text-sm w-min" wire:model.live="pagination">

                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>

                </x-input-select>

            </div>

        </div>

    </div>

    <div class="overflow-x-auto rounded-lg shadow-xl border-t-2 border-t-gray-500">

        <x-table>

            <x-slot name="head">

                <x-table.heading sortable wire:click="sortBy('folio')" :direction="$sort === 'folio' ? $direction : null" >Folio</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('estado')" :direction="$sort === 'estado' ? $direction : null" >estado</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('tomo_antecedente')" :direction="$sort === 'tomo_antecedente' ? $direction : null" >Tomo</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('registro_antecedente')" :direction="$sort === 'registro_antecedente' ? $direction : null" >Registro</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('numero_propiedad_antecedente')" :direction="$sort === 'numero_propiedad_antecedente' ? $direction : null" ># Propiedad</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('distrito_antecedente')" :direction="$sort === 'distrito_antecedente' ? $direction : null" >Distrito</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('created_at')" :direction="$sort === 'created_at' ? $direction : null">Registro</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('updated_at')" :direction="$sort === 'updated_at' ? $direction : null">Actualizado</x-table.heading>
                @if(auth()->user()->hasRole('Administrador'))
                    <x-table.heading >Acciones</x-table.heading>
                @endif

            </x-slot>

            <x-slot name="body">

                @forelse ($folios as $folio)

                    <x-table.row wire:loading.class.delaylongest="opacity-50" wire:key="row-{{ $folio->id }}">

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Folio</span>

                            {{ $folio->folio }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Estado</span>

                            <span class="bg-{{ $folio->estado_color }} py-1 px-2 rounded-full text-white text-xs">{{ ucfirst($folio->estado) }}</span>

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Tomo</span>

                            {{ $folio->tomo_antecedente ?? 'N/A' }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Registro</span>

                            {{ $folio->registro_antecedente ?? 'N/A' }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl"># Propiedad</span>

                            {{ $folio->numero_propiedad_antecedente ?? 'N/A' }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Distrito</span>

                            {{ App\Constantes\Constantes::DISTRITOS[$folio->distrito_antecedente] }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Registrado</span>


                            <span class="font-semibold">@if($folio->creadoPor != null)Registrado por: {{$folio->creadoPor->name}} @else Registro: @endif</span> <br>

                            {{ $folio->created_at }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="font-semibold">@if($folio->actualizadoPor != null)Actualizado por: {{$folio->actualizadoPor->name}} @else Actualizado: @endif</span> <br>

                            {{ $folio->updated_at }}

                        </x-table.cell>

                        @if(auth()->user()->hasRole('Administrador'))

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Acciones</span>

                                <div class="ml-3 relative" x-data="{ open_drop_down:false }">

                                    <div>

                                        <button x-on:click="open_drop_down=true" type="button" class="rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">

                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                            </svg>

                                        </button>

                                    </div>

                                    <div x-cloak x-show="open_drop_down" x-on:click="open_drop_down=false" x-on:click.away="open_drop_down=false" class="z-50 origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu">

                                        @if($folio->estado != 'captura')

                                            @can('Envia a captura')

                                                <button
                                                    wire:click="enviarCaptura({{ $folio->id }})"
                                                    wire:loading.attr="disabled"
                                                    class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                    role="menuitem">
                                                    Enviar a captura
                                                </button>

                                            @endif

                                        @endif

                                       {{--  @if(in_array($folio->estado, ['nuevo', 'captura']))

                                            @can('Reasignar folio')

                                                <button
                                                    wire:click="abrirModalReasignar({{ $folio->id }})"
                                                    wire:loading.attr="disabled"
                                                    class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                    role="menuitem">
                                                    Reasignar
                                                </button>

                                            @endif

                                        @endif --}}

                                        @if(!$folio->matriz)

                                            @can('Convertir en matriz')

                                                <button
                                                    wire:click="cambiarAFolioMatriz({{ $folio->id }})"
                                                    wire:confirm="¿Esta seguro que desea convertir el folio en folio matriz?"
                                                    wire:loading.attr="disabled"
                                                    class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                    role="menuitem">
                                                    Convertir en matriz
                                                </button>

                                            @endif

                                        @endif

                                        <a
                                            href="{{ route('auditoria') . "?modelo=FolioReal&modelo_id=" . $folio->id }}"
                                            class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                            role="menuitem">
                                            Auditar
                                        </a>

                                    </div>

                                </div>

                            </x-table.cell>

                        @endif

                    </x-table.row>

                @empty

                    <x-table.row>

                        <x-table.cell colspan="9">

                            <div class="bg-white text-gray-500 text-center p-5 rounded-full text-lg">

                                No hay resultados.

                            </div>

                        </x-table.cell>

                    </x-table.row>

                @endforelse

            </x-slot>

            <x-slot name="tfoot">

                <x-table.row>

                    <x-table.cell colspan="9" class="bg-gray-50">

                        {{ $folios->links()}}

                    </x-table.cell>

                </x-table.row>

            </x-slot>

        </x-table>

    </div>

    <x-dialog-modal wire:model="modalReasignar" maxWidth="sm">

        <x-slot name="title">

            Reasignar folio

        </x-slot>

        <x-slot name="content">

            <div class="flex flex-col md:flex-row justify-between gap-3 mb-3">

                <x-input-group for="usuario_id" label="Usuario" :error="$errors->first('usuario_id')" class="w-full">

                    <x-input-select id="usuario_id" wire:model="usuario_id" class="w-full">

                        <option value="">Seleccione una opción</option>

                        @foreach ($usuarios as $usuario)

                            <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>

                        @endforeach

                    </x-input-select>

                </x-input-group>

            </div>

        </x-slot>

        <x-slot name="footer">

            <div class="flex gap-3">

                <x-button-blue
                    wire:click="reasignar"
                    wire:loading.attr="disabled"
                    wire:target="reasignar">

                    <img wire:loading wire:target="reasignar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <span>Reasignar</span>
                </x-button-blue>

                <x-button-red
                    wire:click="$toggle('modalReasignar')"
                    wire:loading.attr="disabled"
                    wire:target="$toggle('modalReasignar')"
                    type="button">
                    Cerrar
                </x-button-red>

            </div>

        </x-slot>

    </x-dialog-modal>

</div>
