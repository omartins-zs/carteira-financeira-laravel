<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TwoFactorCodeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'two.factor'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('verify', [TwoFactorCodeController::class, 'verify'])->name('verify');
Route::get('verify/resend', [TwoFactorCodeController::class, 'resend'])->name('verify.resend');
Route::post('verify', [TwoFactorCodeController::class, 'verifyPost'])->name('verify.post');

require __DIR__ . '/auth.php';
