<?php

namespace Database\Seeders;

use App\Models\KategoriObat;
use Illuminate\Database\Seeder;

class KategoriObatSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = ['Tablet', 'Sirup', 'Kapsul', 'Salep', 'Injeksi'];

        foreach ($kategoris as $nama) {
            KategoriObat::create(['nm_kategori' => $nama]);
        }
    }
}
