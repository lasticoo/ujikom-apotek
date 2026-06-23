<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Obat extends Model
{
    use HasFactory;

    protected $primaryKey = 'kd_obat';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kd_obat',
        'nm_obat',
        'id_kategori',
        'satuan',
        'harga_beli',
        'harga_jual',
        'stok',
        'tgl_kadaluarsa',
        'status',
        'kd_suplier',
        'foto_obat',
    ];

    protected function casts(): array
    {
        return [
            'tgl_kadaluarsa' => 'date',
            'harga_beli' => 'decimal:2',
            'harga_jual' => 'decimal:2',
        ];
    }

    // ── Relasi ───────────────────────────────────────────────

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriObat::class, 'id_kategori');
    }

    public function suplier(): BelongsTo
    {
        return $this->belongsTo(Suplier::class, 'kd_suplier');
    }

    public function penjualanDetails(): HasMany
    {
        return $this->hasMany(PenjualanDetail::class, 'kd_obat');
    }

    public function pembelianDetails(): HasMany
    {
        return $this->hasMany(PembelianDetail::class, 'kd_obat');
    }

    // ── Accessor ─────────────────────────────────────────────

    /** Format harga jual jadi "Rp 12.000" siap tampil di view. */
    protected function hargaJualFormat(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn () => 'Rp ' . number_format((float) $this->harga_jual, 0, ',', '.'),
        );
    }

    /** Cek apakah obat ini sudah lewat tanggal kadaluarsa (terlepas dari kolom status). */
    public function sudahKadaluarsa(): bool
    {
        return $this->tgl_kadaluarsa && $this->tgl_kadaluarsa->isPast();
    }

    /** Cek apakah obat akan kadaluarsa dalam $hari ke depan (default 30 hari). Dipakai fitur Admin. */
    public function akanKadaluarsa(int $hari = 30): bool
    {
        if (! $this->tgl_kadaluarsa) {
            return false;
        }

        return $this->tgl_kadaluarsa->isFuture()
            && $this->tgl_kadaluarsa->lte(now()->addDays($hari));
    }

    // ── Query Scope ──────────────────────────────────────────

    /** Scope::akanKadaluarsa() -> dipakai di controller Admin untuk listing obat mendekati expired. */
    public function scopeAkanKadaluarsa(Builder $query, int $hari = 30): Builder
    {
        return $query->whereNotNull('tgl_kadaluarsa')
            ->whereBetween('tgl_kadaluarsa', [now(), now()->addDays($hari)]);
    }

    /** Scope::sudahLewatKadaluarsa() -> obat yang tanggal kadaluarsanya sudah lewat. */
    public function scopeSudahLewatKadaluarsa(Builder $query): Builder
    {
        return $query->whereNotNull('tgl_kadaluarsa')->where('tgl_kadaluarsa', '<', now());
    }

    /** Scope::aktif() -> hanya obat berstatus aktif (dipakai di halaman Pelanggan). */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status', 'aktif');
    }

    /** Generate next medicine code, format: OBT-XXX */
    public static function generateKdObat(): string
    {
        $last = self::orderByRaw('CAST(SUBSTRING(kd_obat, 5) AS UNSIGNED) DESC')->first();
        $nextNumber = $last ? ((int) substr($last->kd_obat, 4)) + 1 : 1;
        return 'OBT-' . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
