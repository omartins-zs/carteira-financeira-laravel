<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use LogicException;

class WalletService
{
    public function __construct()
    {
        //
    }

    public function getHistory(User $user)
    {
        return Transaction::where('user_id', $user->id)
            ->orWhere('target_user_id', $user->id)
            ->with('targetUser')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getOtherUsers(User $user)
    {
        return User::where('id', '!=', $user->id)->get();
    }

    /**
     * Realiza um depósito e registra a transação.
     *
     * @param User $user
     * @param float $amount
     * @throws \Throwable
     */
    public function deposit(User $user, float $amount): void
    {
        try {
            DB::transaction(function () use ($user, $amount) {
                $user->balance += $amount;
                $user->save();

                Transaction::create([
                    'user_id' => $user->id,
                    'type'    => 'deposit',
                    'amount'  => $amount,
                    'status'  => 'completed',
                ]);
            });
        } catch (\Throwable $e) {
            report($e);
            Log::error('Erro ao realizar depósito', [
                'user_id' => $user->id,
                'amount' => $amount,
                'exception' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Realiza uma transferência entre usuários.
     *
     * @param User $from
     * @param int $toUserId
     * @param float $amount
     * @throws InvalidArgumentException
     * @throws \Throwable
     */
    public function transfer(User $from, int $toUserId, float $amount): void
    {
        if ($from->balance < $amount) {
            throw new InvalidArgumentException('Saldo insuficiente.');
        }

        try {
            $to = User::findOrFail($toUserId);

            DB::transaction(function () use ($from, $to, $amount) {
                $from->balance -= $amount;
                $from->save();

                $to->balance += $amount;
                $to->save();

                Transaction::create([
                    'user_id'        => $from->id,
                    'target_user_id' => $to->id,
                    'type'           => 'transfer',
                    'amount'         => $amount,
                    'status'         => 'completed',
                ]);
            });
        } catch (\Throwable $e) {
            report($e);
            Log::error('Erro ao realizar transferência', [
                'from_user_id' => $from->id,
                'to_user_id' => $toUserId,
                'amount' => $amount,
                'exception' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Reverte uma transação do tipo depósito ou transferência.
     *
     * @param User $user
     * @param Transaction $transaction
     * @throws LogicException
     * @throws \Throwable
     */
    public function reverse(User $user, Transaction $transaction): void
    {
        if ($transaction->user_id !== $user->id || $transaction->status !== 'completed') {
            throw new LogicException('Transação não pode ser revertida.');
        }

        try {
            DB::transaction(function () use ($user, $transaction) {
                if ($transaction->type === 'deposit') {
                    $user->balance -= $transaction->amount;
                    $user->save();
                } elseif ($transaction->type === 'transfer' && $transaction->target_user_id) {
                    $recipient = User::find($transaction->target_user_id);
                    if (! $recipient) {
                        throw new LogicException('Usuário recebedor não encontrado.');
                    }

                    $user->balance += $transaction->amount;
                    $user->save();

                    $recipient->balance -= $transaction->amount;
                    $recipient->save();
                }

                $transaction->status = 'reversed';
                $transaction->save();
            });
        } catch (\Throwable $e) {
            report($e);
            Log::error('Erro ao reverter transação', [
                'user_id' => $user->id,
                'transaction_id' => $transaction->id,
                'type' => $transaction->type,
                'exception' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
