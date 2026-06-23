<x-admin-layout>
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <div class="page-pretitle">Master Data</div>
            <h2 class="page-title mb-0">Daftar Suplier</h2>
        </div>
        <a href="{{ route('admin.suplier.create') }}" class="btn btn-primary">
            <i class="ti ti-circle-plus me-1"></i> Tambah Suplier
        </a>
    </div>

    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="Cari nama, kode, atau kota suplier..." value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="ti ti-search me-1"></i> Cari</button>
                    <a href="{{ route('admin.suplier.index') }}" class="btn btn-sm btn-outline-secondary ms-1">Reset</a>
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
                            <th>Nama Suplier</th>
                            <th>Kota</th>
                            <th>Telepon</th>
                            <th>Jumlah Obat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($supliers as $s)
                            <tr>
                                <td><code>{{ $s->kd_suplier }}</code></td>
                                <td class="fw-500">{{ $s->nm_suplier }}</td>
                                <td>{{ $s->kota ?? '-' }}</td>
                                <td>{{ $s->telpon ?? '-' }}</td>
                                <td><span class="badge bg-primary-lt text-primary">{{ $s->obats_count }} obat</span></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.suplier.show', $s->kd_suplier) }}"
                                           class="btn btn-outline-primary py-0 px-2">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.suplier.edit', $s->kd_suplier) }}"
                                           class="btn btn-outline-warning py-0 px-2">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.suplier.destroy', $s->kd_suplier) }}"
                                              class="d-inline btn-delete-form">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger py-0 px-2"
                                                    data-nama="{{ $s->nm_suplier }}">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada data suplier</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($supliers->hasPages())
            <div class="card-footer">{{ $supliers->links() }}</div>
        @endif
    </div>

    @push('scripts')
    <script>
        document.querySelectorAll('.btn-delete-form').forEach(form => {
            form.querySelector('button').addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Hapus Suplier?',
                    text: `"${this.dataset.nama}" akan dihapus. Pastikan tidak ada obat terdaftar.`,
                    icon: 'warning', showCancelButton: true,
                    confirmButtonColor: '#d33', cancelButtonText: 'Batal', confirmButtonText: 'Ya, Hapus!'
                }).then(r => { if(r.isConfirmed) form.submit(); });
            });
        });
    </script>
    @endpush
</x-admin-layout>
