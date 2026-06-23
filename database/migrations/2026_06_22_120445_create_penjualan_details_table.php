<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel penjualan_detail - struktur dipertahankan sesuai "Penjualan_Detail" pada gambar relasi asli
     * (Nota, KdObat, Jumlah). Ditambah id auto-increment sebagai PK teknis
     * karena kombinasi Nota+KdObat dipakai sebagai unique constraint, bukan PK komposit
     * (memudahkan Eloquent yang defaultnya mengharapkan single PK "id").
     */
    public function up(): void
    {
        Schema::create('penjualan_details', function (Blueprint $table) {
            $table->id();
            $table->string('nota', 30);
            $table->foreign('nota')->references('nota')->on('penjualans')->cascadeOnDelete();
            $table->string('kd_obat', 20);
            $table->foreign('kd_obat')->references('kd_obat')->on('obats')->cascadeOnDelete();
            $table->integer('jumlah');
            $table->timestamps();

            $table->unique(['nota', 'kd_obat']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan_details');
    }
};
