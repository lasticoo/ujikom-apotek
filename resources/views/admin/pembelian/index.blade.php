<x-admin-layout>
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <div class="page-pretitle">Laporan</div>
            <h2 class="page-title mb-0">Daftar Pembelian Obat</h2>
        </div>
        <div>
            <a href="{{ route('admin.pembelian.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i> Tambah Restok
            </a>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="No. nota / suplier..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="from" class="form-control form-control-sm" value="{{ request('from') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="to" class="form-control form-control-sm" value="{{ request('to') }}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="ti ti-search me-1"></i> Filter</button>
                    <a href="{{ route('admin.pembelian.index') }}" class="btn btn-sm btn-outline-secondary ms-1">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr><th>No. Nota</th><th>Tgl. Nota</th><th>Suplier</th><th>Oleh</th><th>Item</th><th>Total</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @forelse($pembelians as $p)
                            <tr>
                                <td><code class="text-primary">{{ $p->nota }}</code></td>
                                <td>{{ $p->tgl_nota->format('d/m/Y') }}</td>
                                <td>{{ $p->suplier->nm_suplier ?? '-' }}</td>
                                <td class="text-muted small">{{ $p->user->nama ?? '-' }}</td>
                                <td><span class="badge bg-secondary-lt">{{ $p->details->count() }}</span></td>
                                <td class="fw-600">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('admin.pembelian.show', $p->nota) }}"
                                       class="btn btn-sm btn-outline-primary py-0 px-2">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-4 text-muted">Tidak ada data pembelian</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($pembelians->hasPages())
            <div class="card-footer">{{ $pembelians->links() }}</div>
        @endif
    </div>
</x-admin-layout>
