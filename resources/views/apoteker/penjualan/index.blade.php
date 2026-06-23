<x-apoteker-layout>
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <div class="page-pretitle">Penjualan</div>
            <h2 class="page-title mb-0">Histori Penjualan</h2>
        </div>
        <a href="{{ route('apoteker.penjualan.create') }}" class="btn btn-success">
            <i class="ti ti-circle-plus me-1"></i> Input Penjualan Baru
        </a>
    </div>

    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="No. nota atau nama pelanggan..." value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-success"><i class="ti ti-search me-1"></i> Cari</button>
                    <a href="{{ route('apoteker.penjualan.index') }}" class="btn btn-sm btn-outline-secondary ms-1">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr><th>No. Nota</th><th>Tgl. Nota</th><th>Pelanggan</th><th>Item</th><th>Total</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @forelse($penjualans as $p)
                            <tr>
                                <td><code class="text-success">{{ $p->nota }}</code></td>
                                <td>{{ $p->tgl_nota->format('d/m/Y') }}</td>
                                <td>{{ $p->pelanggan->nm_pelanggan ?? '-' }}</td>
                                <td><span class="badge bg-secondary-lt">{{ $p->details->count() }} item</span></td>
                                <td class="fw-600">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('apoteker.penjualan.show', $p->nota) }}"
                                       class="btn btn-sm btn-outline-success py-0 px-2">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada data penjualan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($penjualans->hasPages())
            <div class="card-footer">{{ $penjualans->links() }}</div>
        @endif
    </div>
</x-apoteker-layout>
