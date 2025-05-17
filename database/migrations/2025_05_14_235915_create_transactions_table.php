<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');          // originador
            $table->unsignedBigInteger('target_user_id')->nullable(); // recebedor (nulo em depósito “externo”)
            $table->enum('type', ['deposit','transfer','reversal']);
            $table->decimal('amount', 15, 2);
            $table->string('status')->default('completed');
            $table->unsignedBigInteger('reverses_id')->nullable(); // id da tx que esta reversão “anula”
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('target_user_id')->references('id')->on('users');
            $table->foreign('reverses_id')->references('id')->on('transactions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
