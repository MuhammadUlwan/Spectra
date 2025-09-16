<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateBonusRule extends Model
{
    use HasFactory;

    protected $table = 'affiliate_bonus_rules';

    protected $fillable = [
        'target_omset',          // target omset untuk dapat bonus
        'extra_percent',         // tambahan persentase bonus
        'required_min_deposit',  // minimal deposit untuk ambil bonus
        'status',                // active / inactive
    ];

    public $timestamps = true;
}
