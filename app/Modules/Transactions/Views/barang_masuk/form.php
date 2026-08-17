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
                'isi_per_ctn'         => $b['isi_per_ctn'] ?? null,
                'jumlah'              => (function($b) {
                    $jumlah = (float)$b['jumlah_awal'];
                    if ((int)$b['bisa_dipecah'] === 1) {
                        if ($b['jumlah_ctn'] !== null) {
                            $jumlah = (float)$b['jumlah_ctn'];
                        } elseif (strtolower($b['satuan'] ?? '') !== 'kg') {
                            $berat = (float)($b['berat_per_satuan'] ?? 1);
                            if ($berat > 0) {
                                if (strtolower($b['satuan_berat'] ?? 'kg') === 'gram') {
                                    $jumlah = ($b['jumlah_awal'] * 1000) / $berat;
                                } else {
                                    $jumlah = $b['jumlah_awal'] / $berat;
                                }
                            }
                        }
                    }
                    // Format agar tidak ada desimal berlebih jika angkanya bulat
                    return (floor($jumlah) == $jumlah) ? (int)$jumlah : $jumlah;
                })($b),
                'satuan'              => $b['satuan'],
                'berat_per_satuan'    => $b['berat_per_satuan'],
                'satuan_berat'        => $b['satuan_berat'],
                'tanggal_kedaluwarsa' => $b['tanggal_kedaluwarsa'],
                'nilai_satuan'        => $b['nilai_satuan'],
                'bisa_dipecah'        => $b['bisa_dipecah'],
            ];
        }
    } elseif (!$oldItems) {
        $oldItems = [];
    }

    // FIX #5: Gunakan $valTanggalMasuk secara konsisten (sebelumnya di-hardcode ulang di input)
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
    min-width: 1060px;
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
    vertical-align: top;
}
#tabelItem tbody td.tc-no,
#tabelItem tbody td.tc-aksi { vertical-align: middle; text-align: center; }

/* column widths */
#tabelItem .tc-no      { width: 42px;  text-align: center; }
#tabelItem .tc-nama    { width: 190px; }
#tabelItem .tc-kat     { width: 120px; }
#tabelItem .tc-sat     { width: 110px; }
#tabelItem .tc-ctn     { width: 75px;  }
#tabelItem .tc-isi-ctn { width: 85px;  }
#tabelItem .tc-jml     { width: 85px;  }
#tabelItem .tc-berat   { width: 85px;  }
#tabelItem .tc-sberat  { width: 95px;  }
#tabelItem .tc-exp     { width: 145px; }
#tabelItem .tc-nilai   { width: 145px; }
#tabelItem .tc-aksi    { width: 48px;  text-align: center; }

/* inputs inside table */
#tabelItem .dm-input,
#tabelItem .dm-select {
    height: 34px;
    font-size: 12px;
    padding: 0 8px;
    border-radius: 6px;
}

/* ── Repack bits ── */
/* FIX #6: Pindah inline style ke class */
.repack-toggle-wrap  { margin-top: 5px; }
.repack-toggle-wrap--hidden { display: none; }
.chk-repack-label {
    font-size: 11px;
    color: #374151;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 4px;
    user-select: none;
}
.chk-repack {
    width: 13px;
    height: 13px;
    accent-color: #16a34a;
    cursor: pointer;
}
.repack-preview {
    font-size: 10.5px;
    color: #16a34a;
    font-weight: 600;
    margin-top: 2px;
}
.repack-preview--hidden { display: none; }
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

