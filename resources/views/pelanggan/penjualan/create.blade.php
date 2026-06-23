<x-pelanggan-layout>
    <div class="container py-4">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('pelanggan.obat.index') }}" class="btn btn-sm btn-outline-secondary me-3">
                <i class="ti ti-arrow-left"></i>
            </a>
            <h4 class="fw-700 mb-0">Buat Pesanan Baru</h4>
        </div>

        <form method="POST" action="{{ route('pelanggan.penjualan.store') }}" id="form-pesan">
            @csrf
            <div class="row g-3">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h6 class="card-title mb-0">Pilih Obat</h6>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add">
                                <i class="ti ti-plus me-1"></i> Tambah Obat
                            </button>
                        </div>
                        <div class="card-body p-0">
                            <table class="table mb-0">
                                <thead>
                                    <tr><th>Obat</th><th>Harga</th><th>Stok</th><th>Qty</th><th>Subtotal</th><th></th></tr>
                                </thead>
                                <tbody id="tbody"></tbody>
                            </table>
                        </div>
                    </div>
                    @error('items')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-4">
                    <div class="card" style="position:sticky;top:80px;">
                        <div class="card-header"><h6 class="card-title mb-0">Ringkasan</h6></div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between fw-500 mb-2">
                                <span>Subtotal</span>
                                <span id="txt-sub">Rp 0</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fw-800 fs-5 text-primary">
                                <span>Total</span>
                                <span id="txt-total">Rp 0</span>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mt-3">
                                <i class="ti ti-check me-1"></i> Konfirmasi Pesanan
                            </button>
                            <a href="{{ route('pelanggan.obat.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                                Kembali ke Katalog
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        const obats = {!! json_encode($obats->map(fn($o) => [
            'kd_obat' => $o->kd_obat, 'nm_obat' => $o->nm_obat,
            'harga_jual' => (float)$o->harga_jual, 'stok' => $o->stok, 'satuan' => $o->satuan,
        ])) !!};
        let idx = 0;
        const fmt = n => 'Rp ' + parseInt(n).toLocaleString('id-ID');

        function addRow() {
            const i = idx++;
            const opts = obats.map(o => `<option value="${o.kd_obat}" data-harga="${o.harga_jual}" data-stok="${o.stok}" data-satuan="${o.satuan}">${o.nm_obat}</option>`).join('');
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td><select name="items[${i}][kd_obat]" class="form-select form-select-sm sel" required>
                    <option value="">-- Pilih --</option>${opts}
                </select></td>
                <td class="tx-h small text-muted align-middle">-</td>
                <td class="tx-s small text-muted align-middle">-</td>
                <td style="width:90px;"><input type="number" name="items[${i}][jumlah]" class="form-control form-control-sm inp" value="1" min="1" required></td>
                <td class="tx-sub fw-600 text-primary align-middle">Rp 0</td>
                <td><button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 btn-rm"><i class="ti ti-x"></i></button></td>`;
            document.getElementById('tbody').appendChild(tr);
            tr.querySelector('.sel').addEventListener('change', () => calcRow(tr));
            tr.querySelector('.inp').addEventListener('input', () => calcRow(tr));
            tr.querySelector('.btn-rm').addEventListener('click', () => { tr.remove(); calcAll(); });
        }

        function calcRow(tr) {
            const sel = tr.querySelector('.sel');
            const opt = sel.options[sel.selectedIndex];
            const h = parseFloat(opt.dataset.harga||0), s = parseInt(opt.dataset.stok||0);
            const q = parseInt(tr.querySelector('.inp').value)||0;
            tr.querySelector('.tx-h').textContent = opt.value ? fmt(h) : '-';
            tr.querySelector('.tx-s').textContent = opt.value ? `${s} ${opt.dataset.satuan||''}` : '-';
            tr.querySelector('.tx-sub').textContent = fmt(h * q);
            calcAll();
        }

        function calcAll() {
            let sub = 0;
            document.querySelectorAll('#tbody tr').forEach(tr => {
                const sel = tr.querySelector('.sel');
                const opt = sel?.options[sel.selectedIndex];
                sub += parseFloat(opt?.dataset.harga||0) * (parseInt(tr.querySelector('.inp')?.value)||0);
            });
            document.getElementById('txt-sub').textContent = fmt(sub);
            document.getElementById('txt-total').textContent = fmt(sub);
        }

        document.getElementById('btn-add').addEventListener('click', addRow);
        addRow(); // 1 baris awal
    </script>
    @endpush
</x-pelanggan-layout>
