<div class="bg-white rounded-lg p-4 shadow-xl grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 text-sm mb-3">

    <span class="flex items-center justify-center text-lg text-gray-700 md:col-span-3 col-span-1 sm:col-span-2 lg:col-span-6">Antecedente</span>

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <strong>Folio real</strong>

        <p>{{ $folioReal->folioRealAntecedente?->folio }}</p>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <strong>Tomo</strong>

        <p>{{ $folioReal->tomo_antecedente }}</p>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <strong>Registro</strong>

        <p>{{ $folioReal->registro_antecedente }}</p>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <strong>Número de propiedad</strong>

        <p>{{ $folioReal->numero_propiedad_antecedente }}</p>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <strong>Distrito</strong>

        <p>{{ $folioReal->distrito }}</p>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <strong>Sección</strong>

        <p>{{ $folioReal->seccion_antecedente }}</p>

    </div>

</div>

<div class="bg-white rounded-lg p-4 shadow-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 text-sm">

    <span class="flex items-center justify-center text-lg text-gray-700 md:col-span-3 col-span-1 sm:col-span-2 lg:col-span-5">Documento de entrada</span>

    @if($folioReal->tipo_documento === 'oficio')

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Autoridad cargo</strong>

            <p>{{ $folioReal->autoridad_cargo }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Nombre de la autoridad</strong>

            <p>{{ $folioReal->autoridad_nombre }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Número de la autoridad</strong>

            <p>{{ $folioReal->autoridad_numero }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Número de documento</strong>

            <p>{{ $folioReal->numero_documento }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>
                Fecha de emisión</strong>

            <p>{{ $folioReal->fecha_emision }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Fecha de inscripción</strong>

            <p>{{ $folioReal->fecha_inscripcion }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Dependencia</strong>

            <p>{{ $folioReal->procedencia }}</p>

        </div>

    @elseif($folioReal->tipo_documento === 'escritura')

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Número de escritura</strong>

            <p>{{ $folioReal->predio->escritura->numero }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Número de notaría</strong>

            <p>{{ $folioReal->predio->escritura->notaria }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Nombre del notario</strong>

            <p>{{ $folioReal->predio->escritura->nombre_notario }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Estado del notario</strong>

            <p>{{ $folioReal->predio->escritura->estado_notario }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Fecha de inscripcion</strong>

            <p>{{ $folioReal->predio->escritura->fecha_inscripcion }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Fecha de la escritura</strong>

            <p>{{ $folioReal->predio->escritura->fecha_escritura }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Número de hojas</strong>

            <p>{{ $folioReal->predio->escritura->numero_hojas }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <strong>Número de paginas</strong>

            <p>{{ $folioReal->predio->escritura->numero_paginas }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2 col-span-1 sm:col-span-2 md:col-span-3 lg:col-span-5">

            <strong>Observaciones</strong>

            <p>{{ $folioReal->predio->escritura->comentario }}</p>

        </div>

    @endif

</div>
