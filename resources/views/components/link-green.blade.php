<a {{ $attributes }} {{ $attributes->merge(['class' => 'bg-green-400 hover:shadow-lg text-white px-4 py-1 rounded-full text-sm hover:bg-green-700 flex items-center justify-center focus:outline-green-400 focus:outline-offset-2']) }}>

    {{ $slot }}

</a>
