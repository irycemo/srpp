@push('styles')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endpush
<div>

    <x-header>Inscripción de propiedad <span class="text-sm tracking-widest">Folio real: {{ $inscripcion->movimientoRegistral->folioReal->folio }} - {{ $inscripcion->movimientoRegistral->folio }}</span></x-header>

    @if($inscripcion->servicio == 'D149')

        @livewire('inscripciones.propiedad.fideicomiso-cancelacion', ['inscripcion' => $inscripcion])

    @else

        @livewire('inscripciones.propiedad.inscripcion-general', ['inscripcion' => $inscripcion])

    @endif

    @filepondScripts

</div>

@push('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@endpush
