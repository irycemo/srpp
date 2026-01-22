<x-header>{{ $titulo }}</x-header>

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-7 gap-4">

    <div class="flex md:block justify-evenly items-center space-x-2 border-t-4 border-gray-400 px-4 py-2 shadow-xl text-gray-600 rounded-xl bg-white text-center">

        <div class="  mb-2 items-center">

            <span class="font-semibold text-2xl text-blueGray-600 mb-2">

                <p>{{ $inscripcion['no recibido'] ?? 0 }}</p>

            </span>

            <h5 class="text-blueGray-400 uppercase  text-center  tracking-widest md:tracking-normal">No recibido</h5>

        </div>

    </div>

    <div class="flex md:block justify-evenly items-center space-x-2 border-t-4 border-blue-400 px-4 py-2 shadow-xl text-gray-600 rounded-xl bg-white text-center">

        <div class="  mb-2 items-center">

            <span class="font-semibold text-2xl text-blueGray-600 mb-2">

                <p>{{ $inscripcion['nuevo'] ?? 0 }}</p>

            </span>

            <h5 class="text-blueGray-400 uppercase  text-center  tracking-widest md:tracking-normal">Nuevo</h5>

        </div>

    </div>

    <div class="flex md:block justify-evenly items-center space-x-2 border-t-4 border-yellow-400 px-4 py-2 shadow-xl text-gray-600 rounded-xl bg-white text-center">

        <div class="  mb-2 items-center">

            <span class="font-semibold text-2xl text-blueGray-600 mb-2">

                <p>{{ $inscripcion['elaborado'] ?? 0 }}</p>

            </span>

            <h5 class="text-blueGray-400 uppercase  text-center  tracking-widest md:tracking-normal">Elaborado</h5>

        </div>

    </div>

    <div class="flex md:block justify-evenly items-center space-x-2 border-t-4 border-indigo-400 px-4 py-2 shadow-xl text-indigo-600 rounded-xl bg-white text-center">

        <div class="  mb-2 items-center">

            <span class="font-semibold text-2xl text-blueGray-600 mb-2">

                <p>{{ $inscripcion['correccion'] ?? 0 }}</p>

            </span>

            <h5 class="text-blueGray-400 uppercase  text-center  tracking-widest md:tracking-normal">Corrección</h5>

        </div>

    </div>

    <div class="flex md:block justify-evenly items-center space-x-2 border-t-4 border-gray-400 px-4 py-2 shadow-xl text-gray-600 rounded-xl bg-white text-center">

        <div class="  mb-2 items-center">

            <span class="font-semibold text-2xl text-blueGray-600 mb-2">

                <p>{{ $inscripcion['finalizado'] ?? 0 }}</p>

            </span>

            <h5 class="text-blueGray-400 uppercase  text-center  tracking-widest md:tracking-normal">Finalizado</h5>

        </div>

    </div>

    <div class="flex md:block justify-evenly items-center space-x-2 border-t-4 border-black px-4 py-2 shadow-xl text-black rounded-xl bg-white text-center">

        <div class="  mb-2 items-center">

            <span class="font-semibold text-2xl text-blueGray-600 mb-2">

                <p>{{ $inscripcion['concluido'] ?? 0 }}</p>

            </span>

            <h5 class="text-blueGray-400 uppercase  text-center  tracking-widest md:tracking-normal">Finalizado</h5>

        </div>

    </div>

    <div class="flex md:block justify-evenly items-center space-x-2 border-t-4 border-red-400 px-4 py-2 shadow-xl text-red-600 rounded-xl bg-white text-center">

        <div class="  mb-2 items-center">

            <span class="font-semibold text-2xl text-blueGray-600 mb-2">

                <p>{{ $inscripcion['rechazado'] ?? 0 }}</p>

            </span>

            <h5 class="text-blueGray-400 uppercase  text-center  tracking-widest md:tracking-normal">Finalizado</h5>

        </div>

    </div>

</div>