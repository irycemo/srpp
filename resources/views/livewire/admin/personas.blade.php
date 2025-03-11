<div>

    <x-header>Personas</x-header>

    <div class="bg-white p-4 rounded-lg mb-5 shadow-xl">

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 mb-3 col-span-2 rounded-lg w-full lg:w-1/2 mx-auto">

            <x-input-group for="nombre" label="Nombre(s)" :error="$errors->first('nombre')" class="w-full">

                <x-input-text id="nombre" wire:model="nombre"/>

            </x-input-group>

            <x-input-group for="ap_paterno" label="Apellido paterno" :error="$errors->first('ap_paterno')" class="w-full">

                <x-input-text id="ap_paterno" wire:model="ap_paterno"/>

            </x-input-group>

            <x-input-group for="ap_materno" label="Apellido materno" :error="$errors->first('ap_materno')" class="w-full">

                <x-input-text id="ap_materno" wire:model="ap_materno"/>

            </x-input-group>

            <x-input-group for="rfc" label="RFC" :error="$errors->first('rfc')" class="w-full">

                <x-input-text id="rfc" wire:model="rfc"/>

            </x-input-group>

            <x-input-group for="curp" label="CURP" :error="$errors->first('curp')" class="w-full">

                <x-input-text id="curp" wire:model="curp"/>

            </x-input-group>

            <x-input-group for="razon_social" label="Razon social" :error="$errors->first('razon_social')" class="w-full">

                <x-input-text id="razon_social" wire:model="razon_social" />

            </x-input-group>

        </div>

        <div class="felx justify-center">

            <x-button-blue
                wire:click="buscar"
                wire:target="buscar"
                wire:loading.attr="disabled"
                class="mx-auto">
                Buscar persona
            </x-button-blue>

        </div>

    </div>

    <div class="overflow-x-auto rounded-lg shadow-xl border-t-2 border-t-gray-500">

        <x-table>

            <x-slot name="head">

                <x-table.heading sortable wire:click="sortBy('nombre')" :direction="$sort === 'nombre' ? $direction : null" >Nombre</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('ap_paterno')" :direction="$sort === 'ap_paterno' ? $direction : null" >Ap paterno</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('ap_paterno')" :direction="$sort === 'ap_paterno' ? $direction : null" >Ap materno</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('rfc')" :direction="$sort === 'rfc' ? $direction : null" >RFC</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('curp')" :direction="$sort === 'curp' ? $direction : null" >CURP</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('created_at')" :direction="$sort === 'created_at' ? $direction : null">Registro</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('updated_at')" :direction="$sort === 'updated_at' ? $direction : null">Actualizado</x-table.heading>
                <x-table.heading >Acciones</x-table.heading>

            </x-slot>

            <x-slot name="body">

                @forelse ($this->personas as $persona)

                    <x-table.row wire:loading.class.delaylongest="opacity-50" wire:key="row-{{$persona->id }}">

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Nombre</span>

                            {{$persona->nombre }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Ap paterno</span>

                            {{$persona->ap_paterno }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Ap materno</span>

                            {{$persona->ap_paterno }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">RFC</span>

                            {{$persona->rfc }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">CURP</span>

                            {{$persona->curp }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Registrado</span>


                            <span class="font-semibold">@if($persona->creadoPor != null)Registrado por: {{$persona->creadoPor->name}} @else Registro: @endif</span> <br>

                            {{$persona->created_at }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="font-semibold">@if($persona->actualizadoPor != null)Actualizado por: {{$persona->actualizadoPor->name}} @else Actualizado: @endif</span> <br>

                            {{$persona->updated_at }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Acciones</span>

                            <div class="flex justify-center lg:justify-start gap-2">

                                @can('Editar persona')

                                    <x-button-blue
                                        wire:click="abrirModalEditar({{$persona->id }})"
                                        wire:target="abrirModalEditar({{$persona->id }})"
                                        wire:loading.attr="disabled"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4 mr-2">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>

                                        <span>Editar</span>

                                    </x-button-blue>

                                @endcan

                            </div>

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

                <x-table.row>

                    <x-table.cell colspan="9" class="bg-gray-50">

                        {{ $this->personas->links()}}

                    </x-table.cell>

                </x-table.row>

            </x-slot>

        </x-table>

    </div>

</div>
