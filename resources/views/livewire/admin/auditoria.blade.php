<div class="">

    <div class="mb-5">

        <x-header>Auditoria</x-header>

        <div class="flex justify-between">

            <div>

                <select class="bg-white rounded-full text-sm" wire:model.live="usuario">

                    <option value="" selected>Seleccione un usuario</option>

                    @foreach ($usuarios as $item)

                        <option value="{{ $item->id }}">{{ $item->name }}</option>

                    @endforeach

                </select>

                <select class="bg-white rounded-full text-sm" wire:model.live="modelo">

                    <option value="" selected>Seleccione un área</option>

                    @foreach ($modelos as $key => $item)

                        <option value="{{ $item }}">{{ $key }}</option>

                    @endforeach

                </select>

                <select class="bg-white rounded-full text-sm" wire:model.live="evento">

                    <option value="" selected>Seleccione una acción</option>
                    <option value="created" selected>Creación</option>
                    <option value="updated" selected>Actualización</option>
                    <option value="deleted" selected>Borrado</option>

                </select>

                <input type="number" class="bg-white rounded-full text-sm p-2 border border-gray-500" wire:model.live="modelo_id" placeholder="Modelo ID">

                <select class="bg-white rounded-full text-sm" wire:model.live="pagination">

                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>

                </select>

            </div>

        </div>

    </div>

    <div class="overflow-x-auto rounded-lg shadow-xl border-t-2 border-t-gray-500">

        <x-table>

            <x-slot name="head">

                <x-table.heading sortable wire:click="sortBy('user_id')" :direction="$sort === 'user_id' ? $direction : null" >Usuario</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('event')" :direction="$sort === 'event' ? $direction : null" >Evento</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('auditable_type')" :direction="$sort === 'auditable_type' ? $direction : null" >Modelo</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('auditable_id')" :direction="$sort === 'auditable_id' ? $direction : null" >ID</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('ip_address')" :direction="$sort === 'ip_address' ? $direction : null" >IP</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('created_at')" :direction="$sort === 'created_at' ? $direction : null">Registro</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('updated_at')" :direction="$sort === 'updated_at' ? $direction : null">Actualizado</x-table.heading>
                <x-table.heading >Acciones</x-table.heading>

            </x-slot>

            <x-slot name="body">

                @forelse ($audits as $audit)

                    <x-table.row wire:loading.class.delaylongest="opacity-50" wire:key="row-{{ $audit->id }}">

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Usuario</span>

                            {{ $audit->user->name ?? 'N/A' }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Evento</span>

                            @if( $audit->event  == 'updated')
                                Actualización
                            @elseif($audit->event  == 'created' )
                                Creado
                            @elseif($audit->event  == 'deleted')
                                Borrado
                            @elseif($audit->event  == 'attach' || $audit->event  == 'sync')
                                Relacionado
                            @endif

                            : {{ $audit->tags }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Modelo</span>

                            {{ Str::substr($audit->auditable_type, 11) }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">ID</span>

                            {{ $audit->auditable_id }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">IP</span>

                            {{ $audit->ip_address }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Registrado</span>

                            {{ $audit->created_at }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Actualizado</span>

                            {{ $audit->updated_at }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Acciones</span>

                            <div class="flex justify-center lg:justify-start gap-2">

                                <x-button-green
                                    wire:click="ver({{ $audit->id }})"
                                    wire:loading.attr="disabled"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>

                                    <span>Ver</span>

                                </x-button-green>

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

                        {{ $audits->links()}}

                    </x-table.cell>

                </x-table.row>

            </x-slot>

        </x-table>

    </div>

    @if($selecetedAudit)

        <x-dialog-modal wire:model="modal">

            <x-slot name="title">

                {{ Str::substr($selecetedAudit['auditable_type'], 11) }}

            </x-slot>

            <x-slot name="content">

                <strong>Evento:</strong>
                @if( $selecetedAudit['event'] == 'updated')
                    <p>Actualización</p>
                @elseif($selecetedAudit['event'] == 'created')
                   <p>Creado</p>
                @elseif($selecetedAudit['event'] == 'sync')
                    <p>Sync</p>
                @elseif($selecetedAudit['event'] == 'sync')
                    <p>Sync</p>
                @elseif($selecetedAudit['event'] == 'attach')
                    <p>Attach</p>
                @else
                    Borrado
                @endif

                <strong>Usuario:</strong>
                <p>{{ $selecetedAudit['user']['name'] ?? 'N/A' }}</p>

                <strong>Modelo:</strong>
                <p>{{ Str::substr($selecetedAudit['auditable_type'], 11) }}, id: {{ $selecetedAudit['auditable_id'] }}</p>

                <strong>URL:</strong>
                <p>{{ $selecetedAudit['url'] }}</p>

                <strong>IP:</strong>
                <p>{{ $selecetedAudit['ip_address'] }}</p>

                <strong>Agente:</strong>
                <p>{{ $selecetedAudit['user_agent'] }}</p>

                <strong>Registrado:</strong>
                <p>{{ $selecetedAudit['created_at'] }}</p>

                @if($selecetedAudit['event'] == 'attach' || $selecetedAudit['event'] == 'sync')

                    <p class="mt-4 capitalize"><strong>Relacion:</strong> {{ key($this->newValues) }}</p>

                    <div class="grid grid-cols-2 gap-3 my-4">

                        <div class="break-words">

                            <strong>Valores anteriores</strong>

                            @if($selecetedAudit['event'] == 'sync')

                                @foreach ($this->oldValues[key($this->oldValues)][0] as $key => $value )

                                    @if($key == 'pivot') @continue @endif

                                    <p>{{ $key }} = {{ $value }}</p>

                                @endforeach

                            @endif

                        </div>

                        <div class="break-words">

                            <strong>Valores nuevos</strong>

                            @foreach ($this->newValues[key($this->newValues)][0] as $key => $value )

                                @if($key == 'pivot') @continue @endif

                                <p>{{ $key }} = {{ $value }}</p>

                            @endforeach

                        </div>

                    </div>

                @else

                    <div class="grid grid-cols-2 gap-3 my-4">

                        <div class="break-words">

                            <strong>Valores anteriores</strong>

                            @foreach (json_decode($selecetedAudit['old_values']) as $key => $value)

                                <p>{{ $key }} = {{ $value ?? 'null' }}</p>

                            @endforeach

                        </div>

                        <div class="break-words">

                            <strong>Valores nuevos</strong>

                            @foreach (json_decode($selecetedAudit['new_values']) as $key => $value)

                                <p>{{ $key }} = {{ $value ?? 'null' }}</p>

                            @endforeach

                        </div>

                    </div>

                @endif

            </x-slot>

            <x-slot name="footer">

                <div class="float-righ">

                    <x-button-red
                        wire:click="$set('modal', false)"
                        wire:loading.attr="disabled"
                        type="button">
                        Cerrar
                    </x-button-red>

                </div>

            </x-slot>

        </x-dialog-modal>

    @endif

</div>
