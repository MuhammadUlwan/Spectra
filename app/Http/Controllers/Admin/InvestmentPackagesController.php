<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvestmentPackage;
use Illuminate\Http\Request;

class InvestmentPackagesController extends Controller
{
    public function index()
    {
        $packages = InvestmentPackage::orderBy('amount')->get();

        return inertia('Admin/InvestmentPackages', [
            'auth' => ['user' => auth()->user()],
            'profileUrl' => route('admin.profile'),
            'logoutUrl' => route('logout'),
            'packages' => $packages
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'duration_months' => 'required|integer|min:1',
            'profit_percent' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
        ]);

        $package = InvestmentPackage::create($validated);

        // Return JSON jika request AJAX (Inertia)
        if ($request->wantsJson()) {
            return $request->wantsJson()
                ? response()->json(['newPackage' => $package])
                : redirect()->route('admin.deposit-packages.index');
;
        }

        return redirect()->route('admin.deposit-packages.index')
                         ->with('success', 'Paket berhasil ditambahkan');
    }

        public function update(Request $request, $id)
        {
            $investmentPackage = InvestmentPackage::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'amount' => 'required|numeric|min:0',
                'duration_months' => 'required|integer|min:1',
                'profit_percent' => 'required|numeric|min:0',
                'currency' => 'required|string|size:3',
            ]);

            $investmentPackage->update($validated);

            if ($request->wantsJson()) {
                return response()->json(['updatedPackage' => $investmentPackage]);
            }

            return redirect()->route('admin.deposit-packages.index')
                            ->with('success', 'Paket berhasil diperbarui');
        }



    public function destroy(Request $request, $id)
    {
        // Cari paket berdasarkan ID
        $investmentPackage = InvestmentPackage::findOrFail($id);
        
        // Hapus paket
        $investmentPackage->delete();

        // Kalau request via AJAX/Inertia
        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        // Redirect biasa untuk request normal
        return redirect()->route('admin.deposit-packages.index')
                        ->with('success', 'Paket berhasil dihapus');
    }
}