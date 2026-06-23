<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AkunPelanggan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PelangganController extends Controller
{
    public function index(Request $request): View
    {
        $query = Pelanggan::with('akun');

        if ($search = $request->search) {
            $query->where('nm_pelanggan', 'like', "%{$search}%")
                  ->orWhere('kd_pelanggan', 'like', "%{$search}%")
                  ->orWhere('kota', 'like', "%{$search}%");
        }

        $pelanggans = $query->orderBy('nm_pelanggan')->paginate(15)->withQueryString();

        return view('admin.pelanggan.index', compact('pelanggans'));
    }

    public function show(Pelanggan $pelanggan): View
    {
        $pelanggan->load(['akun', 'penjualans.details.obat']);

        return view('admin.pelanggan.show', compact('pelanggan'));
    }
}
