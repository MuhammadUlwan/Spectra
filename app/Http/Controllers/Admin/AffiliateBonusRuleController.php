<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AffiliateBonusRule;
use Inertia\Inertia;

class AffiliateBonusRuleController extends Controller
{
    // Tampilkan halaman Inertia
    public function index()
    {
        return Inertia::render('Admin/AffiliateBonusRules');
    }

    // JSON endpoint untuk Vue
    public function jsonIndex()
    {
        $rules = AffiliateBonusRule::orderBy('id')->get();
        return response()->json($rules);
    }

    // Simpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'target_omset' => 'required|numeric',
            'extra_percent' => 'required|numeric',
            'required_min_deposit' => 'required|numeric',
            'status' => 'required|in:active,inactive',
        ]);

        $rule = AffiliateBonusRule::create($request->all());
        return response()->json($rule, 201);
    }

    // Update data
    public function update(Request $request, $id)
    {
        $request->validate([
            'target_omset' => 'required|numeric',
            'extra_percent' => 'required|numeric',
            'required_min_deposit' => 'required|numeric',
            'status' => 'required|in:active,inactive',
        ]);

        $rule = AffiliateBonusRule::findOrFail($id);
        $rule->update($request->all());
        return response()->json($rule);
    }

    // Hapus data
    public function destroy($id)
    {
        $rule = AffiliateBonusRule::findOrFail($id);
        $rule->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
