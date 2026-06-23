<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Apotek Digital') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.31.0/dist/tabler-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #206bc4;
            --primary-dark: #1a5fa8;
            --surface: #f8fafc;
        }
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--surface); color: #1e293b; margin: 0; }

        /* Navbar */
        .site-navbar {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            padding: .75rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 6px rgba(0,0,0,0.06);
        }
        .site-navbar .brand {
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .brand-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, #206bc4, #4299e1);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: white;
        }
        .site-navbar .nav-link {
            color: #475569;
            font-weight: 500;
            font-size: .9rem;
            padding: .4rem .75rem;
            border-radius: 8px;
            transition: all .2s;
        }
        .site-navbar .nav-link:hover, .site-navbar .nav-link.active {
            background: rgba(32,107,196,0.08);
            color: var(--primary);
        }

        /* Hero Banner */
        .hero-banner {
            background: linear-gradient(135deg, #1a2744 0%, #206bc4 60%, #4299e1 100%);
            color: white;
            padding: 4rem 0 3rem;
            position: relative;
            overflow: hidden;
        }
        .hero-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 60%;
            height: 200%;
            background: rgba(255,255,255,0.03);
            border-radius: 50%;
        }
        .hero-banner h1 { font-size: 2.2rem; font-weight: 800; margin-bottom: .5rem; }
        .hero-banner p { font-size: 1.05rem; opacity: .85; }

        /* Cards */
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            overflow: hidden;
            transition: transform .2s, box-shadow .2s;
        }
        .card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,0.1); }
        .card-img-top { height: 180px; object-fit: cover; background: linear-gradient(135deg,#e0f0ff,#f0e0ff); }
        .card-img-placeholder {
            height: 180px;
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, #dbeafe, #ede9fe);
            font-size: 3rem;
            color: #93c5fd;
        }
        .card-title { font-weight: 700; font-size: 1rem; color: #1e293b; }

        /* Badge */
        .badge { border-radius: 6px; font-size: .72rem; }
        .btn { border-radius: 8px; }

        /* Footer */
        .site-footer {
            background: #1e293b;
            color: rgba(255,255,255,0.7);
            padding: 2rem 0;
            margin-top: 4rem;
        }

        /* Cart badge */
        .cart-btn { position: relative; }
        .cart-badge {
            position: absolute;
            top: -6px; right: -6px;
            width: 18px; height: 18px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            font-size: .65rem;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700;
        }

        .alert { border-radius: 10px; border: none; }
        .price-tag { font-size: 1.1rem; font-weight: 700; color: var(--primary); }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="site-navbar">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <a href="{{ route('pelanggan.obat.index') }}" class="brand">
                    <div class="brand-icon"><i class="ti ti-pill"></i></div>
                    <span>Apotek Digital</span>
                </a>

                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('pelanggan.obat.index') }}" class="nav-link {{ request()->routeIs('pelanggan.obat.index') ? 'active' : '' }}">
                        <i class="ti ti-pill me-1"></i> Obat
                    </a>
                    <a href="{{ route('pelanggan.penjualan.index') }}" class="nav-link {{ request()->routeIs('pelanggan.penjualan.*') ? 'active' : '' }}">
                        <i class="ti ti-receipt me-1"></i> Pesanan Saya
                    </a>
                    <a href="{{ route('pelanggan.cart.index') }}" class="nav-link cart-btn {{ request()->routeIs('pelanggan.cart.*') ? 'active' : '' }} me-2">
                        <i class="ti ti-shopping-cart me-1"></i> Keranjang
                        @if(session('cart') && count(session('cart')))
                            <span class="cart-badge">{{ count(session('cart')) }}</span>
                        @endif
                    </a>

                    @auth('pelanggan')
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1" data-bs-toggle="dropdown">
                                <i class="ti ti-user"></i>
                                <span class="d-none d-md-inline">{{ auth('pelanggan')->user()->pelanggan->nm_pelanggan ?? 'Pelanggan' }}</span>
                                <i class="ti ti-chevron-down"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end shadow-sm">
                                <span class="dropdown-item-text small text-muted">{{ auth('pelanggan')->user()->email }}</span>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="ti ti-logout me-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success') || session('error'))
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="ti ti-check me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="ti ti-alert-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>
    @endif

    <!-- Main Slot -->
    {{ $slot }}

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2 mb-2 mb-md-0">
                    <div class="brand-icon" style="width:28px;height:28px;font-size:.8rem;"><i class="ti ti-pill"></i></div>
                    <span class="fw-600 text-white">Apotek Digital</span>
                </div>
                <p class="mb-0 small">&copy; {{ date('Y') }} Apotek Digital. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(session('show_cart_success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Berhasil Ditambahkan!',
                text: '"{!! session("show_cart_success")["nm_obat"] !!}" telah dimasukkan ke keranjang belanja Anda.',
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#206bc4',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="ti ti-shopping-cart me-1"></i> Lihat Keranjang',
                cancelButtonText: 'Lanjut Belanja'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{!! session("show_cart_success")["cart_url"] !!}';
                }
            });
        });
    </script>
    @endif
    @stack('scripts')
</body>
</html>
