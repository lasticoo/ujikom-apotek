<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PenjualanController extends Controller
{
    /** Riwayat pesanan milik pelanggan yang sedang login. */
    public function index(): View
    {
        $akun      = Auth::guard('pelanggan')->user();
        $pelanggan = $akun->pelanggan;

        $penjualans = Penjualan::with(['details.obat'])
            ->where('kd_pelanggan', $pelanggan->kd_pelanggan)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('pelanggan.penjualan.index', compact('penjualans'));
    }

    public function show(Penjualan $penjualan): View
    {
        // Pastikan ini milik pelanggan yang login
        $akun = Auth::guard('pelanggan')->user();
        abort_if($penjualan->kd_pelanggan !== $akun->pelanggan->kd_pelanggan, 403);

        $penjualan->load(['pelanggan', 'details.obat']);

        return view('pelanggan.penjualan.show', compact('penjualan'));
    }

    public function create(): View
    {
        $obats = Obat::aktif()->where('stok', '>', 0)->orderBy('nm_obat')->get();

        return view('pelanggan.penjualan.create', compact('obats'));
    }

    /** Pelanggan melakukan pembelian mandiri. */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'items'          => 'required|array|min:1',
            'items.*.kd_obat'=> 'required|exists:obats,kd_obat',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        $akun      = Auth::guard('pelanggan')->user();
        $pelanggan = $akun->pelanggan;

        try {
            DB::transaction(function () use ($request, $pelanggan) {
                $lastNota = Penjualan::orderByRaw('CAST(SUBSTRING(nota, 5) AS UNSIGNED) DESC')->first();
                $nextNum  = $lastNota ? ((int) substr($lastNota->nota, 4)) + 1 : 1;
                $nota     = 'PJL-' . str_pad($nextNum, 5, '0', STR_PAD_LEFT);

                $penjualan = Penjualan::create([
                    'nota'         => $nota,
                    'tgl_nota'     => now()->toDateString(),
                    'kd_pelanggan' => $pelanggan->kd_pelanggan,
                    'id_user'      => null,   // dibuat oleh pelanggan sendiri
                    'diskon'       => 0,
                ]);

                foreach ($request->items as $item) {
                    $obat = Obat::findOrFail($item['kd_obat']);

                    if ($obat->stok < $item['jumlah']) {
                        throw new \Exception("Stok {$obat->nm_obat} tidak mencukupi.");
                    }

                    PenjualanDetail::create([
                        'nota'    => $nota,
                        'kd_obat' => $obat->kd_obat,
                        'jumlah'  => $item['jumlah'],
                    ]);

                    $obat->decrement('stok', $item['jumlah']);
                }
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }

        return redirect()->route('pelanggan.penjualan.index')
            ->with('success', 'Pesanan berhasil dibuat! Terima kasih.');
    }

    public function uploadBuktiPembayaran(Request $request, Penjualan $penjualan): RedirectResponse
    {
        // Ensure this belongs to logged in customer
        $akun = Auth::guard('pelanggan')->user();
        abort_if($penjualan->kd_pelanggan !== $akun->pelanggan->kd_pelanggan, 403);

        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('bukti_pembayaran')) {
            // Delete old proof if exists
            if ($penjualan->bukti_pembayaran && \Illuminate\Support\Facades\Storage::disk('public')->exists($penjualan->bukti_pembayaran)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($penjualan->bukti_pembayaran);
            }
            $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
            
            $penjualan->update([
                'bukti_pembayaran' => $path,
                'status_pembayaran' => 'menunggu_konfirmasi',
            ]);

            return back()->with('success', 'Bukti pembayaran berhasil diunggah! Menunggu konfirmasi Apoteker.');
        }

        return back()->with('error', 'Gagal mengunggah bukti pembayaran.');
    }
}
