<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drop_off_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // nasabah pelapor
            $table->string('photo_path')->nullable();                       // bukti foto sampah
            $table->string('note')->nullable();
            $table->enum('status', ['menunggu', 'diproses', 'terverifikasi'])->default('menunggu');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drop_off_reports');
    }
};
