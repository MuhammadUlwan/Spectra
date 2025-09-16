<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateCommission extends Model
{
    use HasFactory;

    protected $table = 'affiliate_commissions'; // pastikan nama tabel sesuai

    protected $fillable = [
        'user_id',
        'from_user_id',
        'type',
        'amount',
        'status',
        'level',
    ];

    // Relasi ke user penerima
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke user yang memberi / sponsor
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }
}
