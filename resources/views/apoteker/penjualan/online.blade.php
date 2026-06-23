<x-apoteker-layout>
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <div class="page-pretitle">Transaksi</div>
            <h2 class="page-title mb-0">Pesanan Online Pelanggan</h2>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small mb-1">Status Pembayaran</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="belum_bayar" {{ request('status') === 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                        <option value="menunggu_konfirmasi" {{ request('status') === 'menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                        <option value="lunas" {{ request('status') === 'lunas' ? 'selected' : '' }}>Lunas (Diterima)</option>
                        <option value="dibatalkan" {{ request('status') === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan (Ditolak)</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-success">
                        <i class="ti ti-search me-1"></i> Filter
                    </button>
                    <a href="{{ route('apoteker.penjualan.online') }}" class="btn btn-sm btn-outline-secondary ms-1">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>No. Nota</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Item</th>
                            <th>Total Belanja</th>
                            <th>Alamat Kirim</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penjualans as $p)
                            <tr>
                                <td><code class="text-primary">{{ $p->nota }}</code></td>
                                <td>{{ $p->tgl_nota->format('d/m/Y') }}</td>
                                <td class="fw-500">{{ $p->pelanggan->nm_pelanggan ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-secondary-lt">{{ $p->details->count() }} obat</span>
                                </td>
                                <td class="fw-600">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                                <td><small class="text-muted text-truncate d-inline-block" style="max-width:180px;">{{ $p->alamat_kirim }}</small></td>
                                <td>{!! $p->status_badge !!}</td>
                                <td>
                                    <a href="{{ route('apoteker.penjualan.online.show', $p->nota) }}"
                                       class="btn btn-sm btn-outline-primary py-0 px-2">
                                        @if($p->status_pembayaran === 'menunggu_konfirmasi')
                                            <i class="ti ti-bell me-1 text-danger-pulse"></i> Periksa
                                        @else
                                            <i class="ti ti-eye"></i> Detail
                                        @endif
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-4 text-muted">Tidak ada pesanan online</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($penjualans->hasPages())
            <div class="card-footer">{{ $penjualans->links() }}</div>
        @endif
    </div>

    <style>
        .text-danger-pulse {
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.2); opacity: 0.5; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
</x-apoteker-layout>
