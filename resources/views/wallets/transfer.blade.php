@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 px-4">
    <div class="w-full max-w-md bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-8">
        <h2 class="text-3xl font-extrabold text-center text-gray-900 dark:text-gray-100 mb-6">
            Transferência
        </h2>

        <form action="{{ route('wallet.transfer') }}" method="POST">
            @csrf

            <div class="mb-5">
                <label for="to_user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Para
                </label>
                <select
                    name="to_user_id"
                    id="to_user_id"
                    required
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                >
                    <option value="">Selecione um usuário</option>
                    @foreach($users as $user)
                        @if($user->id !== auth()->id())
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endif
                    @endforeach
                </select>
                @error('to_user_id')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Valor (R$)
                </label>
                <input
                    type="number"
                    name="amount"
                    id="amount"
                    step="0.01"
                    value="{{ old('amount') }}"
                    required
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                    placeholder="0,00"
                >
                @error('amount')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <button
                type="submit"
                class="w-full flex items-center justify-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg py-3 transition"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a4 4 0 00-8 0v2H5l7 7 7-7h-4z" />
                </svg>
                <span>Confirmar Transferência</span>
            </button>
        </form>
    </div>
</div>
@endsection
