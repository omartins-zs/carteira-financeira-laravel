<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepositRequest;
use App\Http\Requests\TransferRequest;
use App\Models\Transaction;
use App\Services\WalletService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WalletController extends Controller
{
    private WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Exibe o dashboard da carteira.
     */
    public function index(): View
    {
        $user = auth()->user();
        $transactions = $this->walletService->getHistory($user);
        $users = $this->walletService->getOtherUsers($user);

        return view('wallets.index', compact('transactions', 'users'));
    }

    /**
     * Exibe o formulário de depósito.
     */
    public function showDepositForm(): View
    {
        return view('wallets.deposit');
    }

    /**
     * Realiza um depósito.
     */
    public function deposit(DepositRequest $request): RedirectResponse
    {
        $user = auth()->user();

        try {
            $this->walletService->deposit($user, $request->amount);
            return redirect()->route('wallet.index')
                ->with('success', 'Depósito realizado com sucesso!');
        } catch (\Exception $e) {
            report($e);
            return back()->withErrors('deposit', 'Erro ao processar depósito.')->withInput();
        }
    }

    /**
     * Exibe o formulário de transferência.
     */
    public function showTransferForm(): View
    {
        $users = $this->walletService->getOtherUsers(auth()->user());
        return view('wallets.transfer', compact('users'));
    }

    /**
     * Realiza uma transferência.
     */
    public function transfer(TransferRequest $request): RedirectResponse
    {
        $from = auth()->user();

        try {
            $this->walletService->transfer(
                $from,
                $request->to_user_id,
                $request->amount
            );
            return redirect()->route('wallet.index')
                ->with('success', 'Transferência realizada com sucesso!');
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors($e->getMessage())->withInput();
        } catch (\Exception $e) {
            report($e);
            return back()->withErrors('transfer', 'Erro ao processar transferência.')->withInput();
        }
    }

    /**
     * Reverte uma transação.
     */
    public function reverse(Transaction $transaction): RedirectResponse
    {
        $user = auth()->user();

        try {
            $this->walletService->reverse($user, $transaction);
            return redirect()->route('wallet.index')
                ->with('success', 'Transação revertida com sucesso!');
        } catch (\LogicException $e) {
            return back()->withErrors($e->getMessage());
        } catch (\Exception $e) {
            report($e);
            return back()->withErrors('reverse', 'Erro ao reverter transação.');
        }
    }
}
