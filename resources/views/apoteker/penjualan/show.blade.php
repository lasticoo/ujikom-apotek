<x-apoteker-layout>
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('apoteker.penjualan.index') }}" class="btn btn-sm btn-outline-secondary me-3">
            <i class="ti ti-arrow-left"></i>
        </a>
        <div>
            <div class="page-pretitle">Penjualan</div>
            <h2 class="page-title mb-0">Detail Penjualan <code>{{ $penjualan->nota }}</code></h2>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header"><h6 class="card-title mb-0">Info Transaksi</h6></div>
                <div class="card-body">
                    <div class="mb-2"><div class="text-muted small">No. Nota</div><code class="text-success">{{ $penjualan->nota }}</code></div>
                    <div class="mb-2"><div class="text-muted small">Tanggal</div><div class="fw-500">{{ $penjualan->tgl_nota->format('d F Y') }}</div></div>
                    <div class="mb-2"><div class="text-muted small">Pelanggan</div><div class="fw-500">{{ $penjualan->pelanggan->nm_pelanggan ?? '-' }}</div></div>
                    <div class="mb-2"><div class="text-muted small">Diinput oleh</div><div>{{ $penjualan->user->nama ?? 'Pelanggan' }}</div></div>
                    <hr>
                    <div class="mb-1"><div class="text-muted small">Diskon</div><div class="text-danger">- Rp {{ number_format($penjualan->diskon, 0, ',', '.') }}</div></div>
                    <div><div class="text-muted small">Total</div><div class="fw-700 fs-5 text-success">Rp {{ number_format($penjualan->total, 0, ',', '.') }}</div></div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header"><h6 class="card-title mb-0">Detail Obat</h6></div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead><tr><th>#</th><th>Nama Obat</th><th>Harga Satuan</th><th>Qty</th><th>Subtotal</th></tr></thead>
                        <tbody>
                            @foreach($penjualan->details as $i => $d)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td class="fw-500">{{ $d->obat->nm_obat ?? '-' }}</td>
                                    <td>Rp {{ number_format($d->obat->harga_jual ?? 0, 0, ',', '.') }}</td>
                                    <td>{{ $d->jumlah }}</td>
                                    <td class="fw-600 text-success">Rp {{ number_format($d->jumlah * ($d->obat->harga_jual ?? 0), 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <td colspan="4" class="text-end fw-600">Total</td>
                                <td class="fw-700 text-success">Rp {{ number_format($penjualan->total, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-apoteker-layout>
