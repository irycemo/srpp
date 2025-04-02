<button
    wire:click="imprimirDocumentoEntradaMovimiento({{ $copiaConsultada->certificacion->id }})"
    wire:loading.attr="disabled"
    class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
    role="menuitem">

    <span>Documento de entrada</span>

</button>

<button
    wire:click="imprimirCaratulaMovimiento({{ $copiaConsultada->certificacion->id }})"
    wire:loading.attr="disabled"
    class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
    role="menuitem">

    <span>Caratula</span>

</button>