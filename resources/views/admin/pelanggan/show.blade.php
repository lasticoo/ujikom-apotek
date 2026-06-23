<x-admin-layout>
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.pelanggan.index') }}" class="btn btn-sm btn-outline-secondary me-3">
            <i class="ti ti-arrow-left"></i>
        </a>
        <div>
            <div class="page-pretitle">Pelanggan</div>
            <h2 class="page-title mb-0">{{ $pelanggan->nm_pelanggan }}</h2>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="mb-2"><code>{{ $pelanggan->kd_pelanggan }}</code></div>
                    <div class="mb-2"><div class="text-muted small">Alamat</div><div>{{ $pelanggan->alamat ?? '-' }}</div></div>
                    <div class="mb-2"><div class="text-muted small">Kota</div><div>{{ $pelanggan->kota ?? '-' }}</div></div>
                    <div class="mb-2"><div class="text-muted small">Telepon</div><div>{{ $pelanggan->telpon ?? '-' }}</div></div>
                    <div><div class="text-muted small">Email Akun</div>
                        <div>{{ $pelanggan->akun->email ?? '-' }}</div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <div class="fw-700 fs-4">{{ $pelanggan->penjualans->count() }}</div>
                    <div class="text-muted small">Total Transaksi</div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header"><h6 class="card-title mb-0">Riwayat Pembelian</h6></div>
                <div class="card-body p-0">
                    @if($pelanggan->penjualans->count())
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Nota</th><th>Tgl</th><th>Item</th><th>Total</th><th></th></tr></thead>
                            <tbody>
                                @foreach($pelanggan->penjualans as $p)
                                    <tr>
                                        <td><code>{{ $p->nota }}</code></td>
                                        <td>{{ $p->tgl_nota->format('d/m/Y') }}</td>
                                        <td>{{ $p->details->count() }} item</td>
                                        <td>Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                                        <td>
                                            <a href="{{ route('admin.penjualan.show', $p->nota) }}" class="btn btn-xs btn-outline-primary py-0 px-2">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="py-4 text-center text-muted small">Belum ada transaksi</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
