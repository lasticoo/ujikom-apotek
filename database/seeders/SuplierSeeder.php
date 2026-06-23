<?php

namespace Database\Seeders;

use App\Models\Suplier;
use Illuminate\Database\Seeder;

class SuplierSeeder extends Seeder
{
    public function run(): void
    {
        $supliers = [
            ['kd_suplier' => 'SUP-001', 'nm_suplier' => 'PT Kimia Farma Distribusi', 'alamat' => 'Jl. Veteran No. 10', 'kota' => 'Surabaya', 'telpon' => '0315551234'],
            ['kd_suplier' => 'SUP-002', 'nm_suplier' => 'PT Anugerah Pharmindo', 'alamat' => 'Jl. Diponegoro No. 5', 'kota' => 'Malang', 'telpon' => '0341552233'],
            ['kd_suplier' => 'SUP-003', 'nm_suplier' => 'CV Sumber Sehat', 'alamat' => 'Jl. Ahmad Yani No. 88', 'kota' => 'Jember', 'telpon' => '0331553344'],
        ];

        foreach ($supliers as $s) {
            Suplier::create($s);
        }
    }
}
