<div class="text-sm bg-white p-4 rounded-lg shadow-xl">

    <div class="lg:flex gap-4 justify-center mb-5 space-y-2 lg:space-y-0">

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Distrito</strong>

            <p>{{ $antecedente->propiedad->distrito }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Tomo</strong>

            <p>{{ $antecedente->propiedad->tomo }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Regsitro</strong>

            <p>{{ $antecedente->propiedad->registro }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong># Propiedad</strong>

            <p>{{ $antecedente->propiedad->noprop }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Status</strong>

            <p>{{ $antecedente->propiedad->status }}</p>

        </div>

    </div>

    <div class="lg:flex gap-4 justify-center mb-5 space-y-2 lg:space-y-0">

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Superficie: </strong>{{ $antecedente->propiedad?->superficie }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Monto: </strong>{{ $antecedente->propiedad?->monto }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Fecha de inscripción: </strong>{{ $antecedente->propiedad?->fechainscripcion }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Hora de inscripción: </strong>{{ $antecedente->propiedad?->horainscripcion }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Notaria: </strong>{{ $antecedente->propiedad?->notaria }}</p>

        </div>

    </div>

    <div class="lg:flex gap-4 justify-center mb-5 space-y-2 lg:space-y-0">

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Escritura: </strong>{{ $antecedente->propiedad?->escritura }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Clave catastral: </strong>{{ $antecedente->propiedad?->clave_catastral }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Cuenta predia: </strong>{{ $antecedente->propiedad?->cuenta_predial }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Localidad: </strong>{{ $antecedente->propiedad?->localidad }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Municipio: </strong>{{ $antecedente->propiedad?->municipio }}</p>

        </div>

    </div>

    <div class="lg:flex gap-4 justify-center mb-5 space-y-2 lg:space-y-0">

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Propietarios</strong>

            <p>{{ $antecedente->propiedad?->propietarios }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Vendedores</strong>

            <p>{{ $antecedente->propiedad?->vendedores }}</p>

        </div>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2 mb-5 mx-auto">

        <strong>Linderos</strong>

        <p>{{ $antecedente->propiedad?->Linderos }}</p>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2  mb-5 mx-auto">

        <strong>Ubicación</strong>

        <p>{{ $antecedente->propiedad?->ubicacion }}</p>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2  mb-5 mx-auto">

        <strong>Comentarios</strong>

        <p>{{ $antecedente->propiedad?->comentarios }}</p>

    </div>

</div>
