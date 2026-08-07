<div>

    <x-header>Reporte de productividad</x-header>

    <div class="md:flex md:flex-row flex-col md:space-x-4 items-end justify-center bg-white rounded-xl mb-5 p-4 gap-4">

        <div>

            <div>

                <Label>Fecha inicial</Label>

            </div>

            <div>

                <input type="date" class="bg-white rounded text-sm " wire:model.live="fecha1">

            </div>

            <div>

                @error('fecha1') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

            </div>

        </div>

        <div class="mt-2 md:mt-0">

            <div>

                <Label>Fecha final</Label>

            </div>

            <div>

                <input type="date" class="bg-white rounded text-sm " wire:model.live="fecha2">

            </div>

            <div>

                @error('fecha2') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

            </div>

        </div>

    </div>

    <div class="md:flex flex-col md:flex-row justify-center md:space-x-3 items-center bg-white rounded-xl mb-5 p-4">

        <div class="">

            <div>

                <Label>Distrito</Label>
            </div>

            <div>

                <select class="rounded text-sm w-full" wire:model.live="distrito">

                    <option value="" selected>Todo el estado</option>

                    @foreach ($distritos as $key => $distrito_item)

                        <option value="{{ $key }}" >{{ $distrito_item }}</option>

                    @endforeach

                </select>

            </div>

            <div>

                @error('estado') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

            </div>

        </div>

        <div class="">

            <div>

                <Label>Servicios</Label>
            </div>

            <div>

                <select class="rounded text-sm w-full" wire:model.live="servicio">

                    <option value="" selected>Seleccione una opción</option>

                    @foreach ($servicios as $servicios)

                        <option value="{{ $servicios }}" >{{$servicios}}</option>

                    @endforeach

                </select>

            </div>

            <div>

                @error('estado') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

            </div>

        </div>

    </div>

    <div class="shadow-lg bg-white rounded-xl mb-5 p-4 w-full">

        <p class="text-center tracking-wider font-semibold">{{ $distrito === "" ? 'Resto del estado' : $distritos[$distrito] }}</p>

        <table class="w-full lg:w-1/2 table-fixed mx-auto">

            <tbody class="divide-y divide-gray-200">

                @php
                    $total_2 = 0;
                @endphp

                @foreach ($movimientos as $key => $item)

                    <tr class="text-gray-500 text-sm leading-relaxed">
                        <td class=" px-2 w-full whitespace-nowrap overflow-hidden text-ellipsis"><p>{{ $key }}</p></td>
                        <td class=" px-2 w-1/12"><p>{{ $item }}</p></td>
                    </tr>

                    @php

                        $total_2 = $total_2 + $item

                    @endphp

                @endforeach

                @php

                    echo " <tr class='text-gray-500 text-sm leading-relaxed'>
                                <td class='px-2 w-full whitespace-nowrap font-bold'>Total</td>
                                <td class='px-2 w-full font-bold'>" . $total_2 . "</td>
                            </tr>
                        ";
                @endphp

            </tbody>

        </table>

    </div>

</div>
