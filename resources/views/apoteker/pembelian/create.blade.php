<x-apoteker-layout>
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('apoteker.pembelian.index') }}" class="btn btn-sm btn-outline-secondary me-3">
            <i class="ti ti-arrow-left"></i>
        </a>
        <div>
            <div class="page-pretitle">Pembelian</div>
            <h2 class="page-title mb-0">Input Restok Baru (Nota: <code>{{ $nota }}</code>)</h2>
        </div>
    </div>

    <form method="POST" action="{{ route('apoteker.pembelian.store') }}" id="form-pembelian">
        @csrf
        <div class="row g-3">
            <!-- Header Transaksi -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header"><h6 class="card-title mb-0">Info Restok / Pembelian</h6></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">No. Nota PO</label>
                            <input type="text" class="form-control" value="{{ $nota }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Nota <span class="text-danger">*</span></label>
                            <input type="date" name="tgl_nota" class="form-control @error('tgl_nota') is-invalid @enderror"
                                   value="{{ old('tgl_nota', now()->format('Y-m-d')) }}" required>
                            @error('tgl_nota') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Supplier <span class="text-danger">*</span></label>
                            <select name="kd_suplier" class="form-select @error('kd_suplier') is-invalid @enderror" required>
                                <option value="">-- Pilih Supplier --</option>
                                @foreach($supliers as $sup)
                                    <option value="{{ $sup->kd_suplier }}" {{ old('kd_suplier') == $sup->kd_suplier ? 'selected' : '' }}>
                                        {{ $sup->nm_suplier }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kd_suplier') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                            <i class="ti ti-device-floppy me-1"></i> Simpan Transaksi PO
                        </button>
                    </div>
                </div>
            </div>

            <!-- Detail Obat -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h6 class="card-title mb-0">Daftar Obat yang Direstok</h6>
                        <button type="button" class="btn btn-sm btn-outline-success" id="btn-add-row">
                            <i class="ti ti-plus me-1"></i> Tambah Baris
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <table class="table mb-0" id="tbl-items">
                            <thead>
                                <tr>
                                    <th>Obat</th>
                                    <th>Harga Beli (Rp)</th>
                                    <th>Stok Saat Ini</th>
                                    <th>Qty Masuk</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
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
            'kd_obat'    => $o->kd_obat,
            'nm_obat'    => $o->nm_obat,
            'harga_beli' => (float) $o->harga_beli,
            'stok'       => $o->stok,
            'kd_suplier' => $o->kd_suplier,
        ])) !!};

        let rowIndex = 0;
        let previousSupplier = document.querySelector('[name="kd_suplier"]').value;

        function formatRp(n) {
            return 'Rp ' + parseInt(n).toLocaleString('id-ID');
        }

        function addRow() {
            const selectedSupplier = document.querySelector('[name="kd_suplier"]').value;
            
            // Filter obat based on selectedSupplier
            const filteredObats = obats.filter(o => o.kd_suplier === selectedSupplier);

            const placeholder = document.getElementById('placeholder-row');
            if (placeholder) {
                placeholder.closest('tr').remove();
            }

            const i = rowIndex++;
            let options = '';
            if (!selectedSupplier) {
                options = `<option value="">-- Pilih Supplier Dahulu --</option>`;
            } else if (filteredObats.length === 0) {
                options = `<option value="">-- Tidak ada obat dari supplier ini --</option>`;
            } else {
                options = filteredObats.map(o =>
                    `<option value="${o.kd_obat}" data-harga="${o.harga_beli}" data-stok="${o.stok}">${o.nm_obat}</option>`
                ).join('');
            }

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <select name="items[${i}][kd_obat]" class="form-select form-select-sm sel-obat" required ${!selectedSupplier ? 'disabled' : ''}>
                        <option value="">-- Pilih --</option>
                        ${options}
                    </select>
                </td>
                <td style="width: 150px;">
                    <input type="number" name="items[${i}][harga_beli]" class="form-control form-control-sm inp-harga"
                           value="0" min="0" required ${!selectedSupplier ? 'disabled' : ''}>
                </td>
                <td class="txt-stok align-middle text-muted small">-</td>
                <td style="width: 100px;">
                    <input type="number" name="items[${i}][jumlah]" class="form-control form-control-sm inp-qty"
                           value="1" min="1" required ${!selectedSupplier ? 'disabled' : ''}>
                </td>
                <td class="txt-sub align-middle fw-500 text-success">Rp 0</td>
                <td>
                    <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 btn-remove">
                        <i class="ti ti-x"></i>
                    </button>
                </td>`;

            document.getElementById('tbody-items').appendChild(tr);

            tr.querySelector('.sel-obat').addEventListener('change', (e) => {
                const sel = e.target;
                const opt = sel.options[sel.selectedIndex];
                const harga = parseFloat(opt.dataset.harga || 0);
                const stok  = parseInt(opt.dataset.stok || 0);

                const inpHarga = tr.querySelector('.inp-harga');
                inpHarga.value = harga;

                const txtStok = tr.querySelector('.txt-stok');
                txtStok.textContent = opt.value ? stok : '-';

                recalcRow(tr);
            });

            tr.querySelector('.inp-harga').addEventListener('input', recalcRow.bind(null, tr));
            tr.querySelector('.inp-qty').addEventListener('input', recalcRow.bind(null, tr));
            tr.querySelector('.btn-remove').addEventListener('click', () => { tr.remove(); recalcAll(); });

            recalcRow(tr);
        }

        function recalcRow(tr) {
            const harga = parseFloat(tr.querySelector('.inp-harga').value) || 0;
            const qty   = parseInt(tr.querySelector('.inp-qty').value) || 0;

            tr.querySelector('.txt-sub').textContent = formatRp(harga * qty);
            recalcAll();
        }

        function recalcAll() {
            let subtotal = 0;
            document.querySelectorAll('#tbody-items tr').forEach(tr => {
                const harga = parseFloat(tr.querySelector('.inp-harga')?.value || 0);
                const qty   = parseInt(tr.querySelector('.inp-qty')?.value || 0);
                if (tr.querySelector('.sel-obat')?.value) {
                    subtotal += harga * qty;
                }
            });

            const diskon = parseFloat(document.querySelector('[name="diskon"]')?.value || 0);
            const total  = Math.max(0, subtotal - diskon);

            document.getElementById('txt-subtotal').textContent = formatRp(subtotal);
            document.getElementById('txt-diskon').textContent   = formatRp(diskon);
            document.getElementById('txt-total').textContent    = formatRp(total);
        }

        // Supplier Selection change triggers resetting list
        document.querySelector('[name="kd_suplier"]').addEventListener('change', (e) => {
            const currentSupplier = e.target.value;
            const rowCount = document.querySelectorAll('#tbody-items tr:not(:has(#placeholder-row))').length;

            if (rowCount > 0 && previousSupplier !== "") {
                if (confirm("Mengubah supplier akan mereset daftar obat yang sudah dipilih. Lanjutkan?")) {
                    document.getElementById('tbody-items').innerHTML = "";
                    previousSupplier = currentSupplier;
                    if (currentSupplier) {
                        addRow();
                    } else {
                        showPlaceholder();
                    }
                } else {
                    e.target.value = previousSupplier;
                }
            } else {
                previousSupplier = currentSupplier;
                document.getElementById('tbody-items').innerHTML = "";
                if (currentSupplier) {
                    addRow();
                } else {
                    showPlaceholder();
                }
            }
        });

        function showPlaceholder() {
            const tbody = document.getElementById('tbody-items');
            tbody.innerHTML = `<tr><td colspan="6" class="text-center text-muted py-3" id="placeholder-row">Pilih supplier terlebih dahulu untuk mulai menambahkan obat.</td></tr>`;
            recalcAll();
        }

        document.getElementById('btn-add-row').addEventListener('click', () => {
            const selectedSupplier = document.querySelector('[name="kd_suplier"]').value;
            if (!selectedSupplier) {
                alert("Silakan pilih Supplier terlebih dahulu.");
                return;
            }
            addRow();
        });

        document.querySelector('[name="diskon"]')?.addEventListener('input', recalcAll);

        // Initial setup
        const initialSupplier = document.querySelector('[name="kd_suplier"]').value;
        if (initialSupplier) {
            addRow();
        } else {
            showPlaceholder();
        }
    </script>
    @endpush
</x-apoteker-layout>
