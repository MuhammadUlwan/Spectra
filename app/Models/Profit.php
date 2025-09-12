<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profit extends Model
{
    use HasFactory;

    protected $fillable = [
        'investment_id',
        'user_id',
        'period_start',
        'period_end',
        'profit_amount',
        'status',
        'paid_at',
    ];

    public function investment()
    {
        return $this->belongsTo(Investment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
