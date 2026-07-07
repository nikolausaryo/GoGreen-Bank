<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DropOffReport extends Model
{
    protected $fillable = ['user_id', 'photo_path', 'note', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