<form action="<?= $actionUrl ?>" method="POST" id="formDonasiMasuk" class="loading-form" data-overlay="true" novalidate>
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
                    <!-- FIX #5: Gunakan $valTanggalMasuk, bukan hardcode date() lagi -->
                    <input type="date" class="dm-input" id="tanggal_masuk" name="tanggal_masuk"
                           value="<?= esc($valTanggalMasuk) ?>" required>
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
                    <input type="date" class="dm-input" id="eta" name="eta" value="<?= esc($valEta) ?>">
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
                            <th class="tc-ctn">JML CTN<br><small style="font-size:9px;font-weight:400;color:#9ca3af;">(OPSIONAL)</small></th>
                            <th class="tc-isi-ctn">ISI/CTN<br><small style="font-size:9px;font-weight:400;color:#9ca3af;">(OPSIONAL)</small></th>
                            <th class="tc-jml">JUMLAH <span class="text-danger">*</span></th>
                            <th class="tc-berat">BERAT/SAT. <span class="text-danger">*</span></th>
                            <th class="tc-sberat">SAT. BERAT <span class="text-danger">*</span></th>
                            <th class="tc-exp">EXPIRED <span class="text-danger">*</span></th>
                            <th class="tc-nilai">NILAI SATUAN</th>
                            <th class="tc-aksi">AKSI</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyItem"></tbody>
                </table>
            </div>
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

    const satuanOptions      = ['Karung', 'Dus', 'Box', 'Kotak', 'Pack', 'Pcs', 'Botol', 'Kaleng', 'Tray', 'Pouch', 'Sachet', 'Renceng', 'Kantong', 'Repack', 'Kg'];
    const satuanBeratOptions = ['Gram', 'Kg', 'ml', 'Liter'];
    const namaBarangPlaceholders = ['Beras Premium','Nasi Box Ayam','Brownies','Air Mineral'];
    let rowCount = 0;

    // Hanya untuk generate unique row ID — tidak dipakai sebagai index name
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

        // FIX #6: Pakai class alih-alih inline style
        toggleWrap.classList.toggle('repack-toggle-wrap--hidden', !isKarung);
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

        // FIX #6: Pakai class
        if (!chkRepack.checked) {
            previewWrap.classList.add('repack-preview--hidden');
            return;
        }
        const jumlah     = parseFloat(inputJumlah?.value) || 0;
        const berat      = parseFloat(inputBerat?.value) || 0;
        const satuanBerat = selectSatuanBerat?.value ?? 'Kg';
        if (jumlah > 0 && berat > 0) {
            const totalKg   = satuanBerat.toLowerCase() === 'gram' ? (jumlah*berat)/1000 : jumlah*berat;
            const formatted = totalKg % 1 === 0
                ? totalKg.toLocaleString('id-ID') + ' Kg'
                : totalKg.toLocaleString('id-ID', {maximumFractionDigits:2}) + ' Kg';
            previewText.textContent = `${jumlah} Karung = ${formatted}`;
            previewWrap.classList.remove('repack-preview--hidden');
        } else {
            previewWrap.classList.add('repack-preview--hidden');
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

    // FIX #2: Re-index semua name="items[N][...]" setelah perubahan urutan baris
    function reindexNames() {
        document.querySelectorAll('#tbodyItem tr').forEach((tr, i) => {
            const idx = i + 1;
            tr.querySelectorAll('[name]').forEach(el => {
                el.name = el.name.replace(/items\[\d+\]/, `items[${idx}]`);
            });
            // Perbarui nomor tampilan
            const noCell = tr.querySelector('.row-number');
            if (noCell) noCell.firstChild.textContent = idx;
        });
    }

    function formatInputRupiah(input) {
        let value = input.value.replace(/[^,\d]/g, '');
        let split = value.split(',');
        let sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        let ribuan = split[0].substr(sisa).match(/\d{3}/gi);
        
        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        
        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        input.value = rupiah;
        
        calculateEstimasiTotal(input.closest('tr'));
    }

    function formatRupiahString(valueStr) {
        if (!valueStr) return '';
        let num = parseFloat(valueStr);
        if (isNaN(num) || num === 0) return '';
        let value = Math.round(num).toString();
        let sisa = value.length % 3;
        let rupiah = value.substr(0, sisa);
        let ribuan = value.substr(sisa).match(/\d{3}/gi);
        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        return rupiah;
    }

    function calculateCtnRow(row) {
        if (!row) return;
        const inputCtn = row.querySelector('.input-jumlah-ctn');
        const inputIsi = row.querySelector('.input-isi-ctn');
        const inputJumlah = row.querySelector('.input-jumlah');
        if (!inputCtn || !inputIsi || !inputJumlah) return;

        const ctn = parseInt(inputCtn.value, 10) || 0;
        const isi = parseInt(inputIsi.value, 10) || 0;

        if (ctn > 0 && isi > 0) {
            inputJumlah.value = ctn * isi;
            inputJumlah.readOnly = true;
            inputJumlah.classList.add('readonly');
        } else {
            inputJumlah.readOnly = false;
            inputJumlah.classList.remove('readonly');
        }
        calculateEstimasiTotal(row);
    }

    function calculateEstimasiTotal(row) {
        const inputJumlah = row.querySelector('.input-jumlah');
        const inputNilai = row.querySelector('.input-nilai');
        const preview = row.querySelector('.preview-estimasi');
        if (!inputJumlah || !inputNilai || !preview) return;
        
        let qty = parseFloat(inputJumlah.value) || 0;
        
        // Remove dots before parsing to float
        let rawNilai = inputNilai.value.replace(/\./g, '').replace(/,/g, '.');
        let nilai = parseFloat(rawNilai) || 0;
        
        if (qty <= 0 || nilai <= 0) {
            preview.textContent = '—';
            return;
        }
        
        const total = qty * nilai;
        const formatted = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(total);
        preview.textContent = formatted;
    }

    function tambahBaris(item = {}, shouldFocus = true) {
        rowCount++;
        const placeholder = namaBarangPlaceholders[(rowCount-1) % namaBarangPlaceholders.length];

        // Gunakan rowCount hanya sebagai ID unik sementara di DOM
        const tmpIdx = rowCount;
        const rowHtml = `
        <tr id="row-${tmpIdx}">
            <td class="tc-no text-secondary fw-semibold">
                <span class="row-number">${tmpIdx}</span>
                <input type="hidden" name="items[${tmpIdx}][id]" value="">
            </td>
            <td class="tc-nama" style="position:relative;">
                <input type="text" class="dm-input input-nama-barang"
                    name="items[${tmpIdx}][nama_barang]" maxlength="150"
                    placeholder="Contoh: ${escapeHtml(placeholder)}"
                    autocomplete="off" required>
                <div class="suggestion-box dropdown-menu shadow-sm" style="display:none; position:absolute; top:100%; left:0; right:0; z-index:1050; max-height:220px; overflow-y:auto;"></div>
                <span class="badge-barang-exists badge bg-success-subtle text-success border border-success-subtle">
                    <i class="fa-solid fa-circle-check me-1"></i>Barang terdaftar
                </span>
            </td>
            <td class="tc-kat">
                <select class="dm-select" name="items[${tmpIdx}][kategori]" required>
                    ${kategoriOptionsSelected(item.kategori)}
                </select>
            </td>
            <td class="tc-sat">
                <select class="dm-select input-satuan" name="items[${tmpIdx}][satuan]" required>
                    ${selectOptions(satuanOptions, item.satuan)}
                </select>
                <input type="hidden" class="input-bisa-dipecah" name="items[${tmpIdx}][bisa_dipecah]" value="${escapeHtml(item.bisa_dipecah ?? '0')}">
                <div class="repack-toggle-wrap repack-toggle-wrap--hidden">
                    <label class="chk-repack-label">
                        <input type="checkbox" class="chk-repack">
                        <span>Repack ke Kg</span>
                    </label>
                    <div class="repack-preview repack-preview--hidden">
                        → <span class="repack-preview-text"></span>
                    </div>
                </div>
            </td>
            <td class="tc-ctn">
                <input type="number" class="dm-input input-jumlah-ctn" name="items[${tmpIdx}][jumlah_ctn]"
                    min="1" step="1" placeholder="—" oninput="calculateCtnRow(this.closest('tr'))">
            </td>
            <td class="tc-isi-ctn">
                <input type="number" class="dm-input input-isi-ctn" name="items[${tmpIdx}][isi_per_ctn]"
                    min="1" step="1" placeholder="—" oninput="calculateCtnRow(this.closest('tr'))">
            </td>
            <td class="tc-jml">
                <input type="number" class="dm-input input-jumlah" name="items[${tmpIdx}][jumlah]"
                    min="1" step="1" required oninput="calculateEstimasiTotal(this.closest('tr'))">
            </td>
            <td class="tc-berat">
                <input type="number" class="dm-input input-berat" name="items[${tmpIdx}][berat_per_satuan]"
                    min="0.01" step="0.01" placeholder="0.5" required>
            </td>
            <td class="tc-sberat">
                <select class="dm-select" name="items[${tmpIdx}][satuan_berat]" required>
                    ${selectOptions(satuanBeratOptions, item.satuan_berat)}
                </select>
            </td>
            <td class="tc-exp">
                <input type="date" class="dm-input input-expired" name="items[${tmpIdx}][tanggal_kedaluwarsa]"
                    required>
            </td>
            <td class="tc-nilai">
                <input type="text" class="dm-input input-nilai" name="items[${tmpIdx}][nilai_satuan]"
                    placeholder="Rp 0" oninput="formatInputRupiah(this)">
                <div style="margin-top: 6px;">
                    <div style="font-size:12px; color:#6c757d; display:flex; align-items:center; line-height: 1;">
                        Estimasi Nilai Donasi
                        <i class="fa-solid fa-circle-info ms-1" style="cursor:help; font-size:11px;" title="Estimasi ini hanya untuk informasi. Nilai akhir dihitung kembali saat laporan PDF/Excel dibuat."></i>
                    </div>
                    <div style="font-size:15px; font-weight:700; color:#198754; margin-top:3px; line-height: 1;" class="preview-estimasi">—</div>
                </div>
            </td>
            <td class="tc-aksi">
                <button type="button" class="btn btn-outline-danger btn-hapus-row" data-row="row-${tmpIdx}">
                    <i class="fa-solid fa-xmark" style="pointer-events:none;"></i>
                </button>
            </td>
        </tr>`;

        document.getElementById('tbodyItem').insertAdjacentHTML('beforeend', rowHtml);

        // FIX #3: Set nilai via DOM, bukan string interpolasi, untuk menghindari XSS
        const newRow = document.getElementById(`row-${tmpIdx}`);
        newRow.querySelector('.input-nama-barang').value   = item.nama_barang        ?? '';
        newRow.querySelector('.input-jumlah-ctn').value    = item.jumlah_ctn         ?? '';
        newRow.querySelector('.input-isi-ctn').value       = item.isi_per_ctn        ?? '';
        newRow.querySelector('.input-jumlah').value        = item.jumlah             ?? '';
        newRow.querySelector('.input-berat').value         = item.berat_per_satuan   ?? '';
        newRow.querySelector('.input-expired').value       = item.tanggal_kedaluwarsa ?? '';
        newRow.querySelector('.input-nilai').value         = formatRupiahString(item.nilai_satuan);
        newRow.querySelector('[name$="[id]"]').value       = item.id                 ?? '';
        
        calculateCtnRow(newRow);

        const chk = newRow.querySelector('.chk-repack');
        if (chk) chk.checked = String(item.bisa_dipecah ?? '0') === '1';

        syncRepackOption(newRow, false);
        syncBarangMaster(newRow, false);

        // Re-index agar name index selalu berurutan
        reindexNames();

        if (shouldFocus) newRow.querySelector('.input-nama-barang').focus();
    }

    let isFormSubmitted = false;

    // FIX #1: Perbandingan tanggal pakai new Date() supaya eksplisit & aman
    function validateField(field) {
        if (!field.willValidate || field.disabled || field.readOnly) return true;
        let valid = field.checkValidity();
        if (field.classList.contains('input-jumlah') && parseFloat(field.value) < 1) valid = false;
        if (field.classList.contains('input-berat')  && parseFloat(field.value) <= 0) valid = false;
        if (field.classList.contains('input-expired')) {
            const tmVal = document.getElementById('tanggal_masuk').value;
            if (tmVal && field.value && new Date(field.value) <= new Date(tmVal)) valid = false;
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
        if (e.target.classList.contains('input-nama-barang')) {
            const input = e.target;
            const row   = input.closest('tr');
            const box   = row ? row.querySelector('.suggestion-box') : null;
            const term  = input.value.trim().toLowerCase();

            if (row) syncBarangMaster(row, false);

            if (box) {
                if (term.length < 1) {
                    box.style.display = 'none';
                    box.innerHTML = '';
                } else {
                    const matches = barangMasterList.filter(b => 
                        String(b.nama_barang ?? '').toLowerCase().includes(term)
                    );
                    if (matches.length > 0) {
                        let html = '';
                        matches.slice(0, 10).forEach(m => {
                            html += `<div class="dropdown-item py-1 px-2 text-wrap suggestion-item" data-nama="${escapeHtml(m.nama_barang)}" style="font-size:12px; cursor:pointer;">
                                        <strong>${escapeHtml(m.nama_barang)}</strong> 
                                        <small class="text-muted">(${escapeHtml(m.kategori)} - ${escapeHtml(m.satuan)})</small>
                                     </div>`;
                        });
                        box.innerHTML = html;
                        box.style.display = 'block';
                    } else {
                        box.style.display = 'none';
                        box.innerHTML = '';
                    }
                }
            }
        }
        if (e.target.classList.contains('input-jumlah') || e.target.classList.contains('input-berat')) {
            const row = e.target.closest('tr');
            if (row) updateRepackPreview(row);
        }
        if (e.target.classList.contains('input-jumlah') || e.target.classList.contains('input-nilai')) {
            const row = e.target.closest('tr');
            if (row) calculateEstimasiTotal(row);
        }
        if (e.target.dataset.touched === 'true' || isFormSubmitted) validateField(e.target);
    });

    document.addEventListener('click', function(e) {
        const item = e.target.closest('.suggestion-item');
        if (item) {
            const row   = item.closest('tr');
            const input = row.querySelector('.input-nama-barang');
            const box   = row.querySelector('.suggestion-box');
            if (input && box) {
                input.value = item.dataset.nama;
                syncBarangMaster(row, true);
                box.style.display = 'none';
                box.innerHTML = '';
            }
            return;
        }
        if (!e.target.closest('.tc-nama')) {
            document.querySelectorAll('.suggestion-box').forEach(b => {
                b.style.display = 'none';
            });
        }
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

        // FIX #2: Reindex setelah hapus agar name array selalu berurutan
        reindexNames();

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
            if (btnSimpan.dataset.submitted === 'true') {
                e.preventDefault();
                return false;
            }
            btnSimpan.dataset.submitted = 'true';
            btnSimpan.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Menyimpan...';
            btnSimpan.style.pointerEvents = 'none';
            btnSimpan.style.opacity = '0.7';
            isFormSubmitted = true;
        }
    });

    // Fix for Back-Forward Cache (bfcache) double submissions
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            const btnSimpan = document.getElementById('btnSimpan');
            btnSimpan.dataset.submitted = 'false';
            btnSimpan.innerHTML = '<i class="fa-solid fa-save"></i> Simpan Transaksi';
            btnSimpan.style.pointerEvents = 'auto';
            btnSimpan.style.opacity = '1';
            isFormSubmitted = false;
        }
    });


    if (oldItems.length > 0) {
        oldItems.forEach(item => tambahBaris(item, false));
    } else {
        tambahBaris({}, false);
    }
</script>
<?= $this->endSection() ?>