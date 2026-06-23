<?php

namespace App\Http\Controllers\Apoteker;

use App\Http\Controllers\Controller;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Obat;
use App\Models\Suplier;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PembelianController extends Controller
{
    public function index(Request $request): View
    {
        $query = Pembelian::with(['suplier', 'user', 'details.obat']);

        if ($search = $request->search) {
            $query->where('nota', 'like', "%{$search}%")
                  ->orWhereHas('suplier', fn ($q) => $q->where('nm_suplier', 'like', "%{$search}%"));
        }

        if ($from = $request->from) {
            $query->where('tgl_nota', '>=', $from);
        }

        if ($to = $request->to) {
            $query->where('tgl_nota', '<=', $to);
        }

        $pembelians = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('apoteker.pembelian.index', compact('pembelians'));
    }

    public function create(): View
    {
        $supliers = Suplier::orderBy('nm_suplier')->get();
        $obats = Obat::orderBy('nm_obat')->get();

        // Auto generate nota PO (restock code)
        $nota = Pembelian::generateNota();

        return view('apoteker.pembelian.create', compact('supliers', 'obats', 'nota'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'tgl_nota' => 'required|date',
            'kd_suplier' => 'required|exists:supliers,kd_suplier',
            'diskon' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.kd_obat' => 'required|exists:obats,kd_obat',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga_beli' => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $nota = Pembelian::generateNota();

                Pembelian::create([
                    'nota' => $nota,
                    'tgl_nota' => $request->tgl_nota,
                    'kd_suplier' => $request->kd_suplier,
                    'id_user' => Auth::guard('web')->id(),
                    'diskon' => $request->diskon ?? 0,
                ]);

                foreach ($request->items as $item) {
                    $obat = Obat::findOrFail($item['kd_obat']);

                    // Create detail
                    PembelianDetail::create([
                        'nota' => $nota,
                        'kd_obat' => $obat->kd_obat,
                        'jumlah' => $item['jumlah'],
                        'harga_beli' => $item['harga_beli'],
                    ]);

                    // 1. Increment medicine stock
                    $obat->increment('stok', $item['jumlah']);

                    // 2. Update medicine cost price to match the latest restock price
                    $obat->update([
                        'harga_beli' => $item['harga_beli'],
                    ]);
                }
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }

        return redirect()->route('apoteker.pembelian.index')
            ->with('success', 'Transaksi pembelian (restok) berhasil ditambahkan.');
    }

    public function show(Pembelian $pembelian): View
    {
        $pembelian->load(['suplier', 'user', 'details.obat']);

        return view('apoteker.pembelian.show', compact('pembelian'));
    }
}
