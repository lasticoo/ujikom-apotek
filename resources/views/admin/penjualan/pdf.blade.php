<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan - Apotek Digital</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 10px; color: #333; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0 0 5px 0; color: #206bc4; }
        .header p { margin: 0; font-size: 11px; color: #666; }
        .stats-table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .stats-table td { padding: 8px; border: 1px solid #ddd; background-color: #f9f9f9; }
        .stats-table .label { font-weight: bold; color: #666; width: 25%; }
        .stats-table .value { font-weight: bold; font-size: 11px; color: #333; }
        .main-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .main-table th { background-color: #f5f5f5; border: 1px solid #ddd; padding: 6px; text-align: left; font-weight: bold; }
        .main-table td { border: 1px solid #ddd; padding: 6px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { margin-top: 30px; text-align: right; font-size: 9px; color: #777; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Apotek Digital</h2>
        <p>Laporan Transaksi Penjualan Obat</p>
        <p style="font-size: 9px; margin-top: 5px;">Dicetak tanggal: {{ now()->format('d F Y H:i') }}</p>
    </div>

    <table class="stats-table">
        <tr>
            <td class="label">Total Pendapatan</td>
            <td class="value" style="color: #198754;">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
            <td class="label">Jumlah Transaksi</td>
            <td class="value">{{ $totalTransactions }} Transaksi</td>
        </tr>
        <tr>
            <td class="label">Total Potongan (Diskon)</td>
            <td class="value" style="color: #dc3545;">Rp {{ number_format($totalDiscount, 0, ',', '.') }}</td>
            <td class="label">Obat Terlaris</td>
            <td class="value">{{ $topDrugName }}</td>
        </tr>
    </table>

    <h3>Rincian Transaksi</h3>
    <table class="main-table">
        <thead>
            <tr>
                <th>No. Nota</th>
                <th>Tgl. Nota</th>
                <th>Pelanggan</th>
                <th>Tipe</th>
                <th>Oleh</th>
                <th>Items</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualans as $p)
                <tr>
                    <td><code>{{ $p->nota }}</code></td>
                    <td>{{ $p->tgl_nota->format('d/m/Y') }}</td>
                    <td>{{ $p->pelanggan->nm_pelanggan ?? 'Umum (Walk-in)' }}</td>
                    <td>{{ $p->alamat_kirim ? 'Online' : 'Offline' }}</td>
                    <td>{{ $p->user->nama ?? '-' }}</td>
                    <td>{{ $p->details->count() }} item</td>
                    <td class="text-right" style="font-weight: bold;">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Laporan Penjualan Otomatis - Apotek Digital
    </div>
</body>
</html>
