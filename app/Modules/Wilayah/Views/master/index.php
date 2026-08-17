<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap');

.mg-wrap * { box-sizing: border-box; }
.mg-wrap {
    --bg: #F8FAFC;
    --card: #fff;
    --border: #E5E7EB;
    --border-strong: #D1D5DB;
    --text-1: #0F172A;
    --text-2: #475569;
    --text-3: #94A3B8;
    --blue: #2563EB;
    --blue-bg: #EFF6FF;
    --blue-text: #1D4ED8;
    --green: #16A34A;
    --green-bg: #F0FDF4;
    --green-text: #15803D;
    --red: #DC2626;
    --red-bg: #FEF2F2;
    --red-text: #B91C1C;
    --r: 16px;
    --r-sm: 10px;
    --shadow: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    font-family: 'Inter', system-ui, sans-serif;
    background: var(--bg);
    padding: 28px 24px;
    color: var(--text-1);
    min-height: 100vh;
}

/* ── Page header ── */
.mg-page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 20px;
}
.mg-page-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--text-1);
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 3px;
}
.mg-page-title i { color: var(--blue); font-size: 17px; }
.mg-page-sub { font-size: 13px; color: var(--text-2); }

/* ── Primary button ── */
.mg-btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    height: 36px;
    padding: 0 16px;
    border-radius: var(--r-sm);
    border: none;
    background: var(--blue);
    color: #fff;
    font-size: 13px;
    font-weight: 500;
    font-family: 'Inter', sans-serif;
    cursor: pointer;
    text-decoration: none;
    transition: opacity .12s;
    white-space: nowrap;
}
.mg-btn-primary:hover { opacity: .88; color: #fff; }
.mg-btn-primary i { font-size: 13px; }

/* ── Flash alerts ── */
.mg-alert {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 12px 16px;
    border-radius: var(--r-sm);
    font-size: 13px;
    margin-bottom: 16px;
    border: 1px solid;
}
.mg-alert.success {
    background: var(--green-bg);
    border-color: #BBF7D0;
    color: var(--green-text);
}
.mg-alert.danger {
    background: var(--red-bg);
    border-color: #FECACA;
    color: var(--red-text);
}
.mg-alert i { font-size: 15px; margin-top: 1px; flex-shrink: 0; }
.mg-alert ul { margin: 0; padding-left: 16px; }
.mg-alert-close {
    margin-left: auto;
    background: none;
    border: none;
    cursor: pointer;
    color: inherit;
    opacity: .6;
    font-size: 16px;
    line-height: 1;
    padding: 0;
    flex-shrink: 0;
}
.mg-alert-close:hover { opacity: 1; }

/* ── Card ── */
.mg-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--r);
    box-shadow: var(--shadow);
}
/* Tambahkan ini */
.mg-card .dataTables_wrapper .dataTables_length,
.mg-card .dataTables_wrapper .dataTables_filter,
.mg-card .dataTables_wrapper .dataTables_info,
.mg-card .dataTables_wrapper .dataTables_paginate {
    padding: 12px 16px;
    font-size: 13px;
    color: var(--text-2);
}
.mg-card .dataTables_wrapper .dataTables_length select,
.mg-card .dataTables_wrapper .dataTables_filter input {
    border: 1px solid var(--border-strong);
    border-radius: var(--r-sm);
    padding: 4px 8px;
    font-size: 13px;
    font-family: 'Inter', sans-serif;
    color: var(--text-1);
}
.mg-card .dataTables_wrapper .dataTables_filter input:focus {
    outline: none;
    border-color: var(--blue);
    box-shadow: 0 0 0 3px #DBEAFE;
}

/* ── Table ── */
.mg-overflow { overflow-x: auto; }
.mg-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}
.mg-table th {
    font-size: 10px;
    font-weight: 600;
    color: var(--text-3);
    text-transform: uppercase;
    letter-spacing: .05em;
    padding: 11px 16px;
    background: var(--bg);
    border-bottom: 1px solid var(--border);
    text-align: left;
    white-space: nowrap;
}
.mg-table th.right { text-align: right; }
.mg-table td {
    padding: 12px 16px;
    border-bottom: 1px solid var(--border);
    color: var(--text-1);
    vertical-align: middle;
}
.mg-table tr:last-child td { border-bottom: none; }
.mg-table tr:hover td { background: #FAFAFA; }

/* cells */
.mg-name { font-weight: 500; color: var(--text-1); }
.mg-phone { font-size: 11px; color: var(--text-3); margin-top: 2px; }
.mg-location { font-size: 13px; color: var(--text-2); }

/* badge */
.mg-badge {
    display: inline-flex;
    align-items: center;
    height: 22px;
    padding: 0 9px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
}
.mg-badge.green { background: var(--green-bg); color: var(--green-text); }
.mg-badge.red   { background: var(--red-bg);   color: var(--red-text); }

/* action buttons */
.mg-actions { display: flex; gap: 6px; justify-content: flex-end; }
.mg-icon-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border-radius: 8px;
    border: 1px solid var(--border-strong);
    background: var(--card);
    cursor: pointer;
    font-size: 13px;
    transition: background .12s;
}
.mg-icon-btn:hover { background: var(--bg); }
.mg-icon-btn.edit  { color: var(--blue); }
.mg-icon-btn.del   { color: var(--red); }

