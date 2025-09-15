<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestmentPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'amount',
        'duration_months',
        'profit_percent',
        'currency',
    ];

    public function investments()
    {
        return $this->hasMany(Investment::class, 'package_id');
    }
}
