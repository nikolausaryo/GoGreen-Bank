<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyReport extends Model
{
    protected $fillable = [
        'period', 'label', 'deposits_count', 'total_weight', 'total_income',
        'withdrawals_count', 'total_withdrawal', 'active_members', 'generated_by',
    ];

    public function generator()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
