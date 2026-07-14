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
                'nama_barang' => $b['nama_barang'],
                'kategori' => $b['kategori'],
                'jumlah' => $b['jumlah_awal'],
                'satuan' => $b['satuan'],
                'berat_per_satuan' => $b['berat_per_satuan'],
                'satuan_berat' => $b['satuan_berat'],
                'tanggal_kedaluwarsa' => $b['tanggal_kedaluwarsa'],
            ];
        }
    } elseif (!$oldItems) {
        $oldItems = [];
    }

    $valTanggalMasuk = old('tanggal_masuk', $isEdit ? $barangMasuk['tanggal_masuk'] : date('Y-m-d'));
    $valIdDonatur = old('id_donatur', $isEdit ? $barangMasuk['id_donatur'] : '');
    $valEta = old('eta', $isEdit ? $barangMasuk['eta'] : '');
    $valKeterangan = old('keterangan', $isEdit ? $barangMasuk['keterangan'] : '');
?>

<style>
    .topbar {
        padding: 12px 18px;
    }
    .topbar .page-title {
        font-size: 1.15rem;
    }
    .topbar .subtle {
        font-size: 0.8rem;
    }
    .panel-card {
        padding: 14px 16px;
    }
    .panel-title {
        font-size: 1rem;
        font-weight: bold;
    }

    /* header form */
    .panel-card .form-label {
        font-size: 0.78rem;
        margin-bottom: 0.25rem;
    }
    .panel-card .form-control,
    .panel-card .form-select {
        font-size: 0.82rem;
        height: 32px;
        padding: 0.3rem 0.6rem;
    }
    .panel-card textarea.form-control {
        height: auto;
        min-height: 32px;
    }

    /* tabel item */
    #tabelItem thead th {
        font-size: 0.68rem;
        padding: 0.45rem 0.5rem;
    }
    #tabelItem tbody td {
        font-size: 0.78rem;
        padding: 0.35rem 0.4rem;
        vertical-align: middle;
    }
    #tabelItem .form-control,
    #tabelItem .form-select {
        font-size: 0.78rem;
        height: 32px;
        padding: 0.25rem 0.5rem;
    }
    #tabelItem .btn-hapus-row {
        width: 28px;
        height: 28px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.72rem;
    }
</style>



<div class="topbar d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <div>
        <h1 class="page-title mb-1"><i class="fa-solid fa-hand-holding-heart me-2"></i><?= esc($title) ?></h1>
        <span class="subtle">Catat seluruh barang yang diterima dari donatur</span>
    </div>
    <a href="<?= site_url('transaksi/barang-masuk') ?>" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<!-- sisanya tidak diubah sama sekali -->
