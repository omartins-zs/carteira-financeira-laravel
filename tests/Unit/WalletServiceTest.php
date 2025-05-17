<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Transaction;
use App\Services\WalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use LogicException;

class WalletServiceTest extends TestCase
{
    use RefreshDatabase;

    private WalletService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new WalletService();
    }

    public function test_deposit_increases_user_balance_and_creates_transaction()
    {
        $user = User::factory()->create(['balance' => 0.0]);

        $this->service->deposit($user, 150.00);

        $this->assertDatabaseHas('users', [
            'id'      => $user->id,
            'balance' => 150.00,
        ]);

        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'type'    => 'deposit',
            'amount'  => 150.00,
            'status'  => 'completed',
        ]);
    }

    public function test_transfer_moves_funds_and_creates_transaction()
    {
        $from = User::factory()->create(['balance' => 200.0]);
        $to   = User::factory()->create(['balance' => 50.0]);

        $this->service->transfer($from, $to->id, 100.00);

        $this->assertDatabaseHas('users', [
            'id'      => $from->id,
            'balance' => 100.00,
        ]);
        $this->assertDatabaseHas('users', [
            'id'      => $to->id,
            'balance' => 150.00,
        ]);

        $this->assertDatabaseHas('transactions', [
            'user_id'        => $from->id,
            'target_user_id' => $to->id,
            'type'           => 'transfer',
            'amount'         => 100.00,
            'status'         => 'completed',
        ]);
    }

    public function test_transfer_throws_exception_if_insufficient_balance()
    {
        $from = User::factory()->create(['balance' => 30.0]);
        $to   = User::factory()->create(['balance' => 0.0]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Saldo insuficiente.');

        $this->service->transfer($from, $to->id, 100.00);
    }

    public function test_reverse_reverts_a_deposit_transaction()
    {
        $user = User::factory()->create(['balance' => 100.0]);
        $tx = Transaction::factory()->create([
            'user_id' => $user->id,
            'type'    => 'deposit',
            'amount'  => 100.00,
            'status'  => 'completed',
        ]);

        $this->service->reverse($user, $tx);

        $this->assertDatabaseHas('users', [
            'id'      => $user->id,
            'balance' => 0.00,
        ]);
        $this->assertDatabaseHas('transactions', [
            'id'     => $tx->id,
            'status' => 'reversed',
        ]);
    }

    public function test_reverse_reverts_a_transfer_transaction()
    {
        $from = User::factory()->create(['balance' => 200.0]);
        $to   = User::factory()->create(['balance' => 50.0]);

        // cria a transferência
        $tx = Transaction::factory()->create([
            'user_id'        => $from->id,
            'target_user_id' => $to->id,
            'type'           => 'transfer',
            'amount'         => 100.00,
            'status'         => 'completed',
        ]);

        // ajusta saldos iniciais
        $from->balance -= 100.00;
        $from->save();
        $to->balance += 100.00;
        $to->save();

        $this->service->reverse($from, $tx);

        $this->assertDatabaseHas('users', [
            'id'      => $from->id,
            'balance' => 200.00, // voltou ao original
        ]);
        $this->assertDatabaseHas('users', [
            'id'      => $to->id,
            'balance' => 50.00,  // voltou ao original
        ]);
        $this->assertDatabaseHas('transactions', [
            'id'     => $tx->id,
            'status' => 'reversed',
        ]);
    }
}
