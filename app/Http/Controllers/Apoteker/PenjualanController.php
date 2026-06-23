<?php

namespace App\Http\Controllers\Apoteker;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PenjualanController extends Controller
{
    public function index(Request $request): View
    {
        $query = Penjualan::with(['pelanggan', 'user', 'details.obat']);

        if ($search = $request->search) {
            $query->where('nota', 'like', "%{$search}%")
                  ->orWhereHas('pelanggan', fn ($q) => $q->where('nm_pelanggan', 'like', "%{$search}%"));
        }

        $penjualans = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('apoteker.penjualan.index', compact('penjualans'));
    }

    public function create(): View
    {
        $pelanggans = Pelanggan::orderBy('nm_pelanggan')->get();
        $obats      = Obat::aktif()->where('stok', '>', 0)->orderBy('nm_obat')->get();

        return view('apoteker.penjualan.create', compact('pelanggans', 'obats'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'tgl_nota'    => 'required|date',
            'kd_pelanggan'=> 'nullable|exists:pelanggans,kd_pelanggan',
            'diskon'      => 'nullable|numeric|min:0',
            'items'       => 'required|array|min:1',
            'items.*.kd_obat' => 'required|exists:obats,kd_obat',
            'items.*.jumlah'  => 'required|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Generate nomor nota
                $lastNota = Penjualan::orderByRaw('CAST(SUBSTRING(nota, 5) AS UNSIGNED) DESC')->first();
                $nextNum  = $lastNota ? ((int) substr($lastNota->nota, 4)) + 1 : 1;
                $nota     = 'PJL-' . str_pad($nextNum, 5, '0', STR_PAD_LEFT);

                $penjualan = Penjualan::create([
                    'nota'              => $nota,
                    'tgl_nota'          => $request->tgl_nota,
                    'kd_pelanggan'      => $request->kd_pelanggan ?: null,
                    'id_user'           => Auth::guard('web')->id(),
                    'diskon'            => $request->diskon ?? 0,
                    'status_pembayaran' => 'lunas',
                ]);

                foreach ($request->items as $item) {
                    $obat = Obat::findOrFail($item['kd_obat']);

                    // Kurangi stok
                    if ($obat->stok < $item['jumlah']) {
                        throw new \Exception("Stok obat {$obat->nm_obat} tidak mencukupi.");
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

        return redirect()->route('apoteker.penjualan.index')
            ->with('success', 'Transaksi penjualan berhasil ditambahkan.');
    }

    public function show(Penjualan $penjualan): View
    {
        $penjualan->load(['pelanggan', 'user', 'details.obat']);

        return view('apoteker.penjualan.show', compact('penjualan'));
    }

    public function onlineOrders(Request $request): View
    {
        $query = Penjualan::with(['pelanggan', 'details.obat'])
            ->whereNotNull('kd_pelanggan')
            ->whereNotNull('alamat_kirim'); // online orders have delivery addresses

        if ($status = $request->status) {
            $query->where('status_pembayaran', $status);
        }

        $penjualans = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('apoteker.penjualan.online', compact('penjualans'));
    }

    public function showOnlineOrder(Penjualan $penjualan): View
    {
        abort_if(!$penjualan->alamat_kirim, 404);
        $penjualan->load(['pelanggan', 'details.obat']);
        return view('apoteker.penjualan.online_show', compact('penjualan'));
    }

    public function confirmPayment(Penjualan $penjualan): RedirectResponse
    {
        abort_if(!$penjualan->alamat_kirim, 404);

        $penjualan->update([
            'status_pembayaran' => 'lunas',
            'id_user' => Auth::guard('web')->id(), // processed by this Apoteker
        ]);

        return redirect()->route('apoteker.penjualan.online.show', $penjualan->nota)
            ->with('success', 'Pembayaran berhasil dikonfirmasi. Obat siap dikirim ke alamat pelanggan.');
    }

    public function rejectPayment(Penjualan $penjualan): RedirectResponse
    {
        abort_if(!$penjualan->alamat_kirim, 404);

        try {
            DB::transaction(function () use ($penjualan) {
                // If payment is already lunas, we should not reject or if it was, we restore stock.
                // We only restore stock if it's currently not 'dibatalkan'
                if ($penjualan->status_pembayaran !== 'dibatalkan') {
                    // Loop through details and restore stock
                    foreach ($penjualan->details as $detail) {
                        $obat = Obat::findOrFail($detail->kd_obat);
                        $obat->increment('stok', $detail->jumlah);
                    }
                }

                $penjualan->update([
                    'status_pembayaran' => 'dibatalkan',
                    'id_user' => Auth::guard('web')->id(),
                ]);
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('apoteker.penjualan.online.show', $penjualan->nota)
            ->with('success', 'Pesanan dibatalkan/ditolak. Stok obat telah dikembalikan.');
    }
}
