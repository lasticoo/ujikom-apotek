<x-admin-layout>
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.obat.index') }}" class="btn btn-sm btn-outline-secondary me-3">
            <i class="ti ti-arrow-left"></i>
        </a>
        <div>
            <div class="page-pretitle">Master Data / Obat</div>
            <h2 class="page-title mb-0">Detail Obat</h2>
        </div>
        <div class="ms-auto d-flex gap-2">
            <a href="{{ route('admin.obat.edit', $obat->kd_obat) }}" class="btn btn-warning">
                <i class="ti ti-edit me-1"></i> Edit
            </a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center py-4">
                    @if($obat->foto_obat)
                        <div class="mb-3" style="width:80px;height:80px;margin:auto;">
                            <img src="{{ asset('storage/' . $obat->foto_obat) }}" class="rounded-3 shadow-sm border" style="width:80px;height:80px;object-fit:cover;">
                        </div>
                    @else
                        <div class="avatar avatar-xl mb-3" style="background:linear-gradient(135deg,#dbeafe,#ede9fe);border-radius:16px;width:80px;height:80px;margin:auto;display:flex;align-items:center;justify-content:center;">
                            <i class="ti ti-pill fs-1 text-primary"></i>
                        </div>
                    @endif
                    <h5 class="fw-700 mb-1">{{ $obat->nm_obat }}</h5>
                    <code class="text-primary">{{ $obat->kd_obat }}</code>
                    <div class="mt-2">
                        <span class="badge {{ $obat->status === 'aktif' ? 'bg-success' : 'bg-secondary' }} fs-6">
                            {{ ucfirst($obat->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row text-center">
                        <div class="col-6 border-end">
                            <div class="fw-700 fs-4">{{ $obat->stok }}</div>
                            <div class="text-muted small">Stok</div>
                        </div>
                        <div class="col-6">
                            <div class="fw-700 fs-4">{{ $obat->penjualanDetails->count() }}</div>
                            <div class="text-muted small">Transaksi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header"><h6 class="card-title mb-0">Informasi Obat</h6></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="text-muted small">Kategori</div>
                            <div class="fw-500">{{ $obat->kategori->nm_kategori ?? '-' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted small">Satuan</div>
                            <div class="fw-500">{{ $obat->satuan }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted small">Harga Beli</div>
                            <div class="fw-500">Rp {{ number_format($obat->harga_beli, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted small">Harga Jual</div>
                            <div class="fw-700 text-primary">Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted small">Suplier</div>
                            <div class="fw-500">{{ $obat->suplier->nm_suplier ?? '-' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted small">Tgl. Kadaluarsa</div>
                            <div class="fw-500 {{ $obat->sudahKadaluarsa() ? 'text-danger' : '' }}">
                                {{ $obat->tgl_kadaluarsa ? $obat->tgl_kadaluarsa->format('d F Y') : '-' }}
                                @if($obat->sudahKadaluarsa())
                                    <span class="badge bg-danger ms-1">Kadaluarsa</span>
                                @elseif($obat->akanKadaluarsa(30))
                                    <span class="badge bg-warning ms-1">Segera</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Riwayat Penjualan -->
            <div class="card">
                <div class="card-header"><h6 class="card-title mb-0">Riwayat Penjualan Obat Ini</h6></div>
                <div class="card-body p-0">
                    @if($obat->penjualanDetails->count())
                        <div class="table-responsive" style="max-height:280px;overflow-y:auto;">
                            <table class="table table-sm mb-0">
                                <thead class="sticky-top bg-white">
                                    <tr>
                                        <th>Nota</th>
                                        <th>Pelanggan</th>
                                        <th>Tgl</th>
                                        <th>Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($obat->penjualanDetails->take(20) as $d)
                                        <tr>
                                            <td><code>{{ $d->nota }}</code></td>
                                            <td>{{ $d->penjualan->pelanggan->nm_pelanggan ?? '-' }}</td>
                                            <td>{{ $d->penjualan->tgl_nota->format('d/m/Y') }}</td>
                                            <td>{{ $d->jumlah }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="py-4 text-center text-muted small">Belum ada riwayat penjualan</div>
                    @endif
                </div>
            </div>

            <!-- Riwayat Restok / Pembelian -->
            <div class="card mt-3">
                <div class="card-header"><h6 class="card-title mb-0">Riwayat Restok / Pembelian Obat Ini</h6></div>
                <div class="card-body p-0">
                    @if($obat->pembelianDetails->count())
                        <div class="table-responsive" style="max-height:280px;overflow-y:auto;">
                            <table class="table table-sm mb-0">
                                <thead class="sticky-top bg-white">
                                    <tr>
                                        <th>Nota PO</th>
                                        <th>Supplier</th>
                                        <th>Tgl Restok</th>
                                        <th>Harga Beli</th>
                                        <th>Qty Masuk</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($obat->pembelianDetails->sortByDesc('created_at')->take(20) as $d)
                                        <tr>
                                            <td><code>{{ $d->nota }}</code></td>
                                            <td>{{ $d->pembelian->suplier->nm_suplier ?? '-' }}</td>
                                            <td>{{ $d->pembelian->tgl_nota->format('d/m/Y') }}</td>
                                            <td>Rp {{ number_format($d->harga_beli, 0, ',', '.') }}</td>
                                            <td class="fw-600 text-success">+{{ $d->jumlah }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="py-4 text-center text-muted small">Belum ada riwayat restok/pembelian</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
