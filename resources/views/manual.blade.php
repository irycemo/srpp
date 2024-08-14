<x-app-layout>

    <div class="relative min-h-screen md:flex">

        {{-- Sidebar --}}
        <div id="sidebar" class="z-50 bg-white w-64 absolute inset-y-0 left-0 transform -translate-x-full transition duration-200 ease-in-out md:relative md:translate-x-0">

            {{-- Header --}}
            <div class="w-100 flex-none bg-white border-b-2 border-b-grey-200 flex flex-row p-5 pr-0 justify-between items-center h-20 ">

                {{-- Logo --}}
                <a href="{{ route('dashboard') }}" class="mx-auto">

                    <img class="h-16" src="{{ asset('storage/img/logo2.png') }}" alt="Logo">

                </a>

                {{-- Side Menu hide button --}}
                <button  type="button" title="Cerrar Menú" id="sidebar-menu-button" class="md:hidden mr-2 inline-flex items-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">

                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>

                </button>

            </div>

            {{-- Nav --}}
            <nav class="p-4 text-rojo">

                @can('Lista de usuarios')

                    <a href="#usuarios" class="mb-3 capitalize font-medium text-md transition ease-in-out duration-500 flex hover  hover:bg-gray-100 p-2 px-4 rounded-xl">

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-4 " fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                            </svg>

                        Usuarios
                    </a>

                @endcan


            </nav>

        </div>

        {{-- Content --}}
        <div class="flex-1 flex-col flex max-h-screen overflow-x-auto min-h-screen">

            {{-- Mobile --}}
            <div class="w-100 bg-white border-b-2 border-b-grey-200 flex-none flex flex-row p-5 justify-between items-center h-20">

                <!-- Mobile menu button-->
                <div class="flex items-center">

                    <button  type="button" title="Abrir Menú" id="mobile-menu-button" class="md:hidden inline-flex items-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">

                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>

                    </button>

                </div>

                {{-- Logo --}}
                <p class="font-semibold text-2xl text-rojo">Manual de Usuario</p>

                <div></div>

            </div>

            {{-- Main Content --}}
            <div class="bg-white flex-1 overflow-y-auto py-8 md:border-l-2 border-l-grey-200">

                <div class="lg:w-2/3 mx-auto rounded-xl">

                    <div class="capitulo mb-10" id="introduccion">

                        <h2 class="text-2xl tracking-widest py-3 px-6 text-gray-600 rounded-xl border-b-2 border-gray-500 font-semibold mb-6  bg-white">Introducción</h2>

                        <div class="  px-3">

                            <p class="mb-2">
                                TBD
                            </p>

                        </div>

                    </div>

                    <div class="capitulo mb-10" id="usuarios">

                        @can('Lista de usuarios')

                            <h2 class="text-2xl tracking-widest py-3 px-6 text-gray-600 rounded-xl border-b-2 border-gray-500 font-semibold mb-6  bg-white">Usuarios</h2>

                            <div class="  px-3">

                                <p class="mb-2">
                                    La sección de usuarios lleva el control del registro de los usuarios del sistema. Los usuarios estan clasificados por roles
                                    cada uno con atribuciones distintas.
                                </p>

                                <p>
                                    <strong>Busqueda de usuario:</strong>
                                    puede hacer busqueda de usuarios por cualquiera de las columnas que muestra la tabla.
                                </p>

                                <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/usuarios_buscar.jpg') }}" alt="Imágen buscar">

                                <p>
                                    <strong>Agregar nuevo usuario:</strong>
                                    puede agregar un nuevo usuario haciendo click el el botón "Agregar nuevo usuario" esta acción deplegará una ventana modal
                                    en la cual se ingresará la información necesaria para el registro. Al hacer click en el botón "Guardar" se generará el registro con los datos
                                    proporcionados. Al hacer click en cerrar se cerrará la ventana modal borrando la información proporcionada.
                                </p>

                                <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/usuarios_modal_crear.jpg') }}" alt="Imágen crear">

                                <p>
                                    <strong>Editar usuario:</strong>
                                    cada usuario tiene asociado dos botones de acciones, puede editar un usuario haciendo click el el botón "Editar" esta acción deplegará una ventana modal
                                    en la cual se mostrará la información del usuario para actualizar.
                                </p>

                                <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/usuarios_editar.jpg') }}" alt="Imágen buscar">

                                <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/usuarios_modal_editar.jpg') }}" alt="Imágen editar">

                                <p>
                                    <strong>Borrar usuario:</strong>
                                    puede borrar un usuario haciendo click el el botón "Borrar" esta acción deplegará una ventana modal
                                    en la cual se mostrará una advertencia, dando la opcion de cancelar o borrar la información.
                                </p>

                                <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/usuarios_borrar.jpg') }}" alt="Imágen borrar">

                                <p>
                                    Al crear un usuario, su credenciales para iniciar sesión seran su correo y la contraseña "sistema", al tratar de iniciar sesión le pedira actualizar su contraseña.
                                </p>

                                <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/actualizar_contraseña.jpg') }}" alt="Imágen contraseña">

                                <p>
                                    Puede revisar su perfil de usuario haciendo click en el circulo superior izquierdo en la opción "Mi perfil"
                                </p>

                                <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/perfil.jpg') }}" alt="Imágen perfil">

                            </div>

                        @endcan

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script>

        const btn_close = document.getElementById('sidebar-menu-button');
        const btn_open = document.getElementById('mobile-menu-button');
        const sidebar = document.getElementById('sidebar');

        btn_open.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });

        btn_close.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });

        /* Change nav profile image */
        window.addEventListener('nav-profile-img', event => {

            document.getElementById('nav-profile').setAttribute('src', event.detail.img);

        });

    </script>

</x-app-layout>
