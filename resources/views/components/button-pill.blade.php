<button {{ $attributes->exceptProps(['class']) }} {{ $attributes->merge(['class' => 'tracking-widest py-1 px-4 text-gray-600 rounded-xl border-b-2 border-gray-500 font-thin mb-2 bg-white cursor-pointer hover:shadow-lg hover:bg-gray-50']) }} type="button">

    {{ $slot }}

</button>
