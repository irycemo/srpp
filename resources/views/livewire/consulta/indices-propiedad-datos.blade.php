<div class="text-sm bg-white p-4 rounded-lg shadow-xl">

    <div class="flex gap-4 justify-center mb-5">

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Distrito</strong>

            <p>{{ $propiedad->distrito }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Tomo</strong>

            <p>{{ $propiedad->tomo }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Regsitro</strong>

            <p>{{ $propiedad->registro }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong># Propiedad</strong>

            <p>{{ $propiedad->noprop }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Status</strong>

            <p>{{ $propiedad->status }}</p>

        </div>

    </div>

    <div class="flex gap-4 justify-center mb-5">

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Superficie: </strong>{{ $propiedad->superficie }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Monto: </strong>{{ $propiedad->monto }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Fecha de inscripción: </strong>{{ $propiedad->fechainscripcion }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Hora de inscripción: </strong>{{ $propiedad->horainscripcion }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Notaria: </strong>{{ $propiedad->notaria }}</p>

        </div>

    </div>

    <div class="flex gap-4 justify-center mb-5">

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Escritura: </strong>{{ $propiedad->escritura }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Clave catastral: </strong>{{ $propiedad->clave_catastral }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Cuenta predia: </strong>{{ $propiedad->cuenta_predial }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Localidad: </strong>{{ $propiedad->localidad }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Municipio: </strong>{{ $propiedad->municipio }}</p>

        </div>

    </div>

    <div class="flex gap-4 justify-center mb-5">

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Propietarios</strong>

            <p>{{ $propiedad->propietarios }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Vendedores</strong>

            <p>{{ $propiedad->vendedores }}</p>

        </div>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2 mb-5 mx-auto">

        <strong>Linderos</strong>

        <p>{{ $propiedad->Linderos }}</p>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2  mb-5 mx-auto">

        <strong>Ubicación</strong>

        <p>{{ $propiedad->ubicacion }}</p>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2  mb-5 mx-auto">

        <strong>Comentarios</strong>

        <p>{{ $propiedad->comentarios }}</p>

    </div>

</div>
