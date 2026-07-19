<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php
    $isEdit = isset($barangMasuk);
    $actionUrl = $isEdit ? site_url('transaksi/barang-masuk/update/' . $barangMasuk['id']) : site_url('transaksi/barang-masuk/store');

    $oldItems = old('items');
    if (!$oldItems && $isEdit && isset($batches)) {
        $oldItems = [];
        foreach ($batches as $b) {
            $oldItems[] = [
                'id'                  => $b['id'],
                'nama_barang'         => $b['nama_barang'],
                'kategori'            => $b['kategori'],
                'jumlah_ctn'          => $b['jumlah_ctn'],
                'jumlah'              => ((int)$b['bisa_dipecah'] === 1 && $b['jumlah_ctn'] !== null) ? $b['jumlah_ctn'] : $b['jumlah_awal'],
                'satuan'              => $b['satuan'],
                'berat_per_satuan'    => $b['berat_per_satuan'],
                'satuan_berat'        => $b['satuan_berat'],
                'tanggal_kedaluwarsa' => $b['tanggal_kedaluwarsa'],
                'bisa_dipecah'        => $b['bisa_dipecah'],
            ];
        }
    } elseif (!$oldItems) {
        $oldItems = [];
    }

    $valTanggalMasuk = old('tanggal_masuk', $isEdit ? $barangMasuk['tanggal_masuk'] : date('Y-m-d'));
    $valIdDonatur    = old('id_donatur',    $isEdit ? $barangMasuk['id_donatur']    : '');
    $valEta          = old('eta',           $isEdit ? $barangMasuk['eta']           : '');
    $valKeterangan   = old('keterangan',    $isEdit ? $barangMasuk['keterangan']    : '');
?>

<style>
/* ─────────────────────────────────────────────
   ROOT SCALE — semua rem diturunkan agar halaman
   proporsional di zoom 100% Full HD
   ───────────────────────────────────────────── */
.dm-page {
    font-size: 13px;
    line-height: 1.45;
    /* Beri batas lebar maksimum supaya konten tidak melebar
       keluar layar, lalu tengahkan. Padding kecil agar tidak
       terpotong di kanan pada viewport sempit. */
    max-width: 1600px;
    width: 100%;
    margin: 0 auto;
    padding: 14px 16px 24px;
    box-sizing: border-box;
}

/* ── Topbar ── */
.dm-topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    padding: 14px 20px;
    margin-bottom: 14px;
    box-shadow: 0 1px 2px rgba(0,0,0,.04);
}
.dm-topbar__title {
    font-size: 20px;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
    line-height: 1.2;
}
.dm-topbar__title i { font-size: 18px; margin-right: 8px; }
.dm-topbar__sub {
    font-size: 12px;
    color: #64748b;
    margin: 2px 0 0;
}
.dm-topbar .btn-back {
    height: 36px;
    padding: 0 16px;
    font-size: 13px;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
}

/* ── Cards ── */
.dm-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    margin-bottom: 14px;
    box-shadow: 0 1px 2px rgba(0,0,0,.04);
    overflow: hidden;
}
.dm-card__header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 18px;
    border-bottom: 1px solid #f1f5f9;
}
.dm-card__title {
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
}
.dm-card__title i { margin-right: 7px; }
.dm-card__body {
    padding: 16px 18px;
}

/* ── Header-form grid ── */
.dm-grid-3 {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 12px;
    margin-bottom: 12px;
}
.dm-grid-12 {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 12px;
}

