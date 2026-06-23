<x-pelanggan-layout>
    <!-- Hero Banner -->
    <div class="hero-banner">
        <div class="container position-relative" style="z-index:1;">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <h1 class="mb-2">Katalog Obat</h1>
                    <p class="mb-3 opacity-85">Temukan berbagai obat berkualitas dari apotek digital kami.</p>

                    <!-- Search Bar -->
                    <form method="GET" action="{{ route('pelanggan.obat.index') }}">
                        <div class="input-group" style="max-width:500px;">
                            <input type="text" name="search" class="form-control"
                                   placeholder="Cari nama obat..." value="{{ request('search') }}"
                                   style="border-radius:10px 0 0 10px;">
                            <button type="submit" class="btn btn-light fw-600" style="border-radius:0 10px 10px 0;">
                                <i class="ti ti-search me-1"></i> Cari
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-md-5 d-none d-md-flex justify-content-end">
                    <div style="font-size:8rem;opacity:.15;"><i class="ti ti-pill"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-4">

        <!-- Filter by Kategori -->
        <div class="d-flex align-items-center gap-2 mb-4 flex-wrap">
            <span class="text-muted small fw-500 me-1">Filter:</span>
            <a href="{{ route('pelanggan.obat.index', array_merge(request()->except('kategori'), ['search' => request('search')])) }}"
               class="btn btn-sm {{ !request('kategori') ? 'btn-primary' : 'btn-outline-secondary' }}">
                Semua
            </a>
            @foreach($kategoris as $k)
                <a href="{{ route('pelanggan.obat.index', array_merge(request()->all(), ['kategori' => $k->id])) }}"
                   class="btn btn-sm {{ request('kategori') == $k->id ? 'btn-primary' : 'btn-outline-secondary' }}">
                    {{ $k->nm_kategori }}
                </a>
            @endforeach
        </div>

        @if($obats->count())
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
                @foreach($obats as $obat)
                    <div class="col">
                        <div class="card h-100" style="cursor:pointer;" onclick="location.href='{{ route('pelanggan.obat.show', $obat->kd_obat) }}'">
                            @if($obat->foto_obat)
                                <img src="{{ asset('storage/' . $obat->foto_obat) }}" class="card-img-top" alt="{{ $obat->nm_obat }}">
                            @else
                                <div class="card-img-placeholder">
                                    <i class="ti ti-pill"></i>
                                </div>
                            @endif
                            <div class="card-body p-3">
                                <div class="mb-1">
                                    <span class="badge bg-primary-lt text-primary" style="font-size:.7rem;">
                                        {{ $obat->kategori->nm_kategori ?? '-' }}
                                    </span>
                                </div>
                                <div class="card-title mb-1">{{ $obat->nm_obat }}</div>
                                <div class="small text-muted mb-2">{{ $obat->satuan }}</div>
                                <div class="price-tag">Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}</div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between py-2" onclick="event.stopPropagation()">
                                <span class="small text-muted">
                                    <i class="ti ti-package me-1"></i>Stok: {{ $obat->stok }}
                                </span>
                                <div class="d-flex gap-1">
                                    <form method="POST" action="{{ route('pelanggan.cart.add', $obat->kd_obat) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-xs btn-outline-primary py-0 px-2" title="Tambah ke Keranjang" {{ $obat->stok <= 0 ? 'disabled' : '' }}>
                                            <i class="ti ti-shopping-cart"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('pelanggan.obat.show', $obat->kd_obat) }}"
                                       class="btn btn-xs btn-primary py-0 px-2">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $obats->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="ti ti-mood-empty" style="font-size:4rem;color:#cbd5e1;"></i>
                <p class="text-muted mt-2">Tidak ada obat yang ditemukan</p>
                @if(request('search'))
                    <a href="{{ route('pelanggan.obat.index') }}" class="btn btn-outline-primary">Reset Pencarian</a>
                @endif
            </div>
        @endif
    </div>
</x-pelanggan-layout>
