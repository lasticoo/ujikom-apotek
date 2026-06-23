<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class PenjualanController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->search;
        $from = $request->from;
        $to = $request->to;

        $query = Penjualan::with(['pelanggan', 'user', 'details.obat']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nota', 'like', "%{$search}%")
                  ->orWhereHas('pelanggan', fn ($sub) => $sub->where('nm_pelanggan', 'like', "%{$search}%"));
            });
        }

        if ($from) {
            $query->where('tgl_nota', '>=', $from);
        }

        if ($to) {
            $query->where('tgl_nota', '<=', $to);
        }

        $penjualans = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        // Stats calculation using filtered query
        $statsQuery = Penjualan::with(['details.obat']);
        if ($search) {
            $statsQuery->where(function($q) use ($search) {
                $q->where('nota', 'like', "%{$search}%")
                  ->orWhereHas('pelanggan', fn ($sub) => $sub->where('nm_pelanggan', 'like', "%{$search}%"));
            });
        }
        if ($from) $statsQuery->where('tgl_nota', '>=', $from);
        if ($to)   $statsQuery->where('tgl_nota', '<=', $to);

        $allFilteredPenjualans = $statsQuery->get();

        $totalTransactions = $allFilteredPenjualans->count();
        $grandTotal = $allFilteredPenjualans->sum('total');
        $totalDiscount = $allFilteredPenjualans->sum('diskon');

        // Top selling drug
        $drugQuantities = [];
        $drugNames = [];
        foreach ($allFilteredPenjualans as $p) {
            foreach ($p->details as $d) {
                if ($d->obat) {
                    $drugQuantities[$d->kd_obat] = ($drugQuantities[$d->kd_obat] ?? 0) + $d->jumlah;
                    $drugNames[$d->kd_obat] = $d->obat->nm_obat;
                }
            }
        }
        arsort($drugQuantities);
        $topDrugKey = key($drugQuantities);
        $topDrugName = $topDrugKey ? ($drugNames[$topDrugKey] . ' (' . $drugQuantities[$topDrugKey] . ' pcs)') : '-';

        // Monthly breakdown
        $monthlyBreakdown = [];
        foreach ($allFilteredPenjualans as $p) {
            $monthKey = $p->tgl_nota->format('Y-m');
            $monthName = $p->tgl_nota->format('F Y');
            if (!isset($monthlyBreakdown[$monthKey])) {
                $monthlyBreakdown[$monthKey] = [
                    'label' => $monthName,
                    'count' => 0,
                    'total' => 0,
                ];
            }
            $monthlyBreakdown[$monthKey]['count']++;
            $monthlyBreakdown[$monthKey]['total'] += $p->total;
        }
        krsort($monthlyBreakdown);

        return view('admin.penjualan.index', compact(
            'penjualans',
            'grandTotal',
            'totalTransactions',
            'totalDiscount',
            'topDrugName',
            'monthlyBreakdown'
        ));
    }

    public function exportExcel(Request $request)
    {
        $search = $request->search;
        $from = $request->from;
        $to = $request->to;

        $query = Penjualan::with(['pelanggan', 'user', 'details.obat']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nota', 'like', "%{$search}%")
                  ->orWhereHas('pelanggan', fn ($sub) => $sub->where('nm_pelanggan', 'like', "%{$search}%"));
            });
        }

        if ($from) {
            $query->where('tgl_nota', '>=', $from);
        }

        if ($to) {
            $query->where('tgl_nota', '<=', $to);
        }

        $penjualans = $query->orderByDesc('tgl_nota')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="laporan_penjualan_' . now()->format('Ymd_His') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($penjualans) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM

            fputcsv($file, [
                'No. Nota',
                'Tanggal',
                'Pelanggan',
                'Tipe Transaksi',
                'Alamat Kirim',
                'Input Oleh',
                'Item Obat (Qty @ Harga)',
                'Subtotal',
                'Diskon',
                'Total Bersih',
                'Status Pembayaran'
            ]);

            foreach ($penjualans as $p) {
                $itemsText = [];
                $subtotal = 0;
                foreach ($p->details as $d) {
                    $hargaJual = (float)($d->obat->harga_jual ?? 0);
                    $subtotal += $d->jumlah * $hargaJual;
                    $itemsText[] = ($d->obat->nm_obat ?? 'Obat Hilang') . ' (' . $d->jumlah . 'x @ Rp ' . number_format($hargaJual, 0, ',', '') . ')';
                }

                fputcsv($file, [
                    $p->nota,
                    $p->tgl_nota->format('Y-m-d'),
                    $p->pelanggan ? $p->pelanggan->nm_pelanggan : 'Umum (Offline)',
                    $p->alamat_kirim ? 'Online' : 'Offline',
                    $p->alamat_kirim ?? '-',
                    $p->user ? $p->user->nama : '-',
                    implode('; ', $itemsText),
                    $subtotal,
                    (float)$p->diskon,
                    (float)$p->total,
                    strtoupper($p->status_pembayaran)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function downloadPdf(Request $request)
    {
        $search = $request->search;
        $from = $request->from;
        $to = $request->to;

        $query = Penjualan::with(['pelanggan', 'user', 'details.obat']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nota', 'like', "%{$search}%")
                  ->orWhereHas('pelanggan', fn ($sub) => $sub->where('nm_pelanggan', 'like', "%{$search}%"));
            });
        }

        if ($from) $query->where('tgl_nota', '>=', $from);
        if ($to)   $query->where('tgl_nota', '<=', $to);

        $penjualans = $query->orderByDesc('created_at')->get();

        // Calculations for header
        $totalTransactions = $penjualans->count();
        $grandTotal = $penjualans->sum('total');
        $totalDiscount = $penjualans->sum('diskon');

        // Top drug
        $drugQuantities = [];
        $drugNames = [];
        foreach ($penjualans as $p) {
            foreach ($p->details as $d) {
                if ($d->obat) {
                    $drugQuantities[$d->kd_obat] = ($drugQuantities[$d->kd_obat] ?? 0) + $d->jumlah;
                    $drugNames[$d->kd_obat] = $d->obat->nm_obat;
                }
            }
        }
        arsort($drugQuantities);
        $topDrugKey = key($drugQuantities);
        $topDrugName = $topDrugKey ? ($drugNames[$topDrugKey] . ' (' . $drugQuantities[$topDrugKey] . ' pcs)') : '-';

        $pdf = Pdf::loadView('admin.penjualan.pdf', compact(
            'penjualans',
            'grandTotal',
            'totalTransactions',
            'totalDiscount',
            'topDrugName'
        ));

        return $pdf->download('laporan_penjualan_' . now()->format('Ymd_His') . '.pdf');
    }

    public function show(Penjualan $penjualan): View
    {
        $penjualan->load(['pelanggan', 'user', 'details.obat']);

        return view('admin.penjualan.show', compact('penjualan'));
    }
}
