<x-apoteker-layout>
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('apoteker.pembelian.index') }}" class="btn btn-sm btn-outline-secondary me-3">
            <i class="ti ti-arrow-left"></i>
        </a>
        <div>
            <div class="page-pretitle">Pembelian</div>
            <h2 class="page-title mb-0">Detail Pembelian: <code>{{ $pembelian->nota }}</code></h2>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header"><h6 class="card-title mb-0">Informasi Pembelian</h6></div>
                <div class="card-body">
                    <div class="mb-2"><div class="text-muted small">No. Nota</div><code class="text-primary">{{ $pembelian->nota }}</code></div>
                    <div class="mb-2"><div class="text-muted small">Tanggal</div><div class="fw-500">{{ $pembelian->tgl_nota->format('d F Y') }}</div></div>
                    <div class="mb-2"><div class="text-muted small">Suplier</div><div class="fw-500">{{ $pembelian->suplier->nm_suplier ?? '-' }}</div></div>
                    <div class="mb-2"><div class="text-muted small">Diinput oleh</div><div>{{ $pembelian->user->nama ?? '-' }}</div></div>
                    <hr>
                    <div class="mb-2"><div class="text-muted small">Diskon</div><div class="text-danger">- Rp {{ number_format($pembelian->diskon, 0, ',', '.') }}</div></div>
                    <div><div class="text-muted small">Total</div><div class="fw-700 fs-5 text-primary">Rp {{ number_format($pembelian->total, 0, ',', '.') }}</div></div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header"><h6 class="card-title mb-0">Detail Obat yang Dibeli</h6></div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead><tr><th>#</th><th>Nama Obat</th><th>Kode</th><th>Harga Beli</th><th>Qty</th><th>Subtotal</th></tr></thead>
                        <tbody>
                            @foreach($pembelian->details as $i => $d)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td class="fw-500">{{ $d->obat->nm_obat ?? '-' }}</td>
                                    <td><code>{{ $d->kd_obat }}</code></td>
                                    <td>Rp {{ number_format($d->harga_beli ?? 0, 0, ',', '.') }}</td>
                                    <td>{{ $d->jumlah }}</td>
                                    <td class="fw-600">Rp {{ number_format($d->jumlah * ($d->harga_beli ?? 0), 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <td colspan="5" class="text-end fw-600">Total</td>
                                <td class="fw-700 text-primary">Rp {{ number_format($pembelian->total, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-apoteker-layout>
