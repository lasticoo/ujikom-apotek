<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel suplier - struktur dipertahankan sesuai Table Suplier pada gambar relasi asli.
     * PK kd_suplier dibuat string supaya bisa pakai kode custom (misal: SUP-001) jika diperlukan,
     * tapi tetap auto-generate increment di controller agar konsisten.
     */
    public function up(): void
    {
        Schema::create('supliers', function (Blueprint $table) {
            $table->string('kd_suplier', 20)->primary();
            $table->string('nm_suplier');
            $table->text('alamat')->nullable();
            $table->string('kota')->nullable();
            $table->string('telpon')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supliers');
    }
};
