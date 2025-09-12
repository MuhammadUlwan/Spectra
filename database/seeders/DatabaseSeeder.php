<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Jalankan seeder default
        $this->call([
            UserSeeder::class,
            InvestmentPackageSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
