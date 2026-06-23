<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel obat - struktur dasar dipertahankan sesuai "Tabel Obat" pada gambar relasi asli
     * (KdObat, NmObat, Jenis->diganti relasi kategori, Satuan, HargaBeli, HargaJual, Stok, KdSuplier).
     * Tambahan baru: id_kategori (relasi), tgl_kadaluarsa, status -- sesuai kesepakatan modifikasi.
     */
    public function up(): void
    {
        Schema::create('obats', function (Blueprint $table) {
            $table->string('kd_obat', 20)->primary();
            $table->string('nm_obat');
            $table->foreignId('id_kategori')
                ->nullable()
                ->constrained('kategori_obats')
                ->nullOnDelete();
            $table->string('satuan')->nullable();
            $table->decimal('harga_beli', 12, 2)->default(0);
            $table->decimal('harga_jual', 12, 2)->default(0);
            $table->integer('stok')->default(0);
            $table->date('tgl_kadaluarsa')->nullable();
            $table->enum('status', ['aktif', 'nonaktif', 'kadaluarsa'])->default('aktif');
            $table->string('foto_obat')->nullable();
            $table->string('kd_suplier', 20)->nullable();
            $table->foreign('kd_suplier')->references('kd_suplier')->on('supliers')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('obats');
    }
};
