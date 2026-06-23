<x-admin-layout>
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.obat.index') }}" class="btn btn-sm btn-outline-secondary me-3">
            <i class="ti ti-arrow-left"></i>
        </a>
        <div>
            <div class="page-pretitle">Master Data / Obat</div>
            <h2 class="page-title mb-0">Edit Obat: {{ $obat->nm_obat }}</h2>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.obat.update', $obat->kd_obat) }}" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Kode Obat</label>
                        <input type="text" class="form-control bg-light" value="{{ $obat->kd_obat }}" disabled>
                        <small class="text-muted">Kode tidak dapat diubah</small>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Nama Obat <span class="text-danger">*</span></label>
                        <input type="text" name="nm_obat" class="form-control @error('nm_obat') is-invalid @enderror"
                               value="{{ old('nm_obat', $obat->nm_obat) }}" required>
                        @error('nm_obat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select name="id_kategori" class="form-select @error('id_kategori') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $k)
                                <option value="{{ $k->id }}" {{ old('id_kategori', $obat->id_kategori) == $k->id ? 'selected' : '' }}>
                                    {{ $k->nm_kategori }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Satuan <span class="text-danger">*</span></label>
                        <input type="text" name="satuan" class="form-control @error('satuan') is-invalid @enderror"
                               value="{{ old('satuan', $obat->satuan) }}" required>
                        @error('satuan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Suplier</label>
                        <select name="kd_suplier" class="form-select @error('kd_suplier') is-invalid @enderror">
                            <option value="">-- Pilih Suplier --</option>
                            @foreach($supliers as $s)
                                <option value="{{ $s->kd_suplier }}" {{ old('kd_suplier', $obat->kd_suplier) == $s->kd_suplier ? 'selected' : '' }}>
                                    {{ $s->nm_suplier }}
                                </option>
                            @endforeach
                        </select>
                        @error('kd_suplier') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Harga Beli (Rp)</label>
                        <input type="number" name="harga_beli" class="form-control bg-light"
                               value="{{ $obat->harga_beli }}" readonly>
                        <small class="text-muted">Diperbarui otomatis melalui Restok</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Harga Jual (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="harga_jual" class="form-control @error('harga_jual') is-invalid @enderror"
                               value="{{ old('harga_jual', $obat->harga_jual) }}" min="0" step="100" required>
                        @error('harga_jual') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stok" class="form-control bg-light"
                               value="{{ $obat->stok }}" readonly>
                        <small class="text-muted">Diperbarui otomatis melalui Transaksi</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tgl. Kadaluarsa</label>
                        <input type="date" name="tgl_kadaluarsa" class="form-control @error('tgl_kadaluarsa') is-invalid @enderror"
                               value="{{ old('tgl_kadaluarsa', $obat->tgl_kadaluarsa?->format('Y-m-d')) }}">
                        @error('tgl_kadaluarsa') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="aktif" {{ old('status', $obat->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status', $obat->status) === 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Foto Obat</label>
                        @if($obat->foto_obat)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $obat->foto_obat) }}" class="img-thumbnail" style="max-height:100px;" alt="{{ $obat->nm_obat }}">
                            </div>
                        @endif
                        <input type="file" name="foto_obat" class="form-control @error('foto_obat') is-invalid @enderror" accept="image/*">
                        <small class="text-muted">Format: jpeg, png, jpg, gif (Max: 2MB). Biarkan kosong jika tidak ingin mengubah.</small>
                        @error('foto_obat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <hr class="my-4">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.obat.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
