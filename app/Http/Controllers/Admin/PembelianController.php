<?php

namespace App\Http\Controllers\Admin;

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
use Barryvdh\DomPDF\Facade\Pdf;

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

        return view('admin.pembelian.index', compact('pembelians'));
    }

    public function create(): View
    {
        $supliers = Suplier::orderBy('nm_suplier')->get();
        $obats = Obat::orderBy('nm_obat')->get();

        // Auto generate nota PO (restock code)
        $nota = Pembelian::generateNota();

        return view('admin.pembelian.create', compact('supliers', 'obats', 'nota'));
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

        return redirect()->route('admin.pembelian.index')
            ->with('success', 'Transaksi pembelian (restok) berhasil ditambahkan.');
    }

    public function report(Request $request): View
    {
        $search = $request->search;
        $from = $request->from;
        $to = $request->to;

        $query = Pembelian::with(['suplier', 'user', 'details.obat']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nota', 'like', "%{$search}%")
                  ->orWhereHas('suplier', fn ($sub) => $sub->where('nm_suplier', 'like', "%{$search}%"));
            });
        }

        if ($from) $query->where('tgl_nota', '>=', $from);
        if ($to)   $query->where('tgl_nota', '<=', $to);

        $pembelians = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        // Calculate statistics for report
        $statsQuery = Pembelian::with(['suplier', 'details.obat']);
        if ($search) {
            $statsQuery->where(function($q) use ($search) {
                $q->where('nota', 'like', "%{$search}%")
                  ->orWhereHas('suplier', fn ($sub) => $sub->where('nm_suplier', 'like', "%{$search}%"));
            });
        }
        if ($from) $statsQuery->where('tgl_nota', '>=', $from);
        if ($to)   $statsQuery->where('tgl_nota', '<=', $to);

        $allFilteredPembelians = $statsQuery->get();

        $totalPO = $allFilteredPembelians->count();
        $grandTotalExpenditure = $allFilteredPembelians->sum('total');
        $totalItemsCount = 0;
        $supplierExpenditures = [];
        $supplierNames = [];

        foreach ($allFilteredPembelians as $p) {
            $totalItemsCount += $p->details->sum('jumlah');
            if ($p->suplier) {
                $supKey = $p->kd_suplier;
                $supplierExpenditures[$supKey] = ($supplierExpenditures[$supKey] ?? 0) + $p->total;
                $supplierNames[$supKey] = $p->suplier->nm_suplier;
            }
        }

        arsort($supplierExpenditures);
        $topSupplierKey = key($supplierExpenditures);
        $topSupplierName = $topSupplierKey ? ($supplierNames[$topSupplierKey] . ' (Rp ' . number_format($supplierExpenditures[$topSupplierKey], 0, ',', '.') . ')') : '-';

        // Monthly breakdown
        $monthlyBreakdown = [];
        foreach ($allFilteredPembelians as $p) {
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

        return view('admin.pembelian.report', compact(
            'pembelians',
            'grandTotalExpenditure',
            'totalPO',
            'totalItemsCount',
            'topSupplierName',
            'monthlyBreakdown'
        ));
    }

    public function reportExportExcel(Request $request)
    {
        $search = $request->search;
        $from = $request->from;
        $to = $request->to;

        $query = Pembelian::with(['suplier', 'user', 'details.obat']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nota', 'like', "%{$search}%")
                  ->orWhereHas('suplier', fn ($sub) => $sub->where('nm_suplier', 'like', "%{$search}%"));
            });
        }

        if ($from) $query->where('tgl_nota', '>=', $from);
        if ($to)   $query->where('tgl_nota', '<=', $to);

        $pembelians = $query->orderByDesc('created_at')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="laporan_restok_' . now()->format('Ymd_His') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($pembelians) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM

            fputcsv($file, [
                'No. Nota PO',
                'Tanggal Restok',
                'Supplier',
                'Diinput Oleh',
                'Total Items',
                'Obat (Qty @ Harga Beli)',
                'Subtotal',
                'Diskon',
                'Total Pengeluaran'
            ]);

            foreach ($pembelians as $p) {
                $itemsText = [];
                $subtotal = 0;
                foreach ($p->details as $d) {
                    $hargaBeli = (float)($d->harga_beli ?? 0);
                    $subtotal += $d->jumlah * $hargaBeli;
                    $itemsText[] = ($d->obat->nm_obat ?? 'Obat Hilang') . ' (' . $d->jumlah . 'x @ Rp ' . number_format($hargaBeli, 0, ',', '') . ')';
                }

                fputcsv($file, [
                    $p->nota,
                    $p->tgl_nota->format('Y-m-d'),
                    $p->suplier ? $p->suplier->nm_suplier : '-',
                    $p->user ? $p->user->nama : '-',
                    $p->details->sum('jumlah'),
                    implode('; ', $itemsText),
                    $subtotal,
                    (float)$p->diskon,
                    (float)$p->total
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function reportDownloadPdf(Request $request)
    {
        $search = $request->search;
        $from = $request->from;
        $to = $request->to;

        $query = Pembelian::with(['suplier', 'user', 'details.obat']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nota', 'like', "%{$search}%")
                  ->orWhereHas('suplier', fn ($sub) => $sub->where('nm_suplier', 'like', "%{$search}%"));
            });
        }

        if ($from) $query->where('tgl_nota', '>=', $from);
        if ($to)   $query->where('tgl_nota', '<=', $to);

        $pembelians = $query->orderByDesc('created_at')->get();

        // Calculate statistics
        $totalPO = $pembelians->count();
        $grandTotalExpenditure = $pembelians->sum('total');
        $totalItemsCount = 0;
        $supplierExpenditures = [];
        $supplierNames = [];

        foreach ($pembelians as $p) {
            $totalItemsCount += $p->details->sum('jumlah');
            if ($p->suplier) {
                $supKey = $p->kd_suplier;
                $supplierExpenditures[$supKey] = ($supplierExpenditures[$supKey] ?? 0) + $p->total;
                $supplierNames[$supKey] = $p->suplier->nm_suplier;
            }
        }

        arsort($supplierExpenditures);
        $topSupplierKey = key($supplierExpenditures);
        $topSupplierName = $topSupplierKey ? ($supplierNames[$topSupplierKey] . ' (Rp ' . number_format($supplierExpenditures[$topSupplierKey], 0, ',', '.') . ')') : '-';

        $pdf = Pdf::loadView('admin.pembelian.report_pdf', compact(
            'pembelians',
            'grandTotalExpenditure',
            'totalPO',
            'totalItemsCount',
            'topSupplierName'
        ));

        return $pdf->download('laporan_restok_obat_' . now()->format('Ymd_His') . '.pdf');
    }

    public function show(Pembelian $pembelian): View
    {
        $pembelian->load(['suplier', 'user', 'details.obat']);

        return view('admin.pembelian.show', compact('pembelian'));
    }
}
