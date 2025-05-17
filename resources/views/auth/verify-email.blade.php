<x-guest-layout>
    <div class="max-w-md mx-auto text-center p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
            Verifique seu e-mail
        </h1>
        <p class="mt-2 text-gray-600 dark:text-gray-300">
            Um link de verificação foi enviado para o seu e-mail. Por favor, verifique sua caixa de entrada.
        </p>

        @if (session('status') === 'verification-link-sent')
            <p class="mt-4 text-green-600 dark:text-green-400">
                Um novo link de verificação foi enviado para seu e-mail.
            </p>
        @endif

        <form method="POST" action="{{ route('verification.send') }}" class="mt-6">
            @csrf
            <x-primary-button>Reenviar link de verificação</x-primary-button>
        </form>
    </div>
</x-guest-layout>
