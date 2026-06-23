<x-pelanggan-layout>
    <div class="container py-4">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('pelanggan.penjualan.index') }}" class="btn btn-sm btn-outline-secondary me-3">
                <i class="ti ti-arrow-left"></i>
            </a>
            <h4 class="fw-700 mb-0">Detail Pesanan <code>{{ $penjualan->nota }}</code></h4>
        </div>

        <div class="row g-3">
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-header"><h6 class="card-title mb-0">Informasi Pesanan</h6></div>
                    <div class="card-body">
                        <div class="mb-2"><div class="text-muted small">No. Pesanan</div><code class="text-primary">{{ $penjualan->nota }}</code></div>
                        <div class="mb-2"><div class="text-muted small">Tanggal</div><div class="fw-500">{{ $penjualan->tgl_nota->format('d F Y') }}</div></div>
                        <div class="mb-2"><div class="text-muted small">Nama Anda</div><div class="fw-500">{{ $penjualan->pelanggan->nm_pelanggan ?? '-' }}</div></div>
                        <div class="mb-2"><div class="text-muted small">Status Pembayaran</div><div>{!! $penjualan->status_badge !!}</div></div>
                        @if($penjualan->alamat_kirim)
                            <div class="mb-2"><div class="text-muted small">Alamat Pengiriman</div><div class="fw-500 text-muted small" style="white-space: pre-line;">{{ $penjualan->alamat_kirim }}</div></div>
                        @endif
                        <hr>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted small">Diskon</span>
                            <span class="text-danger small">- Rp {{ number_format($penjualan->diskon, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between fw-700">
                            <span>Total Bayar</span>
                            <span class="text-primary">Rp {{ number_format($penjualan->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                @if($penjualan->status_pembayaran === 'belum_bayar')
                    <div class="card mb-3" style="border: 2px solid #dbeafe; background-color: #eff6ff;">
                        <div class="card-body">
                            <h6 class="fw-700 text-primary mb-2"><i class="ti ti-credit-card me-1"></i> Transfer Bank</h6>
                            <p class="small text-muted mb-2">Silakan transfer total ke rekening:</p>
                            <div class="small fw-700 text-dark mb-1">Mandiri: 123-456-7890 (PT Apotek Digital)</div>
                            <div class="small fw-700 text-dark mb-2">BCA: 987-654-3210 (PT Apotek Digital)</div>
                            <form method="POST" action="{{ route('pelanggan.penjualan.bayar', $penjualan->nota) }}" enctype="multipart/form-data" id="payment-upload-form">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small fw-700 text-dark">Unggah Bukti Transfer <span class="text-danger">*</span></label>
                                    <div class="border rounded p-3 text-center bg-white position-relative" style="border-style: dashed !important; border-color: #cbd5e1 !important; cursor: pointer; transition: background-color 0.2s;" onclick="document.getElementById('receipt-file-input').click()" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='#fff'">
                                        <input type="file" name="bukti_pembayaran" id="receipt-file-input" class="d-none @error('bukti_pembayaran') is-invalid @enderror" accept="image/*" required onchange="previewImage(this)">
                                        <div id="upload-placeholder">
                                            <i class="ti ti-photo-plus text-muted fs-1 mb-2"></i>
                                            <div class="small fw-600 text-primary">Pilih Gambar Bukti Transfer</div>
                                            <div class="text-muted" style="font-size: 0.72rem;">Format JPG, PNG, GIF (Maks. 2MB)</div>
                                        </div>
                                        <div id="upload-preview-container" class="d-none">
                                            <img id="upload-preview" src="#" class="img-fluid rounded mb-2" style="max-height: 120px;">
                                            <div class="small text-success fw-600"><i class="ti ti-check me-1"></i>Gambar Terpilih</div>
                                            <div class="text-muted small text-truncate" id="filename-label" style="max-width: 200px; margin: 0 auto;"></div>
                                        </div>
                                    </div>
                                    @error('bukti_pembayaran') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                                <button type="submit" class="btn btn-primary w-100"><i class="ti ti-upload me-1"></i> Kirim Bukti Pembayaran</button>
                            </form>
                        </div>
                    </div>
                @elseif($penjualan->status_pembayaran === 'menunggu_konfirmasi')
                    <div class="card mb-3 bg-info-lt border-info" style="border: 1px solid;">
                        <div class="card-body text-center">
                            <i class="ti ti-clock text-info fs-1"></i>
                            <div class="fw-700 text-info mt-2">Menunggu Verifikasi</div>
                            <p class="small text-muted mb-0">Bukti pembayaran telah diunggah. Apoteker kami sedang melakukan verifikasi pembayaran Anda.</p>
                        </div>
                    </div>
                @endif

                @if($penjualan->bukti_pembayaran)
                    <div class="card mb-3">
                        <div class="card-header"><h6 class="card-title mb-0">Bukti Transfer Anda</h6></div>
                        <div class="card-body p-2 text-center">
                            <a href="{{ asset('storage/' . $penjualan->bukti_pembayaran) }}" target="_blank">
                                <img src="{{ asset('storage/' . $penjualan->bukti_pembayaran) }}" class="img-fluid rounded" style="max-height: 180px;">
                            </a>
                            <div class="text-muted small mt-2">Klik gambar untuk memperbesar</div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header"><h6 class="card-title mb-0">Item yang Dibeli</h6></div>
                    <div class="card-body p-0">
                        @foreach($penjualan->details as $d)
                            <div class="d-flex align-items-center gap-3 p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div style="width:48px;height:48px;background:linear-gradient(135deg,#dbeafe,#ede9fe);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="ti ti-pill text-primary fs-4"></i>
                                </div>
                                <div class="flex-fill">
                                    <div class="fw-600">{{ $d->obat->nm_obat ?? '-' }}</div>
                                    <div class="text-muted small">
                                        {{ $d->jumlah }} {{ $d->obat->satuan ?? '' }}
                                        &times; Rp {{ number_format($d->obat->harga_jual ?? 0, 0, ',', '.') }}
                                    </div>
                                </div>
                                <div class="fw-700 text-primary">
                                    Rp {{ number_format($d->jumlah * ($d->obat->harga_jual ?? 0), 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                        <div class="p-3 d-flex justify-content-between" style="background:#f8fafc;">
                            <span class="fw-600">Total Bayar</span>
                            <span class="fw-800 text-primary fs-5">Rp {{ number_format($penjualan->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        function previewImage(input) {
            const container = document.getElementById('upload-preview-container');
            const placeholder = document.getElementById('upload-placeholder');
            const preview = document.getElementById('upload-preview');
            const filenameLabel = document.getElementById('filename-label');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                
                // Validate size (2MB max)
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ukuran File Terlalu Besar',
                        text: 'Maksimum ukuran gambar bukti transfer adalah 2MB!',
                        confirmButtonColor: '#206bc4'
                    });
                    input.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    filenameLabel.textContent = file.name;
                    placeholder.classList.add('d-none');
                    container.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            } else {
                placeholder.classList.remove('d-none');
                container.classList.add('d-none');
            }
        }
    </script>
    @endpush
</x-pelanggan-layout>
