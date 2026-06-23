<x-apoteker-layout>
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('apoteker.penjualan.online') }}" class="btn btn-sm btn-outline-secondary me-3">
            <i class="ti ti-arrow-left"></i>
        </a>
        <div>
            <div class="page-pretitle">Transaksi</div>
            <h2 class="page-title mb-0">Verifikasi Pesanan Online</h2>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header"><h6 class="card-title mb-0">Informasi Pemesan</h6></div>
                <div class="card-body">
                    <div class="mb-2"><div class="text-muted small">No. Pesanan</div><code class="text-success">{{ $penjualan->nota }}</code></div>
                    <div class="mb-2"><div class="text-muted small">Tanggal</div><div class="fw-500">{{ $penjualan->tgl_nota->format('d F Y') }}</div></div>
                    <div class="mb-2"><div class="text-muted small">Nama Pelanggan</div><div class="fw-500">{{ $penjualan->pelanggan->nm_pelanggan ?? '-' }}</div></div>
                    <div class="mb-2"><div class="text-muted small">Telepon Pelanggan</div><div class="fw-500 text-dark">{{ $penjualan->pelanggan->telpon ?? '-' }}</div></div>
                    <div class="mb-2"><div class="text-muted small">Status Pembayaran</div><div>{!! $penjualan->status_badge !!}</div></div>
                    <div class="mb-2">
                        <div class="text-muted small">Alamat Pengiriman</div>
                        <div class="fw-600 bg-light p-2 rounded small text-dark" style="white-space: pre-line;">{{ $penjualan->alamat_kirim }}</div>
                    </div>
                </div>
            </div>

            <!-- Verification Action Box -->
            @if($penjualan->status_pembayaran === 'menunggu_konfirmasi')
                <div class="card mb-3 border-warning bg-warning-lt">
                    <div class="card-body">
                        <h6 class="fw-700 text-warning mb-2"><i class="ti ti-shield-alert"></i> Butuh Konfirmasi</h6>
                        <p class="small text-muted mb-3">Silakan periksa bukti transfer yang diunggah pelanggan di sebelah kanan sebelum memproses transaksi ini.</p>
                        
                        <div class="d-flex gap-2">
                            <form method="POST" action="{{ route('apoteker.penjualan.online.confirm', $penjualan->nota) }}" class="flex-fill">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success w-100"><i class="ti ti-check me-1"></i> Terima Pembayaran</button>
                            </form>
                            <form method="POST" action="{{ route('apoteker.penjualan.online.reject', $penjualan->nota) }}" class="flex-fill btn-reject-form">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger w-100"><i class="ti ti-x me-1"></i> Tolak Pesanan</button>
                            </form>
                        </div>
                    </div>
                </div>
            @elseif($penjualan->status_pembayaran === 'lunas')
                <div class="card mb-3 bg-success-lt border-success">
                    <div class="card-body text-center">
                        <i class="ti ti-circle-check text-success fs-1"></i>
                        <div class="fw-700 text-success mt-2">Selesai / Lunas</div>
                        <p class="small text-muted mb-0">Pesanan telah lunas dikonfirmasi oleh <strong>{{ $penjualan->user->nama ?? 'Apoteker' }}</strong>. Silakan kemas dan kirim obat ke alamat pengiriman.</p>
                    </div>
                </div>
            @elseif($penjualan->status_pembayaran === 'dibatalkan')
                <div class="card mb-3 bg-danger-lt border-danger">
                    <div class="card-body text-center">
                        <i class="ti ti-circle-x text-danger fs-1"></i>
                        <div class="fw-700 text-danger mt-2">Ditolak / Dibatalkan</div>
                        <p class="small text-muted mb-0">Transaksi ini telah ditolak/dibatalkan. Stok obat otomatis dikembalikan.</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header"><h6 class="card-title mb-0">Item Pesanan Obat</h6></div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead>
                            <tr><th>Obat</th><th class="text-center">Stok Saat Ini</th><th class="text-center">Jumlah Beli</th><th class="text-end">Subtotal</th></tr>
                        </thead>
                        <tbody>
                            @foreach($penjualan->details as $d)
                                <tr>
                                    <td>
                                        <div class="fw-600 small">{{ $d->obat->nm_obat ?? '-' }}</div>
                                        <div class="text-muted small"><code>{{ $d->kd_obat }}</code> &bull; Rp {{ number_format($d->obat->harga_jual ?? 0, 0, ',', '.') }} / {{ $d->obat->satuan }}</div>
                                    </td>
                                    <td class="text-center small">{{ $d->obat->stok ?? 0 }}</td>
                                    <td class="text-center fw-600 text-dark small">{{ $d->jumlah }} {{ $d->obat->satuan ?? '' }}</td>
                                    <td class="text-end fw-600 text-success small">Rp {{ number_format($d->jumlah * ($d->obat->harga_jual ?? 0), 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            <tr class="bg-light">
                                <td colspan="3" class="fw-700 text-dark">Total Tagihan</td>
                                <td class="text-end fw-800 text-success fs-4">Rp {{ number_format($penjualan->total, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Proof of payment section -->
            <div class="card">
                <div class="card-header"><h6 class="card-title mb-0">Bukti Pembayaran Pelanggan</h6></div>
                <div class="card-body text-center py-4 bg-light">
                    @if($penjualan->bukti_pembayaran)
                        <div class="mb-3">
                            <a href="{{ asset('storage/' . $penjualan->bukti_pembayaran) }}" target="_blank">
                                <img src="{{ asset('storage/' . $penjualan->bukti_pembayaran) }}" class="rounded shadow-sm border border-secondary img-fluid" style="max-height: 380px;">
                            </a>
                        </div>
                        <a href="{{ asset('storage/' . $penjualan->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-outline-success">
                            <i class="ti ti-external-link"></i> Buka Gambar di Tab Baru
                        </a>
                    @else
                        <div class="py-4 text-center text-muted">
                            <i class="ti ti-photo-x fs-1 d-block mb-2 opacity-50"></i>
                            Pelanggan belum mengunggah bukti transfer pembayaran.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.querySelectorAll('.btn-reject-form').forEach(form => {
            form.querySelector('button').addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Tolak / Batalkan Pesanan?',
                    text: 'Tindakan ini akan mengembalikan stok obat yang telah dipesan kembali ke inventaris.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Ya, Tolak!'
                }).then(r => { if(r.isConfirmed) form.submit(); });
            });
        });
    </script>
    @endpush
</x-apoteker-layout>
