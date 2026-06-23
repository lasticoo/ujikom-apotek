<x-admin-layout>
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <div class="page-pretitle">Master Data</div>
            <h2 class="page-title mb-0">Daftar Apoteker</h2>
        </div>
        <a href="{{ route('admin.apoteker.create') }}" class="btn btn-primary">
            <i class="ti ti-user-plus me-1"></i> Daftarkan Apoteker
        </a>
    </div>

    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="Cari nama atau email..." value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="ti ti-search me-1"></i> Cari</button>
                    <a href="{{ route('admin.apoteker.index') }}" class="btn btn-sm btn-outline-secondary ms-1">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr><th>#</th><th>Nama</th><th>Email</th><th>Telepon</th><th>Terdaftar</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($apotekers as $i => $a)
                        <tr>
                            <td>{{ $apotekers->firstItem() + $i }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2" style="background:linear-gradient(135deg,#206bc4,#4299e1);">
                                        <span class="text-white fw-600" style="font-size:.7rem;">{{ strtoupper(substr($a->nama,0,2)) }}</span>
                                    </div>
                                    <span class="fw-500">{{ $a->nama }}</span>
                                </div>
                            </td>
                            <td>{{ $a->email }}</td>
                            <td>{{ $a->telpon ?? '-' }}</td>
                            <td class="text-muted small">{{ $a->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.apoteker.edit', $a->id) }}"
                                       class="btn btn-outline-warning py-0 px-2" title="Edit">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.apoteker.destroy', $a->id) }}"
                                          class="d-inline btn-delete-form">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger py-0 px-2"
                                                data-nama="{{ $a->nama }}">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada apoteker terdaftar</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($apotekers->hasPages())
            <div class="card-footer">{{ $apotekers->links() }}</div>
        @endif
    </div>

    @push('scripts')
    <script>
        document.querySelectorAll('.btn-delete-form').forEach(form => {
            form.querySelector('button').addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Hapus Apoteker?',
                    text: `Akun "${this.dataset.nama}" akan dihapus permanen.`,
                    icon: 'warning', showCancelButton: true,
                    confirmButtonColor: '#d33', cancelButtonText: 'Batal', confirmButtonText: 'Ya, Hapus!'
                }).then(r => { if(r.isConfirmed) form.submit(); });
            });
        });
    </script>
    @endpush
</x-admin-layout>
