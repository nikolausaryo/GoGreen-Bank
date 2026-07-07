<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('waste_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');                 // PET Bersih, HDPE, dll
            $table->string('category');             // Plastik, Kaca, Logam, Kertas, dll
            $table->unsignedBigInteger('price');    // harga per satuan (Rupiah)
            $table->string('unit')->default('kg');  // kg, biji, liter
            $table->string('icon')->default('bi-recycle'); // bootstrap icon
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waste_categories');
    }
};
