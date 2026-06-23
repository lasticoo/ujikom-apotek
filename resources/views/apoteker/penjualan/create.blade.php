<x-apoteker-layout>
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('apoteker.penjualan.index') }}" class="btn btn-sm btn-outline-secondary me-3">
            <i class="ti ti-arrow-left"></i>
        </a>
        <div>
            <div class="page-pretitle">Penjualan</div>
            <h2 class="page-title mb-0">Input Penjualan Baru</h2>
        </div>
    </div>

    <form method="POST" action="{{ route('apoteker.penjualan.store') }}" id="form-penjualan">
        @csrf
        <div class="row g-3">
            <!-- Header Transaksi -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header"><h6 class="card-title mb-0">Info Transaksi</h6></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="tgl_nota" class="form-control @error('tgl_nota') is-invalid @enderror"
                                   value="{{ old('tgl_nota', now()->format('Y-m-d')) }}" required>
                            @error('tgl_nota') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pelanggan</label>
                            <select name="kd_pelanggan" class="form-select @error('kd_pelanggan') is-invalid @enderror">
                                <option value="">-- Pelanggan Umum / Walk-in --</option>
                                @foreach($pelanggans as $pel)
                                    <option value="{{ $pel->kd_pelanggan }}" {{ old('kd_pelanggan') == $pel->kd_pelanggan ? 'selected' : '' }}>
                                        {{ $pel->nm_pelanggan }} ({{ $pel->kd_pelanggan }})
                                    </option>
                                @endforeach
                            </select>
                            @error('kd_pelanggan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Diskon (Rp)</label>
                            <input type="number" name="diskon" class="form-control" value="{{ old('diskon', 0) }}" min="0" step="100">
                        </div>

                        <hr>
                        <div class="d-flex justify-content-between fw-500 mb-1">
                            <span>Subtotal:</span>
                            <span id="txt-subtotal">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between text-danger mb-2">
                            <span>Diskon:</span>
                            <span id="txt-diskon">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between fw-700 fs-5 text-success">
                            <span>Total:</span>
                            <span id="txt-total">Rp 0</span>
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="ti ti-device-floppy me-1"></i> Simpan Transaksi
                        </button>
                    </div>
                </div>
            </div>

            <!-- Detail Obat -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h6 class="card-title mb-0">Daftar Obat yang Dijual</h6>
                        <button type="button" class="btn btn-sm btn-outline-success" id="btn-add-row">
                            <i class="ti ti-plus me-1"></i> Tambah Baris
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <table class="table mb-0" id="tbl-items">
                            <thead>
                                <tr><th>Obat</th><th>Harga Jual</th><th>Stok</th><th>Qty</th><th>Subtotal</th><th></th></tr>
                            </thead>
                            <tbody id="tbody-items">
                                <!-- Baris dinamis -->
                            </tbody>
                        </table>
                    </div>
                </div>

                @error('items')
                    <div class="alert alert-danger mt-2"><i class="ti ti-alert-circle me-1"></i> {{ $message }}</div>
                @enderror
            </div>
        </div>
    </form>

    @push('scripts')
    <script>
        const obats = {!! json_encode($obats->map(fn($o) => [
            'kd_obat'   => $o->kd_obat,
            'nm_obat'   => $o->nm_obat,
            'harga_jual'=> (float) $o->harga_jual,
            'stok'      => $o->stok,
        ])) !!};

        let rowIndex = 0;

        function formatRp(n) {
            return 'Rp ' + parseInt(n).toLocaleString('id-ID');
        }

        function addRow() {
            const i = rowIndex++;
            const options = obats.map(o =>
                `<option value="${o.kd_obat}" data-harga="${o.harga_jual}" data-stok="${o.stok}">${o.nm_obat} (stok: ${o.stok})</option>`
            ).join('');

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <select name="items[${i}][kd_obat]" class="form-select form-select-sm sel-obat" required>
                        <option value="">-- Pilih --</option>
                        ${options}
                    </select>
                </td>
                <td class="txt-harga align-middle text-muted small">-</td>
                <td class="txt-stok align-middle text-muted small">-</td>
                <td style="width:100px;">
                    <input type="number" name="items[${i}][jumlah]" class="form-control form-control-sm inp-qty"
                           value="1" min="1" required>
                </td>
                <td class="txt-sub align-middle fw-500 text-success">Rp 0</td>
                <td>
                    <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 btn-remove">
                        <i class="ti ti-x"></i>
                    </button>
                </td>`;

            document.getElementById('tbody-items').appendChild(tr);

            tr.querySelector('.sel-obat').addEventListener('change', recalcRow.bind(null, tr));
            tr.querySelector('.inp-qty').addEventListener('input', recalcRow.bind(null, tr));
            tr.querySelector('.btn-remove').addEventListener('click', () => { tr.remove(); recalcAll(); });
        }

        function recalcRow(tr) {
            const sel = tr.querySelector('.sel-obat');
            const opt = sel.options[sel.selectedIndex];
            const harga = parseFloat(opt.dataset.harga || 0);
            const stok  = parseInt(opt.dataset.stok || 0);
            const qty   = parseInt(tr.querySelector('.inp-qty').value) || 0;

            tr.querySelector('.txt-harga').textContent = harga ? formatRp(harga) : '-';
            tr.querySelector('.txt-stok').textContent  = opt.value ? stok : '-';
            tr.querySelector('.txt-sub').textContent   = formatRp(harga * qty);
            recalcAll();
        }

        function recalcAll() {
            let subtotal = 0;
            document.querySelectorAll('#tbody-items tr').forEach(tr => {
                const sel   = tr.querySelector('.sel-obat');
                const opt   = sel?.options[sel.selectedIndex];
                const harga = parseFloat(opt?.dataset.harga || 0);
                const qty   = parseInt(tr.querySelector('.inp-qty')?.value) || 0;
                subtotal += harga * qty;
            });

            const diskon = parseFloat(document.querySelector('[name="diskon"]')?.value || 0);
            const total  = Math.max(0, subtotal - diskon);

            document.getElementById('txt-subtotal').textContent = formatRp(subtotal);
            document.getElementById('txt-diskon').textContent   = formatRp(diskon);
            document.getElementById('txt-total').textContent    = formatRp(total);
        }

        document.getElementById('btn-add-row').addEventListener('click', addRow);
        document.querySelector('[name="diskon"]')?.addEventListener('input', recalcAll);

        // Tambah 1 baris default
        addRow();
    </script>
    @endpush
</x-apoteker-layout>
