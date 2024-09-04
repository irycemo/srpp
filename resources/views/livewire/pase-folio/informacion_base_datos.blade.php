<div class="bg-white rounded-lg p-2 mb-3 shadow-lg">

    <span class="flex items-center justify-center text-lg text-gray-700">Información de la base de datos</span>

    <div class="space-y-1 text-sm">

        @if($propiedadOld->superficie)

            <div class="rounded-lg bg-gray-100 py-1 px-2">

                <p><strong>Superficie</strong> {{ $propiedadOld->superficie }}</p>

            </div>

        @endif

        @if($propiedadOld->monto)

            <div class="rounded-lg bg-gray-100 py-1 px-2">

                <p><strong>Monto</strong> {{ $propiedadOld->monto / 100 }} - <strong>Divisa</strong> {{ $propiedadOld->tipomon }}</p>

            </div>

        @endif

        @if($propiedadOld->fechainscripcion)

            <div class="rounded-lg bg-gray-100 py-1 px-2">

                <p><strong>Fecha de inscripción</strong> {{ $propiedadOld->fechainscripcion }}</p>

            </div>

        @endif

        @if($propiedadOld->notaria)

            <div class="rounded-lg bg-gray-100 py-1 px-2">

                <p><strong>Notaria</strong> {{ $propiedadOld->notaria }}</p>

            </div>

        @endif

        @if($propiedadOld->escritura)

            <div class="rounded-lg bg-gray-100 py-1 px-2">

                <p><strong>Escritura</strong> {{ $propiedadOld->escritura }}</p>

            </div>

        @endif

        @if($propiedadOld->comentarios)

            <div class="rounded-lg bg-gray-100 py-1 px-2">

                <p><strong>Comentarios</strong> {{ $propiedadOld->comentarios }}</p>

            </div>

        @endif

        @if($propiedadOld->Linderos)

            <div class="rounded-lg bg-gray-100 py-1 px-2">


                <p><strong>Linderos</strong> {{ $propiedadOld->Linderos }}</p>

            </div>

        @endif

        @if($propiedadOld->ubicacion)

            <div class="rounded-lg bg-gray-100 py-1 px-2">

                <p><strong>Ubicación</strong> {{ $propiedadOld->ubicacion }}</p>

            </div>

        @endif

        @if($propiedadOld->propietarios)

            <div class="rounded-lg bg-gray-100 py-1 px-2">

                <p><strong>Propietarios</strong> {{ $propiedadOld->propietarios }}</p>

            </div>

        @endif

        @if($propiedadOld->vendedores)

            <div class="rounded-lg bg-gray-100 py-1 px-2">

                <p><strong>Vendedores</strong> {{ $propiedadOld->vendedores }}</p>

            </div>

        @endif

    </div>

</div>
