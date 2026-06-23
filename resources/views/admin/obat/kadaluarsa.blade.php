<x-admin-layout>
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.obat.index') }}" class="btn btn-sm btn-outline-secondary me-3">
            <i class="ti ti-arrow-left"></i>
        </a>
        <div>
            <div class="page-pretitle">Master Data / Obat</div>
            <h2 class="page-title mb-0">
                <i class="ti ti-alert-triangle text-warning me-2"></i> Monitoring Kadaluarsa
            </h2>
        </div>
    </div>

    <!-- Akan Kadaluarsa -->
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center">
            <span class="badge bg-warning me-2">{{ $akanKadaluarsa->count() }}</span>
            <h6 class="card-title mb-0 fw-600">Obat Akan Kadaluarsa (dalam 60 hari)</h6>
        </div>
        <div class="card-body p-0">
            @if($akanKadaluarsa->count())
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Obat</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Tgl. Kadaluarsa</th>
                                <th>Sisa Hari</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($akanKadaluarsa as $obat)
                                @php $silaHari = now()->diffInDays($obat->tgl_kadaluarsa, false) @endphp
                                <tr class="{{ $silaHari <= 14 ? 'table-warning' : '' }}">
                                    <td><code>{{ $obat->kd_obat }}</code></td>
                                    <td class="fw-500">{{ $obat->nm_obat }}</td>
                                    <td>{{ $obat->kategori->nm_kategori ?? '-' }}</td>
                                    <td>{{ $obat->stok }}</td>
                                    <td class="text-warning fw-500">{{ $obat->tgl_kadaluarsa->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge {{ $silaHari <= 14 ? 'bg-danger' : 'bg-warning' }}">
                                            {{ $silaHari }} hari
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.obat.show', $obat->kd_obat) }}"
                                           class="btn btn-sm btn-outline-primary py-0 px-2">
                                            <i class="ti ti-eye me-1"></i> Detail Obat
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-4 text-center text-muted">
                    <i class="ti ti-circle-check fs-2 d-block mb-2 text-success opacity-75"></i>
                    Tidak ada obat yang akan kadaluarsa dalam 60 hari ke depan
                </div>
            @endif
        </div>
    </div>

    <!-- Sudah Kadaluarsa -->
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <span class="badge bg-danger me-2">{{ $sudahKadaluarsa->count() }}</span>
            <h6 class="card-title mb-0 fw-600">Obat Sudah Kadaluarsa</h6>
        </div>
        <div class="card-body p-0">
            @if($sudahKadaluarsa->count())
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Obat</th>
                                <th>Stok</th>
                                <th>Tgl. Kadaluarsa</th>
                                <th>Sudah Lewat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sudahKadaluarsa as $obat)
                                <tr class="table-danger">
                                    <td><code>{{ $obat->kd_obat }}</code></td>
                                    <td class="fw-500">{{ $obat->nm_obat }}</td>
                                    <td>{{ $obat->stok }}</td>
                                    <td class="text-danger fw-500">{{ $obat->tgl_kadaluarsa->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-danger">
                                            {{ abs(now()->diffInDays($obat->tgl_kadaluarsa)) }} hari lalu
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.obat.edit', $obat->kd_obat) }}"
                                               class="btn btn-outline-warning py-0 px-2">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.obat.destroy', $obat->kd_obat) }}"
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-4 text-center text-muted">
                    <i class="ti ti-circle-check fs-2 d-block mb-2 text-success opacity-75"></i>
                    Tidak ada obat yang sudah melewati tanggal kadaluarsa
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        document.querySelectorAll('.btn-delete-form').forEach(form => {
            form.querySelector('button').addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Hapus Obat Kadaluarsa?',
                    text: `"${this.dataset.nama}" akan dihapus permanen.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Ya, Hapus!'
                }).then(r => { if(r.isConfirmed) form.submit(); });
            });
        });
    </script>
    @endpush
</x-admin-layout>
