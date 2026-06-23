<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ApotekerController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::where('role', 'apoteker');

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $apotekers = $query->orderBy('nama')->paginate(15)->withQueryString();

        return view('admin.apoteker.index', compact('apotekers'));
    }

    public function create(): View
    {
        return view('admin.apoteker.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama'     => 'required|string|max:100',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'telpon'   => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        User::create([
            'nama'     => $validated['nama'],
            'email'    => $validated['email'],
            'telpon'   => $validated['telpon'] ?? null,
            'password' => Hash::make($validated['password']),
            'role'     => 'apoteker',
        ]);

        return redirect()->route('admin.apoteker.index')
            ->with('success', 'Apoteker baru berhasil didaftarkan.');
    }

    public function edit(User $apoteker): View
    {
        // Pastikan yang di-edit adalah apoteker, bukan admin
        abort_if($apoteker->role !== 'apoteker', 403);

        return view('admin.apoteker.edit', compact('apoteker'));
    }

    public function update(Request $request, User $apoteker): RedirectResponse
    {
        abort_if($apoteker->role !== 'apoteker', 403);

        $validated = $request->validate([
            'nama'     => 'required|string|max:100',
            'email'    => "required|string|email|max:255|unique:users,email,{$apoteker->id}",
            'telpon'   => 'nullable|string|max:20',
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $data = [
            'nama'   => $validated['nama'],
            'email'  => $validated['email'],
            'telpon' => $validated['telpon'] ?? null,
        ];

        if (! empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $apoteker->update($data);

        return redirect()->route('admin.apoteker.index')
            ->with('success', 'Data apoteker berhasil diperbarui.');
    }

    public function destroy(User $apoteker): RedirectResponse
    {
        abort_if($apoteker->role !== 'apoteker', 403);

        $apoteker->delete();

        return redirect()->route('admin.apoteker.index')
            ->with('success', 'Data apoteker berhasil dihapus.');
    }
}
