<x-apoteker-layout>
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <div class="page-pretitle">Overview</div>
            <h2 class="page-title mb-0">Dashboard Apoteker</h2>
        </div>
        <span class="text-muted small"><i class="ti ti-calendar me-1"></i>{{ now()->isoFormat('D MMMM YYYY') }}</span>
    </div>

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card p-3">
                <div class="d-flex align-items-center">
                    <div class="stat-card-icon me-3" style="background:rgba(47,179,68,.1);color:#2fb344;">
                        <i class="ti ti-pill"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Obat</div>
                        <div class="fw-700 fs-4">{{ $totalObat }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card p-3">
                <div class="d-flex align-items-center">
                    <div class="stat-card-icon me-3" style="background:rgba(234,179,8,.1);color:#ca8a04;">
                        <i class="ti ti-package"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Stok Menipis</div>
                        <div class="fw-700 fs-4 {{ $stokRendah > 0 ? 'text-warning' : '' }}">{{ $stokRendah }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card p-3">
                <div class="d-flex align-items-center">
                    <div class="stat-card-icon me-3" style="background:rgba(239,68,68,.1);color:#dc2626;">
                        <i class="ti ti-alert-triangle"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Obat Kadaluarsa</div>
                        <div class="fw-700 fs-4 {{ $obatKadaluarsa > 0 ? 'text-danger' : '' }}">{{ $obatKadaluarsa }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card p-3">
                <div class="d-flex align-items-center">
                    <div class="stat-card-icon me-3" style="background:rgba(14,165,233,.1);color:#0284c7;">
                        <i class="ti ti-receipt"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Penjualan Hari Ini</div>
                        <div class="fw-700 fs-4">{{ $penjualanHariIni }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <a href="{{ route('apoteker.obat.create') }}" class="card text-decoration-none" style="border:2px dashed #d1fae5;background:#f0fdf4;">
                <div class="card-body text-center py-3">
                    <i class="ti ti-circle-plus fs-2 text-success mb-2 d-block"></i>
                    <div class="fw-600 text-success">Tambah Obat</div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('apoteker.penjualan.create') }}" class="card text-decoration-none" style="border:2px dashed #dbeafe;background:#eff6ff;">
                <div class="card-body text-center py-3">
                    <i class="ti ti-shopping-cart fs-2 text-primary mb-2 d-block"></i>
                    <div class="fw-600 text-primary">Input Penjualan</div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('apoteker.obat.index') }}" class="card text-decoration-none" style="border:2px dashed #ede9fe;background:#faf5ff;">
                <div class="card-body text-center py-3">
                    <i class="ti ti-pill fs-2 text-purple mb-2 d-block" style="color:#7c3aed;"></i>
                    <div class="fw-600" style="color:#7c3aed;">Daftar Obat</div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('apoteker.obat.kadaluarsa') }}" class="card text-decoration-none" style="border:2px dashed #fee2e2;background:#fff5f5;">
                <div class="card-body text-center py-3">
                    <i class="ti ti-alert-triangle fs-2 text-danger mb-2 d-block"></i>
                    <div class="fw-600 text-danger">Obat Kadaluarsa</div>
                </div>
            </a>
        </div>
    </div>

    <!-- Penjualan Terbaru -->
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h6 class="card-title mb-0">Penjualan Terbaru</h6>
            <a href="{{ route('apoteker.penjualan.index') }}" class="btn btn-sm btn-outline-success">Lihat Semua</a>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr><th>Nota</th><th>Pelanggan</th><th>Tgl</th><th>Item</th><th>Total</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse($penjualanTerbaru as $p)
                        <tr>
                            <td><code>{{ $p->nota }}</code></td>
                            <td>{{ $p->pelanggan->nm_pelanggan ?? '-' }}</td>
                            <td>{{ $p->tgl_nota->format('d/m/Y') }}</td>
                            <td>{{ $p->details->count() }} item</td>
                            <td class="fw-600">Rp {{ number_format($p->total,0,',','.') }}</td>
                            <td>
                                <a href="{{ route('apoteker.penjualan.show', $p->nota) }}"
                                   class="btn btn-sm btn-outline-primary py-0 px-2">
                                    <i class="ti ti-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-3 text-muted">Belum ada transaksi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-apoteker-layout>
