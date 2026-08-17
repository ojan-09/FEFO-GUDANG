<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php
    $isEdit = isset($barangKeluar);
    $actionUrl = $isEdit ? site_url('transaksi/barang-keluar/update/' . $barangKeluar['id']) : site_url('transaksi/barang-keluar/store');
    
    $valTanggalKeluar    = old('tanggal_keluar',    $isEdit ? $barangKeluar['tanggal_keluar']    : date('Y-m-d'));
    $valTujuanPenyaluran = old('tujuan_penyaluran', $isEdit ? $barangKeluar['tujuan_penyaluran'] : '');
    $valIdWilayah        = old('id_wilayah',        $isEdit ? $barangKeluar['id_wilayah']        : '');
    $valKeterangan       = old('keterangan',        $isEdit ? $barangKeluar['keterangan']        : '');
?>

<style>
/* ─────────────────────────────────────────────
   ROOT SCALE — proporsional di zoom 100% Full HD
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
.dm-card__body { padding: 16px 18px; }

/* ── Header-form grid ── */
.dm-grid-3 {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 12px;
    margin-bottom: 12px;
}
.dm-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
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
.dm-select:focus { border-color: #6366f1; box-shadow: 0 0 0 2px rgba(99,102,241,.12); }
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
.dm-input.is-invalid,
.dm-select.is-invalid { border-color: #ef4444 !important; }

/* ── Table ── */
.dm-table-wrap { overflow-x: auto; }
#tabelItem {
    width: 100%;
    table-layout: fixed;
    min-width: 860px;
    border-collapse: collapse;
    font-size: 12px;
}
#tabelItem thead tr { background: #f8fafc; }
#tabelItem thead th {
    font-size: 11px;
    font-weight: 700;
    color: #6b7280;
    letter-spacing: .04em;
    text-transform: uppercase;
    padding: 9px 8px;
    border: 1px solid #e5e7eb;
    vertical-align: middle;
    text-align: center;
}
#tabelItem tbody td {
    padding: 6px 7px;
    border: 1px solid #e5e7eb;
    vertical-align: middle;
}
/* column widths */
#tabelItem .tc-no      { width: 42px;  text-align: center; }
#tabelItem .tc-barang  { width: auto;  }
#tabelItem .tc-unit    { width: 110px; text-align: center; }
#tabelItem .tc-stok    { width: 120px; text-align: center; }
#tabelItem .tc-jumlah  { width: 110px; }
#tabelItem .tc-berat   { width: 130px; text-align: center; }
#tabelItem .tc-aksi    { width: 52px;  text-align: center; }

/* inputs inside table */
#tabelItem .dm-input,
#tabelItem .dm-select {
    height: 34px;
    font-size: 12px;
    padding: 0 8px;
    border-radius: 6px;
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
#errorItem { font-size: 12px; }

