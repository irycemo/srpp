<div class="text-sm bg-white p-4 rounded-lg shadow-xl">

    <div class="flex gap-4 justify-center mb-5">

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Distrito</strong>

            <p>{{ $gravamen->Distrito }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Tomo</strong>

            <p>{{ $gravamen->tomog }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Regsitro</strong>

            <p>{{ $gravamen->registrog }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Status</strong>

            <p>{{ $gravamen->stGravamen }}</p>

        </div>

    </div>

    <div class="flex gap-4 justify-center mb-5">

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Moneda: </strong>{{ $gravamen->tmoneda }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Monto: </strong>{{ $gravamen->monto }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Fecha de inscripción: </strong>{{ $gravamen->finscripcion }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Hora de inscripción: </strong>{{ $gravamen->hinscripcion }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Fecha de cancelación: </strong>{{ $gravamen->fcancelacion }}</p>

        </div>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2 mb-5 mx-auto">

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Descripción</strong>

            <p>{{ $gravamen->descGravamen }}</p>

        </div>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2 mb-5 mx-auto">

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Comentarios</strong>

            <p>{{ $gravamen->comentarios }}</p>

        </div>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2 mb-5 mx-auto">

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Acreedores</strong>

            <p>{{ $gravamen->acreedores }}</p>

        </div>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2 mb-5 mx-auto">

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Deudores</strong>

            <p>{{ $gravamen->deudores }}</p>

        </div>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2 mb-5 mx-auto">

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Garantes</strong>

            <p>{{ $gravamen->garantes }}</p>

        </div>

    </div>

</div>
