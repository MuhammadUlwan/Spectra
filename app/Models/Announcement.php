<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image_path',
        'active',
        'order',
    ];

    // helper: kembalikan URL final (bisa local storage atau external)
    public function getImageUrlAttribute(): string
    {
        if (empty($this->image_path)) {
            return '';
        }

        // jika sudah URL (http/https), kembalikan langsung
        if (str_starts_with($this->image_path, 'http')) {
            return $this->image_path;
        }

        return asset('storage/' . $this->image_path);
    }
}
