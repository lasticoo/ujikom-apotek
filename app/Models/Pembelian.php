<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pembelian extends Model
{
    use HasFactory;

    protected $primaryKey = 'nota';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nota',
        'tgl_nota',
        'kd_suplier',
        'id_user',
        'diskon',
    ];

    protected function casts(): array
    {
        return [
            'tgl_nota' => 'date',
            'diskon' => 'decimal:2',
        ];
    }

    // ── Relasi ───────────────────────────────────────────────

    public function suplier(): BelongsTo
    {
        return $this->belongsTo(Suplier::class, 'kd_suplier');
    }

    /** User (admin/apoteker) yang menginput pembelian ini. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function details(): HasMany
    {
        return $this->hasMany(PembelianDetail::class, 'nota');
    }

    // ── Accessor ─────────────────────────────────────────────

    /** Total pembelian = sum(harga_beli * jumlah) per detail, dikurangi diskon. */
    protected function total(): Attribute
    {
        return Attribute::make(
            get: function () {
                $subtotal = $this->details->sum(function ($detail) {
                    return $detail->jumlah * (float) ($detail->harga_beli ?? 0);
                });

                return $subtotal - (float) $this->diskon;
            },
        );
    }

    /** Generate next PO number, format: PO-XXXXX */
    public static function generateNota(): string
    {
        $lastPO = self::orderByRaw('CAST(SUBSTRING(nota, 4) AS UNSIGNED) DESC')->first();
        $nextNum = $lastPO ? ((int) substr($lastPO->nota, 3)) + 1 : 1;
        return 'PO-' . str_pad($nextNum, 5, '0', STR_PAD_LEFT);
    }
}
