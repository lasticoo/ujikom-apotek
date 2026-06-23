<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Apoteker | {{ config('app.name', 'Apotek') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.31.0/dist/tabler-icons.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        :root { --apoteker-primary: #2fb344; --apoteker-dark: #0f2218; }
        body { font-family: 'Inter', sans-serif; background: #f0f6f3; }
        .navbar-vertical.navbar-expand-lg {
            background: linear-gradient(180deg, #0f2218 0%, #1a3a2a 100%) !important;
            border: none;
            box-shadow: 4px 0 24px rgba(0,0,0,0.15);
        }
        .navbar-vertical .nav-link { color: rgba(255,255,255,0.65) !important; border-radius: 8px !important; margin: 2px 8px !important; padding: .5rem .75rem !important; font-weight: 500; font-size: .85rem; transition: all .2s; }
        .navbar-vertical .nav-link:hover, .navbar-vertical .nav-link.active { background: rgba(47,179,68,0.25) !important; color: white !important; }
        .navbar-vertical .nav-link.active { background: linear-gradient(135deg, rgba(47,179,68,0.7), rgba(47,179,68,0.4)) !important; }
        .nav-separator { color: rgba(255,255,255,0.3) !important; font-size: .7rem !important; letter-spacing: .08em !important; margin: .5rem 1rem !important; padding: 0 !important; }
        .navbar-top { background: white !important; border-bottom: 1px solid #e5e9f0; box-shadow: 0 1px 8px rgba(0,0,0,0.06); }
        .card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); }
        .card-header { background: transparent; border-bottom: 1px solid #e5e9f0; }
        .btn { border-radius: 8px; }
        .badge { border-radius: 6px; }
        .table th { font-size: .8rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: .05em; border-bottom: 2px solid #e5e9f0; }
        .table td { vertical-align: middle; }
        .sidebar-brand-icon { width:40px; height:40px; background:linear-gradient(135deg,#2fb344,#4ade80); border-radius:10px; display:flex; align-items:center; justify-content:center; margin-right:.75rem; }
        .sidebar-brand-text { font-weight:700; color:white; font-size:.95rem; line-height:1.2; }
        .sidebar-brand-sub { color:rgba(255,255,255,0.4); font-size:.72rem; }
        .page-title { font-size: 1.25rem; font-weight: 700; color: #0f2218; }
        .page-pretitle { font-size: .75rem; font-weight: 500; color: #64748b; text-transform: uppercase; letter-spacing: .06em; }
        .alert { border-radius: 10px; border: none; }
    </style>
    @stack('styles')
</head>
<body>
<div class="wrapper">
    <!-- Sidebar -->
    <aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-apoteker">
                <span class="navbar-toggler-icon"></span>
            </button>

            <a href="{{ route('apoteker.dashboard') }}" class="navbar-brand d-flex align-items-center text-decoration-none">
                <div class="sidebar-brand-icon me-2">
                    <i class="ti ti-stethoscope text-white fs-5"></i>
                </div>
                <div>
                    <div class="sidebar-brand-text">Apotek Digital</div>
                    <div class="sidebar-brand-sub">Apoteker Panel</div>
                </div>
            </a>

            <div class="collapse navbar-collapse" id="sidebar-apoteker">
                <ul class="navbar-nav pt-lg-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('apoteker.dashboard') ? 'active' : '' }}"
                           href="{{ route('apoteker.dashboard') }}">
                            <span class="nav-link-icon"><i class="ti ti-dashboard"></i></span>
                            <span class="nav-link-title">Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-item mt-2"><span class="nav-separator">Obat</span></li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('apoteker.obat.index') ? 'active' : '' }}"
                           href="{{ route('apoteker.obat.index') }}">
                            <span class="nav-link-icon"><i class="ti ti-pill"></i></span>
                            <span class="nav-link-title">Daftar Obat</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('apoteker.obat.create') ? 'active' : '' }}"
                           href="{{ route('apoteker.obat.create') }}">
                            <span class="nav-link-icon"><i class="ti ti-circle-plus"></i></span>
                            <span class="nav-link-title">Tambah Obat</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('apoteker.obat.kadaluarsa') ? 'active' : '' }}"
                           href="{{ route('apoteker.obat.kadaluarsa') }}">
                            <span class="nav-link-icon"><i class="ti ti-trash"></i></span>
                            <span class="nav-link-title">Obat Kadaluarsa</span>
                        </a>
                    </li>

                    @php
                        $pendingOnlineOrdersCount = \App\Models\Penjualan::where('status_pembayaran', 'menunggu_konfirmasi')
                            ->whereNotNull('alamat_kirim')
                            ->count();
                    @endphp

                    <li class="nav-item mt-2"><span class="nav-separator">Penjualan</span></li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('apoteker.penjualan.index', 'apoteker.penjualan.create', 'apoteker.penjualan.show') ? 'active' : '' }}"
                           href="{{ route('apoteker.penjualan.index') }}">
                            <span class="nav-link-icon"><i class="ti ti-receipt"></i></span>
                            <span class="nav-link-title">Penjualan Offline</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('apoteker.penjualan.online*') ? 'active' : '' }}"
                           href="{{ route('apoteker.penjualan.online') }}">
                            <span class="nav-link-icon"><i class="ti ti-bell"></i></span>
                            <span class="nav-link-title">Pesanan Online</span>
                            @if($pendingOnlineOrdersCount > 0)
                                <span class="badge bg-danger ms-auto text-white">{{ $pendingOnlineOrdersCount }}</span>
                            @endif
                        </a>
                    </li>

                    <li class="nav-item mt-2"><span class="nav-separator">Restok</span></li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('apoteker.pembelian.*') ? 'active' : '' }}"
                           href="{{ route('apoteker.pembelian.index') }}">
                            <span class="nav-link-icon"><i class="ti ti-building-warehouse"></i></span>
                            <span class="nav-link-title">Daftar Restok (Pembelian)</span>
                        </a>
                    </li>
                </ul>

                <div class="mt-auto pb-3 px-3">
                    <div class="d-flex align-items-center p-2 rounded-3" style="background:rgba(255,255,255,0.06);">
                        <div class="avatar avatar-sm me-2" style="background:linear-gradient(135deg,#2fb344,#4ade80);">
                            <i class="ti ti-user text-white"></i>
                        </div>
                        <div class="flex-fill" style="min-width:0;">
                            <div class="fw-600 small text-white text-truncate">{{ auth()->user()->nama }}</div>
                            <div class="text-truncate" style="font-size:.7rem;color:rgba(255,255,255,0.4);">Apoteker</div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="ms-1">
                            @csrf
                            <button type="submit" class="btn btn-sm p-1" style="color:rgba(255,255,255,0.5);" title="Logout">
                                <i class="ti ti-logout"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="page-wrapper">
        <header class="navbar navbar-top sticky-top d-none d-lg-flex">
            <div class="container-fluid">
                <div class="navbar-nav flex-row align-items-center">
                    <div class="page-pretitle text-muted">{{ config('app.name', 'Apotek Digital') }}</div>
                </div>
                <div class="navbar-nav flex-row ms-auto align-items-center">
                    <!-- Bell Notifications -->
                    <div class="nav-item dropdown me-3">
                        <a href="#" class="nav-link px-0 position-relative" data-bs-toggle="dropdown" title="Notifikasi Pesanan Baru">
                            <i class="ti ti-bell fs-2 text-secondary"></i>
                            @if($pendingOnlineOrdersCount > 0)
                                <span class="badge bg-red badge-notification" style="position: absolute; top: 0; right: 0; width: 8px; height: 8px; border-radius: 50%;"></span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-card shadow-lg py-0" style="min-width: 300px; border-radius: 12px; overflow: hidden;">
                            <div class="card mb-0">
                                <div class="card-header py-2 d-flex justify-content-between align-items-center bg-light">
                                    <h3 class="card-title fw-600 small text-muted mb-0">Pesanan Baru Online</h3>
                                    @if($pendingOnlineOrdersCount > 0)
                                        <span class="badge bg-red text-white">{{ $pendingOnlineOrdersCount }} Baru</span>
                                    @endif
                                </div>
                                <div class="list-group list-group-flush" style="max-height: 240px; overflow-y: auto;">
                                    @php
                                        $latestPendingOrders = \App\Models\Penjualan::where('status_pembayaran', 'menunggu_konfirmasi')
                                            ->whereNotNull('alamat_kirim')
                                            ->orderByDesc('created_at')
                                            ->take(5)
                                            ->get();
                                    @endphp
                                    @forelse($latestPendingOrders as $po)
                                        <a href="{{ route('apoteker.penjualan.online.show', $po->nota) }}" class="list-group-item list-group-item-action py-2">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <span class="avatar avatar-sm bg-warning-lt rounded-circle"><i class="ti ti-receipt text-warning"></i></span>
                                                </div>
                                                <div class="col text-truncate">
                                                    <div class="fw-600 small text-truncate">Pesanan {{ $po->nota }}</div>
                                                    <div class="text-muted small text-truncate mt-n1" style="font-size: 0.72rem;">
                                                        Pelanggan: {{ $po->pelanggan->nm_pelanggan ?? 'Umum' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @empty
                                        <div class="text-center py-4 text-muted small">
                                            <i class="ti ti-bell-off fs-2 d-block mb-1 text-secondary"></i>
                                            Tidak ada pesanan menunggu verifikasi
                                        </div>
                                    @endforelse
                                </div>
                                <div class="card-footer py-2 text-center bg-light border-top">
                                    <a href="{{ route('apoteker.penjualan.online') }}" class="small fw-600 text-primary">Lihat Semua Pesanan</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link d-flex align-items-center" data-bs-toggle="dropdown">
                            <div class="avatar avatar-sm" style="background:linear-gradient(135deg,#2fb344,#4ade80);">
                                <i class="ti ti-user text-white" style="font-size:14px;"></i>
                            </div>
                            <span class="ms-2 fw-500 small">{{ auth()->user()->nama }}</span>
                            <i class="ti ti-chevron-down ms-1 small text-muted"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow-sm">
                            <span class="dropdown-item-text small text-muted">{{ auth()->user()->email }}</span>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="ti ti-logout me-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="page-body">
            <div class="container-xl py-3">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-3">
                        <i class="ti ti-check me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-3">
                        <i class="ti ti-alert-circle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{ $slot }}
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@stack('scripts')
</body>
</html>
