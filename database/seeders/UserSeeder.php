<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Snorlax_Reborn',
            'email' => 'Snorlax@gmail.com',
            'password' => Hash::make('123456789'), 
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
