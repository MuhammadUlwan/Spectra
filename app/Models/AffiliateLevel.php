<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateLevel extends Model
{
    use HasFactory;

    protected $table = 'affiliate_levels';

    protected $fillable = [
        'level',        // Level number, misal 1,2,3
        'type',         // sponsor_fee / profit_sharing
        'percent',      // persentase komisi
        'description',  // deskripsi level
    ];

    // Jika ingin timestamp created_at / updated_at otomatis
    public $timestamps = true;
}
