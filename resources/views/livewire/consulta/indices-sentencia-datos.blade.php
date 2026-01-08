<div class="text-sm bg-white p-4 rounded-lg shadow-xl">

    <div class="lg:flex gap-4 justify-center mb-5 space-y-2 lg:space-y-0">

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Distrito</strong>

            <p>{{ $sentencia->distrito }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Tomo</strong>

            <p>{{ $sentencia->tomosen }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Regsitro</strong>

            <p>{{ $sentencia->registrosen }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Status</strong>

            <p>{{ $sentencia->status }}</p>

        </div>

    </div>

    <div class="lg:flex gap-4 justify-center mb-5 space-y-2 lg:space-y-0">

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Juzgado: </strong>{{ $sentencia->juzgado }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Fecha de inscripción: </strong>{{ $sentencia->fechains }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Hora de inscripción: </strong>{{ $sentencia->horains }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Hojas: </strong>{{ $sentencia->hojas }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Oficio: </strong>{{ $sentencia->oficio }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Expediente: </strong>{{ $sentencia->expediente }}</p>

        </div>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2 mb-5 mx-auto">

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Descripcion</strong>

            <p>{{ $sentencia->descripcion }}</p>

        </div>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2 mb-5 mx-auto">

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Comentarios</strong>

            <p>{{ $sentencia->comentarios }}</p>

        </div>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2 mb-5 mx-auto">

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Involucrados</strong>

            <p>{{ $sentencia->involucrados }}</p>

        </div>

    </div>

</div>
