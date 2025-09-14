<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package_id',
        'amount',
        'start_date',
        'end_date',
        'status',
        'proof_transfer',
        'validated_by',
        'validated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(InvestmentPackage::class, 'package_id');
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function profits()
    {
        return $this->hasMany(Profit::class);
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class, 'source_investment');
    }
}
