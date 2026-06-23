<x-apoteker-layout>
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('apoteker.obat.index') }}" class="btn btn-sm btn-outline-secondary me-3">
            <i class="ti ti-arrow-left"></i>
        </a>
        <div>
            <div class="page-pretitle">Obat</div>
            <h2 class="page-title mb-0">Tambah Obat Baru</h2>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('apoteker.obat.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Kode Obat <span class="text-danger">*</span></label>
                        <input type="text" name="kd_obat" class="form-control @error('kd_obat') is-invalid @enderror"
                               value="{{ old('kd_obat', $kd_obat) }}" readonly required style="background-color: #f1f3f5;">
                        @error('kd_obat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Nama Obat <span class="text-danger">*</span></label>
                        <input type="text" name="nm_obat" class="form-control @error('nm_obat') is-invalid @enderror"
                               value="{{ old('nm_obat') }}" required>
                        @error('nm_obat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select name="id_kategori" class="form-select @error('id_kategori') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $k)
                                <option value="{{ $k->id }}" {{ old('id_kategori') == $k->id ? 'selected' : '' }}>{{ $k->nm_kategori }}</option>
                            @endforeach
                        </select>
                        @error('id_kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Satuan <span class="text-danger">*</span></label>
                        <input type="text" name="satuan" class="form-control @error('satuan') is-invalid @enderror"
                               value="{{ old('satuan') }}" required>
                        @error('satuan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Suplier</label>
                        <select name="kd_suplier" class="form-select">
                            <option value="">-- Pilih Suplier --</option>
                            @foreach($supliers as $s)
                                <option value="{{ $s->kd_suplier }}" {{ old('kd_suplier') == $s->kd_suplier ? 'selected' : '' }}>{{ $s->nm_suplier }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Harga Jual (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="harga_jual" class="form-control @error('harga_jual') is-invalid @enderror"
                               value="{{ old('harga_jual') }}" min="0" step="100" required>
                        @error('harga_jual') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tgl. Kadaluarsa</label>
                        <input type="date" name="tgl_kadaluarsa" class="form-control" value="{{ old('tgl_kadaluarsa') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="aktif" selected>Aktif</option>
                            <option value="nonaktif">Non-aktif</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Foto Obat</label>
                        <input type="file" name="foto_obat" class="form-control @error('foto_obat') is-invalid @enderror" accept="image/*">
                        <small class="text-muted">Format: jpeg, png, jpg, gif (Max: 2MB)</small>
                        @error('foto_obat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <hr class="my-4">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="ti ti-device-floppy me-1"></i> Simpan Obat
                    </button>
                    <a href="{{ route('apoteker.obat.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-apoteker-layout>
