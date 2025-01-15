<button {{ $attributes->exceptProps(['class']) }} {{ $attributes->merge(['class' => 'bg-blue-400 hover:shadow-lg text-white px-4 py-1 rounded-full text-sm hover:bg-blue-700 flex items-center justify-center focus:outline-blue-400 focus:outline-offset-2']) }}>

    {{ $slot }}

</button>
