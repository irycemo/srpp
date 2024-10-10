<div class="w-full mt-3 text-sm" x-data="{selected : null}">

    @foreach ($folioReal->certificaciones as $certificado)

        <x-h4 class="flex items-center justify-between">

            Movimiento registral: ({{ $certificado->movimientoRegistral->folio }})

            <div class="flex gap-2">

                @foreach ($certificado->movimientoRegistral->caratula as $image)
                    <a href="{{ Storage::disk('caratulas')->url($image->url) }}" data-lightbox="imagen" data-title="Caratula">
                        <img class="h-20" src="{{ Storage::disk('caratulas')->url($image->url) }}" alt="Caratula">
                    </a>
                @endforeach

            </div>

        </x-h4>

    @endforeach

</div>
