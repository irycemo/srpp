<div>

    @if(auth()->user()->hasRole('Administrador'))

        <x-header class="mt-5">Cantidad de movimientos generados del día actual</x-header>

        <div class="flex gap-4 items-start w-full">

            <div class="shadow-lg bg-white rounded-xl mb-5 p-4 w-full">

                <p class="text-center tracking-wider font-semibold">Uruapan</p>

                <table class="w-full table-fixed">

                    <tbody class="divide-y divide-gray-200">

                        @php
                            $total_1 = 0;
                        @endphp

                        @foreach ($servicios_uruapan as $key => $item)

                            <tr class="text-gray-500 text-sm leading-relaxed">
                                <td class=" px-2 w-full whitespace-nowrap overflow-hidden text-ellipsis"><p>{{ $key }}</p></td>
                                <td class=" px-2 w-1/12"><p>{{ $item }}</p></td>
                            </tr>

                            @php

                                $total_1 = $total_1 + $item

                            @endphp

                        @endforeach

                        @php

                            echo " <tr class='text-gray-500 text-sm leading-relaxed'>
                                        <td class='px-2 w-full whitespace-nowrap font-bold'>Total</td>
                                        <td class='px-2 w-full font-bold'>" . $total_1 . "</td>
                                    </tr>
                                ";
                        @endphp

                    </tbody>

                </table>

            </div>

            <div class="shadow-lg bg-white rounded-xl mb-5 p-4 w-full">

                <p class="text-center tracking-wider font-semibold">Resto del estado</p>

                <table class="w-full table-fixed">

                    <tbody class="divide-y divide-gray-200">

                        @php
                            $total_2 = 0;
                        @endphp

                        @foreach ($servicios_resto as $key => $item)

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
