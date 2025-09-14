<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Investment;
use Carbon\Carbon;

class InvestmentSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $investments = [
            [
                'user_id' => 2,
                'package_id' => 1,
                'amount' => 300,
                'start_date' => '2025-09-01',
                'end_date' => '2025-03-01',
                'status' => 'active',
                'proof_transfer' => null,
                'validated_by' => null,
                'validated_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id' => 3,
                'package_id' => 3,
                'amount' => 1000,
                'start_date' => '2025-09-05',
                'end_date' => '2026-03-05',
                'status' => 'pending',
                'proof_transfer' => null,
                'validated_by' => null,
                'validated_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($investments as $inv) {
            Investment::updateOrCreate(
                ['user_id' => $inv['user_id'], 'package_id' => $inv['package_id'], 'amount' => $inv['amount']],
                $inv
            );
        }
    }
}
