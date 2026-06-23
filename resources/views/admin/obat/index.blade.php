<x-admin-layout>
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <div class="page-pretitle">Master Data</div>
            <h2 class="page-title mb-0">Daftar Obat</h2>
        </div>
        <a href="{{ route('admin.obat.create') }}" class="btn btn-primary">
            <i class="ti ti-circle-plus me-1"></i> Tambah Obat
        </a>
    </div>

    <!-- Filter Card -->
    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small mb-1">Cari Obat</label>
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="Nama atau kode obat..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small mb-1">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="ti ti-search me-1"></i> Cari
                    </button>
                    <a href="{{ route('admin.obat.index') }}" class="btn btn-sm btn-outline-secondary ms-1">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Foto</th>
                            <th>Nama Obat</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th>Harga Jual</th>
                            <th>Stok</th>
                            <th>Kadaluarsa</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($obats as $obat)
                            <tr>
                                <td><code>{{ $obat->kd_obat }}</code></td>
                                <td>
                                    @if($obat->foto_obat)
                                        <img src="{{ asset('storage/' . $obat->foto_obat) }}" class="rounded border shadow-sm" style="width:40px; height:40px; object-fit:cover;">
                                    @else
                                        <span class="avatar avatar-sm bg-blue-lt"><i class="ti ti-pill"></i></span>
                                    @endif
                                </td>
                                <td class="fw-500">{{ $obat->nm_obat }}</td>
                                <td>{{ $obat->kategori->nm_kategori ?? '-' }}</td>
                                <td>{{ $obat->satuan }}</td>
                                <td>Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge {{ $obat->stok < 10 ? 'bg-danger-lt text-danger' : 'bg-success-lt text-success' }}">
                                        {{ $obat->stok }}
                                    </span>
                                </td>
                                <td>
                                    @if($obat->tgl_kadaluarsa)
                                        <span class="{{ $obat->sudahKadaluarsa() ? 'text-danger' : ($obat->akanKadaluarsa(30) ? 'text-warning' : 'text-muted') }} small">
                                            {{ $obat->tgl_kadaluarsa->format('d/m/Y') }}
                                        </span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('admin.obat.updateStatus', $obat->kd_obat) }}" class="d-inline">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="{{ $obat->status === 'aktif' ? 'nonaktif' : 'aktif' }}">
                                        <button type="submit" class="badge border-0 {{ $obat->status === 'aktif' ? 'bg-success' : 'bg-secondary' }}"
                                                style="cursor:pointer;" title="Klik untuk ubah status">
                                            {{ $obat->status === 'aktif' ? 'Aktif' : 'Non-aktif' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.obat.show', $obat->kd_obat) }}"
                                           class="btn btn-outline-primary py-0 px-2" title="Detail">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.obat.edit', $obat->kd_obat) }}"
                                           class="btn btn-outline-warning py-0 px-2" title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.obat.destroy', $obat->kd_obat) }}" class="d-inline btn-delete-form">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger py-0 px-2" title="Hapus"
                                                    data-nama="{{ $obat->nm_obat }}">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    <i class="ti ti-mood-empty fs-2 d-block mb-2 opacity-50"></i>
                                    Tidak ada data obat
                                </td>
                            </tr>
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
                const nama = this.dataset.nama;
                Swal.fire({
                    title: 'Hapus Obat?',
                    text: `"${nama}" akan dihapus secara permanen.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Ya, Hapus!',
                }).then(result => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    </script>
    @endpush
</x-admin-layout>
