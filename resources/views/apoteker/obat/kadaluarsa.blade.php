<x-apoteker-layout>
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('apoteker.obat.index') }}" class="btn btn-sm btn-outline-secondary me-3">
            <i class="ti ti-arrow-left"></i>
        </a>
        <div>
            <div class="page-pretitle">Obat</div>
            <h2 class="page-title mb-0">Monitoring Obat Kadaluarsa</h2>
        </div>
    </div>

    <!-- Sudah Kadaluarsa -->
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center">
            <span class="badge bg-danger me-2">{{ $obatsKadaluarsa->count() }}</span>
            <h6 class="card-title mb-0 fw-600">Obat SUDAH Kadaluarsa — Perlu Dihapus</h6>
        </div>
        <div class="card-body p-0">
            @if($obatsKadaluarsa->count())
                <table class="table table-hover mb-0">
                    <thead>
                        <tr><th>Kode</th><th>Nama Obat</th><th>Stok</th><th>Kadaluarsa</th><th>Lewat</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @foreach($obatsKadaluarsa as $obat)
                            <tr class="table-danger">
                                <td><code>{{ $obat->kd_obat }}</code></td>
                                <td class="fw-500">{{ $obat->nm_obat }}</td>
                                <td>{{ $obat->stok }}</td>
                                <td class="text-danger">{{ $obat->tgl_kadaluarsa->format('d/m/Y') }}</td>
                                <td><span class="badge bg-danger">{{ abs(now()->diffInDays($obat->tgl_kadaluarsa)) }} hari</span></td>
                                <td>
                                    <form method="POST" action="{{ route('apoteker.obat.destroyKadaluarsa', $obat->kd_obat) }}"
                                          class="d-inline btn-destroy-form">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger py-0 px-2"
                                                data-nama="{{ $obat->nm_obat }}">
                                            <i class="ti ti-trash me-1"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="py-4 text-center text-muted">
                    <i class="ti ti-circle-check fs-2 d-block mb-2 text-success opacity-75"></i>
                    Tidak ada obat yang sudah kadaluarsa
                </div>
            @endif
        </div>
    </div>

    <!-- Akan Kadaluarsa -->
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <span class="badge bg-warning me-2">{{ $obatsAkanKadaluarsa->count() }}</span>
            <h6 class="card-title mb-0 fw-600">Obat AKAN Kadaluarsa (30 hari ke depan)</h6>
        </div>
        <div class="card-body p-0">
            @if($obatsAkanKadaluarsa->count())
                <table class="table table-hover mb-0">
                    <thead><tr><th>Kode</th><th>Nama Obat</th><th>Stok</th><th>Kadaluarsa</th><th>Sisa</th></tr></thead>
                    <tbody>
                        @foreach($obatsAkanKadaluarsa as $obat)
                            <tr>
                                <td><code>{{ $obat->kd_obat }}</code></td>
                                <td class="fw-500">{{ $obat->nm_obat }}</td>
                                <td>{{ $obat->stok }}</td>
                                <td class="text-warning">{{ $obat->tgl_kadaluarsa->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge bg-warning text-dark">
                                        {{ now()->diffInDays($obat->tgl_kadaluarsa) }} hari
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="py-4 text-center text-muted">Tidak ada obat yang akan segera kadaluarsa</div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        document.querySelectorAll('.btn-destroy-form').forEach(form => {
            form.querySelector('button').addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Hapus Obat Kadaluarsa?',
                    text: `"${this.dataset.nama}" akan dihapus secara permanen.`,
                    icon: 'warning', showCancelButton: true,
                    confirmButtonColor: '#d33', cancelButtonText: 'Batal', confirmButtonText: 'Ya, Hapus!'
                }).then(r => { if(r.isConfirmed) form.submit(); });
            });
        });
    </script>
    @endpush
</x-apoteker-layout>
