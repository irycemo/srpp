<div class="">

    <button wire:click="$toggle('modal_reasignarme_movimiento_registral')" class="bg-gray-500 hover:shadow-lg hover:bg-gray-700 text-sm py-2 px-4 text-white rounded-full hidden md:block items-center justify-center focus:outline-gray-400 focus:outline-offset-2">

        <img wire:loading wire:target="$toggle('modal_reasignarme_movimiento_registral')" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
        Asignarme trámite

    </button>

    <button wire:click="$toggle('modal_reasignarme_movimiento_registral')" class="bg-gray-500 hover:shadow-lg hover:bg-gray-700 float-right text-sm py-2 px-4 text-white rounded-full md:hidden focus:outline-gray-400 focus:outline-offset-2">+</button>

</div>
