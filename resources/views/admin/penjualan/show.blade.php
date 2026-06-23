<x-admin-layout>
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.penjualan.index') }}" class="btn btn-sm btn-outline-secondary me-3">
            <i class="ti ti-arrow-left"></i>
        </a>
        <div>
            <div class="page-pretitle">Laporan Penjualan</div>
            <h2 class="page-title mb-0">Detail Transaksi: <code>{{ $penjualan->nota }}</code></h2>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header"><h6 class="card-title mb-0">Informasi Transaksi</h6></div>
                <div class="card-body">
                    <div class="mb-2"><div class="text-muted small">No. Nota</div><code class="text-primary">{{ $penjualan->nota }}</code></div>
                    <div class="mb-2"><div class="text-muted small">Tanggal</div><div class="fw-500">{{ $penjualan->tgl_nota->format('d F Y') }}</div></div>
                    <div class="mb-2">
                        <div class="text-muted small">Status Pembayaran</div>
                        <div>{!! $penjualan->status_badge !!}</div>
                    </div>
                    <div class="mb-2">
                        <div class="text-muted small">Pelanggan</div>
                        <div class="fw-500">{{ $penjualan->pelanggan->nm_pelanggan ?? '-' }}</div>
                        <div class="text-muted" style="font-size:.8rem;">{{ $penjualan->pelanggan->kota ?? '' }}</div>
                    </div>
                    <div class="mb-2"><div class="text-muted small">Diinput oleh</div><div>{{ $penjualan->user->nama ?? 'Pelanggan Mandiri' }}</div></div>
                    
                    @if($penjualan->alamat_kirim)
                        <div class="mb-2">
                            <div class="text-muted small">Alamat Pengiriman</div>
                            <div class="fw-500 text-wrap">{{ $penjualan->alamat_kirim }}</div>
                        </div>
                    @endif

                    <hr>
                    <div class="mb-2"><div class="text-muted small">Subtotal</div>
                        <div class="fw-500">Rp {{ number_format($penjualan->details->sum(fn($d)=>$d->jumlah*$d->obat->harga_jual), 0, ',', '.') }}</div>
                    </div>
                    <div class="mb-2"><div class="text-muted small">Diskon</div><div class="text-danger">- Rp {{ number_format($penjualan->diskon, 0, ',', '.') }}</div></div>
                    <div class="mb-3"><div class="text-muted small">Total Bayar</div><div class="fw-700 fs-5 text-primary">Rp {{ number_format($penjualan->total, 0, ',', '.') }}</div></div>

                    @if($penjualan->bukti_pembayaran)
                        <hr>
                        <div class="mt-2">
                            <div class="text-muted small mb-1">Bukti Pembayaran</div>
                            <a href="{{ asset('storage/' . $penjualan->bukti_pembayaran) }}" target="_blank">
                                <img src="{{ asset('storage/' . $penjualan->bukti_pembayaran) }}" 
                                     class="img-fluid rounded border shadow-sm w-100" 
                                     style="max-height: 250px; object-fit: contain; background-color: #f8f9fa;" 
                                     alt="Bukti Pembayaran">
                            </a>
                            <div class="text-muted text-center mt-1"><small style="font-size: 0.75rem;">Klik gambar untuk memperbesar</small></div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header"><h6 class="card-title mb-0">Detail Obat</h6></div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead><tr><th>#</th><th>Nama Obat</th><th>Kode</th><th>Harga Satuan</th><th>Qty</th><th>Subtotal</th></tr></thead>
                        <tbody>
                            @foreach($penjualan->details as $i => $d)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td class="fw-500">{{ $d->obat->nm_obat ?? '-' }}</td>
                                    <td><code>{{ $d->kd_obat }}</code></td>
                                    <td>Rp {{ number_format($d->obat->harga_jual ?? 0, 0, ',', '.') }}</td>
                                    <td>{{ $d->jumlah }}</td>
                                    <td class="fw-600">Rp {{ number_format($d->jumlah * ($d->obat->harga_jual ?? 0), 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <td colspan="5" class="text-end fw-600">Total Bayar</td>
                                <td class="fw-700 text-primary">Rp {{ number_format($penjualan->total, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
