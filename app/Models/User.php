<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// 🔽 import semua relasi agar editor paham
use App\Models\Investment;
use App\Models\Referral;
use App\Models\Commission;
use App\Models\Profit;
use App\Models\Withdrawal;
use App\Models\Notification;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    /* ========================
     |  RELASI ANTAR MODEL
     |======================== */

    public function investments()
    {
        return $this->hasMany(Investment::class);
    }

    public function referrals()
    {
        return $this->hasMany(Referral::class, 'sponsor_id');
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    public function deposits()
    {
        return $this->hasMany(Investment::class, 'user_id');
    }

    public function profits()
    {
        return $this->hasMany(Profit::class, 'user_id');
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
