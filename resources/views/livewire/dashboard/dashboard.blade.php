<div>

    @if(auth()->user()->hasRole(['Administrador', 'Jefe de departamento jurídico', 'Director']))

        @include('livewire.dashboard.propiedad-dashboard', ['inscripcion' => $propiedad, 'titulo' => 'Inscripciones de propiedad'])

        @include('livewire.dashboard.propiedad-dashboard', ['inscripcion' => $gravamen, 'titulo' => 'Inscripciones de gravamen'])

        @include('livewire.dashboard.propiedad-dashboard', ['inscripcion' => $sentencia, 'titulo' => 'Inscripciones de sentencias'])

        @include('livewire.dashboard.propiedad-dashboard', ['inscripcion' => $cancelacion, 'titulo' => 'Inscripciones de cancelación'])

        @include('livewire.dashboard.propiedad-dashboard', ['inscripcion' => $varios, 'titulo' => 'Inscripciones de varios'])

        @include('livewire.dashboard.propiedad-dashboard', ['inscripcion' => $certificado_propiedad, 'titulo' => 'Certificados de propiedad'])

        @include('livewire.dashboard.propiedad-dashboard', ['inscripcion' => $certificado_gravamen, 'titulo' => 'Certificados de gravamen'])

        @include('livewire.dashboard.propiedad-dashboard', ['inscripcion' => $reforma, 'titulo' => 'Inscripciones de folio real de persona moral'])

    @elseif(auth()->user()->hasRole(['Jefe de departamento certificaciones', 'Supervisor certificaciones']))

        @include('livewire.dashboard.propiedad-dashboard', ['inscripcion' => $certificado_propiedad, 'titulo' => 'Certificados de propiedad'])

        @include('livewire.dashboard.propiedad-dashboard', ['inscripcion' => $certificado_gravamen, 'titulo' => 'Certificados de gravamen'])

    @elseif(auth()->user()->hasRole(['Jefe de departamento inscripciones', 'Supervisor inscripciones']))

        @include('livewire.dashboard.propiedad-dashboard', ['inscripcion' => $propiedad, 'titulo' => 'Inscripciones de propiedad'])

        @include('livewire.dashboard.propiedad-dashboard', ['inscripcion' => $gravamen, 'titulo' => 'Inscripciones de gravamen'])

        @include('livewire.dashboard.propiedad-dashboard', ['inscripcion' => $sentencia, 'titulo' => 'Inscripciones de sentencias'])

        @include('livewire.dashboard.propiedad-dashboard', ['inscripcion' => $cancelacion, 'titulo' => 'Inscripciones de cancelación'])

        @include('livewire.dashboard.propiedad-dashboard', ['inscripcion' => $varios, 'titulo' => 'Inscripciones de varios'])

        @include('livewire.dashboard.propiedad-dashboard', ['inscripcion' => $reforma, 'titulo' => 'Inscripciones de folio real de persona moral'])

    @elseif(auth()->user()->hasRole(['Registrador Propiedad', 'Propiedad']))

        @include('livewire.dashboard.propiedad-dashboard', ['inscripcion' => $propiedad, 'titulo' => 'Inscripciones de propiedad'])

    @elseif(auth()->user()->hasRole(['Registrador Gravamen', 'Gravamen']))

        @include('livewire.dashboard.propiedad-dashboard', ['inscripcion' => $gravamen, 'titulo' => 'Inscripciones de gravamen'])

    @elseif(auth()->user()->hasRole(['Registrador Sentencias']))

        @include('livewire.dashboard.propiedad-dashboard', ['inscripcion' => $sentencia, 'titulo' => 'Inscripciones de sentencias'])

    @elseif(auth()->user()->hasRole(['Registrador Cancelación', 'Cancelación']))

        @include('livewire.dashboard.propiedad-dashboard', ['inscripcion' => $cancelacion, 'titulo' => 'Inscripciones de cancelación'])

    @elseif(auth()->user()->hasRole(['Registrador Varios', 'Varios']))

        @include('livewire.dashboard.propiedad-dashboard', ['inscripcion' => $varios, 'titulo' => 'Inscripciones de varios'])

    @elseif(auth()->user()->hasRole(['Certificador Propiedad']))

        @include('livewire.dashboard.propiedad-dashboard', ['inscripcion' => $certificado_propiedad, 'titulo' => 'Certificados de propiedad'])

    @elseif(auth()->user()->hasRole(['Certificador Gravamen']))

        @include('livewire.dashboard.propiedad-dashboard', ['inscripcion' => $certificado_gravamen, 'titulo' => 'Certificados de gravamen'])

    @elseif(auth()->user()->hasRole(['Pase a folio']))

        @include('livewire.dashboard.propiedad-dashboard', ['inscripcion' => $pase_a_folio, 'titulo' => 'Pases a folio'])

    @elseif(auth()->user()->hasRole(['Folio real moral']))

        @include('livewire.dashboard.propiedad-dashboard', ['inscripcion' => $reforma, 'titulo' => 'Actas de asamblea'])

    @endif

    <x-header class="mt-5">Nuevas preguntas frecuentes</x-header>

    <div class="bg-white shadow-xl rounded-lg p-4 mt-5" wire:loading.class.delaylongest="opacity-50">

        <div class="w-full lg:w-1/2 mx-auto ">

            <ul class="w-full space-y-3">

                @foreach ($preguntas as $item)

                    <li class="cursor-pointer hover:bg-gray-100 rounded-lg text-gray-700 border border-gray-300 flex justify-between">

                        <a href="{{ route('consultas.preguntas') . '?search=' . $item->titulo }}" class="w-full h-full p-3 flex justify-between items-center">

                            <span>{{ $item->titulo }}</span>

                        </a>

                    </li>

                @endforeach

                <li class="cursor-pointer bg-gray-200 rounded-lg text-gray-700 border border-gray-400 flex justify-between ">

                    <a href="{{ route('consultas.preguntas') }}" class="w-full h-full p-1 flex justify-center items-center text-gray-700">

                       Ver mas

                    </a>

                </li>

            </ul>

        </div>

    </div>

</div>
