<div>

    <x-header>Inscripción de propiedad <span class="text-sm tracking-widest">Folio real: {{ $inscripcion->movimientoRegistral->folioReal->folio }}</span></x-header>

    <div class="bg-white rounded-lg p-2 shadow-xl grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-1 text-sm mb-3">

        {{-- <span class="flex items-center justify-center text-base text-gray-700 md:col-span-3 col-span-1 sm:col-span-2 lg:col-span-6">Antecedente</span>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Tomo:</strong> {{ $inscripcion->movimientoRegistral->tomo }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Registro:</strong> {{ $inscripcion->movimientoRegistral->registro }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Número de propiedad:</strong> {{ $inscripcion->movimientoRegistral->numero_propiedad }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Distrito:</strong> {{ $inscripcion->movimientoRegistral->distrito }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Sección:</strong> {{ $inscripcion->movimientoRegistral->seccion }}</p>

        </div>

        <span class="flex items-center justify-center text-base text-gray-700 md:col-span-3 col-span-1 sm:col-span-2 lg:col-span-6">Documento de entrada</span>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Tipo de documento: </strong> {{ $inscripcion->movimientoRegistral->tipo_documento }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Autoridad cargo: </strong> {{ $inscripcion->movimientoRegistral->autoridad_cargo }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Nombre de la autoridad:</strong> {{ $inscripcion->movimientoRegistral->autoridad_nombre }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Número de documento: </strong> {{ $inscripcion->movimientoRegistral->numero_documento }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Fecha de emisión:</strong> {{ $inscripcion->movimientoRegistral->fecha_emision }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Procedencia:</strong> {{ $inscripcion->movimientoRegistral->procedencia }}</p>

        </div> --}}

    </div>

    <div class="bg-white rounded-lg p-4 shadow-lg mb-4">

        <x-input-group for="inscripcion.acto_contenido" label="Acto" :error="$errors->first('inscripcion.acto_contenido')" class="w-full lg:w-1/4 mx-auto mb-2">

            <x-input-select id="inscripcion.acto_contenido" wire:model.live="inscripcion.acto_contenido" class="">

                <option value="">Seleccione una opción</option>

                @foreach ($actos as $acto)

                    <option value="{{ $acto }}">{{ $acto }}</option>

                @endforeach

            </x-input-select>

        </x-input-group>

    </div>

    @if($inscripcion->acto_contenido)

        @livewire('inscripciones.propiedad.inscripcion-general', ['inscripcion' => $inscripcion])

    @endif

</div>
