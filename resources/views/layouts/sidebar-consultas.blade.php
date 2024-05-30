<div x-data="{openRoles:true, openDistritos:true, openValores:true, openPredios:true}" class="mb-5">

    <p class="uppercase text-base mb-2 border-b border-rojo rounded-lg px-1.5 w-full">Consultas</p>

    <div class="flex items-center w-full justify-between hover:text-red-600 transition ease-in-out duration-500 hover:bg-gray-100 rounded-xl">

        <a href="{{ route('consultas') }}" class="capitalize font-medium text-sm flex items-center w-full py-2 px-4 focus:outline-rojo focus:outline-offset-2 rounded-lg">

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75l-2.489-2.489m0 0a3.375 3.375 0 10-4.773-4.773 3.375 3.375 0 004.774 4.774zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>


            Consulta
        </a>

    </div>

</div>