/* ── no row number col ── */
.mg-no { color: var(--text-3); font-size: 12px; width: 40px; }

/* ── Modal ── */
.mg-modal-backdrop {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(15,23,42,.35);
    z-index: 1050;
    align-items: center;
    justify-content: center;
    padding: 20px;
}
.mg-modal-backdrop.open { display: flex; }
.mg-modal {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--r);
    box-shadow: 0 8px 32px rgba(0,0,0,.12);
    width: 100%;
    max-width: 520px;
    max-height: 90vh;
    overflow-y: auto;
    animation: mgSlide .15s ease;
}
@keyframes mgSlide {
    from { opacity: 0; transform: translateY(-8px); }
    to   { opacity: 1; transform: translateY(0); }
}
.mg-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 22px 14px;
    border-bottom: 1px solid var(--border);
}
.mg-modal-title { font-size: 15px; font-weight: 600; color: var(--text-1); }
.mg-modal-close {
    background: none;
    border: none;
    cursor: pointer;
    color: var(--text-3);
    font-size: 18px;
    line-height: 1;
    padding: 2px;
    transition: color .12s;
}
.mg-modal-close:hover { color: var(--text-1); }
.mg-modal-body { padding: 20px 22px; }
.mg-modal-footer {
    padding: 14px 22px 18px;
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    border-top: 1px solid var(--border);
}

/* form elements */
.mg-form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
@media (max-width: 480px) { .mg-form-row { grid-template-columns: 1fr; } }
.mg-field { margin-bottom: 14px; }
.mg-field:last-child { margin-bottom: 0; }
.mg-label {
    display: block;
    font-size: 11px;
    font-weight: 600;
    color: var(--text-3);
    text-transform: uppercase;
    letter-spacing: .05em;
    margin-bottom: 5px;
}
.mg-input,
.mg-select,
.mg-textarea {
    width: 100%;
    border: 1px solid var(--border-strong);
    border-radius: var(--r-sm);
    padding: 0 11px;
    height: 36px;
    font-size: 13px;
    font-family: 'Inter', sans-serif;
    color: var(--text-1);
    background: #fff;
    transition: border-color .12s, box-shadow .12s;
}
.mg-textarea { height: auto; padding: 9px 11px; resize: vertical; }
.mg-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2394A3B8' stroke-width='2'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
    cursor: pointer;
}
.mg-input:focus,
.mg-select:focus,
.mg-textarea:focus {
    outline: none;
    border-color: var(--blue);
    box-shadow: 0 0 0 3px #DBEAFE;
}

/* modal footer buttons */
.mg-btn-cancel {
    display: inline-flex;
    align-items: center;
    height: 34px;
    padding: 0 14px;
    border-radius: var(--r-sm);
    border: 1px solid var(--border-strong);
    background: var(--bg);
    font-size: 13px;
    font-weight: 500;
    font-family: 'Inter', sans-serif;
    color: var(--text-2);
    cursor: pointer;
    transition: background .12s;
}
.mg-btn-cancel:hover { background: var(--border); }
.mg-btn-save {
    display: inline-flex;
    align-items: center;
    height: 34px;
    padding: 0 16px;
    border-radius: var(--r-sm);
    border: none;
    background: var(--blue);
    color: #fff;
    font-size: 13px;
    font-weight: 500;
    font-family: 'Inter', sans-serif;
    cursor: pointer;
    transition: opacity .12s;
}
.mg-btn-save:hover { opacity: .88; }
</style>

