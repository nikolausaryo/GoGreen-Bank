<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('amount');                 // nominal ditarik
            $table->enum('method', ['bank', 'ewallet']);
            $table->string('account_name');                       // nama bank / nama e-wallet
            $table->string('account_number');                     // no rekening / no e-wallet
            $table->enum('status', ['menunggu', 'diproses', 'terverifikasi'])->default('menunggu');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};
