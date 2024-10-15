<div class="w-full mt-3 text-sm" x-data="{selected : null}">

    @foreach ($folioReal->certificaciones as $certificado)

        <x-h4 class="flex items-center justify-between">

            Movimiento registral: ({{ $certificado->movimientoRegistral->folio }})

            <div class="flex gap-2 text-xs">

                @if($certificado->movimientoRegistral->caratula())

                    <x-link-blue target="_blank" href="{{ $certificado->movimientoRegistral->caratula() }}">Caratula</x-link-blue>

                @endif

            </div>

        </x-h4>

    @endforeach

</div>