<?php if (session()->getFlashdata('errors')) : ?>
    <div class="alert alert-danger rounded-3" style="font-size:0.9rem;">
        <i class="fa-solid fa-triangle-exclamation me-2"></i><strong>Terjadi Kesalahan:</strong>
        <ul class="mb-0 mt-1">
            <?php foreach (session()->getFlashdata('errors') as $err) : ?>
                <li><?= esc($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?= site_url('transaksi/barang-masuk/store') ?>" method="POST" id="formDonasiMasuk" novalidate>
    <?= csrf_field() ?>

    <div class="panel-card mb-3 p-3">
        <h5 class="panel-title mb-3" style="font-size:1rem;"><i class="fa-solid fa-file-lines me-2"></i>Header</h5>
        <div class="row g-2">
            <div class="col-md-4">
                <div class="mb-2">
                    <label class="form-label fw-semibold mb-1" style="font-size: 12px;">Nomor Transaksi</label>
                    <input type="text" class="form-control form-control-sm" value="<?= esc($nomor_transaksi) ?>" readonly style="background: #f1f5f9; height: 36px;">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-2">
                    <label for="tanggal_masuk" class="form-label fw-semibold mb-1" style="font-size: 12px;">Tanggal Masuk <span class="text-danger">*</span></label>
                    <input type="date" class="form-control form-control-sm" id="tanggal_masuk" name="tanggal_masuk" value="<?= old('tanggal_masuk', date('Y-m-d')) ?>" style="height: 36px;" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-2">
                    <label for="id_donatur" class="form-label fw-semibold mb-1" style="font-size: 12px;">Donatur <span class="text-danger">*</span></label>
                    <select class="form-select form-select-sm" id="id_donatur" name="id_donatur" style="height: 36px;" required>
                        <option value="">-- Pilih Donatur --</option>
                        <?php foreach ($donatur as $d) : ?>
                            <option value="<?= $d['id'] ?>" <?= $valIdDonatur == $d['id'] ? 'selected' : '' ?>><?= esc($d['nama_donatur']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row g-2">
            <div class="col-md-4">
                <div class="mb-0">
                    <label for="eta" class="form-label fw-semibold mb-1" style="font-size: 12px;">Estimasi Kedatangan</label>
                    <input type="date" class="form-control form-control-sm" id="eta" name="eta" value="<?= old('eta') ?>" style="height: 36px;">
                </div>
            </div>
            <div class="col-md-8">
                <div class="mb-0">
                    <label for="keterangan" class="form-label fw-semibold mb-1" style="font-size: 12px;">Keterangan</label>
                    <textarea class="form-control form-control-sm" id="keterangan" name="keterangan" rows="1" placeholder="Opsional" style="min-height: 36px; height: 36px; resize: none;"><?= esc($valKeterangan) ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="panel-card mb-3 p-0 overflow-hidden">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center p-3">
            <h5 class="panel-title mb-0" style="font-size:1rem; font-weight: bold;"><i class="fa-solid fa-boxes-stacked me-2"></i>Detail Barang</h5>
            <button type="button" class="btn btn-success rounded-pill px-4 text-white" id="btnTambahItem" style="height: 38px; display: flex; align-items: center; font-weight: 500;">
                <i class="fa-solid fa-plus me-1"></i> Tambah Item
            </button>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0 w-100" id="tabelItem">
                    <thead class="table-light">
                        <tr class="fw-bold text-secondary" style="font-size: 0.8rem;">
                            <th class="text-center py-3" style="width: 4%;">NO</th>
                            <th class="py-3" style="width: 20%;">NAMA BARANG <span class="text-danger">*</span></th>
                            <th class="py-3" style="width: 14%;">KATEGORI <span class="text-danger">*</span></th>
                            <th class="py-3" style="width: 9%;">CTN <br><small class="text-muted fw-normal" style="font-size: 0.7rem;">(OPSIONAL)</small></th>
                            <th class="py-3" style="width: 9%;">JUMLAH <span class="text-danger">*</span></th>
                            <th class="py-3" style="width: 10%;">SATUAN <span class="text-danger">*</span></th>
                            <th class="py-3" style="width: 10%;">BERAT/SAT. <span class="text-danger">*</span></th>
                            <th class="py-3" style="width: 10%;">SAT. BERAT <span class="text-danger">*</span></th>
                            <th class="py-3" style="width: 10%;">EXPIRED <span class="text-danger">*</span></th>
                            <th class="text-center py-3" style="width: 4%;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyItem"></tbody>
                </table>
            </div>
            <div id="errorItem" class="text-danger mt-2" style="display:none; font-size:0.85rem;">
                <i class="fa-solid fa-circle-exclamation me-1"></i> Minimal harus ada 1 barang.
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 mb-3">
        <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4" id="btnSimpan">
            <i class="fa-solid fa-save me-1"></i> Simpan Transaksi
        </button>
        <a href="<?= site_url('transaksi/barang-masuk') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-4">Batal</a>
    </div>
</form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const kategoriList = <?= json_encode($kategori ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
    const oldItems = <?= json_encode(array_values($oldItems), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
    const satuanOptions = ['Box', 'Dus', 'Pcs', 'Karung', 'Botol', 'Pack', 'Tray', 'Kaleng', 'Pouch', 'Sak'];
    const satuanBeratOptions = ['Gram', 'Kg'];
    const namaBarangPlaceholders = ['Beras Premium', 'Nasi Box Ayam', 'Brownies', 'Air Mineral'];
    let rowCount = 0;

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>'"]/g, char => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            "'": '&#039;',
            '"': '&quot;'
        }[char]));
    }

    function selectOptions(values, selectedValue) {
        const selected = String(selectedValue ?? '');
        let options = '<option value="">-- Pilih --</option>';
        values.forEach(value => {
            const safeValue = escapeHtml(value);
            const isSelected = selected === value ? 'selected' : '';
            options += `<option value="${safeValue}" ${isSelected}>${safeValue}</option>`;
        });
        return options;
    }

    function kategoriOptionsSelected(selectedValue) {
        const selected = String(selectedValue ?? '');
        let options = '<option value="">-- Pilih --</option>';
        kategoriList.forEach(k => {
            const nama = String(k.nama_kategori ?? '');
            const safeNama = escapeHtml(nama);
            const isSelected = selected === nama ? 'selected' : '';
            options += `<option value="${safeNama}" ${isSelected}>${safeNama}</option>`;
        });
        return options;
    }

    function formatNumber(value) {
        const rounded = Math.round((value + Number.EPSILON) * 100) / 100;
        return rounded.toLocaleString('id-ID', { maximumFractionDigits: 2 });
    }

    function tambahBaris(item = {}, shouldFocus = true) {
        rowCount++;
        const placeholder = namaBarangPlaceholders[(rowCount - 1) % namaBarangPlaceholders.length];
        const row = `
            <tr id="row-${rowCount}">
                <td class="text-center row-number text-secondary fw-semibold">${rowCount}</td>
                <td><input type="text" class="form-control input-nama-barang" name="items[${rowCount}][nama_barang]" maxlength="150" placeholder="Contoh: ${escapeHtml(placeholder)}" value="${escapeHtml(item.nama_barang)}" required style="height: 38px;"></td>
                <td>
                    <select class="form-select" name="items[${rowCount}][kategori]" required style="height: 38px;">
                        ${kategoriOptionsSelected(item.kategori)}
                    </select>
                </td>
                <td><input type="number" class="form-control" name="items[${rowCount}][jumlah_ctn]" min="0" step="1" value="${escapeHtml(item.jumlah_ctn)}" placeholder="Opsional" style="height: 38px;"></td>
                <td><input type="number" class="form-control input-jumlah" name="items[${rowCount}][jumlah]" min="1" step="1" value="${escapeHtml(item.jumlah)}" required style="height: 38px;"></td>
                <td>
                    <select class="form-select" name="items[${rowCount}][satuan]" required style="height: 38px;">
                        ${selectOptions(satuanOptions, item.satuan)}
                    </select>
                </td>
                <td><input type="number" class="form-control input-berat" name="items[${rowCount}][berat_per_satuan]" min="0.01" step="0.01" value="${escapeHtml(item.berat_per_satuan)}" placeholder="0.5" required style="height: 38px;"></td>
                <td>
                    <select class="form-select" name="items[${rowCount}][satuan_berat]" required style="height: 38px;">
                        ${selectOptions(satuanBeratOptions, item.satuan_berat)}
                    </select>
                </td>
                <td><input type="date" class="form-control input-expired" name="items[${rowCount}][tanggal_kedaluwarsa]" value="${escapeHtml(item.tanggal_kedaluwarsa)}" required style="height: 38px;"></td>
                <td class="text-center">
                    <button type="button" class="btn btn-outline-danger rounded-circle btn-hapus-row d-flex align-items-center justify-content-center mx-auto" style="width: 32px; height: 32px; padding: 0;" data-row="row-${rowCount}">
                        <i class="fa-solid fa-xmark" style="pointer-events: none;"></i>
                    </button>
                </td>
            </tr>
        `;
        document.getElementById('tbodyItem').insertAdjacentHTML('beforeend', row);
        updateNomor();

        if (shouldFocus) {
            document.querySelector(`#row-${rowCount} .input-nama-barang`).focus();
        }
    }

    function updateNomor() {
        document.querySelectorAll('#tbodyItem tr').forEach((tr, i) => {
            tr.querySelector('.row-number').textContent = i + 1;
        });
    }

    let isFormSubmitted = false;

    function validateField(field) {
        if (!field.willValidate || field.disabled || field.readOnly) return true;

        let valid = field.checkValidity();

        if (field.classList.contains('input-jumlah') && parseFloat(field.value) < 1) {
            valid = false;
        }
        if (field.classList.contains('input-berat') && parseFloat(field.value) <= 0) {
            valid = false;
        }
        if (field.classList.contains('input-expired')) {
            const tanggalMasuk = document.getElementById('tanggal_masuk').value;
            if (tanggalMasuk && field.value && field.value <= tanggalMasuk) {
                valid = false;
            }
        }

        if (valid) {
            field.classList.remove('is-invalid');
        } else {
            field.classList.add('is-invalid');
        }

        return valid;
    }

    const form = document.getElementById('formDonasiMasuk');

    form.addEventListener('blur', function(e) {
        if (e.target.matches('input, select, textarea')) {
            e.target.dataset.touched = 'true';
            validateField(e.target);
        }
    }, true);

    form.addEventListener('input', function(e) {
        if (e.target.matches('input, select, textarea')) {
            if (e.target.dataset.touched === 'true' || isFormSubmitted) {
                validateField(e.target);
            }
        }
    });

    form.addEventListener('change', function(e) {
        if (e.target.matches('input, select, textarea')) {
            if (e.target.id === 'tanggal_masuk') {
                document.querySelectorAll('.input-expired').forEach(el => {
                    if (el.dataset.touched === 'true' || isFormSubmitted) {
                        validateField(el);
                    }
                });
            }
            if (e.target.dataset.touched === 'true' || isFormSubmitted) {
                validateField(e.target);
            }
        }
    });

    document.getElementById('btnTambahItem').addEventListener('click', () => tambahBaris());

    document.getElementById('tbodyItem').addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-hapus-row');
        if (btn) {
            document.getElementById(btn.dataset.row).remove();
            updateNomor();
            if (isFormSubmitted) {
                const rows = document.querySelectorAll('#tbodyItem tr');
                const errorDiv = document.getElementById('errorItem');
                if (rows.length === 0) {
                    errorDiv.innerHTML = '<i class="fa-solid fa-circle-exclamation me-1"></i> Minimal harus ada 1 barang.';
                    errorDiv.style.display = 'block';
                } else if (!form.querySelector('.is-invalid')) {
                    errorDiv.style.display = 'none';
                }
            }
        }
    });

    form.addEventListener('submit', function (e) {
        isFormSubmitted = true;
        let isFormValid = true;

        const allFields = form.querySelectorAll('input, select, textarea');
        allFields.forEach(field => {
            field.dataset.touched = 'true';
            if (!validateField(field)) {
                isFormValid = false;
            }
        });

        const rows = document.querySelectorAll('#tbodyItem tr');
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
        }
    });

    if (oldItems.length > 0) {
        oldItems.forEach(item => tambahBaris(item, false));
    } else {
        tambahBaris({}, false);
    }
</script>
<?= $this->endSection() ?>
