<x-header>{{ $titulo }} ({{ ucfirst(now()->locale('es')->monthName) }})</x-header>

<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4 text-gray-600">

    <div class="mb-5 flex justify-evenly items-center space-x-2 border-t-2 border-gray-400 px-2 rounded-xl bg-white text-center">

            <span class="font-semibold text-2xl text-blueGray-600">

                <p>{{ $inscripcion['no recibido'] ?? 0 }}</p>

            </span>

            <span class="text-blueGray-400 uppercase  text-center  tracking-widest md:tracking-normal">No recibido</span>

    </div>

    <div class="mb-5 flex justify-evenly items-center space-x-2 border-t-2 border-blue-400 px-2 py-2 rounded-xl bg-white text-center">

            <span class="font-semibold text-2xl text-blueGray-600">

                <p>{{ $inscripcion['nuevo'] ?? 0 }}</p>

            </span>

            <span class="text-blueGray-400 uppercase  text-center  tracking-widest md:tracking-normal">Nuevo</span>

    </div>

    <div class="mb-5 flex justify-evenly items-center space-x-2 border-t-2 border-yellow-400 px-2 py-2 rounded-xl bg-white text-center">

            <span class="font-semibold text-2xl text-blueGray-600">

                <p>{{ $inscripcion['elaborado'] ?? 0 }}</p>

            </span>

            <span class="text-blueGray-400 uppercase  text-center  tracking-widest md:tracking-normal">Elaborado</span>

    </div>

    <div class="mb-5 flex justify-evenly items-center space-x-2 border-t-2 border-indigo-400 px-2 py-2 rounded-xl bg-white text-center">

            <span class="font-semibold text-2xl text-blueGray-600">

                <p>{{ $inscripcion['correccion'] ?? 0 }}</p>

            </span>

            <span class="text-blueGray-400 uppercase  text-center  tracking-widest md:tracking-normal">Corrección</span>

    </div>

    <div class="mb-5 flex justify-evenly items-center space-x-2 border-t-2 border-gray-400 px-2 py-2 rounded-xl bg-white text-center">

            <span class="font-semibold text-2xl text-blueGray-600">

                <p>{{ $inscripcion['finalizado'] ?? 0 }}</p>

            </span>

            <span class="text-blueGray-400 uppercase  text-center  tracking-widest md:tracking-normal">Finalizado</span>

    </div>

    <div class="mb-5 flex justify-evenly items-center space-x-2 border-t-2 border-black px-4 py-2 rounded-xl bg-white text-center">

            <span class="font-semibold text-2xl text-blueGray-600">

                <p>{{ $inscripcion['concluido'] ?? 0 }}</p>

            </span>

            <span class="text-blueGray-400 uppercase  text-center  tracking-widest md:tracking-normal">Concluido</span>

    </div>

    <div class="mb-5 flex justify-evenly items-center space-x-2 border-t-2 border-red-400 px-2 py-2 rounded-xl bg-white text-center">

            <span class="font-semibold text-2xl text-blueGray-600">

                <p>{{ $inscripcion['rechazado'] ?? 0 }}</p>

            </span>

            <span class="text-blueGray-400 uppercase  text-center  tracking-widest md:tracking-normal">Rechazado</span>

    </div>

</div>