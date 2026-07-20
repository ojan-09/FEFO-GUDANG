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

<form action="<?= $actionUrl ?>" method="POST" id="formBarangKeluar" class="loading-form" data-overlay="true">
    <?= csrf_field() ?>

    <!-- ── Informasi Transaksi card ── -->
    <div class="dm-card">
        <div class="dm-card__header">
            <h5 class="dm-card__title"><i class="fa-solid fa-file-lines"></i>Informasi Transaksi</h5>
        </div>
        <div class="dm-card__body">
            <!-- Row 1: Nomor | Tanggal | Tujuan -->
            <div class="dm-grid-3">
                <div>
                    <label class="dm-label">Nomor Transaksi</label>
                    <input type="text" class="dm-input readonly" value="<?= esc($nomor_transaksi) ?>" readonly>
                </div>
                <div>
                    <label for="tanggal_keluar" class="dm-label">Tanggal Keluar <span class="text-danger">*</span></label>
                    <input type="date" class="dm-input" id="tanggal_keluar" name="tanggal_keluar"
                           value="<?= esc($valTanggalKeluar) ?>" required>
                </div>
                <div>
                    <label for="tujuan_penyaluran" class="dm-label">Program / Tujuan Penyaluran <span class="text-danger">*</span></label>
                    <input type="text" class="dm-input" id="tujuan_penyaluran" name="tujuan_penyaluran"
                           placeholder="Contoh: Penyaluran Ramadhan 2026"
                           value="<?= esc($valTujuanPenyaluran) ?>" required>
                </div>
            </div>
            <!-- Row 2: Wilayah | Keterangan -->
            <div class="dm-grid-2">
                <div>
                    <label for="id_wilayah" class="dm-label">Wilayah Tujuan <span class="text-danger">*</span></label>
                    <select class="dm-select" id="id_wilayah" name="id_wilayah" required>
                        <option value="">-- Pilih Wilayah --</option>
                        <?php foreach ($wilayah as $w) : ?>
                            <option value="<?= $w['id'] ?>" <?= $valIdWilayah == $w['id'] ? 'selected' : '' ?>>
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
<!-- JS tidak diubah sama sekali -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Data barang dari server (dengan stok tersedia)
    const dataBarang = <?= json_encode($barang) ?>;
    const oldDetails = <?= json_encode($details ?? []) ?>;
    let rowCount = 0;

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

    function formatTglExp(dateStr) {
        if (!dateStr) return '-';
        const date = new Date(dateStr);
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
        return `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
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
            
            const optionText = `${b.nama_barang} (Exp: ${tglFormatted} • ${kemasanAsli} • ${beratKemasan} • Stok: ${parseFloat(b.stok_tersedia)} ${unitTersedia} • ${statusLabel})`;

            options += `<option value="${b.id}"
                data-satuan="${b.satuan}"
                data-satuan-kemasan="${kemasanAsli}"
                data-berat="${b.berat_per_satuan}"
                data-satuan-berat="${b.satuan_berat}"
                data-bisa-dipecah="${b.bisa_dipecah}"
                data-stok="${b.stok_tersedia}" ${isSelected}>
                ${optionText}
            </option>`;
        });

        const row = `
            <tr id="row-${rowCount}">
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
                           min="1" placeholder="0" value="${detail ? detail.jumlah_keluar : ''}" required>
                </td>
                <td class="tc-berat">
                    <input type="text" class="dm-input readonly field-berat" readonly value="-" style="text-align:center;">
                </td>
                <td class="tc-aksi">
                    <button type="button" class="btn btn-outline-danger btn-hapus-row" data-row="row-${rowCount}">
                        <i class="fa-solid fa-xmark" style="pointer-events:none;"></i>
                    </button>
                </td>
            </tr>
        `;
        document.getElementById('tbodyItem').insertAdjacentHTML('beforeend', row);
        updateNomor();
        
        if (detail) {
            const select = document.querySelector(`#row-${rowCount} .select-barang`);
            select.dispatchEvent(new Event('change', { bubbles: true }));
            const inputJumlah = document.querySelector(`#row-${rowCount} .input-jumlah`);
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

            if (!e.target.value) {
                row.querySelector('.field-satuan').value = '-';
                row.querySelector('.field-stok').value = '-';
                row.querySelector('.field-berat').value = '-';
                const inputJumlah = row.querySelector('.input-jumlah');
                inputJumlah.value = '';
                inputJumlah.min = "1";
                inputJumlah.step = "1";
                inputJumlah.placeholder = "0";
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
            const inputJumlah = row.querySelector('.input-jumlah');

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
            const jumlah = parseFloat(row.querySelector('.input-jumlah').value) || 0;
            const stok = parseFloat(row.querySelector('.field-stok').value) || 0;
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
    });

    if (oldDetails.length > 0) { oldDetails.forEach(d => tambahBaris(d)); } else { tambahBaris(); }
</script>
<?= $this->endSection() ?>