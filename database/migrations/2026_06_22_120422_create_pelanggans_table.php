<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel pelanggan - struktur DIPERTAHANKAN sesuai "Tabel Pelanggan" pada gambar relasi asli
     * (KdPelanggan, NmPelanggan, Alamat, Kota, Telpon).
     * Kredensial login TIDAK ditaruh di sini -> lihat tabel akun_pelanggans.
     */
    public function up(): void
    {
        Schema::create('pelanggans', function (Blueprint $table) {
            $table->string('kd_pelanggan', 20)->primary();
            $table->string('nm_pelanggan');
            $table->text('alamat')->nullable();
            $table->string('kota')->nullable();
            $table->string('telpon')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggans');
    }
};
