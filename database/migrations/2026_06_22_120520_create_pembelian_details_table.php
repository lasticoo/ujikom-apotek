<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel pembelian_detail - struktur dipertahankan sesuai "Pembelian_detail" pada gambar relasi asli
     * (Nota, KdObat, Jumlah).
     */
    public function up(): void
    {
        Schema::create('pembelian_details', function (Blueprint $table) {
            $table->id();
            $table->string('nota', 30);
            $table->foreign('nota')->references('nota')->on('pembelians')->cascadeOnDelete();
            $table->string('kd_obat', 20);
            $table->foreign('kd_obat')->references('kd_obat')->on('obats')->cascadeOnDelete();
            $table->integer('jumlah');
            $table->decimal('harga_beli', 12, 2)->default(0);
            $table->timestamps();

            $table->unique(['nota', 'kd_obat']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembelian_details');
    }
};
