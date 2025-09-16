<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

use App\Models\Investment;
use App\Models\Commission;
use App\Models\Profit;
use App\Models\Withdrawal;
use App\Models\Notification;
use App\Models\UserPreference;

/**
 * @property int $id
 * @property string $name
 * @property string|null $email
 * @property string|null $phone
 * @property string $password
 * @property string $role
 * @property int $is_consultant
 * @property int $is_active
 * @property Carbon|null $last_login_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'password',
        'role',
        'is_consultant',
        'is_active',
        'referral_code',   // kode referral
        'konsultan_id',    // id sponsor/konsultan
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
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
        return $this->hasMany(User::class, 'konsultan_id'); // pastikan kolom sponsor_id/konsultan_id sesuai di DB
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

    public function preference()
    {
        return $this->hasOne(UserPreference::class);
    }

    /* ========================
     |  ACCESSOR STATUS
     |======================== */
    public function getStatusAttribute(): string
    {
        return $this->is_active ? 'Aktif' : 'Nonaktif';
    }

    /* ========================
     |  REFERRAL RECURSIVE
     |======================== */
    public function referralCountByLevel(int $level): int
    {
        if ($level === 1) {
            return $this->referrals()->count();
        }

        $count = 0;
        foreach ($this->referrals as $ref) {
            $count += $ref->referralCountByLevel($level - 1);
        }
        return $count;
    }

    public function referralSummary(int $maxLevel = 3): array
    {
        $summary = [];
        for ($i = 1; $i <= $maxLevel; $i++) {
            $summary[$i] = $this->referralCountByLevel($i);
        }
        return $summary;
    }
}
