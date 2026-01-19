<div class="flex gap-3 overflow-auto p-1">

    <select class="bg-white rounded-full text-sm" wire:model.live="filters.año">

        <option value="">Año</option>

        @foreach ($años as $año)

            <option value="{{ $año }}">{{ $año }}</option>

        @endforeach

    </select>

    <input type="number" wire:model.live.debounce.500ms="filters.tramite" placeholder="# control" class="bg-white rounded-full text-sm w-24">

    <input type="number" wire:model.live.debounce.500ms="filters.usuario" placeholder="Usuario" class="bg-white rounded-full text-sm w-20">

    <input type="number" wire:model.live.debounce.500ms="filters.folio_real" placeholder="F. Real" class="bg-white rounded-full text-sm w-24">

    <input type="number" wire:model.live.debounce.500ms="filters.folio" placeholder="M.R." class="bg-white rounded-full text-sm w-24">

    <select class="bg-white rounded-full text-sm w-min" wire:model.live="filters.estado">

        <option value="">Estado</option>
        <option value="no recibido">No recibido</option>
        <option value="nuevo">Nuevo</option>
        <option value="elaborado">Elaborado</option>
        <option value="rechazado">Rechazado</option>
        <option value="finalizado">Finalizado</option>
        <option value="correccion">Corrección</option>

    </select>

    <select class="bg-white rounded-full text-sm" wire:model.live="pagination">

        <option value="10">10</option>
        <option value="25">25</option>
        <option value="50">50</option>
        <option value="100">100</option>

    </select>

</div>