/* ── Labels & inputs ── */
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
.dm-select:focus  { border-color: #6366f1; box-shadow: 0 0 0 2px rgba(99,102,241,.12); }
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
    transition: border-color .15s;
    font-family: inherit;
}
.dm-textarea:focus { border-color: #6366f1; box-shadow: 0 0 0 2px rgba(99,102,241,.12); }

/* Bootstrap is-invalid compat */
.dm-input.is-invalid,
.dm-select.is-invalid,
.dm-textarea.is-invalid { border-color: #ef4444 !important; }

/* ── Table ── */
.dm-table-wrap { overflow-x: auto; }
#tabelItem {
    width: 100%;
    table-layout: fixed;
    min-width: 1060px;   /* cukup untuk 10 kolom tanpa overflow di 1366px */
    border-collapse: collapse;
    font-size: 12px;
}
#tabelItem thead tr {
    background: #f8fafc;
}
#tabelItem thead th {
    font-size: 11px;
    font-weight: 700;
    color: #6b7280;
    letter-spacing: .04em;
    text-transform: uppercase;
    padding: 9px 8px;
    border: 1px solid #e5e7eb;
    vertical-align: middle;
    white-space: nowrap;
}
#tabelItem tbody td {
    padding: 8px 7px;
    border: 1px solid #e5e7eb;
    vertical-align: top;        /* rata atas supaya repack toggle tidak mendorong cell lain */
}
/* NO & AKSI tetap center vertikal */
#tabelItem tbody td.tc-no,
#tabelItem tbody td.tc-aksi { vertical-align: middle; text-align: center; }

/* column widths — total ≈ 1060px */
#tabelItem .tc-no      { width: 42px;  text-align: center; }
#tabelItem .tc-nama    { width: 210px; }
#tabelItem .tc-kat     { width: 130px; }
#tabelItem .tc-sat     { width: 125px; }
#tabelItem .tc-ctn     { width: 72px;  }
#tabelItem .tc-jml     { width: 82px;  }
#tabelItem .tc-berat   { width: 92px;  }
#tabelItem .tc-sberat  { width: 100px; }
#tabelItem .tc-exp     { width: 155px; }
#tabelItem .tc-aksi    { width: 52px;  text-align: center; }

/* inputs inside table */
#tabelItem .dm-input,
#tabelItem .dm-select {
    height: 34px;
    font-size: 12px;
    padding: 0 8px;
    border-radius: 6px;
}

/* ── Repack bits ── */
.repack-toggle-wrap  { margin-top: 5px; }
.chk-repack-label {
    font-size: 11px;
    color: #374151;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 4px;
    user-select: none;
}
.repack-preview {
    font-size: 10.5px;
    color: #16a34a;
    font-weight: 600;
    margin-top: 2px;
}
.badge-barang-exists {
    display: none;
    font-size: 10px;
    font-weight: 500;
    margin-top: 3px;
    align-items: center;
}

