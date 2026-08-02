<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monthly_reports', function (Blueprint $table) {
            $table->id();
            $table->string('period', 7)->unique();          // format 'YYYY-MM'
            $table->string('label');                          // mis. 'Agustus 2026'
            $table->unsignedInteger('deposits_count')->default(0);
            $table->decimal('total_weight', 12, 2)->default(0);
            $table->unsignedBigInteger('total_income')->default(0);
            $table->unsignedInteger('withdrawals_count')->default(0);
            $table->unsignedBigInteger('total_withdrawal')->default(0);
            $table->unsignedInteger('active_members')->default(0);
            $table->foreignId('generated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_reports');
    }
};
