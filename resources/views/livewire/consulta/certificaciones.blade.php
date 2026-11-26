<div class="w-full mt-3 text-sm" x-data="{selected : null}">

    @foreach ($folioReal->certificaciones as $certificado)

        <x-h4 class="flex items-center justify-between">

            <div class="flex items-center gap-4">

                Movimiento registral: ({{ $certificado->movimientoRegistral->folio }})

                <span class="bg-{{ $certificado->movimientoRegistral->estado_color }} py-1 px-2 rounded-full text-white text-xs whitespace-nowrap">{{ ucfirst($certificado->movimientoRegistral->estado) }}</span>

            </div>

            <div class="flex gap-2 text-xs">

                @if($certificado->movimientoRegistral->caratula())

                    <x-link-blue target="_blank" href="{{ $certificado->movimientoRegistral->caratula() }}">Caratula</x-link-blue>

                @endif

            </div>

        </x-h4>

    @endforeach

</div>
