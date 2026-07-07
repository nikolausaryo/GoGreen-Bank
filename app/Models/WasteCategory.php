<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WasteCategory extends Model
{
    protected $fillable = ['name', 'category', 'price', 'unit', 'icon'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
