<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenjualanDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'nota',
        'kd_obat',
        'jumlah',
    ];

    public function penjualan(): BelongsTo
    {
        return $this->belongsTo(Penjualan::class, 'nota');
    }

    public function obat(): BelongsTo
    {
        return $this->belongsTo(Obat::class, 'kd_obat');
    }

    /** Subtotal baris ini = harga_jual obat * jumlah. */
    public function subtotal(): float
    {
        return (float) $this->jumlah * (float) ($this->obat->harga_jual ?? 0);
    }
}
