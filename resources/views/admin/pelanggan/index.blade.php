<x-admin-layout>
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <div class="page-pretitle">Master Data</div>
            <h2 class="page-title mb-0">Daftar Pelanggan</h2>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="Cari nama, kode, atau kota..." value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="ti ti-search me-1"></i> Cari</button>
                    <a href="{{ route('admin.pelanggan.index') }}" class="btn btn-sm btn-outline-secondary ms-1">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr><th>#</th><th>Kode</th><th>Nama Pelanggan</th><th>Kota</th><th>Telepon</th><th>Email Akun</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($pelanggans as $i => $p)
                        <tr>
                            <td>{{ $pelanggans->firstItem() + $i }}</td>
                            <td><code>{{ $p->kd_pelanggan }}</code></td>
                            <td class="fw-500">{{ $p->nm_pelanggan }}</td>
                            <td>{{ $p->kota ?? '-' }}</td>
                            <td>{{ $p->telpon ?? '-' }}</td>
                            <td>
                                @if($p->akun)
                                    <span class="badge bg-success-lt text-success">
                                        <i class="ti ti-mail me-1"></i>{{ $p->akun->email }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary-lt text-muted">Belum punya akun</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.pelanggan.show', $p->kd_pelanggan) }}"
                                   class="btn btn-sm btn-outline-primary py-0 px-2">
                                    <i class="ti ti-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada pelanggan terdaftar</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pelanggans->hasPages())
            <div class="card-footer">{{ $pelanggans->links() }}</div>
        @endif
    </div>
</x-admin-layout>