/* ── Action buttons ── */
.btn-tambah-item {
    height: 36px;
    padding: 0 16px;
    font-size: 12.5px;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    white-space: nowrap;
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
.btn-batal {
    height: 38px;
    padding: 0 18px;
    font-size: 13px;
    display: inline-flex;
    align-items: center;
    border-radius: 999px;
}

/* ── Error notice ── */
#errorItem { font-size: 12px; }

/* ── Responsive ── */
@media (max-width: 1199px) {
    .dm-grid-3  { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 767px) {
    .dm-page    { padding: 10px 12px; }
    .dm-grid-3,
    .dm-grid-12 { grid-template-columns: 1fr; }
}
</style>

<div class="dm-page">

<!-- ── Topbar ── -->
<div class="dm-topbar">
    <div>
        <h1 class="dm-topbar__title">
            <i class="fa-solid fa-hand-holding-heart"></i><?= esc($title) ?>
        </h1>
        <p class="dm-topbar__sub">Catat seluruh barang yang diterima dari donatur</p>
    </div>
    <a href="<?= site_url('transaksi/barang-masuk') ?>" class="btn btn-outline-secondary btn-back">
        <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>
</div>

<!-- ── Flash errors ── -->
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

<form action="<?= $actionUrl ?>" method="POST" id="formDonasiMasuk" novalidate>
    <?= csrf_field() ?>

    <!-- ── Header card ── -->
    <div class="dm-card">
        <div class="dm-card__header">
            <h5 class="dm-card__title"><i class="fa-solid fa-file-lines"></i>Header</h5>
        </div>
        <div class="dm-card__body">
            <!-- Row 1: Nomor | Tanggal | Donatur -->
            <div class="dm-grid-3">
                <div>
                    <label class="dm-label">Nomor Transaksi</label>
                    <input type="text" class="dm-input readonly" value="<?= esc($nomor_transaksi) ?>" readonly>
                </div>
                <div>
                    <label for="tanggal_masuk" class="dm-label">Tanggal Masuk <span class="text-danger">*</span></label>
                    <input type="date" class="dm-input" id="tanggal_masuk" name="tanggal_masuk"
                           value="<?= old('tanggal_masuk', date('Y-m-d')) ?>" required>
                </div>
                <div>
                    <label for="id_donatur" class="dm-label">Donatur <span class="text-danger">*</span></label>
                    <select class="dm-select" id="id_donatur" name="id_donatur" required>
                        <option value="">-- Pilih Donatur --</option>
                        <?php foreach ($donatur as $d) : ?>
                            <option value="<?= $d['id'] ?>" <?= $valIdDonatur == $d['id'] ? 'selected' : '' ?>>
                                <?= esc($d['nama_donatur']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <!-- Row 2: ETA | Keterangan (2/3 lebar) -->
            <div class="dm-grid-12">
                <div>
                    <label for="eta" class="dm-label">Estimasi Kedatangan</label>
                    <input type="date" class="dm-input" id="eta" name="eta" value="<?= old('eta') ?>">
                </div>
                <div>
                    <label for="keterangan" class="dm-label">Keterangan</label>
                    <textarea class="dm-textarea" id="keterangan" name="keterangan"
                              placeholder="Opsional"><?= esc($valKeterangan) ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Detail Barang card ── -->
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
                            <th class="tc-ctn">CTN<br><small style="font-size:9px;font-weight:400;color:#9ca3af;">(OPSIONAL)</small></th>
                            <th class="tc-jml">JUMLAH <span class="text-danger">*</span></th>
                            <th class="tc-berat">BERAT/SAT. <span class="text-danger">*</span></th>
                            <th class="tc-sberat">SAT. BERAT <span class="text-danger">*</span></th>
                            <th class="tc-exp">EXPIRED <span class="text-danger">*</span></th>
                            <th class="tc-aksi">AKSI</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyItem"></tbody>
                </table>
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

    <!-- ── Footer buttons ── -->
    <div class="dm-footer">
        <button type="submit" class="btn btn-primary btn-simpan" id="btnSimpan">
            <i class="fa-solid fa-save"></i> Simpan Transaksi
        </button>
        <a href="<?= site_url('transaksi/barang-masuk') ?>" class="btn btn-outline-secondary btn-batal">Batal</a>
    </div>
</form>

</div><!-- /.dm-page -->

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const kategoriList      = <?= json_encode($kategori    ?? [], JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) ?>;
    const oldItems          = <?= json_encode(array_values($oldItems), JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) ?>;
    const barangMasterList  = <?= json_encode($barangList  ?? [], JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) ?>;

    const barangMasterMap = {};
    barangMasterList.forEach(b => {
        barangMasterMap[String(b.nama_barang ?? '').trim().toLowerCase()] = b;
    });

    const satuanOptions      = ['Box','Dus','Pcs','Karung','Botol','Pack','Tray','Kaleng','Pouch','Sak'];
    const satuanBeratOptions = ['Gram','Kg'];
    const namaBarangPlaceholders = ['Beras Premium','Nasi Box Ayam','Brownies','Air Mineral'];
    let rowCount = 0;

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>'"]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[c]));
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
            const nama = String(k.nama_kategori ?? '');
            const sn   = escapeHtml(nama);
            o += `<option value="${sn}" ${sel===nama?'selected':''}>${sn}</option>`;
        });
        return o;
    }

    function syncRepackOption(row, forceDefault = false) {
        const selectSatuan = row.querySelector('.input-satuan');
        const hiddenPecah  = row.querySelector('.input-bisa-dipecah');
        const toggleWrap   = row.querySelector('.repack-toggle-wrap');
        const chkRepack    = row.querySelector('.chk-repack');
        if (!selectSatuan || !hiddenPecah || !toggleWrap || !chkRepack) return;
        const isKarung = selectSatuan.value === 'Karung';
        toggleWrap.style.display = isKarung ? 'block' : 'none';
        if (!isKarung) {
            chkRepack.checked = false;
            hiddenPecah.value = '0';
        } else if (forceDefault) {
            chkRepack.checked = true;
            hiddenPecah.value = '1';
        }
        updateRepackPreview(row);
    }

    function updateRepackPreview(row) {
        const chkRepack         = row.querySelector('.chk-repack');
        const hiddenPecah       = row.querySelector('.input-bisa-dipecah');
        const previewWrap       = row.querySelector('.repack-preview');
        const previewText       = row.querySelector('.repack-preview-text');
        const inputJumlah       = row.querySelector('.input-jumlah');
        const inputBerat        = row.querySelector('.input-berat');
        const selectSatuanBerat = row.querySelector('[name$="[satuan_berat]"]');
        if (!chkRepack || !previewWrap || !previewText) return;
        hiddenPecah.value = chkRepack.checked ? '1' : '0';
        if (!chkRepack.checked) { previewWrap.style.display = 'none'; return; }
        const jumlah     = parseFloat(inputJumlah?.value) || 0;
        const berat      = parseFloat(inputBerat?.value) || 0;
        const satuanBerat = selectSatuanBerat?.value ?? 'Kg';
        if (jumlah > 0 && berat > 0) {
            const totalKg   = satuanBerat.toLowerCase() === 'gram' ? (jumlah*berat)/1000 : jumlah*berat;
            const formatted = totalKg % 1 === 0
                ? totalKg.toLocaleString('id-ID') + ' Kg'
                : totalKg.toLocaleString('id-ID', {maximumFractionDigits:2}) + ' Kg';
            previewText.textContent = `${jumlah} Karung = ${formatted}`;
            previewWrap.style.display = 'block';
        } else {
            previewWrap.style.display = 'none';
        }
    }

    function syncBarangMaster(row, applyValues = false) {
        const inputNama = row.querySelector('.input-nama-barang');
        const badge     = row.querySelector('.badge-barang-exists');
        if (!inputNama || !badge) return;
        const key   = inputNama.value.trim().toLowerCase();
        const match = key ? barangMasterMap[key] : null;
        badge.style.display = match ? 'inline-flex' : 'none';
        if (match && applyValues) {
            const selectKategori    = row.querySelector('[name$="[kategori]"]');
            const selectSatuan      = row.querySelector('.input-satuan');
            const inputBerat        = row.querySelector('.input-berat');
            const selectSatuanBerat = row.querySelector('[name$="[satuan_berat]"]');
            const chkPecah          = row.querySelector('.chk-repack');
            if (selectKategori)    selectKategori.value    = match.kategori         ?? '';
            if (selectSatuan)      selectSatuan.value      = match.satuan            ?? '';
            if (inputBerat)        inputBerat.value        = match.berat_per_satuan  ?? '';
            if (selectSatuanBerat) selectSatuanBerat.value = match.satuan_berat      ?? '';
            if (chkPecah)          chkPecah.checked        = String(match.bisa_dipecah ?? '0') === '1';
            syncRepackOption(row, false);
        }
    }

    function tambahBaris(item = {}, shouldFocus = true) {
        rowCount++;
        const placeholder = namaBarangPlaceholders[(rowCount-1) % namaBarangPlaceholders.length];
        const row = `
        <tr id="row-${rowCount}">
            <td class="tc-no text-secondary fw-semibold row-number">
                ${rowCount}
                <input type="hidden" name="items[${rowCount}][id]" value="${escapeHtml(item.id)}">
            </td>
            <td class="tc-nama">
                <input type="text" class="dm-input input-nama-barang"
                    name="items[${rowCount}][nama_barang]" maxlength="150"
                    placeholder="Contoh: ${escapeHtml(placeholder)}"
                    value="${escapeHtml(item.nama_barang)}"
                    list="daftarNamaBarang" autocomplete="off" required>
                <span class="badge-barang-exists badge bg-success-subtle text-success border border-success-subtle">
                    <i class="fa-solid fa-circle-check me-1"></i>Barang terdaftar
                </span>
            </td>
            <td class="tc-kat">
                <select class="dm-select" name="items[${rowCount}][kategori]" required>
                    ${kategoriOptionsSelected(item.kategori)}
                </select>
            </td>
            <td class="tc-sat">
                <select class="dm-select input-satuan" name="items[${rowCount}][satuan]" required>
                    ${selectOptions(satuanOptions, item.satuan)}
                </select>
                <input type="hidden" class="input-bisa-dipecah" name="items[${rowCount}][bisa_dipecah]" value="${escapeHtml(item.bisa_dipecah ?? '0')}">
                <div class="repack-toggle-wrap" style="display:none;">
                    <label class="chk-repack-label">
                        <input type="checkbox" class="chk-repack" style="width:13px;height:13px;accent-color:#16a34a;cursor:pointer;">
                        <span>Repack ke Kg</span>
                    </label>
                    <div class="repack-preview" style="display:none;">
                        → <span class="repack-preview-text"></span>
                    </div>
                </div>
            </td>
            <td class="tc-ctn">
                <input type="number" class="dm-input" name="items[${rowCount}][jumlah_ctn]"
                    min="0" step="1" value="${escapeHtml(item.jumlah_ctn)}" placeholder="—">
            </td>
            <td class="tc-jml">
                <input type="number" class="dm-input input-jumlah" name="items[${rowCount}][jumlah]"
                    min="1" step="1" value="${escapeHtml(item.jumlah)}" required>
            </td>
            <td class="tc-berat">
                <input type="number" class="dm-input input-berat" name="items[${rowCount}][berat_per_satuan]"
                    min="0.01" step="0.01" value="${escapeHtml(item.berat_per_satuan)}" placeholder="0.5" required>
            </td>
            <td class="tc-sberat">
                <select class="dm-select" name="items[${rowCount}][satuan_berat]" required>
                    ${selectOptions(satuanBeratOptions, item.satuan_berat)}
                </select>
            </td>
            <td class="tc-exp">
                <input type="date" class="dm-input input-expired" name="items[${rowCount}][tanggal_kedaluwarsa]"
                    value="${escapeHtml(item.tanggal_kedaluwarsa)}" required>
            </td>
            <td class="tc-aksi">
                <button type="button" class="btn btn-outline-danger btn-hapus-row" data-row="row-${rowCount}">
                    <i class="fa-solid fa-xmark" style="pointer-events:none;"></i>
                </button>
            </td>
        </tr>`;
        document.getElementById('tbodyItem').insertAdjacentHTML('beforeend', row);
        updateNomor();
        const newRow = document.getElementById(`row-${rowCount}`);
        const chk = newRow.querySelector('.chk-repack');
        if (chk) chk.checked = String(item.bisa_dipecah ?? '0') === '1';
        syncRepackOption(newRow, false);
        syncBarangMaster(newRow, false);
        if (shouldFocus) newRow.querySelector('.input-nama-barang').focus();
    }

    function updateNomor() {
        document.querySelectorAll('#tbodyItem tr').forEach((tr, i) => {
            tr.querySelector('.row-number').firstChild.textContent = i + 1;
        });
    }

    let isFormSubmitted = false;

    function validateField(field) {
        if (!field.willValidate || field.disabled || field.readOnly) return true;
        let valid = field.checkValidity();
        if (field.classList.contains('input-jumlah') && parseFloat(field.value) < 1) valid = false;
        if (field.classList.contains('input-berat')  && parseFloat(field.value) <= 0) valid = false;
        if (field.classList.contains('input-expired')) {
            const tm = document.getElementById('tanggal_masuk').value;
            if (tm && field.value && field.value <= tm) valid = false;
        }
        field.classList.toggle('is-invalid', !valid);
        return valid;
    }

    const form = document.getElementById('formDonasiMasuk');

    form.addEventListener('blur', function(e) {
        if (!e.target.matches('input,select,textarea')) return;
        e.target.dataset.touched = 'true';
        if (e.target.classList.contains('input-nama-barang')) {
            e.target.value = e.target.value.trim();
            syncBarangMaster(e.target.closest('tr'), true);
        }
        validateField(e.target);
    }, true);

    form.addEventListener('input', function(e) {
        if (!e.target.matches('input,select,textarea')) return;
        if (e.target.classList.contains('input-jumlah') || e.target.classList.contains('input-berat')) {
            const row = e.target.closest('tr');
            if (row) updateRepackPreview(row);
        }
        if (e.target.dataset.touched === 'true' || isFormSubmitted) validateField(e.target);
    });

    form.addEventListener('change', function(e) {
        if (e.target.classList.contains('input-satuan')) syncRepackOption(e.target.closest('tr'), true);
        if (e.target.classList.contains('chk-repack'))   updateRepackPreview(e.target.closest('tr'));
        if (e.target.matches('[name$="[satuan_berat]"]')) { const r=e.target.closest('tr'); if(r) updateRepackPreview(r); }
        if (e.target.id === 'tanggal_masuk') {
            document.querySelectorAll('.input-expired').forEach(el => {
                if (el.dataset.touched === 'true' || isFormSubmitted) validateField(el);
            });
        }
        if (e.target.matches('input,select,textarea') && (e.target.dataset.touched === 'true' || isFormSubmitted)) validateField(e.target);
    });

    document.getElementById('btnTambahItem').addEventListener('click', () => tambahBaris());

    document.getElementById('tbodyItem').addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-hapus-row');
        if (!btn) return;
        document.getElementById(btn.dataset.row).remove();
        updateNomor();
        if (isFormSubmitted) {
            const rows     = document.querySelectorAll('#tbodyItem tr');
            const errorDiv = document.getElementById('errorItem');
            if (rows.length === 0) {
                errorDiv.innerHTML = '<i class="fa-solid fa-circle-exclamation me-1"></i> Minimal harus ada 1 barang.';
                errorDiv.style.display = 'block';
            } else if (!form.querySelector('.is-invalid')) {
                errorDiv.style.display = 'none';
            }
        }
    });

    form.addEventListener('submit', function(e) {
        isFormSubmitted = true;
        let isFormValid = true;
        form.querySelectorAll('input,select,textarea').forEach(field => {
            field.dataset.touched = 'true';
            if (!validateField(field)) isFormValid = false;
        });
        const rows     = document.querySelectorAll('#tbodyItem tr');
        const errorDiv = document.getElementById('errorItem');
        if (rows.length === 0) {
            errorDiv.innerHTML = '<i class="fa-solid fa-circle-exclamation me-1"></i> Minimal harus ada 1 barang.';
            errorDiv.style.display = 'block';
            isFormValid = false;
        } else if (!isFormValid) {
            errorDiv.innerHTML = '<i class="fa-solid fa-circle-exclamation me-1"></i> Lengkapi semua yang ditandai merah.';
            errorDiv.style.display = 'block';
        } else {
            errorDiv.style.display = 'none';
        }
        if (!isFormValid) {
            e.preventDefault();
            const firstError = form.querySelector('.is-invalid');
            if (firstError) firstError.focus();
        } else {
            const btnSimpan = document.getElementById('btnSimpan');
            btnSimpan.disabled = true;
            btnSimpan.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Menyimpan...';
        }
    });

    if (oldItems.length > 0) {
        oldItems.forEach(item => tambahBaris(item, false));
    } else {
        tambahBaris({}, false);
    }
</script>
<?= $this->endSection() ?>