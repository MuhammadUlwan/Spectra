<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@spectra.com'],
            [
                'name' => 'Admin Spectra',
                'phone' => '081234567890',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'is_consultant' => 0,
            ]
        );

        User::updateOrCreate(
            ['email' => 'investor1@spectra.com'],
            [
                'name' => 'Investor One',
                'phone' => '081298765432',
                'password' => bcrypt('password'),
                'role' => 'investor',
                'is_consultant' => 1,  // contoh sudah jadi konsultan
            ]
        );

        User::updateOrCreate(
            ['email' => 'investor2@spectra.com'],
            [
                'name' => 'Investor Two',
                'phone' => '081223344556',
                'password' => bcrypt('password'),
                'role' => 'investor',
                'is_consultant' => 0,
            ]
        );
    }
}
