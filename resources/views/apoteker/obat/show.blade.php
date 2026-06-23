<x-apoteker-layout>
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('apoteker.obat.index') }}" class="btn btn-sm btn-outline-secondary me-3">
            <i class="ti ti-arrow-left"></i>
        </a>
        <div>
            <div class="page-pretitle">Obat</div>
            <h2 class="page-title mb-0">{{ $obat->nm_obat }}</h2>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card text-center py-4">
                <div class="card-body">
                    @if($obat->foto_obat)
                        <div class="mb-3" style="width:72px;height:72px;margin:auto;">
                            <img src="{{ asset('storage/' . $obat->foto_obat) }}" class="rounded-3 shadow-sm border" style="width:72px;height:72px;object-fit:cover;">
                        </div>
                    @else
                        <div class="mb-3" style="width:72px;height:72px;background:linear-gradient(135deg,#dcfce7,#d1fae5);border-radius:16px;margin:auto;display:flex;align-items:center;justify-content:center;">
                            <i class="ti ti-pill fs-1 text-success"></i>
                        </div>
                    @endif
                    <h5 class="fw-700 mb-1">{{ $obat->nm_obat }}</h5>
                    <code class="text-success">{{ $obat->kd_obat }}</code>
                    <div class="mt-2">
                        <span class="badge {{ $obat->status === 'aktif' ? 'bg-success' : 'bg-secondary' }}">
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
                            <div class="text-muted small">Terjual</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header"><h6 class="card-title mb-0">Detail Informasi</h6></div>
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
                            <div class="fw-700 text-success">Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted small">Suplier</div>
                            <div>{{ $obat->suplier->nm_suplier ?? '-' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted small">Kadaluarsa</div>
                            <div class="{{ $obat->sudahKadaluarsa() ? 'text-danger fw-600' : '' }}">
                                {{ $obat->tgl_kadaluarsa ? $obat->tgl_kadaluarsa->format('d/m/Y') : '-' }}
                                @if($obat->sudahKadaluarsa()) <span class="badge bg-danger">Kadaluarsa!</span> @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-apoteker-layout>
