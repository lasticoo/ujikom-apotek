<?php

namespace App\Http\Controllers\Apoteker;

use App\Http\Controllers\Controller;
use App\Models\KategoriObat;
use App\Models\Obat;
use App\Models\Suplier;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ObatController extends Controller
{
    public function index(Request $request): View
    {
        $query = Obat::with(['kategori', 'suplier']);

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('nm_obat', 'like', "%{$search}%")
                  ->orWhere('kd_obat', 'like', "%{$search}%");
            });
        }

        $obats = $query->orderBy('nm_obat')->paginate(15)->withQueryString();

        return view('apoteker.obat.index', compact('obats'));
    }

    public function create(): View
    {
        $kategoris = KategoriObat::orderBy('nm_kategori')->get();
        $supliers  = Suplier::orderBy('nm_suplier')->get();
        $kd_obat   = Obat::generateKdObat();

        return view('apoteker.obat.create', compact('kategoris', 'supliers', 'kd_obat'));
    }

    public function store(Request $request): RedirectResponse
    {
        // Enforce auto-generated code
        $kd_obat = Obat::generateKdObat();
        $request->merge(['kd_obat' => $kd_obat]);

        $validated = $request->validate([
            'kd_obat'        => 'required|string|max:20|unique:obats,kd_obat',
            'nm_obat'        => 'required|string|max:100',
            'id_kategori'    => 'required|exists:kategori_obats,id',
            'satuan'         => 'required|string|max:20',
            'harga_jual'     => 'required|numeric|min:0',
            'tgl_kadaluarsa' => 'nullable|date',
            'status'         => 'required|in:aktif,nonaktif',
            'kd_suplier'     => 'nullable|exists:supliers,kd_suplier',
            'foto_obat'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['harga_beli'] = 0;
        $validated['stok'] = 0;

        if ($request->hasFile('foto_obat')) {
            $path = $request->file('foto_obat')->store('obats', 'public');
            $validated['foto_obat'] = $path;
        }

        Obat::create($validated);

        return redirect()->route('apoteker.obat.index')
            ->with('success', 'Obat berhasil ditambahkan.');
    }

    public function show(Obat $obat): View
    {
        $obat->load(['kategori', 'suplier', 'penjualanDetails.penjualan']);

        return view('apoteker.obat.show', compact('obat'));
    }

    public function destroy(Obat $obat): RedirectResponse
    {
        // Apoteker hanya boleh hapus obat yang tidak ada di riwayat penjualan
        if ($obat->penjualanDetails()->exists()) {
            return back()->with('error', 'Obat tidak dapat dihapus karena memiliki riwayat transaksi.');
        }

        $obat->delete();

        return redirect()->route('apoteker.obat.index')
            ->with('success', 'Obat berhasil dihapus.');
    }

    public function kadaluarsa(): View
    {
        $obatsKadaluarsa = Obat::with(['kategori', 'suplier'])
            ->sudahLewatKadaluarsa()
            ->orderBy('tgl_kadaluarsa')
            ->get();

        $obatsAkanKadaluarsa = Obat::with(['kategori', 'suplier'])
            ->akanKadaluarsa(30)
            ->orderBy('tgl_kadaluarsa')
            ->get();

        return view('apoteker.obat.kadaluarsa', compact('obatsKadaluarsa', 'obatsAkanKadaluarsa'));
    }

    /** Hapus semua obat yang sudah kadaluarsa. */
    public function destroyKadaluarsa(Obat $obat): RedirectResponse
    {
        if (! $obat->sudahKadaluarsa()) {
            return back()->with('error', 'Obat belum melewati tanggal kadaluarsa.');
        }

        $obat->delete();

        return back()->with('success', "Obat {$obat->nm_obat} berhasil dihapus.");
    }
}
