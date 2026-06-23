<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin | {{ config('app.name', 'Apotek') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tabler CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css" rel="stylesheet">
    <!-- Tabler Icons -->
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.31.0/dist/tabler-icons.min.css" rel="stylesheet">
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        :root {
            --apotek-primary: #206bc4;
            --apotek-dark: #0f172a;
            --apotek-sidebar: #1a2744;
            --apotek-sidebar-active: #206bc4;
            --apotek-accent: #4299e1;
        }
        body { font-family: 'Inter', sans-serif; background: #f0f4f8; }

        /* Sidebar Overrides */
        .navbar-vertical.navbar-expand-lg {
            background: linear-gradient(180deg, #1a2744 0%, #0f1f3d 100%) !important;
            border: none;
            box-shadow: 4px 0 24px rgba(0,0,0,0.15);
        }
        .navbar-brand-image { width: 32px; height: 32px; }
        .navbar-brand { padding: .8rem 1rem; }
        .navbar-brand-autodark { filter: none !important; }

        /* Sidebar menu items */
        .navbar-vertical .nav-link {
            color: rgba(255,255,255,0.65) !important;
            border-radius: 8px !important;
            margin: 2px 8px !important;
            padding: .5rem .75rem !important;
            font-weight: 500;
            font-size: .85rem;
            transition: all .2s ease;
        }
        .navbar-vertical .nav-link:hover,
        .navbar-vertical .nav-link.active {
            background: rgba(32,107,196,0.25) !important;
            color: white !important;
        }
        .navbar-vertical .nav-link.active {
            background: linear-gradient(135deg, rgba(32,107,196,0.8), rgba(32,107,196,0.5)) !important;
        }
        .navbar-vertical .nav-link .nav-link-icon { color: rgba(255,255,255,0.5); }
        .navbar-vertical .nav-link:hover .nav-link-icon,
        .navbar-vertical .nav-link.active .nav-link-icon { color: white; }

        .nav-separator { color: rgba(255,255,255,0.3) !important; font-size: .7rem !important; letter-spacing: .08em !important; margin: .5rem 1rem !important; padding: 0 !important; }

        /* Top header */
        .navbar-top {
            background: white !important;
            border-bottom: 1px solid #e5e9f0;
            box-shadow: 0 1px 8px rgba(0,0,0,0.06);
        }

        /* Page card */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
        }
        .card-header { background: transparent; border-bottom: 1px solid #e5e9f0; }

        /* Stat cards */
        .stat-card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            transition: transform .2s, box-shadow .2s;
            overflow: hidden;
            position: relative;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,0.12); }
        .stat-card-icon {
            width: 52px; height: 52px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
        }

        /* Badges & buttons */
        .btn { border-radius: 8px; }
        .badge { border-radius: 6px; }

        /* Page title */
        .page-header { background: transparent; border: none; padding: 0; margin-bottom: 1.5rem; }
        .page-title { font-size: 1.25rem; font-weight: 700; color: #1a2744; }
        .page-pretitle { font-size: .75rem; font-weight: 500; color: #64748b; text-transform: uppercase; letter-spacing: .06em; }

        /* Table */
        .table th { font-size: .8rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: .05em; border-bottom: 2px solid #e5e9f0; }
        .table td { vertical-align: middle; }
        .table-hover tbody tr:hover { background: #f8fafc; }

        /* Alert */
        .alert { border-radius: 10px; border: none; }

        /* Sidebar logo area */
        .sidebar-brand { padding: 1.2rem 1rem; border-bottom: 1px solid rgba(255,255,255,0.08); margin-bottom: .5rem; }
        .sidebar-brand-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, #206bc4, #4299e1);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            margin-right: .75rem;
        }
        .sidebar-brand-text { font-weight: 700; color: white; font-size: .95rem; line-height: 1.2; }
        .sidebar-brand-sub { color: rgba(255,255,255,0.4); font-size: .72rem; }

        @media print {
            .navbar-vertical,
            .navbar-top,
            .alert,
            .btn,
            .card-footer,
            .no-print,
            form,
            .btn-close {
                display: none !important;
            }
            body {
                background: white !important;
                color: black !important;
            }
            .page-wrapper {
                margin: 0 !important;
                padding: 0 !important;
            }
            .container-xl {
                max-width: 100% !important;
                width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .card {
                box-shadow: none !important;
                border: none !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="antialiased">
<div class="wrapper">

    <!-- Vertical Sidebar -->
    <aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Brand -->
            <a href="{{ route('admin.dashboard') }}" class="navbar-brand d-flex align-items-center text-decoration-none">
                <div class="sidebar-brand-icon me-2">
                    <i class="ti ti-pill text-white fs-5"></i>
                </div>
                <div>
                    <div class="sidebar-brand-text">Apotek Digital</div>
                    <div class="sidebar-brand-sub">Admin Panel</div>
                </div>
            </a>

            <!-- Nav Items -->
            <div class="collapse navbar-collapse" id="sidebar-menu">
                <ul class="navbar-nav pt-lg-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                           href="{{ route('admin.dashboard') }}">
                            <span class="nav-link-icon"><i class="ti ti-dashboard"></i></span>
                            <span class="nav-link-title">Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-item mt-2">
                        <span class="nav-separator">Master Data</span>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.obat.*') ? 'active' : '' }}"
                           href="{{ route('admin.obat.index') }}">
                            <span class="nav-link-icon"><i class="ti ti-pill"></i></span>
                            <span class="nav-link-title">Daftar Obat</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.suplier.*') ? 'active' : '' }}"
                           href="{{ route('admin.suplier.index') }}">
                            <span class="nav-link-icon"><i class="ti ti-truck-delivery"></i></span>
                            <span class="nav-link-title">Daftar Suplier</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.pelanggan.*') ? 'active' : '' }}"
                           href="{{ route('admin.pelanggan.index') }}">
                            <span class="nav-link-icon"><i class="ti ti-users"></i></span>
                            <span class="nav-link-title">Daftar Pelanggan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.apoteker.*') ? 'active' : '' }}"
                           href="{{ route('admin.apoteker.index') }}">
                            <span class="nav-link-icon"><i class="ti ti-stethoscope"></i></span>
                            <span class="nav-link-title">Daftar Apoteker</span>
                        </a>
                    </li>

                    <li class="nav-item mt-2">
                        <span class="nav-separator">Transaksi</span>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.pembelian.index', 'admin.pembelian.create', 'admin.pembelian.show') ? 'active' : '' }}"
                           href="{{ route('admin.pembelian.index') }}">
                            <span class="nav-link-icon"><i class="ti ti-building-warehouse"></i></span>
                            <span class="nav-link-title">Restok Obat (Pembelian)</span>
                        </a>
                    </li>

                    <li class="nav-item mt-2">
                        <span class="nav-separator">Laporan & Analisa</span>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.penjualan.*') ? 'active' : '' }}"
                           href="{{ route('admin.penjualan.index') }}">
                            <span class="nav-link-icon"><i class="ti ti-receipt"></i></span>
                            <span class="nav-link-title">Laporan Penjualan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.pembelian.report*') ? 'active' : '' }}"
                           href="{{ route('admin.pembelian.report') }}">
                            <span class="nav-link-icon"><i class="ti ti-file-analytics"></i></span>
                            <span class="nav-link-title">Laporan Restok (Pembelian)</span>
                        </a>
                    </li>

                    <li class="nav-item mt-2">
                        <span class="nav-separator">Tools</span>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.obat.kadaluarsa') ? 'active' : '' }}"
                           href="{{ route('admin.obat.kadaluarsa') }}">
                            <span class="nav-link-icon"><i class="ti ti-alert-triangle"></i></span>
                            <span class="nav-link-title">Obat Kadaluarsa</span>
                        </a>
                    </li>
                </ul>

                <div class="mt-auto pb-3 px-3">
                    <div class="d-flex align-items-center p-2 rounded-3" style="background:rgba(255,255,255,0.06);">
                        <div class="avatar avatar-sm me-2" style="background:linear-gradient(135deg,#206bc4,#4299e1);">
                            <i class="ti ti-user text-white"></i>
                        </div>
                        <div class="flex-fill" style="min-width:0;">
                            <div class="fw-600 small text-white text-truncate">{{ auth()->user()->nama }}</div>
                            <div class="text-truncate" style="font-size:.7rem;color:rgba(255,255,255,0.4);">Administrator</div>
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
        <!-- Top bar -->
        <header class="navbar navbar-top sticky-top d-none d-lg-flex">
            <div class="container-fluid">
                <div class="navbar-nav flex-row align-items-center">
                    <div class="page-pretitle text-muted">
                        {{ config('app.name', 'Apotek Digital') }}
                    </div>
                </div>
                <div class="navbar-nav flex-row ms-auto">
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link d-flex align-items-center" data-bs-toggle="dropdown">
                            <div class="avatar avatar-sm" style="background:linear-gradient(135deg,#206bc4,#4299e1);">
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
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                        <i class="ti ti-check me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                        <i class="ti ti-alert-circle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{ $slot }}
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@stack('scripts')
</body>
</html>
