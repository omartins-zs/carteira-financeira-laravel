<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    @if (session('success'))
        <p class="text-green">{{ session('success') }}</p>
    @endif

    <form method="POST" action="{{ route('verify.post') }}">
        @csrf

        <p class="text-white">
            Enviamos um código para o seu e-mail cadastrado. Se você não o recebeu, clique
            <a class="text-blue-400" href="{{ route('verify.resend') }}">aqui.</a>
        </p>
        <br />

        <!-- Email Address -->
        <div>
            <x-input-label for="code" :value="__('Código')" />
            <x-text-input id="code" class="block mt-1 w-full" type="text" name="code" :value="old('code')" required
                autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>


        @if (session('error'))
            <p class="text-red-400">{{ $value }}</p>
        @endif

        <div class="flex items-center justify-end mt-4">


            <x-primary-button class="ms-3">
                {{ __('Verificar') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
