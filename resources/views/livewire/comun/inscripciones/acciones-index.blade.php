@if($movimiento->estado === 'no recibido' && !auth()->user()->hasRole(['Supervisor inscripciones']))

    <button
        wire:click="abrirModalRecibirDocumentacion({{  $movimiento->id }})"
        wire:loading.attr="disabled"
        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
        role="menuitem">
        Recibir documentación
    </button>

@endif

@can('Elaborar inscripción')

    @if(in_array($movimiento->estado, ['nuevo', 'captura', 'correccion']))

        <button
            wire:click="elaborar({{  $movimiento->id }})"
            wire:loading.attr="disabled"
            class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
            role="menuitem">
            Elaborar
        </button>

    @endif

@endcan

@can('Rechazar inscripción')

    @if(in_array($movimiento->estado, ['nuevo', 'captura', 'correccion']))

        <button
            wire:click="abrirModalRechazar({{  $movimiento->id }})"
            wire:loading.attr="disabled"
            class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
            role="menuitem">

            Rechazar

        </button>

    @endif

@endcan

@can('Reasignar inscripción')

    @if(in_array($movimiento->estado, ['nuevo', 'captura', 'correccion', 'no recibido']))

        <button
            wire:click="abrirModalReasignarUsuario({{  $movimiento->id }})"
            wire:loading.attr="disabled"
            class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
            role="menuitem">
            Reasignar
        </button>

    @endif

@endcan

@can('Imprimir inscripción')

    @if(in_array($movimiento->estado, ['elaborado','finalizado', 'concluido']))

        <button
            wire:click="imprimir({{  $movimiento->id }})"
            wire:loading.attr="disabled"
            class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
            role="menuitem">
            Imprimir
        </button>

    @endif

@endcan

@can('Finalizar inscripción')

    @if($movimiento->estado == 'elaborado')

        <button
            wire:click="abrirModalFinalizar({{  $movimiento->id }})"
            wire:loading.attr="disabled"
            class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
            role="menuitem">
            Finalizar
        </button>

    @endif

@endcan

@can('Concluir inscripción')

    @if($movimiento->estado == 'finalizado')

        <button
            wire:click="abrirModalConcluir({{  $movimiento->id }})"
            wire:loading.attr="disabled"
            class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
            role="menuitem">
            Concluir
        </button>

    @endif

@endcan

@can('Corregir inscripción')

    @if(in_array($movimiento->estado, ['elaborado','finalizado', 'concluido']))

        <button
            wire:click="abrirModalCorreccion({{  $movimiento->id }})"
            wire:loading.attr="disabled"
            class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
            role="menuitem">
            Corregir
        </button>

    @endif

@endcan