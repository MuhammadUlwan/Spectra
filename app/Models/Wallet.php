<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AffiliateCommission;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'description',
        'reference_id',
        'status',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke affiliate_commissions untuk fetch bonus / sponsor
    public function affiliateCommissions()
    {
        return $this->hasMany(AffiliateCommission::class, 'user_id', 'user_id');
    }

    /**
     * Helper untuk fetch semua wallet entries termasuk deposit, profit, bonus, sponsor
     */
    public static function fetchWalletData($user)
    {
        $wallets = collect();

        // ===== Deposit =====
        $deposits = $user->deposits()
            ->whereIn('status', ['active','success','completed','paid'])
            ->get();
        $depositBalance = $deposits->sum('amount');
        $wallets = $wallets->merge($deposits->map(fn($d) => [
            'id' => $d->id,
            'type' => 'deposit',
            'amount' => $d->amount,
            'status' => $d->status,
            'created_at' => $d->created_at,
        ]));

        // ===== Profit =====
        $profits = $user->profits()->where('status', 'paid')->get();
        $totalProfit = $profits->sum('profit_amount');
        $wallets = $wallets->merge($profits->map(fn($p) => [
            'id' => $p->id,
            'type' => 'profit',
            'amount' => $p->profit_amount,
            'status' => $p->status,
            'created_at' => $p->created_at,
        ]));

        // ===== Affiliate Commissions (Bonus + Sponsor + Affiliate Bonus) =====
        $affiliates = AffiliateCommission::where('user_id', $user->id)
            ->where('status', 'paid')
            ->get();

        // Hitung Extra Bonus & Sponsor Fee
        $extraBonus = $affiliates->filter(fn($a) => strtolower($a->type) === 'bonus')->sum('amount');
        $sponsorFee = $affiliates->filter(fn($a) => in_array(strtolower($a->type), ['sponsor','affiliate_bonus']))->sum('amount');

        // Merge semua affiliate commissions ke wallet
        $wallets = $wallets->merge($affiliates->map(fn($a) => [
            'id' => $a->id,
            'type' => match(strtolower($a->type)) {
                'bonus' => 'extra_bonus',
                'affiliate_bonus' => 'affiliate_bonus',
                'sponsor' => 'sponsor_fee',
                default => 'other'
            },
            'amount' => $a->amount,
            'status' => $a->status,
            'created_at' => $a->created_at,
        ]));

        // ===== Urutkan berdasarkan created_at descending =====
        $wallets = $wallets->sortByDesc('created_at')->values();

        return [$wallets, $depositBalance, $totalProfit, $extraBonus, $sponsorFee];
    }
}
