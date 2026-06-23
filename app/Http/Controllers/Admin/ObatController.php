<?php

namespace App\Http\Controllers\Admin;

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
            $query->where('nm_obat', 'like', "%{$search}%")
                  ->orWhere('kd_obat', 'like', "%{$search}%");
        }

        if ($status = $request->status) {
            $query->where('status', $status);
        }

        $obats = $query->orderBy('nm_obat')->paginate(15)->withQueryString();

        return view('admin.obat.index', compact('obats'));
    }

    public function create(): View
    {
        $kategoris = KategoriObat::orderBy('nm_kategori')->get();
        $supliers  = Suplier::orderBy('nm_suplier')->get();
        $kd_obat   = Obat::generateKdObat();

        return view('admin.obat.create', compact('kategoris', 'supliers', 'kd_obat'));
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

        return redirect()->route('admin.obat.index')
            ->with('success', 'Obat berhasil ditambahkan.');
    }

    public function show(Obat $obat): View
    {
        $obat->load([
            'kategori',
            'suplier',
            'penjualanDetails.penjualan.pelanggan',
            'pembelianDetails.pembelian.suplier'
        ]);

        return view('admin.obat.show', compact('obat'));
    }

    public function edit(Obat $obat): View
    {
        $kategoris = KategoriObat::orderBy('nm_kategori')->get();
        $supliers  = Suplier::orderBy('nm_suplier')->get();

        return view('admin.obat.edit', compact('obat', 'kategoris', 'supliers'));
    }

    public function update(Request $request, Obat $obat): RedirectResponse
    {
        $validated = $request->validate([
            'nm_obat'        => 'required|string|max:100',
            'id_kategori'    => 'required|exists:kategori_obats,id',
            'satuan'         => 'required|string|max:20',
            'harga_beli'     => 'required|numeric|min:0',
            'harga_jual'     => 'required|numeric|min:0',
            'stok'           => 'required|integer|min:0',
            'tgl_kadaluarsa' => 'nullable|date',
            'status'         => 'required|in:aktif,nonaktif',
            'kd_suplier'     => 'nullable|exists:supliers,kd_suplier',
            'foto_obat'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto_obat')) {
            // Delete old photo if exists
            if ($obat->foto_obat && \Illuminate\Support\Facades\Storage::disk('public')->exists($obat->foto_obat)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($obat->foto_obat);
            }
            $path = $request->file('foto_obat')->store('obats', 'public');
            $validated['foto_obat'] = $path;
        }

        $obat->update($validated);

        return redirect()->route('admin.obat.index')
            ->with('success', 'Data obat berhasil diperbarui.');
    }

    public function destroy(Obat $obat): RedirectResponse
    {
        // Cek jika ada relasi detail penjualan
        if ($obat->penjualanDetails()->exists()) {
            return back()->with('error', 'Obat tidak dapat dihapus karena memiliki riwayat penjualan.');
        }

        $obat->delete();

        return redirect()->route('admin.obat.index')
            ->with('success', 'Obat berhasil dihapus.');
    }

    public function kadaluarsa(): View
    {
        // Obat yang AKAN kadaluarsa dalam 60 hari
        $akanKadaluarsa = Obat::with(['kategori', 'suplier'])
            ->akanKadaluarsa(60)
            ->orderBy('tgl_kadaluarsa')
            ->get();

        // Obat yang SUDAH kadaluarsa
        $sudahKadaluarsa = Obat::with(['kategori', 'suplier'])
            ->sudahLewatKadaluarsa()
            ->orderBy('tgl_kadaluarsa')
            ->get();

        return view('admin.obat.kadaluarsa', compact('akanKadaluarsa', 'sudahKadaluarsa'));
    }

    /** Hanya ubah kolom status (AJAX-friendly) */
    public function updateStatus(Request $request, Obat $obat): RedirectResponse
    {
        $request->validate(['status' => 'required|in:aktif,nonaktif']);
        $obat->update(['status' => $request->status]);

        return back()->with('success', "Status obat {$obat->nm_obat} diubah ke {$request->status}.");
    }
}
