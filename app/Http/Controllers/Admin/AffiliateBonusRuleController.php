<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AffiliateBonusRule;
use Inertia\Inertia;

class AffiliateBonusRuleController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/AffiliateBonusRules');
    }

    public function jsonIndex()
    {
        $rules = AffiliateBonusRule::orderBy('id')->get();
        return response()->json($rules);
    }

    public function store(Request $request)
    {
        $request->validate([
            'target_omset' => 'required|numeric',
            'extra_percent' => 'required|numeric',
            'required_min_deposit' => 'required|numeric',
        ]);

        $rule = AffiliateBonusRule::create($request->only([
            'target_omset',
            'extra_percent',
            'required_min_deposit',
        ]));

        return response()->json($rule, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'target_omset' => 'required|numeric',
            'extra_percent' => 'required|numeric',
            'required_min_deposit' => 'required|numeric',
        ]);

        $rule = AffiliateBonusRule::findOrFail($id);
        $rule->update($request->only([
            'target_omset',
            'extra_percent',
            'required_min_deposit',
        ]));

        return response()->json($rule);
    }

    public function destroy($id)
    {
        $rule = AffiliateBonusRule::findOrFail($id);
        $rule->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
