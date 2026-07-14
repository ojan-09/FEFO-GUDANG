<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php
    $isEdit = isset($barangKeluar);
    $actionUrl = $isEdit ? site_url('transaksi/barang-keluar/update/' . $barangKeluar['id']) : site_url('transaksi/barang-keluar/store');
    
    $valTanggalKeluar = old('tanggal_keluar', $isEdit ? $barangKeluar['tanggal_keluar'] : date('Y-m-d'));
    $valTujuanPenyaluran = old('tujuan_penyaluran', $isEdit ? $barangKeluar['tujuan_penyaluran'] : '');
    $valIdWilayah = old('id_wilayah', $isEdit ? $barangKeluar['id_wilayah'] : '');
    $valKeterangan = old('keterangan', $isEdit ? $barangKeluar['keterangan'] : '');
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
    .panel-card .form-label {
        font-size: 0.78rem;
        margin-bottom: 0.25rem;
    }
    .panel-card .form-control,
    .panel-card .form-select {
        font-size: 0.85rem;
        padding: 0.4rem 0.65rem;
        height: 36px;
    }
    .panel-card textarea.form-control {
        height: auto;
    }
    .panel-card .btn {
        font-size: 0.82rem;
        padding: 0.4rem 0.9rem;
    }
    .panel-title {
        font-size: 1rem;
        font-weight: bold;
    }
    #tabelItem thead th {
        font-size: 0.72rem;
        padding: 0.55rem 0.6rem;
    }
    #tabelItem tbody td {
        font-size: 0.8rem;
        padding: 0.45rem 0.6rem;
        vertical-align: middle;
    }
    #tabelItem .btn-sm {
        width: 28px;
        height: 28px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.72rem;
    }
</style>

<!-- Topbar -->
<div class="topbar d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-arrow-up me-2"></i><?= $title ?></h1>
        <span class="subtle">Keluarkan barang dari gudang menggunakan metode FEFO</span>
    </div>
    <a href="<?= site_url('transaksi/barang-keluar') ?>" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<!-- Validation Errors -->
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

