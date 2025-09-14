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

            if (in_array($status, ['active', 'cancelled'])) {
                $investment->validated_by = Auth::id();
                $investment->validated_at = now();
            } else {
                $investment->validated_by = null;
                $investment->validated_at = null;
            }

            $investment->save();

            // Redirect ke index dengan flash message
            return redirect()->route('admin.investments.index')
                 ->with('success', 'Status berhasil diperbarui');
        }
}
