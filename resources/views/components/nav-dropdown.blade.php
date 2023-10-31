<div x-data="{ open:true }">

    <div class="flex items-center mb-3 w-full justify-between hover:text-red-600 transition ease-in-out duration-500 hover:bg-gray-100 rounded-xl ">


        {{ $head }}

        <svg @click="open = false" x-show="open" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-gray-300 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>

        <svg @click="open = true" x-show="!open" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-gray-300 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
        </svg>

    </div>

    <div
        x-transition:enter="transition duration-2000 transform ease-out"
        x-transition:leave="transition duration-200 transform ease-in"
        x-transition:leave-end="opacity-0 scale-90"
        x-transition:enter-start="scale-75"
        class="flex flex-col items-center mb-3 w-full justify-between rounded-xl text-sm ml-2 gap-2"
        x-show="!open">

        {{ $body }}

    </div>

</div>
