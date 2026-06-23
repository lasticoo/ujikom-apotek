<x-pelanggan-layout>
    <div class="container py-4">
        <h4 class="fw-700 mb-4">Keranjang Belanja</h4>

        @if(count($cart))
            <div class="row g-3">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Foto</th>
                                            <th>Obat</th>
                                            <th>Harga</th>
                                            <th style="width:120px;">Jumlah</th>
                                            <th>Subtotal</th>
                                            <th>Aksi</th>
                                        </tr>
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
                                                    @if($item['foto_obat'])
                                                        <img src="{{ asset('storage/' . $item['foto_obat']) }}" class="rounded" style="width:48px;height:48px;object-fit:cover;">
                                                    @else
                                                        <span class="avatar avatar-sm bg-blue-lt"><i class="ti ti-pill"></i></span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="fw-600">{{ $item['nm_obat'] }}</div>
                                                    <div class="text-muted small">Stok tersedia: {{ $item['stok'] }}</div>
                                                </td>
                                                <td>Rp {{ number_format($item['harga_jual'], 0, ',', '.') }}</td>
                                                <td>
                                                    <form method="POST" action="{{ route('pelanggan.cart.update', $item['kd_obat']) }}" class="qty-form">
                                                        @csrf @method('PATCH')
                                                        <div class="input-group input-group-sm border rounded" style="width: 110px; overflow: hidden; height: 32px; background: #fff;">
                                                            <button type="button" class="btn btn-link text-secondary px-2 py-0 border-0 bg-transparent" onclick="decrementQty(this)">
                                                                <i class="ti ti-minus"></i>
                                                            </button>
                                                            <input type="number" name="jumlah" class="form-control form-control-sm text-center border-0 bg-transparent px-0 fw-700" 
                                                                   value="{{ $item['jumlah'] }}" min="1" max="{{ $item['stok'] }}" readonly style="pointer-events: none;">
                                                            <button type="button" class="btn btn-link text-secondary px-2 py-0 border-0 bg-transparent" onclick="incrementQty(this)">
                                                                <i class="ti ti-plus"></i>
                                                            </button>
                                                        </div>
                                                    </form>
                                                </td>
                                                <td class="fw-600 text-primary">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                                <td>
                                                    <form method="POST" action="{{ route('pelanggan.cart.remove', $item['kd_obat']) }}">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2" title="Hapus">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('pelanggan.obat.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Kembali ke Katalog
                        </a>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header"><h6 class="card-title mb-0">Ringkasan Pesanan</h6></div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                <span class="fw-500">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between mb-4 fs-5 fw-800 text-primary">
                                <span>Total</span>
                                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <a href="{{ route('pelanggan.cart.checkout') }}" class="btn btn-primary w-100 py-2">
                                <i class="ti ti-check me-1"></i> Lanjut ke Checkout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="card py-5 text-center">
                <div class="card-body">
                    <i class="ti ti-shopping-cart-x text-muted" style="font-size: 5rem;"></i>
                    <h5 class="mt-3 text-muted">Keranjang Belanja Anda Kosong</h5>
                    <p class="text-muted small">Anda belum menambahkan obat apapun ke keranjang.</p>
                    <a href="{{ route('pelanggan.obat.index') }}" class="btn btn-primary mt-2">
                        <i class="ti ti-pill me-1"></i> Mulai Cari Obat
                    </a>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        function decrementQty(btn) {
            const form = btn.closest('form');
            const input = form.querySelector('input[name="jumlah"]');
            const current = parseInt(input.value) || 1;
            if (current > 1) {
                input.value = current - 1;
                form.submit();
            }
        }

        function incrementQty(btn) {
            const form = btn.closest('form');
            const input = form.querySelector('input[name="jumlah"]');
            const current = parseInt(input.value) || 1;
            const max = parseInt(input.max) || 999;
            if (current < max) {
                input.value = current + 1;
                form.submit();
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Stok Terbatas',
                    text: 'Jumlah melebihi batas stok obat yang tersedia (' + max + ')!',
                    confirmButtonColor: '#206bc4'
                });
            }
        }
    </script>
    @endpush
</x-pelanggan-layout>
