<x-pelanggan-layout>
    <div class="container py-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h4 class="fw-700 mb-0">Pesanan Saya</h4>
            <a href="{{ route('pelanggan.obat.index') }}" class="btn btn-primary">
                <i class="ti ti-shopping-cart me-1"></i> Beli Obat
            </a>
        </div>

        @if($penjualans->count())
            @foreach($penjualans as $p)
                <div class="card mb-3">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <code class="text-primary">{{ $p->nota }}</code>
                            <span class="text-muted small">{{ $p->tgl_nota->format('d F Y') }}</span>
                            {!! $p->status_badge !!}
                        </div>
                        <a href="{{ route('pelanggan.penjualan.show', $p->nota) }}"
                           class="btn btn-sm btn-outline-primary py-0 px-3">
                            Lihat Detail
                        </a>
                    </div>
                    <div class="card-body py-2 px-3">
                        @foreach($p->details->take(3) as $d)
                            <div class="d-flex align-items-center gap-2 py-1 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div style="width:36px;height:36px;background:#eff6ff;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                    <i class="ti ti-pill text-primary"></i>
                                </div>
                                <div class="flex-fill">
                                    <div class="fw-500 small">{{ $d->obat->nm_obat ?? '-' }}</div>
                                    <div class="text-muted" style="font-size:.75rem;">{{ $d->jumlah }} {{ $d->obat->satuan ?? '' }}</div>
                                </div>
                                <div class="text-primary fw-600 small">
                                    Rp {{ number_format($d->jumlah * ($d->obat->harga_jual ?? 0), 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                        @if($p->details->count() > 3)
                            <div class="text-muted small mt-1 text-center">
                                + {{ $p->details->count() - 3 }} item lainnya...
                            </div>
                        @endif
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <span class="text-muted small">{{ $p->details->count() }} item</span>
                        <span class="fw-700 text-primary">Total: Rp {{ number_format($p->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            @endforeach

            <div class="d-flex justify-content-center mt-3">
                {{ $penjualans->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="ti ti-shopping-bag" style="font-size:4rem;color:#cbd5e1;"></i>
                <h5 class="text-muted mt-3">Belum ada pesanan</h5>
                <p class="text-muted small">Anda belum pernah melakukan pembelian obat.</p>
                <a href="{{ route('pelanggan.obat.index') }}" class="btn btn-primary">
                    <i class="ti ti-pill me-1"></i> Lihat Katalog Obat
                </a>
            </div>
        @endif
    </div>
</x-pelanggan-layout>
