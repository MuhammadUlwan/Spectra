<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    // Nama tabel (opsional, kalau nama tabel berbeda dari default 'payment_methods')
    protected $table = 'payment_methods';

    // Kolom yang bisa diisi secara massal
    protected $fillable = [
        'name',
        'code',      // misal kode internal atau singkatan metode
        'active',    // 1 = aktif, 0 = nonaktif
        'details',   // optional, misal nomor rekening, keterangan dll
    ];

    // Tipe data kolom tertentu
    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Relasi: metode pembayaran bisa dipakai di banyak tarik tunai
     */
    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class, 'type'); // 'type' di tabel withdrawals menunjuk ke id PaymentMethod
    }
}
