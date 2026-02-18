<div>

    <x-header>Capacitación continua</x-header>

    @if(auth()->user()->hasRole('Administrador'))

        <div class="flex justify-end mb-5">

            <a href="{{ route('consultas.pregunta') }}" class="bg-gray-500 hover:shadow-lg hover:bg-gray-700 text-sm py-2 px-4 text-white rounded-full hidden md:block items-center justify-center focus:outline-gray-400 focus:outline-offset-2">

                Agregar nueva pregunta

            </a>

            <a href="{{ route('consultas.pregunta') }}" class="bg-gray-500 hover:shadow-lg hover:bg-gray-700 float-right text-sm py-2 px-4 text-white rounded-full md:hidden focus:outline-gray-400 focus:outline-offset-2">+</a>

        </div>

    @endif

    <div class="bg-white shadow-xl rounded-lg p-4 mb-5">

        <input type="text" class="rounded-lg w-full lg:w-1/2 mx-auto flex" placeholder="Buscar.." autofocus wire:model.live="search">

    </div>

    <div class="bg-white shadow-xl rounded-lg p-4" wire:loading.class.delaylongest="opacity-50">

        <div class="w-full lg:w-1/2 mx-auto ">

            <div class="flex items-center justify-center gap-9 mb-5">

                <div class="flex items-center">

                    <div class="w-4 h-4 bg-red-400 rounded-full mr-3"></div>

                    <span>Lectura obligatoria</span>

                </div>

                <div class="flex items-center">

                    <div class="w-4 h-4 bg-yellow-300 rounded-full mr-3"></div>

                    <span>Lectura sugerida</span>

                </div>

            </div>

            <ul class="w-full space-y-3">

                @forelse ($this->preguntas as $item)

                    <li class="
                        @if($item->categoria == 'Todos')
                            border-l-red-400
                        @elseif($item->categoria == 'Inscripciones' && array_intersect($inscripciones, auth()->user()->getRoleNames()->toArray()))
                            border-l-red-400
                        @elseif($item->categoria == 'Certificaciones' && array_intersect($certificaciones, auth()->user()->getRoleNames()->toArray()))
                            border-l-red-400
                        @elseif($item->categoria == 'Recepción' && array_intersect($recepcion, auth()->user()->getRoleNames()->toArray()))
                            border-l-red-400
                        @else
                            border-l-yellow-300
                        @endif
                        border-l-8 cursor-pointer hover:bg-gray-100 rounded-lg text-gray-700 border border-gray-300 flex justify-between"
                        wire:key="pregunta-{{ $item->id }}">

                        <div class="w-full h-full p-3 flex justify-between items-center" wire:click="verPregunta({{ $item->id }})">

                            <span>{{ $item->titulo }}</span>

                            @if (auth()->user()->preguntasLeidas()->where('pregunta_id', $item->id)->first())

                                <div class="text-green-600 flex items-center" title="Leido">

                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                    </svg>

                                </div>

                            @endif

                        </div>

                        <div class="flex items-center">

                            @if(auth()->user()->hasRole('Administrador'))

                                <div class="ml-3 relative lg:hidden flex items-center" x-data="{ open_drop_down:false }">

                                    <div>

                                        <button x-on:click="open_drop_down=true" type="button" class="mr-3 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">

                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                            </svg>

                                        </button>

                                    </div>

                                    <div x-cloak x-show="open_drop_down" x-on:click="open_drop_down=false" x-on:click.away="open_drop_down=false" class="z-50 origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu">

                                        <button
                                        wire:click="verUsuarios({{ $item->id }})"
                                            wire:target="verUsuarios({{ $item->id }})"
                                            wire:loading.attr="disabled"
                                            class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                            role="menuitem">
                                            Ver usuarios
                                        </button>

                                        <a
                                            href="{{ route('consultas.pregunta', $item) }}"
                                            class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                            role="menuitem">
                                            Editar pregunta
                                        </a>

                                        <button
                                            wire:click="abrirModalBorrar({{ $item->id }})"
                                            wire:target="abrirModalBorrar({{ $item->id }})"
                                            wire:loading.attr="disabled"
                                            class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                            role="menuitem">
                                            Borrar
                                        </button>

                                    </div>

                                </div>

                                <div class="hidden  lg:flex">

                                    <div class="text-blue-700 p-2 flex items-center" wire:click="verUsuarios({{ $item->id }})">

                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                        </svg>

                                    </div>

                                    <a href="{{ route('consultas.pregunta', $item) }}" class="text-green-700 p-2 flex items-center" >

                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>

                                    </a>

                                    <div class="text-red-700 p-2 flex items-center" wire:click="abrirModalBorrar({{ $item->id }})">

                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>

                                    </div>

                                </div>

                            @endif

                        </div>

                    </li>

                @empty

                    <div class="bg-white text-gray-500 text-center p-5 rounded-full text-lg">

                        No hay resultados.

                    </div>

                @endforelse

            </ul>

            <div class="mt-5">

                {{ $this->preguntas->links()}}

            </div>

        </div>

    </div>

    <x-dialog-modal wire:model="modal" maxWidth="2xl">

        <x-slot name="title">

            {{ $pregunta?->titulo }}

        </x-slot>

        <x-slot name="content">

            <div class="overflow-auto">

                <input type="text" class="sr-only">

                {!! $pregunta?->contenido !!}

            </div>

        </x-slot>

        <x-slot name="footer">

            <div class="flex gap-3">

                <x-button-blue
                    wire:click="marcarComoLeido"
                    wire:loading.attr="disabled"
                    wire:target="marcarComoLeido"
                    type="button">
                    Marcar como leido
                </x-button-blue>

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

    <x-dialog-modal wire:model="modalUsuarios" maxWidth="lg">

        <x-slot name="title">

            <p>Usuarios que han leido:</p>

            <p class="text-sm">{{ $pregunta?->titulo }}</p>

        </x-slot>

        <x-slot name="content">

            <x-table>

                <x-slot name="head">

                    <x-table.heading >Usuario</x-table.heading>
                    <x-table.heading >Registro</x-table.heading>

                </x-slot>

                <x-slot name="body">

                    @forelse ($usuarios as $usuario)

                    <x-table.row wire:loading.class.delaylongest="opacity-50" wire:key="row-{{ $usuario->id }}">

                        <x-table.cell>

                            {{ $usuario->usuario->name }}

                        </x-table.cell>

                        <x-table.cell>

                            {{ $usuario->created_at }}

                        </x-table.cell>

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

                </x-slot>

            </x-table>


        </x-slot>

        <x-slot name="footer">

            <div class="flex gap-3">

                <x-button-red
                    wire:click="$toggle('modalUsuarios')"
                    wire:loading.attr="disabled"
                    wire:target="$toggle('modalUsuarios')"
                    type="button">
                    Cerrar
                </x-button-red>

            </div>

        </x-slot>

    </x-dialog-modal>

    <x-confirmation-modal wire:model="modalBorrar" maxWidth="sm">

        <x-slot name="title">
            Eliminar
        </x-slot>

        <x-slot name="content">
            ¿Esta seguro que desea eliminar la información? No sera posible recuperar la información.
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
                wire:click="borrarPregunta"
                wire:loading.attr="disabled"
                wire:target="borrarPregunta"
            >
                Borrar
            </x-danger-button>

        </x-slot>

    </x-confirmation-modal>

</div>
