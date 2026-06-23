<x-admin-layout>
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <div class="page-pretitle">Laporan</div>
            <h2 class="page-title mb-0">Laporan Restok & Pembelian Obat</h2>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.pembelian.report.pdf', request()->query()) }}" class="btn btn-outline-primary">
                <i class="ti ti-download me-1"></i> Unduh PDF
            </a>
            <a href="{{ route('admin.pembelian.report.export', request()->query()) }}" class="btn btn-primary">
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
                            <span class="bg-primary text-white avatar"><i class="ti ti-wallet fs-3"></i></span>
                        </div>
                        <div class="col col-truncate">
                            <div class="font-weight-medium">Total Pengeluaran</div>
                            <div class="text-secondary fw-700 fs-4">Rp {{ number_format($grandTotalExpenditure, 0, ',', '.') }}</div>
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
                            <span class="bg-green text-white avatar"><i class="ti ti-file-text fs-3"></i></span>
                        </div>
                        <div class="col col-truncate">
                            <div class="font-weight-medium">Jumlah Transaksi PO</div>
                            <div class="text-secondary fw-700 fs-4">{{ $totalPO }} Transaksi</div>
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
                            <span class="bg-red text-white avatar"><i class="ti ti-package fs-3"></i></span>
                        </div>
                        <div class="col col-truncate">
                            <div class="font-weight-medium">Total Item Direstok</div>
                            <div class="text-secondary fw-700 fs-4">{{ $totalItemsCount }} Pcs</div>
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
                            <span class="bg-yellow text-white avatar"><i class="ti ti-truck fs-3"></i></span>
                        </div>
                        <div class="col col-truncate">
                            <div class="font-weight-medium">Supplier Teraktif</div>
                            <div class="text-secondary fw-700 text-truncate" title="{{ $topSupplierName }}">{{ $topSupplierName }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card mb-3 no-print">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small mb-1">Cari</label>
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="No. nota / supplier..." value="{{ request('search') }}">
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
                    <a href="{{ route('admin.pembelian.report') }}" class="btn btn-sm btn-outline-secondary ms-1">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Restocking Table -->
    <div class="card mb-3">
        <div class="card-header"><h3 class="card-title mb-0">Rincian Transaksi Restok (Pembelian)</h3></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>No. Nota PO</th>
                            <th>Tgl. Nota</th>
                            <th>Supplier</th>
                            <th>Diinput Oleh</th>
                            <th>Jumlah Item</th>
                            <th>Diskon</th>
                            <th>Total Belanja</th>
                            <th class="no-print">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembelians as $p)
                            <tr>
                                <td><code class="text-primary">{{ $p->nota }}</code></td>
                                <td>{{ $p->tgl_nota->format('d/m/Y') }}</td>
                                <td>{{ $p->suplier->nm_suplier ?? '-' }}</td>
                                <td class="text-muted small">{{ $p->user->nama ?? '-' }}</td>
                                <td><span class="badge bg-secondary-lt">{{ $p->details->count() }} jenis</span></td>
                                <td>{{ $p->diskon > 0 ? 'Rp '.number_format($p->diskon,0,',','.') : '-' }}</td>
                                <td class="fw-600 text-danger">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                                <td class="no-print">
                                    <a href="{{ route('admin.pembelian.show', $p->nota) }}"
                                       class="btn btn-sm btn-outline-primary py-0 px-2">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-4 text-muted">Tidak ada data restok pembelian</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($pembelians->hasPages())
            <div class="card-footer no-print">{{ $pembelians->links() }}</div>
        @endif
    </div>

    <!-- Monthly Restock Summary -->
    <div class="card">
        <div class="card-header"><h3 class="card-title mb-0">Analisa Pengeluaran Restok Bulanan</h3></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Bulan</th>
                            <th>Jumlah Transaksi PO</th>
                            <th class="text-end">Total Pengeluaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($monthlyBreakdown as $month)
                            <tr>
                                <td class="fw-600">{{ $month['label'] }}</td>
                                <td>{{ $month['count'] }} PO</td>
                                <td class="text-end fw-700 text-danger">Rp {{ number_format($month['total'], 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">Tidak ada analisa restok bulanan tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
