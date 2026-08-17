<?= $this->extend('layout/main') ?>

<?= $this->section('styles') ?>
<style>
/* ── Variables & Reset ── */
:root {
    --dm-primary: #4f46e5;
    --dm-primary-hover: #4338ca;
    --dm-bg: #f8fafc;
    --dm-border: #e2e8f0;
    --dm-text-main: #1e293b;
    --dm-text-muted: #64748b;
    --dm-radius: 12px;
    --dm-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
}
.dm-page {
    background: var(--dm-bg);
    min-height: calc(100vh - 60px);
    padding: 24px;
    font-family: 'Inter', system-ui, sans-serif;
    color: var(--dm-text-main);
}
.dm-topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 2px dashed var(--dm-border);
}
.dm-topbar__title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--dm-text-main);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}
.dm-topbar__title i {
    color: var(--dm-primary);
    background: #eef2ff;
    padding: 10px;
    border-radius: 10px;
    font-size: 1.1rem;
}
.dm-topbar__sub {
    color: var(--dm-text-muted);
    font-size: 0.9rem;
    margin: 4px 0 0 0;
}
.dm-card {
    background: #fff;
    border-radius: var(--dm-radius);
    box-shadow: var(--dm-shadow);
    border: 1px solid #f1f5f9;
    margin-bottom: 24px;
    overflow: hidden;
}
.dm-card__header {
    background: #f8fafc;
    padding: 16px 20px;
    border-bottom: 1px solid var(--dm-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.dm-card__title {
    font-size: 1rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
    color: #334155;
}
.dm-card__title i { color: var(--dm-primary); }
.dm-card__body { padding: 20px; }

/* ── Form Grid ── */
.dm-grid-3 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 16px;
}
.dm-grid-12 {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 16px;
    margin-bottom: 16px;
}
.dm-label {
    display: block;
    font-size: 11.5px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 4px;
}
.dm-input,
.dm-select {
    width: 100%;
    height: 36px;
    padding: 0 10px;
    font-size: 13px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    background: #fff;
    color: #111827;
    transition: border-color .15s;
    outline: none;
}
.dm-input:focus,
.dm-select:focus  { border-color: var(--dm-primary); box-shadow: 0 0 0 2px rgba(99,102,241,.12); }
.dm-input.readonly { background: #f8fafc; color: #6b7280; }
.dm-textarea {
    width: 100%;
    min-height: 36px;
    height: 36px;
    padding: 8px 10px;
    font-size: 13px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    background: #fff;
    color: #111827;
    resize: none;
    outline: none;
}

/* ── Table ── */
.dm-table-wrap { overflow-x: auto; }
#tabelItem {
    width: 100%;
    table-layout: fixed;
    min-width: 900px;
    border-collapse: collapse;
    font-size: 12px;
}
#tabelItem thead tr { background: #f8fafc; }
#tabelItem thead th {
    font-size: 11px;
    font-weight: 700;
    color: #6b7280;
    padding: 9px 8px;
    border: 1px solid #e5e7eb;
}
#tabelItem tbody td {
    padding: 8px 7px;
    border: 1px solid #e5e7eb;
    vertical-align: top;
}
#tabelItem .tc-no      { width: 42px;  text-align: center; }
#tabelItem .tc-nama    { width: 210px; }
#tabelItem .tc-kat     { width: 130px; }
#tabelItem .tc-sat     { width: 125px; }
#tabelItem .tc-jml     { width: 92px;  }
#tabelItem .tc-berat   { width: 92px;  }
#tabelItem .tc-sberat  { width: 100px; }
#tabelItem .tc-aksi    { width: 52px;  text-align: center; }

#tabelItem .dm-input,
#tabelItem .dm-select {
    height: 34px;
    font-size: 12px;
    padding: 0 8px;
    border-radius: 6px;
}

