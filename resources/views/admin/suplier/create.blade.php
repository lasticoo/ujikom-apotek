<x-admin-layout>
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.suplier.index') }}" class="btn btn-sm btn-outline-secondary me-3">
            <i class="ti ti-arrow-left"></i>
        </a>
        <div>
            <div class="page-pretitle">Suplier</div>
            <h2 class="page-title mb-0">Tambah Suplier</h2>
        </div>
    </div>
    <div class="card" style="max-width:600px;">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.suplier.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Kode Suplier <span class="text-danger">*</span></label>
                    <input type="text" name="kd_suplier" class="form-control @error('kd_suplier') is-invalid @enderror"
                           value="{{ old('kd_suplier', $kd_suplier) }}" readonly required style="background-color: #f1f3f5;">
                    @error('kd_suplier') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Suplier <span class="text-danger">*</span></label>
                    <input type="text" name="nm_suplier" class="form-control @error('nm_suplier') is-invalid @enderror"
                           value="{{ old('nm_suplier') }}" required>
                    @error('nm_suplier') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="2">{{ old('alamat') }}</textarea>
                    @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Kota</label>
                        <input type="text" name="kota" class="form-control" value="{{ old('kota') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="telpon" class="form-control" value="{{ old('telpon') }}">
                    </div>
                </div>
                <hr>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i> Simpan
                    </button>
                    <a href="{{ route('admin.suplier.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
