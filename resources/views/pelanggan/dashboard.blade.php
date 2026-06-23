<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-bold">Dashboard Pelanggan</h2>
    </x-slot>

    <div class="container py-4">
        <div class="alert alert-success">
            Login berhasil sebagai <strong>{{ Auth::guard('pelanggan')->user()->pelanggan->nm_pelanggan }}</strong> (pelanggan).
        </div>
        <p class="text-muted">Halaman ini akan diisi katalog obat, detail obat, dan pembelian di Tahap 7.</p>
    </div>
</x-app-layout>
