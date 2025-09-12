<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InvestmentPackage;

class InvestmentPackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            ['name' => 'USD 300', 'amount' => 300, 'duration_months' => 6, 'profit_percent' => 7.5, 'currency' => 'USD'],
            ['name' => 'USD 500', 'amount' => 500, 'duration_months' => 6, 'profit_percent' => 7.5, 'currency' => 'USD'],
            ['name' => 'USD 1000', 'amount' => 1000, 'duration_months' => 6, 'profit_percent' => 7.5, 'currency' => 'USD'],
            ['name' => 'USD 5000', 'amount' => 5000, 'duration_months' => 6, 'profit_percent' => 7.5, 'currency' => 'USD'],
            ['name' => 'USD 10000', 'amount' => 10000, 'duration_months' => 6, 'profit_percent' => 7.5, 'currency' => 'USD'],
        ];

        foreach ($packages as $pkg) {
            InvestmentPackage::updateOrCreate(['amount' => $pkg['amount']], $pkg);
        }
    }
}
