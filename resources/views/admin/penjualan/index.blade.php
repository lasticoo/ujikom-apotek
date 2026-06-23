<x-admin-layout>
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <div class="page-pretitle">Laporan</div>
            <h2 class="page-title mb-0">Laporan Penjualan & Analisa</h2>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.penjualan.pdf', request()->query()) }}" class="btn btn-outline-primary">
                <i class="ti ti-download me-1"></i> Unduh PDF
            </a>
            <a href="{{ route('admin.penjualan.export', request()->query()) }}" class="btn btn-primary">
                <i class="ti ti-file-spreadsheet me-1"></i> Ekspor Excel (CSV)
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row row-cards mb-3 no-print">
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-primary text-white avatar"><i class="ti ti-currency-dollar fs-3"></i></span>
                        </div>
                        <div class="col col-truncate">
                            <div class="font-weight-medium">Total Pendapatan</div>
                            <div class="text-secondary fw-700 fs-4">Rp {{ number_format($grandTotal, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-green text-white avatar"><i class="ti ti-shopping-cart fs-3"></i></span>
                        </div>
                        <div class="col col-truncate">
                            <div class="font-weight-medium">Jumlah Transaksi</div>
                            <div class="text-secondary fw-700 fs-4">{{ $totalTransactions }} Transaksi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-red text-white avatar"><i class="ti ti-discount-2 fs-3"></i></span>
                        </div>
                        <div class="col col-truncate">
                            <div class="font-weight-medium">Total Diskon</div>
                            <div class="text-secondary fw-700 fs-4">Rp {{ number_format($totalDiscount, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-yellow text-white avatar"><i class="ti ti-award fs-3"></i></span>
                        </div>
                        <div class="col col-truncate">
                            <div class="font-weight-medium">Obat Terlaris</div>
                            <div class="text-secondary fw-700 text-truncate" title="{{ $topDrugName }}">{{ $topDrugName }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Form (no-print) -->
    <div class="card mb-3 no-print">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small mb-1">Cari</label>
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="No. nota / pelanggan..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1">Dari Tanggal</label>
                    <input type="date" name="from" class="form-control form-control-sm" value="{{ request('from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1">Sampai Tanggal</label>
                    <input type="date" name="to" class="form-control form-control-sm" value="{{ request('to') }}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="ti ti-search me-1"></i> Filter</button>
                    <a href="{{ route('admin.penjualan.index') }}" class="btn btn-sm btn-outline-secondary ms-1">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table of Sales -->
    <div class="card mb-3">
        <div class="card-header"><h3 class="card-title mb-0">Rincian Transaksi Penjualan</h3></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr><th>No. Nota</th><th>Tgl. Nota</th><th>Pelanggan</th><th>Tipe</th><th>Oleh</th><th>Item</th><th>Diskon</th><th>Total</th><th class="no-print">Aksi</th></tr>
                    </thead>
                    <tbody>
                        @forelse($penjualans as $p)
                            <tr>
                                <td><code class="text-primary">{{ $p->nota }}</code></td>
                                <td>{{ $p->tgl_nota->format('d/m/Y') }}</td>
                                <td>{{ $p->pelanggan->nm_pelanggan ?? 'Umum (Walk-in)' }}</td>
                                <td>
                                    @if($p->alamat_kirim)
                                        <span class="badge bg-blue-lt">Online</span>
                                    @else
                                        <span class="badge bg-green-lt">Offline</span>
                                    @endif
                                </td>
                                <td class="text-muted small">{{ $p->user->nama ?? '-' }}</td>
                                <td><span class="badge bg-secondary-lt">{{ $p->details->count() }}</span></td>
                                <td>{{ $p->diskon > 0 ? 'Rp '.number_format($p->diskon,0,',','.') : '-' }}</td>
                                <td class="fw-600 text-success">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                                <td class="no-print">
                                    <a href="{{ route('admin.penjualan.show', $p->nota) }}"
                                       class="btn btn-sm btn-outline-primary py-0 px-2">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center py-4 text-muted">Tidak ada data penjualan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($penjualans->hasPages())
            <div class="card-footer no-print">{{ $penjualans->links() }}</div>
        @endif
    </div>

    <!-- Monthly Sales Summary -->
    <div class="card">
        <div class="card-header"><h3 class="card-title mb-0">Analisa Penjualan Bulanan</h3></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Bulan</th>
                            <th>Jumlah Transaksi</th>
                            <th class="text-end">Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($monthlyBreakdown as $month)
                            <tr>
                                <td class="fw-600">{{ $month['label'] }}</td>
                                <td>{{ $month['count'] }} Transaksi</td>
                                <td class="text-end fw-700 text-success">Rp {{ number_format($month['total'], 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">Tidak ada analisa data bulanan tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
