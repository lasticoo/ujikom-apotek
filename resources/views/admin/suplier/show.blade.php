<x-admin-layout>
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.suplier.index') }}" class="btn btn-sm btn-outline-secondary me-3">
            <i class="ti ti-arrow-left"></i>
        </a>
        <div>
            <div class="page-pretitle">Suplier</div>
            <h2 class="page-title mb-0">{{ $suplier->nm_suplier }}</h2>
        </div>
        <a href="{{ route('admin.suplier.edit', $suplier->kd_suplier) }}" class="btn btn-warning ms-auto">
            <i class="ti ti-edit me-1"></i> Edit
        </a>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <div class="text-muted small">Kode Suplier</div>
                        <code class="fs-6">{{ $suplier->kd_suplier }}</code>
                    </div>
                    <div class="mb-3">
                        <div class="text-muted small">Nama</div>
                        <div class="fw-600">{{ $suplier->nm_suplier }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="text-muted small">Alamat</div>
                        <div>{{ $suplier->alamat ?? '-' }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="text-muted small">Kota</div>
                        <div>{{ $suplier->kota ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-muted small">Telepon</div>
                        <div>{{ $suplier->telpon ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Obat dari Suplier Ini ({{ $suplier->obats->count() }})</h6>
                </div>
                <div class="card-body p-0">
                    @if($suplier->obats->count())
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr><th>Kode</th><th>Nama Obat</th><th>Stok</th><th>Harga Jual</th></tr>
                            </thead>
                            <tbody>
                                @foreach($suplier->obats as $obat)
                                    <tr>
                                        <td><code>{{ $obat->kd_obat }}</code></td>
                                        <td>{{ $obat->nm_obat }}</td>
                                        <td>{{ $obat->stok }}</td>
                                        <td>Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="py-4 text-center text-muted small">Belum ada obat dari suplier ini</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
