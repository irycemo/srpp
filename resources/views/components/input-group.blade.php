@props([
    'label',
    'for',
    'error' => false,
    'helpText' => false,
])

<div {{ $attributes->merge(['class']) }}>

    <label for="{{ $for }}" class="block text-sm font-medium leading-6 text-gray-900">
        {{ $label }}
    </label>

    <div class="">

        {{ $slot }}

        @if($error)

            <div class="text-red-500 text-sm mt-1"> {{ $error }} </div>

        @endif

        @if($helpText)

            <p class="mt-3 text-sm leading-6 text-gray-600">
                {{ $helpText }}
            </p>

        @endif

    </div>

</div>