<div class="mg-wrap">

    <!-- ── Page header ── -->
    <div class="mg-page-header">
        <div>
            <div class="mg-page-title">
                <i class="fa-solid fa-building" aria-hidden="true"></i>
                Master Gudang Wilayah
            </div>
            <p class="mg-page-sub">Kelola data gudang wilayah Foodbank Indonesia</p>
        </div>
        <button class="mg-btn-primary" onclick="openModal('modalTambah')">
            <i class="fa-solid fa-plus" aria-hidden="true"></i> Tambah gudang
        </button>
    </div>

    <!-- ── Flash messages ── -->
    <?php if (session()->getFlashdata('success')): ?>
    <div class="mg-alert success" id="alertSuccess">
        <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
        <span><?= session()->getFlashdata('success') ?></span>
        <button class="mg-alert-close" onclick="this.parentElement.remove()" aria-label="Tutup">&times;</button>
    </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
    <div class="mg-alert danger" id="alertError">
        <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
        <ul>
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
        <button class="mg-alert-close" onclick="this.parentElement.remove()" aria-label="Tutup">&times;</button>
    </div>
    <?php endif; ?>

    <!-- ── Table card ── -->
     <!-- SESUDAH -->
    <div class="mg-card">
        <div class="mg-overflow">
            <table class="mg-table datatable" id="tblGudang">
                <thead>
                    <tr>
                        <th class="mg-no">#</th>
                        <th>Nama gudang</th>
                        <th>Lokasi</th>
                        <th>PIC</th>
                        <th>Status</th>
                        <th class="right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($gudang as $g): ?>
                    <tr>
                        <td class="mg-no"><?= $no++ ?></td>
                        <td>
                            <div class="mg-name"><?= esc($g['nama']) ?></div>
                            <div class="mg-phone"><?= esc($g['telepon']) ?></div>
                        </td>
                        <td class="mg-location"><?= esc($g['kota']) ?>, <?= esc($g['provinsi']) ?></td>
                        <td style="color:var(--text-2)"><?= esc($g['pic']) ?></td>
                        <td>
                            <span class="mg-badge <?= $g['status'] === 'Aktif' ? 'green' : 'red' ?>">
                                <?= esc($g['status']) ?>
                            </span>
                        </td>
                        <td>
                            <div class="mg-actions">
                                <button class="mg-icon-btn edit"
                                    onclick="openModal('modalEdit<?= $g['id'] ?>')"
                                    title="Edit gudang"
                                    aria-label="Edit <?= esc($g['nama']) ?>">
                                    <i class="fa-solid fa-pen" aria-hidden="true"></i>
                                </button>
                                <button class="mg-icon-btn del"
                                    onclick="confirmDelete('<?= site_url('wilayah/master/delete/'.$g['id']) ?>')"
                                    title="Hapus gudang"
                                    aria-label="Hapus <?= esc($g['nama']) ?>">
                                    <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

<!-- ════════════════════════════════
     MODAL TAMBAH
════════════════════════════════ -->
<div class="mg-modal-backdrop" id="modalTambah" role="dialog" aria-modal="true" aria-labelledby="titleTambah">
    <div class="mg-modal">
        <div class="mg-modal-header">
            <span class="mg-modal-title" id="titleTambah">Tambah gudang wilayah</span>
            <button class="mg-modal-close" onclick="closeModal('modalTambah')" aria-label="Tutup">&times;</button>
        </div>
        <form action="<?= site_url('wilayah/master/store') ?>" method="post">
            <?= csrf_field() ?>
            <div class="mg-modal-body">
                <div class="mg-field">
                    <label class="mg-label" for="tambah_nama">Nama gudang</label>
                    <input id="tambah_nama" type="text" class="mg-input" name="nama" placeholder="cth. GW Jakarta Selatan" required>
                </div>
                <div class="mg-form-row mg-field">
                    <div>
                        <label class="mg-label" for="tambah_provinsi">Provinsi</label>
                        <input id="tambah_provinsi" type="text" class="mg-input" name="provinsi" placeholder="DKI Jakarta" required>
                    </div>
                    <div>
                        <label class="mg-label" for="tambah_kota">Kota / kabupaten</label>
                        <input id="tambah_kota" type="text" class="mg-input" name="kota" placeholder="Jakarta Selatan" required>
                    </div>
                </div>
                <div class="mg-field">
                    <label class="mg-label" for="tambah_alamat">Alamat lengkap</label>
                    <textarea id="tambah_alamat" class="mg-textarea" name="alamat" rows="2" placeholder="Jl. Contoh No. 1, RT 01/02" required></textarea>
                </div>
                <div class="mg-form-row mg-field">
                    <div>
                        <label class="mg-label" for="tambah_pic">Penanggung jawab (PIC)</label>
                        <input id="tambah_pic" type="text" class="mg-input" name="pic" placeholder="Nama lengkap" required>
                    </div>
                    <div>
                        <label class="mg-label" for="tambah_telepon">No. telepon / WA</label>
                        <input id="tambah_telepon" type="text" class="mg-input" name="telepon" placeholder="08xxxxxxxxxx">
                    </div>
                </div>
                <div class="mg-field">
                    <label class="mg-label" for="tambah_status">Status</label>
                    <select id="tambah_status" class="mg-select" name="status">
                        <option value="Aktif">Aktif</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="mg-modal-footer">
                <button type="button" class="mg-btn-cancel" onclick="closeModal('modalTambah')">Batal</button>
                <button type="submit" class="mg-btn-save">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- ════════════════════════════════
     MODAL EDIT (per gudang)
