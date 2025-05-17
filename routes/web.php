<?php

use App\Http\Controllers\WalletController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TwoFactorCodeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('wallet.index');
})->middleware(['auth', 'verified', 'two.factor'])->name('dashboard');

// Rotas protegidas, na mesma ordem de middleware
Route::middleware(['auth', 'two.factor'])->group(function () {

    Route::middleware(['verified', 'two.factor'])->group(function () {
        // Carteira
        Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
        Route::get('/wallet/deposit', [WalletController::class, 'showDepositForm'])->name('wallet.deposit.form');
        Route::post('/wallet/deposit', [WalletController::class, 'deposit'])->name('wallet.deposit');
        Route::get('/wallet/transfer', [WalletController::class, 'showTransferForm'])->name('wallet.transfer.form');
        Route::post('/wallet/transfer', [WalletController::class, 'transfer'])->name('wallet.transfer');
        Route::post('/wallet/{transaction}/reverse', [WalletController::class, 'reverse'])->name('wallet.reverse');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('verify', [TwoFactorCodeController::class, 'verify'])->name('verify');
Route::get('verify/resend', [TwoFactorCodeController::class, 'resend'])->name('verify.resend');
Route::post('verify', [TwoFactorCodeController::class, 'verifyPost'])->name('verify.post');

require __DIR__ . '/auth.php';
