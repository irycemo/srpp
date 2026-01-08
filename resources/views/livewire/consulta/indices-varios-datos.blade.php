<div class="text-sm bg-white p-4 rounded-lg shadow-xl">

    <div class="lg:flex gap-4 justify-center mb-5 space-y-2 lg:space-y-0">

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Distrito</strong>

            <p>{{ $vario->distrito }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Tomo</strong>

            <p>{{ $vario->tomovar }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Regsitro</strong>

            <p>{{ $vario->registrovar }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Status</strong>

            <p>{{ $vario->status }}</p>

        </div>

    </div>

    <div class="lg:flex gap-4 justify-center mb-5 space-y-2 lg:space-y-0">

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Fecha de inscripción: </strong>{{ $vario->finscripcion }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Hora de inscripción: </strong>{{ $vario->hinscripcion }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Notario: </strong>{{ $vario->notario }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong># Escritura: </strong>{{ $vario->nescritura }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Hojas: </strong>{{ $vario->nhojas }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Expediente: </strong>{{ $vario->expediente }}</p>

        </div>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2 mb-5 mx-auto">

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Descripcion</strong>

            <p>{{ $vario->descripcion }}</p>

        </div>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2 mb-5 mx-auto">

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Comentarios</strong>

            <p>{{ $vario->comentarios }}</p>

        </div>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2 mb-5 mx-auto">

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Involucrados</strong>

            <p>{{ $vario->intervinientes }}</p>

        </div>

    </div>

</div>
