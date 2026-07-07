<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id', 'operator_id', 'waste_category_id',
        'weight', 'amount', 'method', 'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function wasteCategory()
    {
        return $this->belongsTo(WasteCategory::class);
    }

    // ID setoran tampilan, contoh: #DEP-9021
    public function getCodeAttribute(): string
    {
        return '#DEP-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }
}
