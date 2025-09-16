<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallet;
use App\Models\Profit;
use App\Models\Deposit;
use App\Models\AffiliateCommission; // sudah ada model baru

class WalletController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        [$wallets, $depositBalance, $totalProfit, $extraBonus, $sponsorFee] = $this->fetchWalletData($user);

        return inertia('Investor/Dompet', [
            'auth' => ['user' => $user],
            'depositBalance' => $depositBalance,
            'totalProfit'    => $totalProfit,
            'extraBonus'     => $extraBonus,
            'sponsorFee'     => $sponsorFee,
            'wallets'        => $wallets,
        ]);
    }

    public function getWalletBalance()
    {
        $user = Auth::user();

        [$wallets, $depositBalance, $totalProfit, $extraBonus, $sponsorFee] = $this->fetchWalletData($user);

        return response()->json([
            'depositBalance' => $depositBalance,
            'totalProfit'    => $totalProfit,
            'extraBonus'     => $extraBonus,
            'sponsorFee'     => $sponsorFee,
            'wallets'        => $wallets,
        ]);
    }

    private function fetchWalletData($user)
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

        // ===== Extra Bonus =====
        $extraBonuses = AffiliateCommission::where('user_id', $user->id)
            ->where('type', 'bonus')
            ->where('status', 'paid')
            ->get();
        $extraBonus = $extraBonuses->sum('amount');
        $wallets = $wallets->merge($extraBonuses->map(fn($c) => [
            'id' => $c->id,
            'type' => 'extra_bonus',
            'amount' => $c->amount,
            'status' => $c->status,
            'created_at' => $c->created_at,
        ]));

        // ===== Sponsor Fee / Affiliate =====
        $sponsorFees = AffiliateCommission::where('user_id', $user->id)
            ->where('status', 'paid')
            ->whereIn('type', ['sponsor', 'sponsor_fee', 'affiliate_bonus'])
            ->get();

        $sponsorFee = $sponsorFees->sum('amount');

        $wallets = $wallets->merge($sponsorFees->map(fn($c) => [
            'id' => $c->id,
            'type' => in_array($c->type, ['sponsor', 'sponsor_fee']) ? 'sponsor_fee' : 'affiliate_bonus',
            'amount' => $c->amount,
            'status' => $c->status,
            'created_at' => $c->created_at,
        ]));

        // ===== Urutkan descending =====
        $wallets = $wallets->sortByDesc('created_at')->values();

        return [$wallets, $depositBalance, $totalProfit, $extraBonus, $sponsorFee];
    }
}
