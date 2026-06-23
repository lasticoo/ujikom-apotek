<x-guest-layout>
    <div class="text-center mb-4">
        <h2 class="fw-bold">Daftar Akun Pelanggan</h2>
        <p class="text-muted">Hanya untuk pelanggan -- Admin/Apoteker didaftarkan oleh Admin</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label for="nm_pelanggan" class="form-label">Nama Lengkap</label>
            <input id="nm_pelanggan" type="text" name="nm_pelanggan" value="{{ old('nm_pelanggan') }}"
                   class="form-control @error('nm_pelanggan') is-invalid @enderror"
                   required autofocus>
            @error('nm_pelanggan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea id="alamat" name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="2">{{ old('alamat') }}</textarea>
            @error('alamat')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="kota" class="form-label">Kota</label>
                <input id="kota" type="text" name="kota" value="{{ old('kota') }}"
                       class="form-control @error('kota') is-invalid @enderror">
                @error('kota')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="telpon" class="form-label">No. Telepon</label>
                <input id="telpon" type="text" name="telpon" value="{{ old('telpon') }}"
                       class="form-control @error('telpon') is-invalid @enderror">
                @error('telpon')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror"
                   required autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   required autocomplete="new-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   class="form-control @error('password_confirmation') is-invalid @enderror"
                   required autocomplete="new-password">
            @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex align-items-center justify-content-between">
            <a class="text-muted small" href="{{ route('login') }}">
                Sudah punya akun? Login
            </a>

            <button type="submit" class="btn btn-primary">
                Daftar
            </button>
        </div>
    </form>
</x-guest-layout>
