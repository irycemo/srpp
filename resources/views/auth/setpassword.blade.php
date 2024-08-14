<x-guest-layout>

    <x-authentication-card>

        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        @if(session('mensaje'))

            <div class="mb-4">

                <p>{{ session('mensaje') }}</p>

            </div>

        @endif

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('setpassword.store') }}">
            @csrf

            <input type="hidden" name="email" value="{{ $email }}">

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button class="ml-4">
                    {{ __('Guardar Contraseña') }}
                </x-button>
            </div>

        </form>

    </x-authentication-card>

</x-guest-layout>
