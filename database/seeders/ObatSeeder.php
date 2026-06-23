<?php

namespace Database\Seeders;

use App\Models\KategoriObat;
use App\Models\Obat;
use Illuminate\Database\Seeder;

class ObatSeeder extends Seeder
{
    public function run(): void
    {
        $tablet = KategoriObat::where('nm_kategori', 'Tablet')->first()->id;
        $sirup = KategoriObat::where('nm_kategori', 'Sirup')->first()->id;
        $kapsul = KategoriObat::where('nm_kategori', 'Kapsul')->first()->id;

        $obats = [
            [
                'kd_obat' => 'OBT-001',
                'nm_obat' => 'Paracetamol 500mg',
                'id_kategori' => $tablet,
                'satuan' => 'Strip',
                'harga_beli' => 8000,
                'harga_jual' => 12000,
                'stok' => 150,
                'tgl_kadaluarsa' => now()->addMonths(18),
                'status' => 'aktif',
                'kd_suplier' => 'SUP-001',
            ],
            [
                'kd_obat' => 'OBT-002',
                'nm_obat' => 'Amoxicillin 500mg',
                'id_kategori' => $kapsul,
                'satuan' => 'Strip',
                'harga_beli' => 15000,
                'harga_jual' => 22000,
                'stok' => 80,
                'tgl_kadaluarsa' => now()->addMonths(12),
                'status' => 'aktif',
                'kd_suplier' => 'SUP-002',
            ],
            [
                'kd_obat' => 'OBT-003',
                'nm_obat' => 'OBH Combi Sirup',
                'id_kategori' => $sirup,
                'satuan' => 'Botol',
                'harga_beli' => 18000,
                'harga_jual' => 25000,
                'stok' => 40,
                'tgl_kadaluarsa' => now()->addDays(20),
                'status' => 'aktif',
                'kd_suplier' => 'SUP-003',
            ],
            [
                'kd_obat' => 'OBT-004',
                'nm_obat' => 'Vitamin C 1000mg',
                'id_kategori' => $tablet,
                'satuan' => 'Botol',
                'harga_beli' => 25000,
                'harga_jual' => 35000,
                'stok' => 60,
                'tgl_kadaluarsa' => now()->subDays(5),
                'status' => 'kadaluarsa',
                'kd_suplier' => 'SUP-001',
            ],
        ];

        foreach ($obats as $o) {
            Obat::create($o);
        }
    }
}
