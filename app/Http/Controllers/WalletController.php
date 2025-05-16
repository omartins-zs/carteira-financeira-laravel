<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function index()
    {
        $transactions = auth()->user()
            ->transactions()
            ->with('targetUser')
            ->latest()
            ->get();

        return view('wallets.index', [
            'transactions' => $transactions,
            'users'        => User::where('id', '!=', auth()->id())->get(),
        ]);
    }

    public function showDepositForm()
    {
        return view('wallets.deposit');
    }

    public function deposit(Request $request)
    {
        $request->validate(['amount' => 'required|numeric|min:0.01']);

        DB::transaction(function () use ($request) {
            $user = auth()->user();
            $user->balance += $request->amount;
            $user->save();

            Transaction::create([
                'user_id' => $user->id,
                'type'    => 'deposit',
                'amount'  => $request->amount,
                'status'  => 'completed',
            ]);
        });

        return redirect()->route('wallets.index')
            ->with('success', 'Depósito realizado com sucesso!');
    }

    public function showTransferForm()
    {
        return view('wallets.transfer', [
            'users' => User::where('id', '!=', auth()->id())->get(),
        ]);
    }

    public function transfer(Request $request)
    {
        $request->validate([
            'to_user_id' => 'required|exists:users,id',
            'amount'     => 'required|numeric|min:0.01',
        ]);

        $from = auth()->user();
        $to   = User::findOrFail($request->to_user_id);

        if ($from->id === $to->id) {
            return back()->withErrors(['to_user_id' => 'Não é possível transferir para você mesmo.']);
        }
        if ($from->balance < $request->amount) {
            return back()->withErrors(['amount' => 'Saldo insuficiente.']);
        }

        DB::transaction(function () use ($from, $to, $request) {
            $from->balance -= $request->amount;
            $from->save();

            $to->balance += $request->amount;
            $to->save();

            Transaction::create([
                'user_id'        => $from->id,
                'target_user_id' => $to->id,
                'type'           => 'transfer',
                'amount'         => $request->amount,
                'status'         => 'completed',
            ]);
        });

        return redirect()->route('wallets.index')
            ->with('success', 'Transferência realizada com sucesso!');
    }

    public function reverse(Transaction $transaction)
    {
        $user = auth()->user();

        // Verifica propriedade e status
        if ($transaction->user_id !== $user->id || $transaction->status !== 'completed') {
            abort(403);
        }

        DB::transaction(function () use ($transaction, $user) {
            if ($transaction->type === 'deposit') {
                $user->balance -= $transaction->amount;
                $user->save();
            } elseif ($transaction->type === 'transfer' && $transaction->target_user_id) {
                $recipient = User::find($transaction->target_user_id);
                if ($recipient) {
                    $user->balance += $transaction->amount;
                    $user->save();

                    $recipient->balance -= $transaction->amount;
                    $recipient->save();
                }
            }

            $transaction->status = 'reversed';
            $transaction->save();
        });

        return redirect()->route('wallet.index')
            ->with('success', 'Transação revertida com sucesso.');
    }
}
