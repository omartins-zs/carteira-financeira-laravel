<?php

namespace App\Http\Controllers;

use App\Notifications\TwoFactorCodeNotification;
use Illuminate\Http\Request;

class TwoFactorCodeController extends Controller
{
    public function verify()
    {
        return view('auth.verify');
    }

    public function resend(Request $request)
    {
        auth()->user()->regenerateTwoFactorCode();
        auth()->user()->notify(new TwoFactorCodeNotification());

        return back()->with('success', "Reenviamos o código de autenticação de dois fatores!");
    }

    public function verifyPost(Request $request)
    {
        $request->validate([
            "code" => "required",
        ]);

        $user = auth()->user();

        if ($user->two_factor_code !== $request->code) {
            return back()->with("error", "Código incorreto.");
        }

        if ($user->two_factor_expires_at < now()) {
            return back()->with("error", "O código de autenticação expirou.");
        }

        $user->clearTwoFactorCode();

        return redirect()->route("dashboard")->with("success", "Código de autenticação de dois fatores verificado com sucesso.");
    }
}
