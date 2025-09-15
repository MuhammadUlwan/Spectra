<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminInvestmentController extends Controller
{
    /**
     * Tampilkan daftar investasi
     */
    public function index()
    {
        $investments = Investment::with(['user', 'package', 'validator'])->get();

        return Inertia::render('Admin/Investments', [
            'auth' => ['user' => Auth::user()],
            'logoutUrl' => route('logout'),
            'profileUrl'    => route('admin.profile'),
            'investments' => $investments,
            'currencySymbol' => 'USD', // softcode, bisa ganti sesuai admin
        ]);
    }

    /**
     * Tampilkan detail investasi
     */
    public function show($id)
    {
        $investment = Investment::with(['user', 'package', 'validator'])->findOrFail($id);

        return Inertia::render('Admin/InvestmentDetail', [
            'investment' => $investment
        ]);
    }

    /**
     * Update status investasi (approve/reject)
     */
    // AdminInvestmentController.php
    public function updateStatus(Request $request, $id)
    {
        $investment = Investment::findOrFail($id);
        $status = $request->input('status');

        if (!in_array($status, ['active', 'pending', 'completed', 'cancelled'])) {
            return back()->with('error', 'Status tidak valid');
        }

        $investment->status = $status;

        if ($status === 'active') {
            $investment->validated_by = Auth::id();
            $investment->validated_at = now();
            $investment->start_date = now()->toDateString();
            $investment->end_date = now()->addMonths($investment->package->duration_months)->toDateString();
        } elseif ($status === 'cancelled') {
            $investment->validated_by = Auth::id();
            $investment->validated_at = now();
            $investment->start_date = null;
            $investment->end_date = null;
        } else {
            $investment->validated_by = null;
            $investment->validated_at = null;
            $investment->start_date = null;
            $investment->end_date = null;
        }

        $investment->save();

        return redirect()->route('admin.investments.index')
            ->with('success', 'Status berhasil diperbarui');
    }
}