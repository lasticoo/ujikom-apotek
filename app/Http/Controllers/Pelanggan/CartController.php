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

class CartController extends Controller
{
    public function index(): View
    {
        $cart = session()->get('cart', []);
        return view('pelanggan.cart.index', compact('cart'));
    }

    public function add(Request $request, Obat $obat): RedirectResponse
    {
        $qty = (int) $request->input('jumlah', 1);

        if ($qty <= 0) {
            return back()->with('error', 'Jumlah obat harus minimal 1.');
        }

        if ($obat->stok < $qty) {
            return back()->with('error', "Stok obat {$obat->nm_obat} tidak mencukupi (Tersedia: {$obat->stok}).");
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$obat->kd_obat])) {
            $newQty = $cart[$obat->kd_obat]['jumlah'] + $qty;
            if ($obat->stok < $newQty) {
                return back()->with('error', "Total di keranjang ({$newQty}) melebihi stok yang tersedia.");
            }
            $cart[$obat->kd_obat]['jumlah'] = $newQty;
        } else {
            $cart[$obat->kd_obat] = [
                'kd_obat'    => $obat->kd_obat,
                'nm_obat'    => $obat->nm_obat,
                'harga_jual' => (float) $obat->harga_jual,
                'jumlah'     => $qty,
                'satuan'     => $obat->satuan,
                'foto_obat'  => $obat->foto_obat,
                'stok'       => $obat->stok
            ];
        }

        session()->put('cart', $cart);

        return back()
            ->with('success', "{$obat->nm_obat} berhasil ditambahkan ke keranjang.")
            ->with('show_cart_success', [
                'nm_obat' => $obat->nm_obat,
                'cart_url' => route('pelanggan.cart.index')
            ]);
    }

    public function update(Request $request, $kd_obat): RedirectResponse
    {
        $qty = (int) $request->input('jumlah');
        if ($qty <= 0) {
            return $this->remove($kd_obat);
        }

        $obat = Obat::findOrFail($kd_obat);
        if ($obat->stok < $qty) {
            return back()->with('error', "Stok obat {$obat->nm_obat} tidak mencukupi (Tersedia: {$obat->stok}).");
        }

        $cart = session()->get('cart', []);
        if (isset($cart[$kd_obat])) {
            $cart[$kd_obat]['jumlah'] = $qty;
            session()->put('cart', $cart);
        }

        return redirect()->route('pelanggan.cart.index')
            ->with('success', 'Keranjang berhasil diperbarui.');
    }

    public function remove($kd_obat): RedirectResponse
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$kd_obat])) {
            unset($cart[$kd_obat]);
            session()->put('cart', $cart);
        }

        return redirect()->route('pelanggan.cart.index')
            ->with('success', 'Obat dihapus dari keranjang.');
    }

    public function checkout(): View|RedirectResponse
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('pelanggan.obat.index')
                ->with('error', 'Keranjang belanja Anda kosong.');
        }

        return view('pelanggan.cart.checkout', compact('cart'));
    }

    public function processCheckout(Request $request): RedirectResponse
    {
        $request->validate([
            'alamat_kirim' => 'required|string|max:500',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('pelanggan.obat.index')
                ->with('error', 'Keranjang belanja Anda kosong.');
        }

        $akun      = Auth::guard('pelanggan')->user();
        $pelanggan = $akun->pelanggan;

        // Generate nomor nota
        $lastNota = Penjualan::orderByRaw('CAST(SUBSTRING(nota, 5) AS UNSIGNED) DESC')->first();
        $nextNum  = $lastNota ? ((int) substr($lastNota->nota, 4)) + 1 : 1;
        $nota     = 'PJL-' . str_pad($nextNum, 5, '0', STR_PAD_LEFT);

        try {
            DB::transaction(function () use ($request, $cart, $pelanggan, $nota) {
                $penjualan = Penjualan::create([
                    'nota'              => $nota,
                    'tgl_nota'          => now()->toDateString(),
                    'kd_pelanggan'      => $pelanggan->kd_pelanggan,
                    'id_user'           => null,
                    'diskon'            => 0,
                    'alamat_kirim'      => $request->alamat_kirim,
                    'status_pembayaran' => 'belum_bayar',
                ]);

                foreach ($cart as $item) {
                    $obat = Obat::findOrFail($item['kd_obat']);

                    // Verify stock
                    if ($obat->stok < $item['jumlah']) {
                        throw new \Exception("Stok obat {$obat->nm_obat} tidak mencukupi untuk pemesanan.");
                    }

                    PenjualanDetail::create([
                        'nota'    => $nota,
                        'kd_obat' => $obat->kd_obat,
                        'jumlah'  => $item['jumlah'],
                    ]);

                    // Decrement stock immediately (reserve stock)
                    $obat->decrement('stok', $item['jumlah']);
                }
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }

        // Clear cart
        session()->forget('cart');

        return redirect()->route('pelanggan.penjualan.show', $nota)
            ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran dan unggah bukti transfer di bawah ini.');
    }
}
