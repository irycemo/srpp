<div>

    <p class="uppercase text-md text-rojo mb-4 tracking-wider">Inscripciones</p>

    @can('Propiedad')

        <a href="{{ route('propiedad') }}" class="mb-3 capitalize font-medium text-md hover:text-red-600 transition ease-in-out duration-500 flex items-center hover  hover:bg-gray-100 p-2 px-4 rounded-xl focus:outline-rojo focus:outline-offset-2 w-full">

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m1.5.5l-1.5-.5M6.75 7.364V3h-3v18m3-13.636l10.5-3.819" />
            </svg>

            Propiedad
        </a>

    @endcan

    @can('Gravamen')

        <a href="#" class="mb-3 capitalize font-medium text-md hover:text-red-600 transition ease-in-out duration-500 flex items-center hover  hover:bg-gray-100 p-2 px-4 rounded-xl focus:outline-rojo focus:outline-offset-2 w-full">

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
            </svg>

            Gravamen
        </a>

    @endif

    @can('Sentencias')

        <a href="#" class="mb-3 capitalize font-medium text-md hover:text-red-600 transition ease-in-out duration-500 flex items-center hover  hover:bg-gray-100 p-2 px-4 rounded-xl focus:outline-rojo focus:outline-offset-2 w-full">

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0M3.124 7.5A8.969 8.969 0 015.292 3m13.416 0a8.969 8.969 0 012.168 4.5" />
            </svg>

            Sentencias
        </a>

    @endif

    @can('Cancelaciones')

        <a href="#" class="mb-3 capitalize font-medium text-md hover:text-red-600 transition ease-in-out duration-500 flex items-center hover  hover:bg-gray-100 p-2 px-4 rounded-xl focus:outline-rojo focus:outline-offset-2 w-full">

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
            </svg>

            Cancelaciones
        </a>

    @endif

    @can('Varios')

        <a href="#" class="mb-3 capitalize font-medium text-md hover:text-red-600 transition ease-in-out duration-500 flex items-center hover  hover:bg-gray-100 p-2 px-4 rounded-xl focus:outline-rojo focus:outline-offset-2 w-full">

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0l4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0l-5.571 3-5.571-3" />
            </svg>

            Varios
        </a>

    @endif

</div>
