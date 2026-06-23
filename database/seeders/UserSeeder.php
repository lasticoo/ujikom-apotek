<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nama' => 'Admin Apotek',
            'email' => 'admin@apotek.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'telpon' => '081234567890',
        ]);

        User::create([
            'nama' => 'Apoteker Satu',
            'email' => 'apoteker@apotek.test',
            'password' => Hash::make('password'),
            'role' => 'apoteker',
            'telpon' => '081298765432',
        ]);
    }
}
