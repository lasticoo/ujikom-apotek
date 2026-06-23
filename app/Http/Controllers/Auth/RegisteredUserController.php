<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AkunPelanggan;
use App\Models\Pelanggan;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Registrasi PELANGGAN BARU.
     * Insert ke 2 tabel sekaligus dalam 1 transaction:
     * - pelanggans      (data profil)
     * - akun_pelanggans (kredensial login)
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nm_pelanggan' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'kota' => ['nullable', 'string', 'max:100'],
            'telpon' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:akun_pelanggans,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $akun = DB::transaction(function () use ($request) {
            $kdPelanggan = $this->generateKdPelanggan();

            $pelanggan = Pelanggan::create([
                'kd_pelanggan' => $kdPelanggan,
                'nm_pelanggan' => $request->nm_pelanggan,
                'alamat' => $request->alamat,
                'kota' => $request->kota,
                'telpon' => $request->telpon,
            ]);

            return AkunPelanggan::create([
                'kd_pelanggan' => $pelanggan->kd_pelanggan,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
        });

        event(new Registered($akun));

        Auth::guard('pelanggan')->login($akun);

        return redirect(route('pelanggan.dashboard'));
    }

    /** Generate kode pelanggan otomatis, format: PLG-XXX (increment dari data terakhir). */
    protected function generateKdPelanggan(): string
    {
        $last = Pelanggan::orderByRaw('CAST(SUBSTRING(kd_pelanggan, 5) AS UNSIGNED) DESC')->first();

        $nextNumber = $last
            ? ((int) substr($last->kd_pelanggan, 4)) + 1
            : 1;

        return 'PLG-' . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
