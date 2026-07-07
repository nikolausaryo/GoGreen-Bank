<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();        // nasabah
            $table->foreignId('operator_id')->nullable()->constrained('users');    // karyawan pencatat
            $table->foreignId('waste_category_id')->constrained();
            $table->decimal('weight', 10, 2)->default(0);   // berat (kg/liter/biji)
            $table->unsignedBigInteger('amount')->default(0); // penghasilan (Rupiah)
            $table->enum('method', ['scan_qr', 'drop_off'])->default('scan_qr');
            $table->enum('status', ['menunggu', 'diproses', 'terverifikasi'])->default('diproses');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
