<x-pelanggan-layout>
    <div class="container py-4">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('pelanggan.obat.index') }}">Katalog Obat</a></li>
                <li class="breadcrumb-item active">{{ $obat->nm_obat }}</li>
            </ol>
        </nav>

        <div class="row g-4">
            <!-- Obat Image & Price -->
            <div class="col-lg-4">
                <div class="card text-center py-4">
                    <div class="card-body">
                        @if($obat->foto_obat)
                            <div class="mb-3 d-flex align-items-center justify-content-center shadow-sm" style="width:120px;height:120px;margin:auto;border-radius:20px;overflow:hidden;border:1px solid #e2e8f0;">
                                <img src="{{ asset('storage/' . $obat->foto_obat) }}" style="width:100%;height:100%;object-fit:cover;">
                            </div>
                        @else
                            <div class="mb-3" style="width:120px;height:120px;background:linear-gradient(135deg,#dbeafe,#ede9fe);border-radius:20px;margin:auto;display:flex;align-items:center;justify-content:center;">
                                <i class="ti ti-pill" style="font-size:4rem;color:#93c5fd;"></i>
                            </div>
                        @endif
                        <span class="badge bg-primary-lt text-primary mb-2">{{ $obat->kategori->nm_kategori ?? '-' }}</span>
                        <h4 class="fw-800 mb-1">{{ $obat->nm_obat }}</h4>
                        <code class="text-muted">{{ $obat->kd_obat }}</code>
                        <div class="my-3">
                            <span class="price-tag" style="font-size:1.5rem;">
                                Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}
                            </span>
                            <div class="text-muted small">/ {{ $obat->satuan }}</div>
                        </div>
                        <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
                            <i class="ti ti-package text-muted"></i>
                            <span class="text-muted small">Stok tersedia: <strong>{{ $obat->stok }}</strong></span>
                        </div>
                        @if($obat->stok > 0)
                            <form method="POST" action="{{ route('pelanggan.cart.add', $obat->kd_obat) }}">
                                @csrf
                                <div class="input-group mb-2">
                                    <span class="input-group-text small">Qty</span>
                                    <input type="number" name="jumlah" class="form-control" value="1" min="1" max="{{ $obat->stok }}">
                                </div>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ti ti-shopping-cart me-1"></i> Tambah ke Keranjang
                                </button>
                            </form>
                        @else
                            <button class="btn btn-secondary w-100" disabled>Stok Habis</button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Detail Info -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0 fw-600">Detail Informasi Obat</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="p-3 rounded-3" style="background:#f8fafc;">
                                    <div class="text-muted small mb-1"><i class="ti ti-tag me-1"></i>Kategori</div>
                                    <div class="fw-600">{{ $obat->kategori->nm_kategori ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 rounded-3" style="background:#f8fafc;">
                                    <div class="text-muted small mb-1"><i class="ti ti-ruler me-1"></i>Satuan</div>
                                    <div class="fw-600">{{ $obat->satuan }}</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 rounded-3" style="background:#f8fafc;">
                                    <div class="text-muted small mb-1"><i class="ti ti-building-store me-1"></i>Suplier</div>
                                    <div class="fw-600">{{ $obat->suplier->nm_suplier ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 rounded-3" style="background:#f8fafc;">
                                    <div class="text-muted small mb-1"><i class="ti ti-calendar me-1"></i>Tgl. Kadaluarsa</div>
                                    <div class="fw-600">{{ $obat->tgl_kadaluarsa ? $obat->tgl_kadaluarsa->format('d F Y') : 'Tidak tertera' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cara Beli -->
                <div class="card mt-3" style="border:2px solid #dbeafe;background:#eff6ff;">
                    <div class="card-body">
                        <h6 class="fw-600 text-primary mb-2">
                            <i class="ti ti-info-circle me-1"></i> Cara Membeli
                        </h6>
                        <ol class="mb-0 small text-muted ps-3">
                            <li>Tentukan jumlah dan klik tombol <strong>"Tambah ke Keranjang"</strong> di atas.</li>
                            <li>Obat akan dimasukkan ke keranjang belanja Anda. Anda dapat kembali memilih obat lainnya.</li>
                            <li>Buka menu <strong>"Keranjang"</strong> di navigasi atas untuk melihat ringkasan belanja Anda.</li>
                            <li>Klik <strong>"Lanjut ke Checkout"</strong>, isi alamat pengiriman Anda, lalu buat pesanan.</li>
                            <li>Lakukan transfer bank ke salah satu rekening apotek resmi yang tertera.</li>
                            <li>Unggah bukti transfer Anda. Apoteker kami akan memverifikasi dan segera memproses pengiriman obat ke alamat Anda!</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-pelanggan-layout>
