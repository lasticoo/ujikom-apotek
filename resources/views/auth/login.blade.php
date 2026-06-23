<x-guest-layout>
    <div class="text-center mb-4">
        <h5 class="fw-bold mb-1">Selamat Datang</h5>
        <p class="text-muted small mb-0">Masuk sebagai Admin, Apoteker, atau Pelanggan</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success py-2 mb-3">
            <i class="ti ti-check me-1"></i> {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label fw-500 small">Email</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                    <i class="ti ti-mail text-muted" style="font-size:1rem;"></i>
                </span>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       class="form-control border-start-0 @error('email') is-invalid @enderror"
                       placeholder="email@apotek.com" required autofocus autocomplete="username">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label fw-500 small">Password</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                    <i class="ti ti-lock text-muted" style="font-size:1rem;"></i>
                </span>
                <input id="password" type="password" name="password"
                       class="form-control border-start-0 @error('password') is-invalid @enderror"
                       placeholder="••••••••" required autocomplete="current-password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="form-check">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                <label for="remember_me" class="form-check-label small">Ingat saya</label>
            </div>
            @if (Route::has('password.request'))
                <a class="small text-primary" href="{{ route('password.request') }}">Lupa password?</a>
            @endif
        </div>

        <button type="submit" class="btn btn-primary w-100 fw-600">
            <i class="ti ti-login me-1"></i> Masuk
        </button>

        <hr class="my-4">

        <p class="text-center text-muted small mb-0">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-primary fw-500">Daftar sebagai Pelanggan</a>
        </p>
    </form>
</x-guest-layout>
