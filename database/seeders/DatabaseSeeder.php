<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Urutan call HARUS seperti ini karena ada foreign key dependency:
     * Kategori & Suplier dulu (dipakai Obat) -> Obat -> User -> Pelanggan (+akun).
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            KategoriObatSeeder::class,
            SuplierSeeder::class,
            ObatSeeder::class,
            PelangganSeeder::class,
        ]);
    }
}
