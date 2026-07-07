<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 'nasabah' = warga penyetor, 'karyawan' = petugas bank sampah
            $table->enum('role', ['nasabah', 'karyawan'])->default('nasabah')->after('email');
            $table->string('member_id')->nullable()->unique()->after('role'); // contoh: GGB-9021
            $table->string('phone')->nullable()->after('member_id');
            $table->string('address')->nullable()->after('phone');
            $table->unsignedBigInteger('balance')->default(0)->after('address'); // saldo tabungan (Rupiah)
            $table->string('avatar')->nullable()->after('balance');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'member_id', 'phone', 'address', 'balance', 'avatar']);
        });
    }
};
