<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Suplier;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SuplierController extends Controller
{
    public function index(Request $request): View
    {
        $query = Suplier::withCount('obats');

        if ($search = $request->search) {
            $query->where('nm_suplier', 'like', "%{$search}%")
                  ->orWhere('kd_suplier', 'like', "%{$search}%")
                  ->orWhere('kota', 'like', "%{$search}%");
        }

        $supliers = $query->orderBy('nm_suplier')->paginate(15)->withQueryString();

        return view('admin.suplier.index', compact('supliers'));
    }

    public function create(): View
    {
        $kd_suplier = Suplier::generateKdSuplier();
        return view('admin.suplier.create', compact('kd_suplier'));
    }

    public function store(Request $request): RedirectResponse
    {
        // Enforce auto-generated code
        $kd_suplier = Suplier::generateKdSuplier();
        $request->merge(['kd_suplier' => $kd_suplier]);

        $validated = $request->validate([
            'kd_suplier' => 'required|string|max:20|unique:supliers,kd_suplier',
            'nm_suplier' => 'required|string|max:100',
            'alamat'     => 'nullable|string|max:255',
            'kota'       => 'nullable|string|max:100',
            'telpon'     => 'nullable|string|max:20',
        ]);

        Suplier::create($validated);

        return redirect()->route('admin.suplier.index')
            ->with('success', 'Suplier berhasil ditambahkan.');
    }

    public function show(Suplier $suplier): View
    {
        $suplier->load(['obats', 'pembelians']);

        return view('admin.suplier.show', compact('suplier'));
    }

    public function edit(Suplier $suplier): View
    {
        return view('admin.suplier.edit', compact('suplier'));
    }

    public function update(Request $request, Suplier $suplier): RedirectResponse
    {
        $validated = $request->validate([
            'nm_suplier' => 'required|string|max:100',
            'alamat'     => 'nullable|string|max:255',
            'kota'       => 'nullable|string|max:100',
            'telpon'     => 'nullable|string|max:20',
        ]);

        $suplier->update($validated);

        return redirect()->route('admin.suplier.index')
            ->with('success', 'Data suplier berhasil diperbarui.');
    }

    public function destroy(Suplier $suplier): RedirectResponse
    {
        if ($suplier->obats()->exists()) {
            return back()->with('error', 'Suplier tidak dapat dihapus karena masih memiliki obat terdaftar.');
        }

        $suplier->delete();

        return redirect()->route('admin.suplier.index')
            ->with('success', 'Suplier berhasil dihapus.');
    }
}
