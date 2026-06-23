<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penjualan extends Model
{
    use HasFactory;

    protected $primaryKey = 'nota';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nota',
        'tgl_nota',
        'kd_pelanggan',
        'id_user',
        'diskon',
        'alamat_kirim',
        'status_pembayaran',
        'bukti_pembayaran',
    ];

    protected function casts(): array
    {
        return [
            'tgl_nota' => 'date',
            'diskon' => 'decimal:2',
        ];
    }

    // ── Relasi ───────────────────────────────────────────────

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'kd_pelanggan');
    }

    /** User (admin/apoteker) yang menginput transaksi ini. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function details(): HasMany
    {
        return $this->hasMany(PenjualanDetail::class, 'nota');
    }

    // ── Accessor ─────────────────────────────────────────────

    /** Total belanja = sum(harga_jual * jumlah) per detail, dikurangi diskon. */
    protected function total(): Attribute
    {
        return Attribute::make(
            get: function () {
                $subtotal = $this->details->sum(function ($detail) {
                    return $detail->jumlah * (float) ($detail->obat->harga_jual ?? 0);
                });

                return $subtotal - (float) $this->diskon;
            },
        );
    }

    /** Helper status badge. */
    public function getStatusBadgeAttribute()
    {
        switch ($this->status_pembayaran) {
            case 'belum_bayar':
                return '<span class="badge bg-warning-lt text-warning"><i class="ti ti-clock me-1"></i>Belum Bayar</span>';
            case 'menunggu_konfirmasi':
                return '<span class="badge bg-info-lt text-info"><i class="ti ti-loader me-1"></i>Menunggu Konfirmasi</span>';
            case 'lunas':
                return '<span class="badge bg-success-lt text-success"><i class="ti ti-check me-1"></i>Lunas</span>';
            case 'dibatalkan':
                return '<span class="badge bg-danger-lt text-danger"><i class="ti ti-x me-1"></i>Dibatalkan</span>';
            default:
                return '<span class="badge bg-secondary-lt">' . $this->status_pembayaran . '</span>';
        }
    }
}
