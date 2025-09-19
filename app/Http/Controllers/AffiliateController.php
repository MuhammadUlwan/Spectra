<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AffiliateController extends Controller
{
    // Summary referral per level
    public function summary()
    {
        $user = Auth::user();
        $userId = $user->id;

        // Level 1: direct referral
        $level1Users = User::where('referred_by', $userId)->get(['id','name','email']);
        $level1Ids = $level1Users->pluck('id')->toArray();

        // Level 2: indirect referral (anak dari level 1)
        $level2Users = $level1Ids
            ? User::whereIn('referred_by', $level1Ids)->get(['id','name','email'])
            : collect([]);
        $level2Ids = $level2Users->pluck('id')->toArray();

        // Level 3: indirect referral (anak dari level 2)
        $level3Users = $level2Ids
            ? User::whereIn('referred_by', $level2Ids)->get(['id','name','email'])
            : collect([]);

        return response()->json([
            '1' => [
                'count' => $level1Users->count(),
                'users' => $level1Users->toArray()
            ],
            '2' => [
                'count' => $level2Users->count(),
                'users' => $level2Users->toArray()
            ],
            '3' => [
                'count' => $level3Users->count(),
                'users' => $level3Users->toArray()
            ],
        ]);
    }

    // Tree referral (rekursif sampai level 3)
    public function tree()
    {
        $user = Auth::user();
        $tree = $this->buildTree($user->id);
        return response()->json($tree);
    }

    private function buildTree($parentId, $level = 1)
    {
        if ($level > 3) return [];

        $children = User::where('referred_by', $parentId)->get(['id','name']);
        $tree = [];

        foreach ($children as $child) {
            $tree[] = [
                'id' => $child->id,
                'name' => $child->name,
                'level' => $level,
                'children' => $this->buildTree($child->id, $level + 1),
            ];
        }

        return $tree;
    }
}