<form action="<?= $actionUrl ?>" method="POST" id="formBarangKeluar">
    <?= csrf_field() ?>

    <div class="panel-card mb-3">
        <h5 class="panel-title mb-3"><i class="fa-solid fa-file-lines me-2"></i>Informasi Transaksi</h5>
        <div class="row g-2">
            <div class="col-md-4">
                <div class="mb-2">
                    <label class="form-label fw-semibold">Nomor Transaksi</label>
                    <input type="text" class="form-control" value="<?= esc($nomor_transaksi) ?>" readonly style="background: #f1f5f9;">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-2">
                    <label for="tanggal_keluar" class="form-label fw-semibold">Tanggal Keluar <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="tanggal_keluar" name="tanggal_keluar" value="<?= esc($valTanggalKeluar) ?>" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-2">
                    <label for="tujuan_penyaluran" class="form-label fw-semibold">Program / Tujuan Penyaluran <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="tujuan_penyaluran" name="tujuan_penyaluran" placeholder="Contoh: Penyaluran Ramadhan 2026" value="<?= esc($valTujuanPenyaluran) ?>" required>
                </div>
            </div>
        </div>
        <div class="row g-2">
            <div class="col-md-6">
                <div class="mb-0">
                    <label for="id_wilayah" class="form-label fw-semibold">Wilayah Tujuan <span class="text-danger">*</span></label>
                    <select class="form-select" id="id_wilayah" name="id_wilayah" required>
                        <option value="">-- Pilih Wilayah --</option>
                        <?php foreach ($wilayah as $w) : ?>
                            <option value="<?= $w['id'] ?>" <?= $valIdWilayah == $w['id'] ? 'selected' : '' ?>><?= esc($w['nama_wilayah']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-0">
                    <label for="keterangan" class="form-label fw-semibold">Keterangan</label>
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="1" placeholder="Opsional" style="min-height: 36px; resize: none;"><?= esc($valKeterangan) ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="panel-card mb-3 p-0 overflow-hidden">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center p-3">
            <h5 class="panel-title mb-0"><i class="fa-solid fa-boxes-stacked me-2"></i>Daftar Barang Keluar</h5>
            <button type="button" class="btn btn-success rounded-pill px-4" id="btnTambahItem" style="height: 36px; display: flex; align-items: center;">
                <i class="fa-solid fa-plus me-1"></i> Tambah Item
            </button>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0" id="tabelItem">
                    <thead class="table-light text-center">
                        <tr>
                            <th width="3%">No</th>
                            <th>Barang <span class="text-danger">*</span></th>
                            <th width="12%">Satuan</th>
                            <th width="12%">Stok Tersedia</th>
                            <th width="12%">Jumlah Keluar <span class="text-danger">*</span></th>
                            <th width="15%">Berat</th>
                            <th width="5%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyItem"></tbody>
                </table>
            </div>
            <div id="errorItem" class="text-danger mt-2" style="display:none; font-size:0.85rem;">
                <i class="fa-solid fa-circle-exclamation me-1"></i> <span id="errorItemMsg">Minimal harus ada 1 barang.</span>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 mb-3">
        <button type="submit" class="btn btn-primary rounded-pill px-4" id="btnSimpan">
            <i class="fa-solid fa-save me-1"></i> Simpan Transaksi
        </button>
        <a href="<?= site_url('transaksi/barang-keluar') ?>" class="btn btn-outline-secondary rounded-pill px-4">Batal</a>
    </div>
</form>

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

    function tambahBaris(detail = null) {
        rowCount++;
        let options = '<option value="">-- Pilih Barang --</option>';
        dataBarang.forEach(b => {
            const isSelected = detail && detail.id_barang == b.id ? 'selected' : '';
            options += `<option value="${b.id}"
                data-satuan="${b.satuan}"
                data-berat="${b.berat_per_satuan}"
                data-satuan-berat="${b.satuan_berat}"
                data-stok="${b.stok_tersedia}" ${isSelected}>
                ${b.nama_barang} (Stok: ${b.stok_tersedia})
            </option>`;
        });

        const row = `
            <tr id="row-${rowCount}">
                <td class="text-center row-number">${rowCount}</td>
                <td>
                    <select class="form-select form-select-sm select-barang" name="items[${rowCount}][id_barang]" required>
                        ${options}
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm field-satuan" readonly style="background:#f1f5f9;" value="-">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm field-stok text-center fw-bold" readonly style="background:#f1f5f9;" value="-">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm input-jumlah" name="items[${rowCount}][jumlah_keluar]" min="1" placeholder="0" value="${detail ? detail.jumlah_keluar : ''}" required>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm field-berat" readonly style="background:#f1f5f9;" value="-">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-row" data-row="row-${rowCount}">
                        <i class="fa-solid fa-xmark" style="pointer-events: none;"></i>
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

            let satuan = selected.dataset.satuan;
            if (!satuan || satuan === 'undefined' || satuan === 'null' || satuan.trim() === '') satuan = '-';
            
            let stok = parseInt(selected.dataset.stok) || 0;
            let berat = parseFloat(selected.dataset.berat) || 0;
            let satuanBerat = selected.dataset.satuanBerat;
            if (!satuanBerat || satuanBerat === 'undefined' || satuanBerat === 'null' || satuanBerat.trim() === '') satuanBerat = '';

            row.querySelector('.field-satuan').value = satuan;
            row.querySelector('.field-stok').value = stok;
            
            if (e.isTrusted) {
                row.querySelector('.input-jumlah').value = '';
                row.querySelector('.field-berat').value = '-';
            }
            row.querySelector('.input-jumlah').max = stok;
            
            const inputJumlah = row.querySelector('.input-jumlah');
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
            const satuanBerat = selected.dataset.satuanBerat || '';
            const stok = parseInt(selected.dataset.stok) || 0;
            const jumlah = parseInt(e.target.value) || 0;

            const totalBerat = jumlah * berat;
            row.querySelector('.field-berat').value = totalBerat > 0 ? `${totalBerat} ${satuanBerat}` : '-';

            if (jumlah > stok) {
                e.target.classList.add('is-invalid');
                Swal.fire({
                    icon: 'warning',
                    title: 'Stok Tidak Cukup!',
                    text: `Stok tersedia hanya ${stok}. Anda memasukkan ${jumlah}.`,
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
            const jumlah = parseInt(row.querySelector('.input-jumlah').value) || 0;
            const stok = parseInt(row.querySelector('.field-stok').value) || 0;
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