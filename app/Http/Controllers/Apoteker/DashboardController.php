<?php

namespace App\Http\Controllers\Apoteker;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use App\Models\Penjualan;
use App\Models\Pelanggan;

class DashboardController extends Controller
{
    public function index()
    {
        $totalObat        = Obat::count();
        $stokRendah       = Obat::where('stok', '<', 10)->count();
        $obatKadaluarsa   = Obat::sudahLewatKadaluarsa()->count();
        $penjualanHariIni = Penjualan::whereDate('tgl_nota', today())->count();

        // Top 5 obat terlaris
        $topObat = Obat::withCount(['penjualanDetails as total_terjual' => function ($q) {
            $q->selectRaw('SUM(jumlah)');
        }])
        ->orderByDesc('total_terjual')
        ->take(5)
        ->get();

        // Transaksi terbaru
        $penjualanTerbaru = Penjualan::with(['pelanggan', 'details'])
            ->latest('tgl_nota')
            ->take(5)
            ->get();

        return view('apoteker.dashboard', compact(
            'totalObat', 'stokRendah', 'obatKadaluarsa', 'penjualanHariIni',
            'topObat', 'penjualanTerbaru'
        ));
    }
}
