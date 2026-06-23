<?php

namespace Database\Seeders;

use App\Models\AkunPelanggan;
use App\Models\Pelanggan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PelangganSeeder extends Seeder
{
    public function run(): void
    {
        $pelanggans = [
            ['kd_pelanggan' => 'PLG-001', 'nm_pelanggan' => 'Budi Santoso', 'alamat' => 'Jl. Mawar No. 1', 'kota' => 'Surabaya', 'telpon' => '081111111111', 'email' => 'budi@example.com'],
            ['kd_pelanggan' => 'PLG-002', 'nm_pelanggan' => 'Siti Aminah', 'alamat' => 'Jl. Melati No. 2', 'kota' => 'Madiun', 'telpon' => '082222222222', 'email' => 'siti@example.com'],
        ];

        foreach ($pelanggans as $p) {
            $pelanggan = Pelanggan::create([
                'kd_pelanggan' => $p['kd_pelanggan'],
                'nm_pelanggan' => $p['nm_pelanggan'],
                'alamat' => $p['alamat'],
                'kota' => $p['kota'],
                'telpon' => $p['telpon'],
            ]);

            AkunPelanggan::create([
                'kd_pelanggan' => $pelanggan->kd_pelanggan,
                'email' => $p['email'],
                'password' => Hash::make('password'),
            ]);
        }
    }
}
