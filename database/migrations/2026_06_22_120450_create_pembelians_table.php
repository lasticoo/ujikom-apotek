<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel pembelian - struktur dipertahankan sesuai "Pembelian" pada gambar relasi asli
     * (Nota, TglNota, KdSuplier, Diskon).
     * Tambahan: id_user (FK ke users) -- mencatat admin/apoteker yang menginput pembelian ke suplier.
     */
    public function up(): void
    {
        Schema::create('pembelians', function (Blueprint $table) {
            $table->string('nota', 30)->primary();
            $table->date('tgl_nota');
            $table->string('kd_suplier', 20)->nullable();
            $table->foreign('kd_suplier')->references('kd_suplier')->on('supliers')->nullOnDelete();
            $table->foreignId('id_user')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('diskon', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembelians');
    }
};
