<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
        'role', 'member_id', 'phone', 'address', 'balance', 'avatar',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isNasabah(): bool
    {
        return $this->role === 'nasabah';
    }

    public function isKaryawan(): bool
    {
        return $this->role === 'karyawan';
    }

    // Setoran milik nasabah ini
    public function transactions()
    {
        return $this->hasMany(Transaction::class)->latest();
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class)->latest();
    }

    public function dropOffReports()
    {
        return $this->hasMany(DropOffReport::class)->latest();
    }
}