.btn-tambah-item {
    height: 36px;
    padding: 0 16px;
    font-size: 12.5px;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.btn-hapus-row {
    width: 28px;
    height: 28px;
    padding: 0;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}
.dm-footer {
    display: flex;
    gap: 8px;
    margin-bottom: 8px;
    align-items: center;
}
.btn-simpan {
    height: 38px;
    padding: 0 22px;
    font-size: 13px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border-radius: 999px;
}
.badge-barang-exists {
    display: none;
    font-size: 10px;
    font-weight: 500;
    margin-top: 3px;
    align-items: center;
}
</style>
<div class="dm-page">
    <div class="dm-topbar">
        <div>
            <h1 class="dm-topbar__title"><i class="fa-solid fa-hand-holding-heart"></i><?= esc($title) ?></h1>
            <p class="dm-topbar__sub">Catat seluruh barang yang diterima ke Gudang Wilayah</p>
        </div>
        <a href="<?= site_url('wilayah/masuk') ?>" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    <?php if (session()->getFlashdata('errors')) : ?>
        <div class="alert alert-danger rounded-3 mb-3" style="font-size:12px;">
            <i class="fa-solid fa-triangle-exclamation me-2"></i><strong>Terjadi Kesalahan:</strong>
            <ul class="mb-0 mt-1">
                <?php foreach (session()->getFlashdata('errors') as $err) : ?>
                    <li><?= esc($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger rounded-3 mb-3" style="font-size:12px;">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <form action="<?= site_url('wilayah/masuk/store') ?>" method="POST" id="formDonasiMasuk">
        <?= csrf_field() ?>
        
        <!-- Header -->
        <div class="dm-card">
            <div class="dm-card__header">
                <h5 class="dm-card__title"><i class="fa-solid fa-file-lines"></i>Header Transaksi</h5>
            </div>
            <div class="dm-card__body">
                <div class="dm-grid-3">
                    <div>
                        <label class="dm-label">Kode Transaksi Barang Masuk <span class="text-danger">*</span></label>
                        <input type="text" class="dm-input bg-light" name="nomor_dokumen" value="<?= old('nomor_dokumen', $autoDoc) ?>" readonly>
                    </div>
                    <div>
                        <label class="dm-label">Tanggal Masuk <span class="text-danger">*</span></label>
                        <input type="date" class="dm-input" name="tanggal" value="<?= old('tanggal', date('Y-m-d')) ?>" required>
                    </div>
                    <div>
                        <label class="dm-label">Gudang Wilayah <span class="text-danger">*</span></label>
                        <?php if (!empty($isAdmin)): ?>
                            <select name="id_gudang" id="id_gudang" class="dm-select select2" required>
                                <?php foreach ($allGudang as $g): ?>
                                    <option value="<?= $g['id'] ?>" <?= ($g['id'] == $userGudangId) ? 'selected' : '' ?>><?= esc($g['nama']) ?> - <?= esc($g['kota']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php else: ?>
                            <select name="id_gudang" id="id_gudang" class="dm-select readonly" style="pointer-events: none; background-color: #f8fafc;">
                                <?php foreach ($gudang as $g): ?>
                                    <option value="<?= $g['id'] ?>" selected><?= esc($g['nama']) ?> - <?= esc($g['kota']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="dm-grid-12">
                    <div>
                        <label class="dm-label">Donatur <span class="text-danger">*</span></label>
                        <select name="id_donatur" class="dm-select select2" required>
                            <option value="">Pilih Donatur...</option>
                            <?php foreach ($donatur as $d): ?>
                                <option value="<?= $d['id'] ?>" <?= old('id_donatur') == $d['id'] ? 'selected' : '' ?>><?= esc($d['nama_donatur']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="dm-label">Keterangan</label>
                        <textarea class="dm-textarea" name="keterangan" placeholder="Opsional"><?= old('keterangan') ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Barang -->
        <div class="dm-card">
            <div class="dm-card__header">
                <h5 class="dm-card__title"><i class="fa-solid fa-boxes-stacked"></i>Detail Barang</h5>
                <button type="button" class="btn btn-success text-white btn-tambah-item" id="btnTambahItem">
                    <i class="fa-solid fa-plus"></i> Tambah Item
                </button>
            </div>
            <div class="dm-card__body">
                <div class="dm-table-wrap">
                    <table id="tabelItem">
                        <thead>
                            <tr>
                                <th class="tc-no">NO</th>
                                <th class="tc-nama">NAMA BARANG <span class="text-danger">*</span></th>
                                <th class="tc-kat">KATEGORI <span class="text-danger">*</span></th>
                                <th class="tc-sat">SATUAN <span class="text-danger">*</span></th>
                                <th class="tc-jml">JUMLAH <span class="text-danger">*</span></th>
                                <th class="tc-berat">BERAT/SAT.</th>
                                <th class="tc-sberat">SAT. BERAT</th>
                                <th class="tc-harga">HARGA SATUAN (Rp)</th>
                                <th class="tc-aksi">AKSI</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyItem"></tbody>
                    </table>
                </div>
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mt-3 p-3 bg-light border rounded-3" id="summaryBox">
                    <div class="d-flex align-items-center gap-4 text-secondary small fw-semibold flex-wrap">
                        <div><i class="fa-solid fa-boxes-packing me-1 text-primary"></i> Total Item: <span id="sumTotalItem" class="fw-bold text-dark">0 Barang</span></div>
                        <div><i class="fa-solid fa-cubes me-1 text-success"></i> Total Barang: <span id="sumTotalQty" class="fw-bold text-dark">0 Unit</span></div>
                        <div><i class="fa-solid fa-weight-hanging me-1 text-info"></i> Total Berat Masuk: <span id="sumTotalBerat" class="fw-bold text-dark">0 Kg</span></div>
                        <div><i class="fa-solid fa-money-bill-wave me-1 text-warning"></i> Total Nilai Barang: <span id="sumTotalNilai" class="fw-bold text-success">Rp 0</span></div>
                    </div>
                </div>
                <datalist id="daftarNamaBarang">
                    <?php foreach ($barangList ?? [] as $b) : ?>
                        <option value="<?= esc($b['nama_barang']) ?>"></option>
                    <?php endforeach; ?>
                </datalist>
                <div id="errorItem" class="text-danger mt-2" style="display:none;">
                    <i class="fa-solid fa-circle-exclamation me-1"></i> Minimal harus ada 1 barang.
                </div>
            </div>
        </div>

        <div class="dm-footer">
            <button type="submit" class="btn btn-primary btn-simpan" id="btnSimpan">
                <i class="fa-solid fa-save"></i> Simpan Transaksi
            </button>
            <a href="<?= site_url('wilayah/masuk') ?>" class="btn btn-outline-secondary btn-batal">Batal</a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const kategoriList      = <?= json_encode($kategori ?? []) ?>;
    const oldItems          = <?= json_encode(array_values($oldItems ?? [])) ?>;
    const barangMasterList  = <?= json_encode($barangList ?? []) ?>;
    const satuanOptions     = ['Box','Kotak','Dus','Pcs','Karung','Botol','Pack','Tray','Kaleng','Pouch','Sak','Sachet','Renceng','Kantong','Kardus'];
    const satuanBeratOptions= ['Kg','Gram'];

    const barangMasterMap = {};
    barangMasterList.forEach(b => {
        barangMasterMap[String(b.nama_barang ?? '').trim().toLowerCase()] = b;
    });

    let rowCount = 0;

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>'"]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[c]));
    }

    function formatRupiahVal(numStr) {
        if (!numStr || isNaN(numStr) || parseFloat(numStr) === 0) return '';
        let val = Math.round(parseFloat(numStr)).toString();
        return val.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function parseRupiahVal(str) {
        if (!str) return 0;
        let clean = str.replace(/[^\d]/g, '');
        return clean ? parseFloat(clean) : 0;
    }

    function selectOptions(values, selectedValue) {
        const sel = String(selectedValue ?? '');
        let o = '<option value="">-- Pilih --</option>';
        values.forEach(v => {
            const sv = escapeHtml(v);
            o += `<option value="${sv}" ${sel===v?'selected':''}>${sv}</option>`;
        });
        return o;
    }

    function kategoriOptionsSelected(selectedValue) {
        const sel = String(selectedValue ?? '');
        let o = '<option value="">-- Pilih --</option>';
        kategoriList.forEach(k => {
            const sn = escapeHtml(k.nama_kategori);
            o += `<option value="${k.id}" ${sel===String(k.id)?'selected':''}>${sn}</option>`;
        });
        return o;
    }

    function syncBarangMaster(row, applyValues = false) {
        const inputNama = row.querySelector('.input-nama-barang');
        const badge     = row.querySelector('.badge-barang-exists');
        if (!inputNama || !badge) return;
        
        const key   = inputNama.value.trim().toLowerCase();
        const match = key ? barangMasterMap[key] : null;
        badge.style.display = match ? 'inline-flex' : 'none';
        
        const selectKategori    = row.querySelector('[name$="[id_kategori]"]');
        const selectSatuan      = row.querySelector('.input-satuan');

        if (match) {
            if (applyValues) {
                if (selectKategori) selectKategori.value = match.id_kategori ?? '';
                if (selectSatuan)   selectSatuan.value   = match.satuan ?? '';
            }
            if (selectKategori) { selectKategori.style.pointerEvents = 'none'; selectKategori.classList.add('readonly'); }
            if (selectSatuan)   { selectSatuan.style.pointerEvents = 'none'; selectSatuan.classList.add('readonly'); }
        } else {
            if (selectKategori) { selectKategori.style.pointerEvents = 'auto'; selectKategori.classList.remove('readonly'); }
            if (selectSatuan)   { selectSatuan.style.pointerEvents = 'auto'; selectSatuan.classList.remove('readonly'); }
        }

        calculateTotalSummary();
    }

    function calculateTotalSummary() {
        const rows = document.querySelectorAll('#tbodyItem tr');
        let totalItems = rows.length;
        let totalQty = 0;
        let totalBeratKg = 0;
        let totalNilai = 0;

        rows.forEach(tr => {
            const inputJml   = tr.querySelector('[name$="[jumlah]"]');
            const inputBerat = tr.querySelector('.input-berat');
            const selectSbrt = tr.querySelector('[name$="[satuan_berat]"]');
            const inputHarga = tr.querySelector('.input-harga');

            const jml   = inputJml ? parseFloat(inputJml.value || 0) : 0;
            const berat = inputBerat ? parseFloat(inputBerat.value || 0) : 0;
            const sbrt  = selectSbrt ? selectSbrt.value : 'Kg';
            const harga = inputHarga ? parseFloat(inputHarga.value || 0) : 0;

            if (!isNaN(jml) && jml > 0) {
                totalQty += jml;
                if (!isNaN(berat) && berat > 0) {
                    const rowKg = (sbrt === 'Gram') ? (jml * berat / 1000) : (jml * berat);
                    totalBeratKg += rowKg;
                }
                if (!isNaN(harga) && harga > 0) {
                    totalNilai += (jml * harga);
                }
            }
        });

        document.getElementById('sumTotalItem').textContent = totalItems + ' Barang';
        document.getElementById('sumTotalQty').textContent  = totalQty.toLocaleString('id-ID') + ' Unit';
        document.getElementById('sumTotalBerat').textContent = totalBeratKg.toLocaleString('id-ID', { maximumFractionDigits: 2 }) + ' Kg';
        document.getElementById('sumTotalNilai').textContent = 'Rp ' + Math.round(totalNilai).toLocaleString('id-ID');
    }

    function reindexNames() {
        document.querySelectorAll('#tbodyItem tr').forEach((tr, i) => {
            const idx = i + 1;
            tr.querySelectorAll('[name]').forEach(el => {
                el.name = el.name.replace(/items\[\d+\]/, `items[${idx}]`);
            });
            const noCell = tr.querySelector('.row-number');
            if (noCell) noCell.textContent = idx;
        });
        calculateTotalSummary();
    }

    function addRow(data = {}) {
        rowCount++;
        const idRow = 'row_' + Date.now() + Math.random().toString(36).substr(2, 5);
        const hargaVal = parseFloat(data.harga_satuan || 0);
        const html = `
            <tr id="${idRow}">
                <td class="tc-no row-number">${rowCount}</td>
                <td class="tc-nama">
                    <input type="text" class="dm-input input-nama-barang" name="items[${rowCount}][nama_barang]" 
                           value="${escapeHtml(data.nama_barang)}" list="daftarNamaBarang" placeholder="Ketik/Pilih Barang..." required>
                    <div class="badge-barang-exists text-success"><i class="fa-solid fa-check-circle me-1"></i> Data Master Ditemukan</div>
                </td>
                <td class="tc-kat">
                    <select class="dm-select" name="items[${rowCount}][id_kategori]" required>
                        ${kategoriOptionsSelected(data.id_kategori)}
                    </select>
                </td>
                <td class="tc-sat">
                    <select class="dm-select input-satuan" name="items[${rowCount}][satuan]" required>
                        ${selectOptions(satuanOptions, data.satuan)}
                    </select>
                </td>
                <td class="tc-jml">
                    <input type="number" class="dm-input input-jumlah" name="items[${rowCount}][jumlah]" value="${escapeHtml(data.jumlah)}" min="0.01" step="0.01" required>
                </td>
                <td class="tc-berat">
                    <input type="number" class="dm-input input-berat" name="items[${rowCount}][berat_per_satuan]" value="${escapeHtml(data.berat_per_satuan)}" min="0" step="0.01" placeholder="Berat">
                </td>
                <td class="tc-sberat">
                    <select class="dm-select select-satuan-berat" name="items[${rowCount}][satuan_berat]">
                        ${selectOptions(satuanBeratOptions, data.satuan_berat || 'Kg')}
                    </select>
                </td>
                <td class="tc-harga">
                    <input type="text" class="dm-input input-harga-display" value="${formatRupiahVal(hargaVal)}" placeholder="0">
                    <input type="hidden" class="input-harga" name="items[${rowCount}][harga_satuan]" value="${hargaVal}">
                </td>
                <td class="tc-aksi">
                    <button type="button" class="btn btn-danger btn-hapus-row"><i class="fa-solid fa-trash-can"></i></button>
                </td>
            </tr>
        `;
        document.getElementById('tbodyItem').insertAdjacentHTML('beforeend', html);
        const tr = document.getElementById(idRow);
        
        tr.querySelector('.input-nama-barang').addEventListener('input', function() {
            syncBarangMaster(tr, true);
        });
        tr.querySelector('.input-jumlah').addEventListener('input', calculateTotalSummary);
        tr.querySelector('.input-berat').addEventListener('input', calculateTotalSummary);
        tr.querySelector('.select-satuan-berat').addEventListener('change', calculateTotalSummary);

        tr.querySelector('.input-harga-display').addEventListener('input', function() {
            let raw = parseRupiahVal(this.value);
            this.value = raw > 0 ? raw.toLocaleString('id-ID') : '';
            tr.querySelector('.input-harga').value = raw;
            calculateTotalSummary();
        });

        tr.querySelector('.btn-hapus-row').addEventListener('click', function() {
            tr.remove();
            reindexNames();
        });
        
        if (data.nama_barang) {
            syncBarangMaster(tr, false);
        } else {
            calculateTotalSummary();
        }
    }

    document.getElementById('btnTambahItem').addEventListener('click', () => addRow());

    document.getElementById('formDonasiMasuk').addEventListener('submit', function(e) {
        if (document.querySelectorAll('#tbodyItem tr').length === 0) {
            e.preventDefault();
            const err = document.getElementById('errorItem');
            err.style.display = 'block';
            setTimeout(() => err.style.display = 'none', 3000);
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        if (oldItems && oldItems.length > 0) {
            oldItems.forEach(item => addRow(item));
        } else {
            addRow();
        }
        if ($.fn.select2) {
            $('.select2').select2({ theme: 'bootstrap-5', width: '100%' });
        }
    });
</script>
<?= $this->endSection() ?>
