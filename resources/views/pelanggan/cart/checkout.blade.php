<x-pelanggan-layout>
    <div class="container py-4">
        <h4 class="fw-700 mb-4">Checkout Pesanan</h4>

        <form method="POST" action="{{ route('pelanggan.cart.processCheckout') }}">
            @csrf
            <div class="row g-3">
                <div class="col-lg-7">
                    <div class="card mb-3">
                        <div class="card-header"><h6 class="card-title mb-0">Informasi Pengiriman</h6></div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Alamat Lengkap Pengiriman <span class="text-danger">*</span></label>
                                <textarea name="alamat_kirim" class="form-control @error('alamat_kirim') is-invalid @enderror" rows="4" placeholder="Masukkan alamat lengkap rumah Anda beserta kode pos untuk pengiriman obat..." required>{{ old('alamat_kirim', auth('pelanggan')->user()->pelanggan->alamat) }}</textarea>
                                @error('alamat_kirim') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3" style="border: 2px solid #dbeafe; background-color: #eff6ff;">
                        <div class="card-body">
                            <h6 class="fw-700 text-primary mb-2"><i class="ti ti-info-circle me-1"></i> Metode Pembayaran: Transfer Bank</h6>
                            <p class="small text-muted mb-3">Silakan transfer jumlah total pesanan Anda ke rekening apotek resmi berikut:</p>
                            <div class="p-3 bg-white rounded border mb-2 d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-800 text-dark small">Bank Mandiri</div>
                                    <div class="fs-4 text-primary fw-800">123-456-7890</div>
                                    <div class="small text-muted">a.n. PT Apotek Digital Utama</div>
                                </div>
                                <i class="ti ti-credit-card text-muted fs-1"></i>
                            </div>
                            <div class="p-3 bg-white rounded border d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-800 text-dark small">Bank BCA</div>
                                    <div class="fs-4 text-primary fw-800">987-654-3210</div>
                                    <div class="small text-muted">a.n. PT Apotek Digital Utama</div>
                                </div>
                                <i class="ti ti-credit-card text-muted fs-1"></i>
                            </div>
                            <small class="text-muted d-block mt-3">* Bukti transfer dapat diunggah setelah Anda menekan tombol "Konfirmasi Pesanan" di bawah.</small>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-header"><h6 class="card-title mb-0">Rincian Obat</h6></div>
                        <div class="card-body p-0">
                            <table class="table mb-0">
                                <thead>
                                    <tr><th>Obat</th><th>Qty</th><th>Total</th></tr>
                                </thead>
                                <tbody>
                                    @php $total = 0 @endphp
                                    @foreach($cart as $item)
                                        @php
                                            $subtotal = $item['harga_jual'] * $item['jumlah'];
                                            $total += $subtotal;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="fw-600 small">{{ $item['nm_obat'] }}</div>
                                                <div class="text-muted small">@ Rp {{ number_format($item['harga_jual'], 0, ',', '.') }}</div>
                                            </td>
                                            <td class="small">{{ $item['jumlah'] }} {{ $item['satuan'] }}</td>
                                            <td class="fw-600 small text-dark">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="p-3 border-top bg-light">
                                <div class="d-flex justify-content-between fw-800 text-primary fs-5">
                                    <span>Total Bayar</span>
                                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary w-100 py-2">
                                <i class="ti ti-circle-check me-1"></i> Konfirmasi Pesanan & Bayar
                            </button>
                            <a href="{{ route('pelanggan.cart.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                                Kembali ke Keranjang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-pelanggan-layout>
