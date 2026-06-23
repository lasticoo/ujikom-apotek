<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Apotek') }} – Login</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tabler CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css" rel="stylesheet">
    <!-- Tabler Icons -->
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.31.0/dist/tabler-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --apotek-primary: #206bc4;
            --apotek-accent: #4299e1;
            --apotek-green: #2fb344;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #1a4480 100%);
            min-height: 100vh;
        }
        .auth-card {
            background: rgba(255,255,255,0.97);
            border-radius: 16px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.35);
            border: none;
            overflow: hidden;
        }
        .auth-brand {
            background: linear-gradient(135deg, #206bc4 0%, #0f4c9a 100%);
            padding: 2rem;
            text-align: center;
            color: white;
        }
        .auth-brand .brand-logo {
            width: 64px;
            height: 64px;
            background: rgba(255,255,255,0.2);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }
        .auth-body { padding: 2rem; }
        .form-control:focus { border-color: var(--apotek-primary); box-shadow: 0 0 0 0.2rem rgba(32,107,196,.15); }
        .btn-primary { background: var(--apotek-primary); border-color: var(--apotek-primary); }
        .btn-primary:hover { background: #1a5fa8; border-color: #1a5fa8; }
        .floating-particles { position: fixed; inset: 0; pointer-events: none; overflow: hidden; z-index: 0; }
        .particle { position: absolute; border-radius: 50%; background: rgba(255,255,255,0.04); animation: float 12s infinite ease-in-out; }
        @keyframes float { 0%,100%{transform:translateY(0) rotate(0deg);} 50%{transform:translateY(-20px) rotate(180deg);} }
    </style>
</head>
<body>
    <!-- Floating background particles -->
    <div class="floating-particles">
        @for($i = 0; $i < 8; $i++)
            <div class="particle" style="
                width:{{ rand(40,120) }}px; height:{{ rand(40,120) }}px;
                top:{{ rand(0,100) }}%; left:{{ rand(0,100) }}%;
                animation-delay:{{ $i * 1.5 }}s; animation-duration:{{ rand(8,16) }}s;
            "></div>
        @endfor
    </div>

    <div class="position-relative" style="z-index:1; min-height:100vh; display:flex; align-items:center; justify-content:center; padding:2rem;">
        <div style="width:100%; max-width:440px;">
            <div class="auth-card">
                <!-- Brand Header -->
                <div class="auth-brand">
                    <div class="brand-logo">
                        <i class="ti ti-pill fs-2"></i>
                    </div>
                    <h4 class="mb-1 fw-bold">Apotek Digital</h4>
                    <p class="mb-0 opacity-75 small">Sistem Manajemen Apotek Terpadu</p>
                </div>

                <!-- Form Body -->
                <div class="auth-body">
                    {{ $slot }}
                </div>
            </div>

            <p class="text-center mt-3 small" style="color:rgba(255,255,255,0.5);">
                &copy; {{ date('Y') }} Apotek Digital. All rights reserved.
            </p>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Tabler JS -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
</body>
</html>
