<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use App\Models\Penjualan;
use App\Models\Pelanggan;
use App\Models\User;
use App\Models\Suplier;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik ringkas
        $totalObat      = Obat::count();
        $totalPelanggan = Pelanggan::count();
        $totalApoteker  = User::where('role', 'apoteker')->count();
        $totalSuplier   = Suplier::count();

        // Penjualan bulan ini
        $penjualanBulanIni = Penjualan::whereMonth('tgl_nota', now()->month)
            ->whereYear('tgl_nota', now()->year)
            ->count();

        // Obat hampir kadaluarsa (30 hari ke depan)
        $obatHampirKadaluarsa = Obat::whereNotNull('tgl_kadaluarsa')
            ->whereBetween('tgl_kadaluarsa', [now(), now()->addDays(30)])
            ->count();

        // Obat stok menipis (< 10)
        $obatStokRendah = Obat::where('stok', '<', 10)->count();

        // Penjualan 7 hari terakhir (untuk grafik)
        $penjualan7Hari = Penjualan::selectRaw('DATE(tgl_nota) as tanggal, COUNT(*) as jumlah')
            ->where('tgl_nota', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->keyBy('tanggal');

        // Build array 7 hari
        $grafikLabels = [];
        $grafikData   = [];
        for ($i = 6; $i >= 0; $i--) {
            $tgl = Carbon::today()->subDays($i)->format('Y-m-d');
            $grafikLabels[] = Carbon::today()->subDays($i)->format('d M');
            $grafikData[]   = $penjualan7Hari[$tgl]->jumlah ?? 0;
        }

        // Top 5 obat terlaris
        $topObat = Obat::withCount(['penjualanDetails as total_terjual' => function ($q) {
            $q->selectRaw('SUM(jumlah)');
        }])
        ->orderByDesc('total_terjual')
        ->take(5)
        ->get();

        // Transaksi terbaru
        $penjualanTerbaru = Penjualan::with(['pelanggan', 'user', 'details'])
            ->latest('tgl_nota')
            ->take(6)
            ->get();

        return view('admin.dashboard', compact(
            'totalObat', 'totalPelanggan', 'totalApoteker', 'totalSuplier',
            'penjualanBulanIni', 'obatHampirKadaluarsa', 'obatStokRendah',
            'grafikLabels', 'grafikData', 'topObat', 'penjualanTerbaru'
        ));
    }
}