/* ── Responsive ── */
@media (max-width: 1199px) {
    .dm-grid-3 { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 767px) {
    .dm-page   { padding: 10px 12px; }
    .dm-grid-3,
    .dm-grid-2 { grid-template-columns: 1fr; }
}
</style>

<div class="dm-page">

<!-- ── Topbar ── -->
<div class="dm-topbar">
    <div>
        <h1 class="dm-topbar__title">
            <i class="fa-solid fa-arrow-up"></i><?= esc($title) ?>
        </h1>
        <p class="dm-topbar__sub">Keluarkan barang dari gudang menggunakan metode FEFO</p>
    </div>
    <a href="<?= site_url('transaksi/barang-keluar') ?>" class="btn btn-outline-secondary btn-back">
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

<?php
    $valJenisPenyaluran  = old('jenis_penyaluran',  $isEdit ? ($barangKeluar['jenis_penyaluran'] ?? 'Penyaluran Relawan') : 'Penyaluran Relawan');
    $valPenerimaRelawan = old('penerima_relawan', $isEdit ? ($barangKeluar['penerima_relawan'] ?? '') : '');
    $valUnitInternal    = old('unit_internal',    $isEdit ? ($barangKeluar['unit_internal'] ?? '') : '');
?>

<form action="<?= $actionUrl ?>" method="POST" id="formBarangKeluar" class="loading-form" data-overlay="true">
    <?= csrf_field() ?>

    <!-- ── Informasi Transaksi card ── -->
    <div class="dm-card">
        <div class="dm-card__header">
            <h5 class="dm-card__title"><i class="fa-solid fa-file-lines"></i>Informasi Transaksi</h5>
        </div>
        <div class="dm-card__body">
            <!-- Row 1: Jenis Penyaluran | Nomor | Tanggal -->
            <div class="dm-grid-3 mb-3">
                <div>
                    <label for="jenis_penyaluran" class="dm-label">Jenis Penyaluran <span class="text-danger">*</span></label>
                    <select class="dm-select" id="jenis_penyaluran" name="jenis_penyaluran" onchange="toggleJenisPenyaluran()" required>
                        <option value="Penyaluran Relawan" <?= $valJenisPenyaluran === 'Penyaluran Relawan' ? 'selected' : '' ?>>Penyaluran Relawan</option>
                        <option value="Penyaluran Internal" <?= $valJenisPenyaluran === 'Penyaluran Internal' ? 'selected' : '' ?>>Penyaluran Internal</option>
                    </select>
                </div>
                <div>
                    <label class="dm-label">Nomor Dokumen <span class="text-muted">(Otomatis Global)</span></label>
                    <input type="text" class="dm-input readonly" value="<?= esc($nomor_transaksi) ?>" readonly>
                </div>
                <div>
                    <label for="tanggal_keluar" class="dm-label">Tanggal Penyaluran <span class="text-danger">*</span></label>
                    <input type="date" class="dm-input" id="tanggal_keluar" name="tanggal_keluar"
                           value="<?= esc($valTanggalKeluar) ?>" required>
                </div>
            </div>

            <!-- Row 2: Dynamic Field (Relawan vs Internal) & Tujuan -->
            <div class="dm-grid-2 mb-3">
                <div id="field_relawan_wrap">
                    <label for="penerima_relawan" class="dm-label">Nama Relawan / Penerima <span class="text-danger">*</span></label>
                    <input type="text" class="dm-input" id="penerima_relawan" name="penerima_relawan"
                           placeholder="Contoh: Andi / Tim Relawan Dapur Umum"
                           value="<?= esc($valPenerimaRelawan) ?>">
                </div>
                <div id="field_internal_wrap" style="display: none;">
                    <label for="unit_internal" class="dm-label">Unit / Bagian Internal <span class="text-danger">*</span></label>
                    <input type="text" class="dm-input" id="unit_internal" name="unit_internal"
                           placeholder="Contoh: Operasional / Dapur Komunitas"
                           value="<?= esc($valUnitInternal) ?>">
                </div>
                <div>
                    <label for="tujuan_penyaluran" class="dm-label">Program / Tujuan Penyaluran <span class="text-danger">*</span></label>
                    <input type="text" class="dm-input" id="tujuan_penyaluran" name="tujuan_penyaluran"
                           placeholder="Contoh: Penyaluran Ramadhan 2026"
                           value="<?= esc($valTujuanPenyaluran) ?>" required>
                </div>
            </div>

            <!-- Row 3: Wilayah | Keterangan -->
            <div class="dm-grid-2">
                <div>
                    <label for="id_wilayah" class="dm-label">Wilayah Tujuan <span class="text-danger">*</span></label>
                    <select class="dm-select" id="id_wilayah" name="id_wilayah" required>
                        <option value="">-- Pilih Wilayah --</option>
                        <?php foreach ($wilayah as $w) : ?>
                            <option value="<?= esc($w['id']) ?>" <?= $valIdWilayah == $w['id'] ? 'selected' : '' ?>>
                                <?= esc($w['nama_wilayah']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="keterangan" class="dm-label">Keterangan</label>
                    <textarea class="dm-textarea" id="keterangan" name="keterangan"
                               placeholder="Opsional"><?= esc($valKeterangan) ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Daftar Barang Keluar card ── -->
    <div class="dm-card">
        <div class="dm-card__header">
            <h5 class="dm-card__title"><i class="fa-solid fa-boxes-stacked"></i>Daftar Barang Keluar</h5>
            <button type="button" class="btn btn-success text-white btn-tambah-item" id="btnTambahItem">
                <i class="fa-solid fa-plus"></i> Tambah Item
            </button>
        </div>
        <div class="dm-card__body">
            <div class="dm-table-wrap">
                <table id="tabelItem">
                    <thead>
                        <tr>
                            <th class="tc-no">No</th>
                            <th class="tc-barang">Barang <span class="text-danger">*</span></th>
                            <th class="tc-unit">Unit Penyaluran</th>
                            <th class="tc-stok">Stok Tersedia</th>
                            <th class="tc-jumlah">Jumlah Keluar <span class="text-danger">*</span></th>
                            <th class="tc-berat">Berat</th>
                            <th class="tc-aksi">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyItem"></tbody>
                </table>
            </div>
            <div id="errorItem" class="text-danger mt-2" style="display:none;">
                <i class="fa-solid fa-circle-exclamation me-1"></i>
                <span id="errorItemMsg">Minimal harus ada 1 barang.</span>
            </div>
        </div>
    </div>

    <!-- ── Footer buttons ── -->
    <div class="dm-footer">
        <button type="submit" class="btn btn-primary btn-simpan" id="btnSimpan">
            <i class="fa-solid fa-save"></i> Simpan Transaksi
        </button>
        <a href="<?= site_url('transaksi/barang-keluar') ?>" class="btn btn-outline-secondary btn-batal">Batal</a>
    </div>
</form>

</div><!-- /.dm-page -->

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Data barang dari server (dengan stok tersedia)
    const dataBarang = <?= json_encode($barang) ?>;
    const oldDetails = <?= json_encode($details ?? []) ?>;
    let rowCount = 0;

    // FIX: helper escaping HTML untuk cegah XSS saat insert ke innerHTML
    function escHtml(str) {
        return String(str ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function updateDropdownOptions() {
        const selects = document.querySelectorAll('.select-barang');
        const selectedValues = [];

        selects.forEach(select => {
            if (select.value) selectedValues.push(select.value);
        });

        selects.forEach(select => {
            const options = select.querySelectorAll('option');
            options.forEach(option => {
                if (option.value === "") return;

                if (selectedValues.includes(option.value) && option.value !== select.value) {
                    option.style.display = 'none';
                    option.disabled = true;
                } else {
                    option.style.display = '';
                    option.disabled = false;
                }
            });
        });

        const btnTambah = document.getElementById('btnTambahItem');
        if (dataBarang.length > 0 && selectedValues.length >= dataBarang.length) {
            btnTambah.disabled = true;
            btnTambah.innerHTML = '<i class="fa-solid fa-ban me-1"></i> Semua Barang Terpilih';
            btnTambah.classList.replace('btn-success', 'btn-secondary');
        } else {
            btnTambah.disabled = false;
            btnTambah.innerHTML = '<i class="fa-solid fa-plus me-1"></i> Tambah Item';
            btnTambah.classList.replace('btn-secondary', 'btn-success');
        }
    }

    // FIX: parse tanggal manual untuk hindari timezone offset bug
    // "2026-01-15" diparsing sebagai UTC oleh new Date(), bisa mundur 1 hari di +7
    function formatTglExp(dateStr) {
        if (!dateStr) return '-';
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
        const parts = String(dateStr).split('-');
        if (parts.length !== 3) return dateStr;
        const d = parseInt(parts[2], 10);
        const m = parseInt(parts[1], 10) - 1;
        const y = parts[0];
        return `${d} ${months[m]} ${y}`;
    }

    function tambahBaris(detail = null) {
        rowCount++;
        let options = '<option value="">-- Pilih Barang --</option>';
        dataBarang.forEach(b => {
            const isSelected = detail && detail.id_barang == b.id ? 'selected' : '';
            const tglFormatted = formatTglExp(b.tanggal_kedaluwarsa);
            const unitTersedia = parseInt(b.bisa_dipecah) === 1 ? 'Kg' : b.satuan;
            const statusLabel = parseInt(b.bisa_dipecah) === 1 ? 'Repack' : 'Utuh';
            const kemasanAsli = parseInt(b.bisa_dipecah) === 1 ? 'Karung' : (b.satuan_kemasan || b.satuan);
            const beratKemasan = parseFloat(b.berat_per_satuan) + ' ' + b.satuan_berat + '/' + kemasanAsli;

            const expDateObj = new Date(b.tanggal_kedaluwarsa);
            const todayObj = new Date();
            todayObj.setHours(0,0,0,0);
            
            let expWarning = '';
            if (expDateObj < todayObj) {
                const diffTime = Math.abs(todayObj - expDateObj);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                expWarning = `🔴 Expired sejak ${tglFormatted} (Expired ${diffDays} hari)`;
            } else {
                expWarning = `Exp: ${tglFormatted}`;
            }

            // FIX: escHtml untuk semua nilai string yang masuk ke HTML (cegah XSS & attribute injection)
            const optionText = escHtml(`${b.nama_barang} (${expWarning} • ${kemasanAsli} • ${beratKemasan} • Stok: ${parseFloat(b.stok_tersedia)} ${unitTersedia} • ${statusLabel})`);

            options += `<option value="${escHtml(b.id)}"
                data-satuan="${escHtml(b.satuan)}"
                data-satuan-kemasan="${escHtml(kemasanAsli)}"
                data-berat="${escHtml(b.berat_per_satuan)}"
                data-satuan-berat="${escHtml(b.satuan_berat)}"
                data-bisa-dipecah="${escHtml(b.bisa_dipecah)}"
                data-stok="${escHtml(b.stok_tersedia)}" ${isSelected}>
                ${optionText}
            </option>`;
        });

        // FIX: rowId pakai rowCount saja (stabil), tidak berubah saat delete baris lain
        const rowId = 'row-' + rowCount;

        const row = `
            <tr id="${rowId}">
                <td class="tc-no row-number">${rowCount}</td>
                <td class="tc-barang">
                    <select class="dm-select select-barang" name="items[${rowCount}][id_barang]" required>
                        ${options}
                    </select>
                </td>
                <td class="tc-unit">
                    <input type="text" class="dm-input readonly field-satuan" readonly value="-">
                </td>
                <td class="tc-stok">
                    <input type="text" class="dm-input readonly field-stok" readonly value="-" style="text-align:center;font-weight:600;">
                </td>
                <td class="tc-jumlah">
                    <input type="number" class="dm-input input-jumlah" name="items[${rowCount}][jumlah_keluar]"
                           min="1" placeholder="0" value="${detail ? escHtml(detail.jumlah_keluar) : ''}" required
                           data-stok="0">
                </td>
                <td class="tc-berat">
                    <input type="text" class="dm-input readonly field-berat" readonly value="-" style="text-align:center;">
                </td>
                <td class="tc-aksi">
                    <button type="button" class="btn btn-outline-danger btn-hapus-row" data-row="${rowId}">
                        <i class="fa-solid fa-xmark" style="pointer-events:none;"></i>
                    </button>
                </td>
            </tr>
        `;
        document.getElementById('tbodyItem').insertAdjacentHTML('beforeend', row);
        updateNomor();
        
        if (detail) {
            const select = document.querySelector(`#${rowId} .select-barang`);
            select.dispatchEvent(new Event('change', { bubbles: true }));
            const inputJumlah = document.querySelector(`#${rowId} .input-jumlah`);
            inputJumlah.dispatchEvent(new Event('input', { bubbles: true }));
        }

        updateDropdownOptions();
    }

    function updateNomor() {
        document.querySelectorAll('#tbodyItem tr').forEach((tr, i) => {
            tr.querySelector('.row-number').textContent = i + 1;
        });
    }

    document.getElementById('btnTambahItem').addEventListener('click', tambahBaris);

    document.getElementById('tbodyItem').addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-hapus-row');
        if (btn) {
            document.getElementById(btn.dataset.row).remove();
            updateNomor();
            updateDropdownOptions();
        }
    });

    document.getElementById('tbodyItem').addEventListener('change', function (e) {
        if (e.target.classList.contains('select-barang')) {
            const row = e.target.closest('tr');
            const selected = e.target.options[e.target.selectedIndex];
            const inputJumlah = row.querySelector('.input-jumlah');

            if (!e.target.value) {
                row.querySelector('.field-satuan').value = '-';
                row.querySelector('.field-stok').value = '-';
                row.querySelector('.field-berat').value = '-';
                inputJumlah.value = '';
                inputJumlah.min = "1";
                inputJumlah.step = "1";
                inputJumlah.placeholder = "0";
                // FIX: reset data-stok pada input-jumlah
                inputJumlah.dataset.stok = "0";
                updateDropdownOptions();
                return;
            }

            let satuan = selected.dataset.satuan;
            if (!satuan || satuan === 'undefined' || satuan === 'null' || satuan.trim() === '') satuan = '-';
            
            let stok = parseFloat(selected.dataset.stok) || 0;
            let berat = parseFloat(selected.dataset.berat) || 0;
            let satuanBeratVal = selected.dataset.satuanBerat;
            if (!satuanBeratVal || satuanBeratVal === 'undefined' || satuanBeratVal === 'null' || satuanBeratVal.trim() === '') satuanBeratVal = '';
            
            const bisaDipecah = parseInt(selected.dataset.bisaDipecah) || 0;

            if (bisaDipecah === 1) {
                row.querySelector('.field-satuan').value = 'Repack';
                row.querySelector('.field-stok').value = stok.toFixed(2) + ' Kg';
                inputJumlah.min = "0.01";
                inputJumlah.step = "0.01";
                inputJumlah.placeholder = "0.00";
            } else {
                row.querySelector('.field-satuan').value = satuan;
                row.querySelector('.field-stok').value = Math.floor(stok) + ' ' + satuan;
                inputJumlah.min = "1";
                inputJumlah.step = "1";
                inputJumlah.placeholder = "0";
            }

            // FIX: simpan nilai stok murni (angka) ke data-stok input-jumlah
            // supaya validasi submit bisa baca angka bukan parse teks display
            inputJumlah.dataset.stok = stok;
            
            if (e.isTrusted) {
                inputJumlah.value = '';
                row.querySelector('.field-berat').value = '-';
            }
            inputJumlah.max = stok;
            
            if (inputJumlah.value) {
                inputJumlah.dispatchEvent(new Event('input', { bubbles: true }));
            }
            
            updateDropdownOptions();
        }
    });

    document.getElementById('tbodyItem').addEventListener('input', function (e) {
        if (e.target.classList.contains('input-jumlah')) {
            const row = e.target.closest('tr');
            const selected = row.querySelector('.select-barang').options[row.querySelector('.select-barang').selectedIndex];
            const berat = parseFloat(selected.dataset.berat) || 0;
            const satuan = selected.dataset.satuan || '';
            const satuanBerat = selected.dataset.satuanBerat || '';
            const stok = parseFloat(selected.dataset.stok) || 0;
            const jumlah = parseFloat(e.target.value) || 0;
            const bisaDipecah = parseInt(selected.dataset.bisaDipecah) || 0;

            const totalBerat = bisaDipecah === 1 ? jumlah : jumlah * berat;
            const labelBerat = bisaDipecah === 1 ? 'Kg' : satuanBerat;
            row.querySelector('.field-berat').value = totalBerat > 0 ? `${totalBerat.toFixed(2)} ${labelBerat}` : '-';

            if (jumlah > stok) {
                e.target.classList.add('is-invalid');
                Swal.fire({
                    icon: 'warning',
                    title: 'Stok Tidak Cukup!',
                    text: `Stok tersedia hanya ${bisaDipecah === 1 ? stok.toFixed(2) + ' Kg' : Math.floor(stok) + ' ' + satuan}. Anda memasukkan ${jumlah}.`,
                    confirmButtonColor: '#2563EB'
                });
            } else {
                e.target.classList.remove('is-invalid');
            }
        }
    });

    document.getElementById('formBarangKeluar').addEventListener('submit', function (e) {
        const rows = document.querySelectorAll('#tbodyItem tr');
        const errorDiv = document.getElementById('errorItem');
        const errorMsg = document.getElementById('errorItemMsg');

        if (rows.length === 0) {
            e.preventDefault();
            errorMsg.textContent = 'Minimal harus ada 1 barang.';
            errorDiv.style.display = 'block';
            return false;
        }

        let adaError = false;
        rows.forEach(row => {
            const inputJumlah = row.querySelector('.input-jumlah');
            const jumlah = parseFloat(inputJumlah.value) || 0;
            // FIX: baca stok dari data-stok (angka murni) bukan parse teks field-stok
            const stok = parseFloat(inputJumlah.dataset.stok) || 0;
            if (jumlah > stok) adaError = true;
        });

        if (adaError) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Ada barang yang jumlahnya melebihi stok tersedia. Silakan perbaiki terlebih dahulu.',
                confirmButtonColor: '#2563EB'
            });
            return false;
        }

        errorDiv.style.display = 'none';

        const formElem = document.getElementById('formBarangKeluar');
        const btnSimpan = document.getElementById('btnSimpan');
        
        if (formElem.dataset.submitting === 'true' || btnSimpan.dataset.submitted === 'true') {
            e.preventDefault();
            return false;
        }

        if (!document.getElementById('force_expired')) {
            e.preventDefault(); // Stop native submit

            formElem.dataset.submitting = 'true';
            btnSimpan.dataset.submitted = 'true';
            btnSimpan.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mengecek...';
            btnSimpan.style.pointerEvents = 'none';
            btnSimpan.style.opacity = '0.7';

            let formData = new FormData(document.getElementById('formBarangKeluar'));

            fetch('<?= site_url("transaksi/barang-keluar/validateExpired") ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(res => {
                if (res.csrf) {
                    let csrfInput = document.querySelector('input[name="<?= csrf_token() ?>"]');
                    if (csrfInput) csrfInput.value = res.csrf;
                }

                if (res.expired) {
                    // Restore button state
                    btnSimpan.dataset.submitted = 'false';
                    btnSimpan.innerHTML = '<i class="fa-solid fa-save"></i> Simpan Transaksi';
                    btnSimpan.style.pointerEvents = 'auto';
                    btnSimpan.style.opacity = '1';

                    let tableHtml = `<table style="width:100%; text-align:left; border-collapse:collapse; font-size:13px; margin-top:10px;">
                        <tr style="border-bottom:1px solid #ddd;">
                            <th style="padding:4px;">Barang</th>
                            <th style="padding:4px;">Batch</th>
                            <th style="padding:4px;">Expired</th>
                            <th style="padding:4px;">Qty</th>
                        </tr>`;
                    res.data.forEach(d => {
                        let expDate = new Date(d.expired).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'});
                        tableHtml += `<tr style="border-bottom:1px solid #eee;">
                            <td style="padding:4px;">${escHtml(d.barang)}</td>
                            <td style="padding:4px;">${escHtml(d.batch)}</td>
                            <td style="padding:4px;">${escHtml(expDate)}</td>
                            <td style="padding:4px;">${escHtml(d.jumlah)}</td>
                        </tr>`;
                    });
                    tableHtml += `</table>`;

                    if (window.hideOverlay) window.hideOverlay();

                    Swal.fire({
                        icon: 'warning',
                        title: '⚠ Barang Expired Ditemukan',
                        html: `Barang berikut sudah melewati tanggal kedaluwarsa.<br>${tableHtml}<br><br>Apakah Anda tetap ingin mengeluarkan barang ini?`,
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Tetap Keluarkan',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let inputForce = document.createElement('input');
                            inputForce.type = 'hidden';
                            inputForce.id = 'force_expired';
                            inputForce.name = 'force_expired';
                            inputForce.value = 'true';
                            document.getElementById('formBarangKeluar').appendChild(inputForce);
                            
                            btnSimpan.dataset.submitted = 'true';
                            btnSimpan.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';
                            btnSimpan.style.pointerEvents = 'none';
                            btnSimpan.style.opacity = '0.7';
                            if (window.showOverlay) window.showOverlay('Menyimpan Transaksi...');
                            HTMLFormElement.prototype.submit.call(document.getElementById('formBarangKeluar'));
                        }
                    });
                } else {
                    let inputForce = document.createElement('input');
                    inputForce.type = 'hidden';
                    inputForce.id = 'force_expired';
                    inputForce.name = 'force_expired';
                    inputForce.value = 'false';
                    document.getElementById('formBarangKeluar').appendChild(inputForce);
                    
                    btnSimpan.dataset.submitted = 'true';
                    btnSimpan.disabled = true;
                    btnSimpan.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';
                    btnSimpan.style.pointerEvents = 'none';
                    btnSimpan.style.opacity = '0.7';
                    if (window.showOverlay) window.showOverlay('Menyimpan Transaksi...');
                    HTMLFormElement.prototype.submit.call(document.getElementById('formBarangKeluar'));
                }
            })
            .catch(err => {
                console.error(err);
                if (window.hideOverlay) window.hideOverlay();
                Swal.fire('Error', 'Gagal memvalidasi barang.', 'error');
                btnSimpan.dataset.submitted = 'false';
                btnSimpan.innerHTML = '<i class="fa-solid fa-save"></i> Simpan Transaksi';
                btnSimpan.style.pointerEvents = 'auto';
                btnSimpan.style.opacity = '1';
            });

            return false;
        }

        btnSimpan.dataset.submitted = 'true';
        btnSimpan.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';
        btnSimpan.style.pointerEvents = 'none';
        btnSimpan.style.opacity = '0.7';
    });

    // Fix for Back-Forward Cache (bfcache) double submissions
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            const btnSimpan = document.getElementById('btnSimpan');
            btnSimpan.dataset.submitted = 'false';
            btnSimpan.innerHTML = '<i class="fa-solid fa-save"></i> Simpan Transaksi';
            btnSimpan.style.pointerEvents = 'auto';
            btnSimpan.style.opacity = '1';
        }
    });

    if (oldDetails.length > 0) { oldDetails.forEach(d => tambahBaris(d)); } else { tambahBaris(); }
    function toggleJenisPenyaluran() {
        const val = document.getElementById('jenis_penyaluran').value;
        const relawanWrap = document.getElementById('field_relawan_wrap');
        const internalWrap = document.getElementById('field_internal_wrap');
        
        if (val === 'Penyaluran Internal') {
            relawanWrap.style.display = 'none';
            internalWrap.style.display = 'block';
        } else {
            relawanWrap.style.display = 'block';
            internalWrap.style.display = 'none';
        }
    }

    // Call on DOM content loaded to initialize field state
    document.addEventListener('DOMContentLoaded', function() {
        toggleJenisPenyaluran();
    });
</script>
<?= $this->endSection() ?>