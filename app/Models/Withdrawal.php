<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable = [
        'user_id', 'amount', 'method', 'account_name', 'account_number', 'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCodeAttribute(): string
    {
        return '#DEN-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }
}
