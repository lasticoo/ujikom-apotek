<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ObatController extends Controller
{
    public function index(Request $request): View
    {
        $query = Obat::with(['kategori'])->aktif();

        if ($search = $request->search) {
            $query->where('nm_obat', 'like', "%{$search}%");
        }

        if ($kategori = $request->kategori) {
            $query->where('id_kategori', $kategori);
        }

        $obats     = $query->orderBy('nm_obat')->paginate(12)->withQueryString();
        $kategoris = \App\Models\KategoriObat::orderBy('nm_kategori')->get();

        return view('pelanggan.obat.index', compact('obats', 'kategoris'));
    }

    public function show(Obat $obat): View
    {
        // Hanya tampilkan obat aktif ke pelanggan
        abort_if($obat->status !== 'aktif', 404);

        $obat->load(['kategori', 'suplier']);

        return view('pelanggan.obat.show', compact('obat'));
    }
}
