<div x-data="{ open:true }">

    <div class="flex items-center w-full justify-between hover:text-red-600 transition ease-in-out duration-500 hover:bg-gray-100 rounded-xl ">


        {{ $head }}

        <button x-show="open" class="rounded-full">

            <svg @click="open = false"  xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-300 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>

        </button>

        <button x-show="!open" class="rounded-full">

            <svg x-cloak @click="open = true"  xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-300 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
            </svg>

        </button>

    </div>

    <div
        x-transition:enter="transition duration-2000 transform ease-out"
        x-transition:leave="transition duration-200 transform ease-in"
        x-transition:leave-end="opacity-0 scale-90"
        x-transition:enter-start="scale-75"
        class="flex flex-col items-center mb-3 w-full justify-between rounded-xl text-sm ml-2 gap-2"
        x-show="!open"
        x-cloak>

        {{ $body }}

    </div>

</div>