════════════════════════════════ -->
<?php foreach ($gudang as $g): ?>
<div class="mg-modal-backdrop" id="modalEdit<?= $g['id'] ?>" role="dialog" aria-modal="true" aria-labelledby="titleEdit<?= $g['id'] ?>">
    <div class="mg-modal">
        <div class="mg-modal-header">
            <span class="mg-modal-title" id="titleEdit<?= $g['id'] ?>">Edit gudang wilayah</span>
            <button class="mg-modal-close" onclick="closeModal('modalEdit<?= $g['id'] ?>')" aria-label="Tutup">&times;</button>
        </div>
        <form action="<?= site_url('wilayah/master/update/'.$g['id']) ?>" method="post">
            <?= csrf_field() ?>
            <div class="mg-modal-body">
                <div class="mg-field">
                    <label class="mg-label">Nama gudang</label>
                    <input type="text" class="mg-input" name="nama" value="<?= esc($g['nama']) ?>" required>
                </div>
                <div class="mg-form-row mg-field">
                    <div>
                        <label class="mg-label">Provinsi</label>
                        <input type="text" class="mg-input" name="provinsi" value="<?= esc($g['provinsi']) ?>" required>
                    </div>
                    <div>
                        <label class="mg-label">Kota / kabupaten</label>
                        <input type="text" class="mg-input" name="kota" value="<?= esc($g['kota']) ?>" required>
                    </div>
                </div>
                <div class="mg-field">
                    <label class="mg-label">Alamat lengkap</label>
                    <textarea class="mg-textarea" name="alamat" rows="2" required><?= esc($g['alamat']) ?></textarea>
                </div>
                <div class="mg-form-row mg-field">
                    <div>
                        <label class="mg-label">Penanggung jawab (PIC)</label>
                        <input type="text" class="mg-input" name="pic" value="<?= esc($g['pic']) ?>" required>
                    </div>
                    <div>
                        <label class="mg-label">No. telepon / WA</label>
                        <input type="text" class="mg-input" name="telepon" value="<?= esc($g['telepon']) ?>">
                    </div>
                </div>
                <div class="mg-field">
                    <label class="mg-label">Status</label>
                    <select class="mg-select" name="status">
                        <option value="Aktif"    <?= $g['status'] === 'Aktif'    ? 'selected' : '' ?>>Aktif</option>
                        <option value="Nonaktif" <?= $g['status'] === 'Nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="mg-modal-footer">
                <button type="button" class="mg-btn-cancel" onclick="closeModal('modalEdit<?= $g['id'] ?>')">Batal</button>
                <button type="submit" class="mg-btn-save">Simpan perubahan</button>
            </div>
        </form>
    </div>
</div>
<?php endforeach; ?>

</div><!-- end .mg-wrap -->

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
/* ── Modal helpers ── */
function openModal(id) {
    document.getElementById(id).classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    document.getElementById(id).classList.remove('open');
    document.body.style.overflow = '';
}
/* Close on backdrop click */
document.querySelectorAll('.mg-modal-backdrop').forEach(function(backdrop) {
    backdrop.addEventListener('click', function(e) {
        if (e.target === backdrop) closeModal(backdrop.id);
    });
});
/* Close on Escape */
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.mg-modal-backdrop.open').forEach(function(m) {
            closeModal(m.id);
        });
    }
});

/* ── DataTable — pagination tetap Bootstrap bawaan ── */
$(document).ready(function() {
    $('#tblGudang').DataTable({
        dom: '<"d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3"lf><"table-responsive"rt><"d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3"ip>',
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
        }
    });
});

/* ── Confirm delete ── */
function confirmDelete(url) {
    Swal.fire({
        title: 'Hapus gudang?',
        text: 'Data yang dihapus tidak dapat dikembalikan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#64748B',
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal'
    }).then(function(result) {
        if (result.isConfirmed) window.location.href = url;
    });
}
</script>
<?= $this->endSection() ?>