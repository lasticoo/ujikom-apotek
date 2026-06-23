<x-admin-layout>
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <div class="page-pretitle">Overview</div>
            <h2 class="page-title mb-0">Dashboard Admin</h2>
        </div>
        <div class="text-muted small">
            <i class="ti ti-calendar me-1"></i> {{ now()->isoFormat('dddd, D MMMM YYYY') }}
        </div>
    </div>

    <!-- Stat Cards Row 1 -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card p-3">
                <div class="d-flex align-items-center">
                    <div class="stat-card-icon me-3" style="background:rgba(32,107,196,.12); color:#206bc4;">
                        <i class="ti ti-pill"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-500">Total Obat</div>
                        <div class="fw-700 fs-4">{{ number_format($totalObat) }}</div>
                    </div>
                </div>
                <a href="{{ route('admin.obat.index') }}" class="stretched-link"></a>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card p-3">
                <div class="d-flex align-items-center">
                    <div class="stat-card-icon me-3" style="background:rgba(47,179,68,.12); color:#2fb344;">
                        <i class="ti ti-users"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-500">Total Pelanggan</div>
                        <div class="fw-700 fs-4">{{ number_format($totalPelanggan) }}</div>
                    </div>
                </div>
                <a href="{{ route('admin.pelanggan.index') }}" class="stretched-link"></a>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card p-3">
                <div class="d-flex align-items-center">
                    <div class="stat-card-icon me-3" style="background:rgba(234,179,8,.12); color:#ca8a04;">
                        <i class="ti ti-receipt"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-500">Penjualan Bulan Ini</div>
                        <div class="fw-700 fs-4">{{ number_format($penjualanBulanIni) }}</div>
                    </div>
                </div>
                <a href="{{ route('admin.penjualan.index') }}" class="stretched-link"></a>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card p-3">
                <div class="d-flex align-items-center">
                    <div class="stat-card-icon me-3" style="background:rgba(239,68,68,.12); color:#dc2626;">
                        <i class="ti ti-alert-triangle"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-500">Hampir Kadaluarsa</div>
                        <div class="fw-700 fs-4">{{ number_format($obatHampirKadaluarsa) }}</div>
                    </div>
                </div>
                <a href="{{ route('admin.obat.kadaluarsa') }}" class="stretched-link"></a>
            </div>
        </div>
    </div>

    <!-- Stat Cards Row 2 -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card p-3">
                <div class="d-flex align-items-center">
                    <div class="stat-card-icon me-3" style="background:rgba(139,92,246,.12); color:#7c3aed;">
                        <i class="ti ti-stethoscope"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-500">Total Apoteker</div>
                        <div class="fw-700 fs-4">{{ number_format($totalApoteker) }}</div>
                    </div>
                </div>
                <a href="{{ route('admin.apoteker.index') }}" class="stretched-link"></a>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card p-3">
                <div class="d-flex align-items-center">
                    <div class="stat-card-icon me-3" style="background:rgba(14,165,233,.12); color:#0284c7;">
                        <i class="ti ti-truck-delivery"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-500">Total Suplier</div>
                        <div class="fw-700 fs-4">{{ number_format($totalSuplier) }}</div>
                    </div>
                </div>
                <a href="{{ route('admin.suplier.index') }}" class="stretched-link"></a>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card p-3 border border-warning border-opacity-50">
                <div class="d-flex align-items-center">
                    <div class="stat-card-icon me-3" style="background:rgba(234,179,8,.12); color:#ca8a04;">
                        <i class="ti ti-package"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-500">Stok Menipis (&lt;10)</div>
                        <div class="fw-700 fs-4 {{ $obatStokRendah > 0 ? 'text-warning' : '' }}">{{ number_format($obatStokRendah) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart + Top Obat -->
    <div class="row g-3 mb-4">
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h6 class="card-title mb-0 fw-600">Penjualan 7 Hari Terakhir</h6>
                </div>
                <div class="card-body">
                    <canvas id="chartPenjualan" height="220"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="card-title mb-0 fw-600">Top 5 Obat Terlaris</h6>
                </div>
                <div class="card-body p-0">
                    @forelse($topObat as $index => $obat)
                        <div class="d-flex align-items-center px-3 py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <span class="badge me-3 rounded-circle d-flex align-items-center justify-content-center"
                                  style="width:28px;height:28px;background:rgba(32,107,196,.1);color:#206bc4;font-size:.8rem;">
                                {{ $index + 1 }}
                            </span>
                            <div class="flex-fill">
                                <div class="fw-500 small">{{ $obat->nm_obat }}</div>
                                <div class="text-muted" style="font-size:.72rem;">{{ $obat->kategori->nm_kategori ?? '-' }}</div>
                            </div>
                            <span class="badge bg-primary-lt text-primary fw-600">
                                {{ number_format($obat->total_terjual ?? 0) }} terjual
                            </span>
                        </div>
                    @empty
                        <div class="px-3 py-4 text-center text-muted small">Belum ada data penjualan</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Transaksi Terbaru -->
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h6 class="card-title mb-0 fw-600">Transaksi Terbaru</h6>
            <a href="{{ route('admin.penjualan.index') }}" class="btn btn-sm btn-outline-primary">
                Lihat Semua <i class="ti ti-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>No. Nota</th>
                            <th>Pelanggan</th>
                            <th>Tgl. Nota</th>
                            <th>Item</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penjualanTerbaru as $p)
                            <tr>
                                <td><code class="text-primary">{{ $p->nota }}</code></td>
                                <td>{{ $p->pelanggan->nm_pelanggan ?? '-' }}</td>
                                <td>{{ $p->tgl_nota->format('d/m/Y') }}</td>
                                <td><span class="badge bg-secondary-lt">{{ $p->details->count() }} item</span></td>
                                <td class="fw-600">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('admin.penjualan.show', $p->nota) }}"
                                       class="btn btn-sm btn-outline-primary py-0 px-2">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada transaksi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('chartPenjualan').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($grafikLabels) !!},
                datasets: [{
                    label: 'Jumlah Transaksi',
                    data: {!! json_encode($grafikData) !!},
                    backgroundColor: 'rgba(32,107,196,0.15)',
                    borderColor: 'rgba(32,107,196,0.9)',
                    borderWidth: 2,
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, color: '#94a3b8', font: { size: 11 } },
                        grid: { color: '#f1f5f9' },
                    },
                    x: {
                        ticks: { color: '#94a3b8', font: { size: 11 } },
                        grid: { display: false },
                    }
                }
            }
        });
    </script>
    @endpush
</x-admin-layout>
