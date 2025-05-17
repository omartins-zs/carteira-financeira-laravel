@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto p-6" x-data="{ show: false }">
        <!-- Saldo Atual -->
        <div class="bg-white shadow rounded-lg p-6 mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-semibold mb-1">Saldo Atual</h2>
                <p class="text-4xl font-bold">
                    <span x-text="show ? '{{ number_format(auth()->user()->balance, 2, ',', '.') }}' : '****,**'"></span>
                </p>
            </div>
            <button @click="show = !show" class="text-gray-500 hover:text-gray-700">
                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.27-2.944-9.544-7a10.05 10.05 0 011.715-3.11m2.278-1.783A9.969 9.969 0 0112 5c4.478 0 8.27 2.944 9.544 7a10.015 10.015 0 01-4.619 5.421M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </button>
        </div>

        <!-- Extrato -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
            <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Extrato</h3>
            <ul class="space-y-2">
                @foreach ($transactions as $tx)
                    @php
                        // cor do valor
                        $colorClass =
                            $tx->type === 'deposit'
                                ? 'text-green-600 dark:text-green-400'
                                : 'text-red-600 dark:text-red-400';
                    @endphp

                    <li
                        class="flex justify-between items-center p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <!-- data e tipo -->
                        <div>
                            <span class="text-gray-800 dark:text-gray-200">
                                {{ $tx->created_at->format('d/m H:i') }} — {{ ucfirst($tx->type) }}
                            </span>
                            @if ($tx->status === 'reversed')
                                <span class="ml-2 text-sm italic text-gray-500 dark:text-gray-400">(Revertida)</span>
                            @endif
                        </div>

                        <div class="flex items-center space-x-2">
                            <!-- valor colorido -->
                            <span class="font-medium {{ $colorClass }}">
                                {{ $tx->type === 'transfer' ? '-' : '' }}R$ {{ number_format($tx->amount, 2, ',', '.') }}
                            </span>

                            <!-- botão de reversão, só se status == completed -->
                            @if ($tx->status === 'completed')
                                <form action="{{ route('wallet.reverse', $tx) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 transition font-medium"
                                        onclick="return confirm('Tem certeza que deseja reverter esta transação?');"
                                        title="Reverter transação">
                                        Reverter
                                    </button>
                                </form>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <a href="{{ route('wallet.deposit.form') }}"
                class="bg-green-500 hover:bg-green-600 text-white p-6 rounded-lg text-center">
                <h4 class="text-lg font-semibold">Depósito</h4>
            </a>
            <a href="{{ route('wallet.transfer.form') }}"
                class="bg-blue-500 hover:bg-blue-600 text-white p-6 rounded-lg text-center">
                <h4 class="text-lg font-semibold">Transferência</h4>
            </a>
        </div>
    </div>
@endsection
