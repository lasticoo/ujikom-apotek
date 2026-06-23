<x-apoteker-layout>
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <div class="page-pretitle">Obat</div>
            <h2 class="page-title mb-0">Daftar Obat</h2>
        </div>
        <a href="{{ route('apoteker.obat.create') }}" class="btn btn-success">
            <i class="ti ti-circle-plus me-1"></i> Tambah Obat
        </a>
    </div>

    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="Cari nama atau kode obat..." value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-success"><i class="ti ti-search me-1"></i> Cari</button>
                    <a href="{{ route('apoteker.obat.index') }}" class="btn btn-sm btn-outline-secondary ms-1">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr><th>Kode</th><th>Foto</th><th>Nama Obat</th><th>Kategori</th><th>Satuan</th><th>Harga Jual</th><th>Stok</th><th>Status</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @forelse($obats as $obat)
                            <tr>
                                <td><code>{{ $obat->kd_obat }}</code></td>
                                <td>
                                    @if($obat->foto_obat)
                                        <img src="{{ asset('storage/' . $obat->foto_obat) }}" class="rounded border shadow-sm" style="width:40px; height:40px; object-fit:cover;">
                                    @else
                                        <span class="avatar avatar-sm bg-green-lt"><i class="ti ti-pill"></i></span>
                                    @endif
                                </td>
                                <td class="fw-500">
                                    <a href="{{ route('apoteker.obat.show', $obat->kd_obat) }}"
                                       class="text-decoration-none text-dark">{{ $obat->nm_obat }}</a>
                                </td>
                                <td>{{ $obat->kategori->nm_kategori ?? '-' }}</td>
                                <td>{{ $obat->satuan }}</td>
                                <td>Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge {{ $obat->stok < 10 ? 'bg-danger-lt text-danger' : 'bg-success-lt text-success' }}">
                                        {{ $obat->stok }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $obat->status === 'aktif' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ ucfirst($obat->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('apoteker.obat.show', $obat->kd_obat) }}"
                                           class="btn btn-outline-primary py-0 px-2">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        <form method="POST" action="{{ route('apoteker.obat.destroy', $obat->kd_obat) }}"
                                              class="d-inline btn-delete-form">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger py-0 px-2"
                                                    data-nama="{{ $obat->nm_obat }}">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-4 text-muted">Tidak ada obat ditemukan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($obats->hasPages())
            <div class="card-footer">{{ $obats->links() }}</div>
        @endif
    </div>

    @push('scripts')
    <script>
        document.querySelectorAll('.btn-delete-form').forEach(form => {
            form.querySelector('button').addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Hapus Obat?', text: `"${this.dataset.nama}" akan dihapus.`,
                    icon: 'warning', showCancelButton: true,
                    confirmButtonColor: '#d33', cancelButtonText: 'Batal', confirmButtonText: 'Ya, Hapus!'
                }).then(r => { if(r.isConfirmed) form.submit(); });
            });
        });
    </script>
    @endpush
</x-apoteker-layout>
