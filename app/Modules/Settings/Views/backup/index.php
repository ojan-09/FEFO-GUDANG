<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<style>
    :root {
        --wh-bg: #F8FAFC; --wh-card: #FFFFFF; --wh-border: #E5E7EB;
        --wh-primary: #2563EB; --wh-primary-soft: #EFF6FF;
        --wh-success: #16A34A; --wh-success-soft: #DCFCE7;
        --wh-danger: #DC2626; --wh-danger-soft: #FEE2E2;
        --wh-text: #111827; --wh-text-soft: #6B7280;
    }
    .wh-page { background: var(--wh-bg); margin: -1.5rem -1.5rem 0 -1.5rem; padding: 24px; min-height: 100vh; }

    /* Stats */
    .stat-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px; margin-bottom: 24px; }
    .stat-card { background: #fff; border: 1px solid var(--wh-border); border-radius: 16px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
    .stat-val { font-size: 1.6rem; font-weight: 800; color: var(--wh-text); line-height: 1.2; }
    .stat-lbl { font-size: 0.75rem; font-weight: 700; color: var(--wh-text-soft); text-transform: uppercase; letter-spacing: 0.05em; margin-top: 4px; }

    /* Action Cards */
    .action-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px; }
    .act-card { background: #fff; border: 1px solid var(--wh-border); border-radius: 18px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
    .act-card h3 { font-size: 1.1rem; font-weight: 700; margin: 0 0 16px 0; display: flex; align-items: center; gap: 8px; }

    .wh-btn { padding: 10px 20px; border-radius: 10px; font-size: 0.9rem; font-weight: 600; cursor: pointer; border: none; display: inline-flex; align-items: center; justify-content: center; gap: 8px; transition: 0.2s; text-decoration: none; }
    .wh-btn-primary { background: var(--wh-primary); color: #fff; }
    .wh-btn-primary:hover { background: #1D4ED8; color: #fff; }
    .wh-btn-primary:disabled { background: #93C5FD; cursor: not-allowed; }
    .wh-btn-outline { background: #fff; border: 1px solid var(--wh-border); color: var(--wh-text); }
    .wh-btn-outline:hover { background: #F1F5F9; color: var(--wh-text); }
    .wh-btn-success { background: var(--wh-success-soft); color: var(--wh-success); }
    .wh-btn-success:hover { background: #BBF7D0; color: var(--wh-success); }
    .wh-btn-danger { background: var(--wh-danger-soft); color: var(--wh-danger); }
    .wh-btn-danger:hover { background: #FECACA; color: var(--wh-danger); }

    /* Tabs & Table */
    .wh-tabs { display: flex; gap: 8px; border-bottom: 1px solid var(--wh-border); margin-bottom: 16px; }
    .wh-tab { padding: 12px 20px; font-weight: 600; font-size: 0.9rem; color: var(--wh-text-soft); cursor: pointer; border-bottom: 2px solid transparent; transition: 0.2s; }
    .wh-tab.active { color: var(--wh-primary); border-bottom: 2px solid var(--wh-primary); }
    .wh-tab:hover:not(.active) { color: var(--wh-text); }

    .wh-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .wh-table th { background: #F8FAFC; color: var(--wh-text-soft); font-size: 0.75rem; font-weight: 700; text-transform: uppercase; padding: 12px 16px; border-bottom: 1px solid var(--wh-border); text-align: left; }
    .wh-table td { padding: 16px; border-bottom: 1px solid var(--wh-border); font-size: 0.9rem; vertical-align: middle; }
    .wh-table tr:hover td { background: #F8FAFC; }

    .badge-latest { background: var(--wh-success-soft); color: var(--wh-success); font-size: 0.7rem; font-weight: 700; padding: 2px 8px; border-radius: 6px; margin-left: 8px; }
    .badge-old { background: #F1F5F9; color: var(--wh-text-soft); font-size: 0.7rem; font-weight: 700; padding: 2px 8px; border-radius: 6px; margin-left: 8px; }

    /* File Input */
    .file-drop-area { border: 2px dashed var(--wh-border); border-radius: 12px; padding: 24px; text-align: center; background: #F8FAFC; transition: 0.2s; cursor: pointer; }
    .file-drop-area:hover { border-color: var(--wh-primary); background: var(--wh-primary-soft); }
    .file-drop-area i { font-size: 2rem; color: var(--wh-text-soft); margin-bottom: 8px; }
    .file-drop-area span { display: block; font-size: 0.9rem; font-weight: 600; color: var(--wh-text); }

    /* Loading Overlay */
    .restore-overlay { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(15,23,42,0.8); z-index: 9999; display: none; align-items: center; justify-content: center; flex-direction: column; color: #fff; }
    .restore-spinner { width: 60px; height: 60px; border: 4px solid rgba(255,255,255,0.2); border-top-color: #fff; border-radius: 50%; animation: spin 1s linear infinite; margin-bottom: 24px; }
    .restore-title { font-size: 1.5rem; font-weight: 800; margin-bottom: 8px; }
    .restore-desc { font-size: 1rem; color: #CBD5E1; }
    .overlay-close-btn { margin-top: 24px; padding: 8px 20px; border-radius: 10px; background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.3); cursor: pointer; font-size: 0.85rem; font-weight: 600; display: none; }
    .overlay-close-btn:hover { background: rgba(255,255,255,0.25); }

    @keyframes spin { 100% { transform: rotate(360deg); } }
</style>

<div class="wh-page">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 800; margin:0;">Backup & Restore</h1>
            <p style="color: var(--wh-text-soft); margin:0;">Manajemen data operasional FEFO Gudang</p>
        </div>
    </div>

    <!-- DB Stats -->
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-val"><?= esc($dbInfo['name']) ?></div>
            <div class="stat-lbl">Database</div>
        </div>
        <div class="stat-card">
            <div class="stat-val"><?= esc($dbInfo['size_mb']) ?> <span style="font-size:0.9rem">MB</span></div>
            <div class="stat-lbl">Ukuran Database</div>
        </div>
        <div class="stat-card">
            <div class="stat-val"><?= esc($dbInfo['tables']) ?></div>
            <div class="stat-lbl">Jumlah Tabel</div>
        </div>
        <div class="stat-card">
            <div class="stat-val"><?= esc($totalFiles) ?> <span style="font-size:0.9rem">(<?= esc($folderSizeMb) ?> MB)</span></div>
            <div class="stat-lbl">Total Backup</div>
        </div>
        <div class="stat-card">
            <div class="stat-val" style="font-size:1.2rem; margin-top:8px;"><?= esc($lastBackup) ?></div>
            <div class="stat-lbl">Backup Terakhir</div>
        </div>
    </div>

    <!-- Actions -->
    <div class="action-grid">
        <!-- Card Backup -->
        <div class="act-card">
            <h3><i class="fa-solid fa-download text-primary"></i> Buat Backup Baru</h3>
            <p style="font-size:0.9rem; color:var(--wh-text-soft); margin-bottom:16px;">
                Amankan seluruh struktur dan data aplikasi ke dalam file terenkripsi SHA256 secara real-time.
            </p>
            <form action="<?= site_url('pengaturan/backup/doBackup') ?>" method="post" id="formBackup">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label style="font-size:0.8rem; font-weight:600;">Keterangan / Backup Notes (Opsional)</label>
                    <input type="text" name="notes" class="form-control" maxlength="150"
                           placeholder="Misal: Backup bulanan sebelum update v2..." style="border-radius:10px;">
                </div>
                <button type="submit" class="wh-btn wh-btn-primary w-100" id="btnBackup">
                    <i class="fa-solid fa-cloud-arrow-down"></i> Eksekusi Backup Sekarang
                </button>
            </form>
        </div>

        <!-- Card Restore dari Upload -->
        <div class="act-card">
            <h3><i class="fa-solid fa-upload text-danger"></i> Restore dari Komputer</h3>
            <p style="font-size:0.9rem; color:var(--wh-text-soft); margin-bottom:16px;">
                Unggah file <code>.sql</code> untuk memulihkan seluruh data. Peringatan: Data saat ini akan ditimpa!
            </p>
            <form action="<?= site_url('pengaturan/backup/restore') ?>" method="post"
                  enctype="multipart/form-data" id="formRestoreUp">
                <?= csrf_field() ?>
                <label class="file-drop-area w-100 mb-3" for="backup_file">
                    <i class="fa-solid fa-file-sql"></i>
                    <span id="fileNameDisplay">Pilih atau Seret File .sql Kesini</span>
                    <input type="file" name="backup_file" id="backup_file" accept=".sql" style="display:none;" required>
                </label>
                <button type="button" class="wh-btn wh-btn-outline w-100"
                        onclick="confirmRestore('formRestoreUp')">
                    <i class="fa-solid fa-clock-rotate-left"></i> Unggah & Restore
                </button>
            </form>
        </div>
    </div>

    <!-- History Tabs -->
    <div class="act-card" style="padding:0; overflow:hidden;">
        <div class="wh-tabs pt-2 px-3">
            <div class="wh-tab active" onclick="switchTab('history')">
                <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Backup
            </div>
            <div class="wh-tab" onclick="switchTab('restore_points')">
                <i class="fa-solid fa-shield-halved"></i> Restore Points (Auto)
            </div>
        </div>

        <!-- Tab: Riwayat Backup -->
        <div id="tab_history" style="padding:20px; overflow-x:auto;">
            <table class="wh-table">
                <thead>
                    <tr>
                        <th>Nama File</th>
                        <th>Keterangan</th>
                        <th>Ukuran</th>
                        <th>Tanggal Dibuat</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($history)): ?>
                        <tr><td colspan="5" class="text-center text-muted">Belum ada riwayat backup.</td></tr>
                    <?php else: ?>
                        <?php $i = 0; foreach ($history as $h):
                            $isLatest = ($i === 0);
                            $i++;
                            // FIX #10: esc() pada safeId — meski preg_replace sudah aman, tetap konsisten
                            $safeId = 'f_' . preg_replace('/[^a-zA-Z0-9]/', '_', $h['filename']);
                        ?>
                        <tr>
                            <td>
                                <strong><?= esc($h['filename']) ?></strong>
                                <?php if ($isLatest): ?>
                                    <span class="badge-latest">LATEST</span>
                                <?php else: ?>
                                    <span class="badge-old">OLD</span>
                                <?php endif; ?>
                            </td>
                            <td><span style="font-size:0.8rem; color:#475569;"><?= esc($h['metadata']['note'] ?? '-') ?></span></td>
                            <td><?= esc(round($h['size'] / 1024 / 1024, 2)) ?> MB</td>
                            <td><?= esc(date('d M Y, H:i', $h['date'])) ?></td>
                            <td class="text-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    <!-- FIX (View): json_encode untuk filename di atribut onclick — aman dari XSS -->
                                    <button class="wh-btn wh-btn-success py-1 px-2" style="font-size:0.8rem"
                                            onclick='verifyFile(<?= json_encode($h['filename']) ?>)'
                                            title="Verify Integrity">
                                        <i class="fa-solid fa-check-double"></i>
                                    </button>
                                    <a href="<?= site_url('pengaturan/backup/download/' . urlencode($h['filename'])) ?>"
                                       class="wh-btn wh-btn-outline py-1 px-2" style="font-size:0.8rem">
                                        <i class="fa-solid fa-download"></i>
                                    </a>
                                    <form action="<?= site_url('pengaturan/backup/restore') ?>" method="post"
                                          id="<?= esc($safeId) ?>_res" class="d-inline">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="filename" value="<?= esc($h['filename']) ?>">
                                        <button type="button"
                                                class="wh-btn wh-btn-outline py-1 px-2 text-danger"
                                                style="font-size:0.8rem; border-color:#FEE2E2"
                                                onclick='confirmRestore(<?= json_encode($safeId . '_res') ?>)'>
                                            <i class="fa-solid fa-rotate-left"></i>
                                        </button>
                                    </form>
                                    <form action="<?= site_url('pengaturan/backup/delete/' . urlencode($h['filename'])) ?>"
                                          method="post" id="<?= esc($safeId) ?>_del" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button type="button"
                                                class="wh-btn wh-btn-danger py-1 px-2" style="font-size:0.8rem"
                                                onclick='confirmDelete(<?= json_encode($safeId . '_del') ?>)'>
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Tab: Restore Points -->
        <div id="tab_restore_points" style="padding:20px; overflow-x:auto; display:none;">
            <table class="wh-table">
                <thead>
                    <tr>
                        <th>Nama File</th>
                        <th>Keterangan</th>
                        <th>Ukuran</th>
                        <th>Tanggal Dibuat</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($restorePoints)): ?>
                        <tr><td colspan="5" class="text-center text-muted">Belum ada Restore Point.</td></tr>
                    <?php else: ?>
                        <?php foreach ($restorePoints as $h):
                            $safeId = 'rp_' . preg_replace('/[^a-zA-Z0-9]/', '_', $h['filename']);
                        ?>
                        <tr>
                            <td><strong><?= esc($h['filename']) ?></strong></td>
                            <td><span style="font-size:0.8rem; color:#475569;"><?= esc($h['metadata']['note'] ?? '-') ?></span></td>
                            <td><?= esc(round($h['size'] / 1024 / 1024, 2)) ?> MB</td>
                            <td><?= esc(date('d M Y, H:i', $h['date'])) ?></td>
                            <td class="text-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    <form action="<?= site_url('pengaturan/backup/restore') ?>" method="post"
                                          id="<?= esc($safeId) ?>_res" class="d-inline">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="filename" value="<?= esc($h['filename']) ?>">
                                        <button type="button"
                                                class="wh-btn wh-btn-outline py-1 px-2 text-danger"
                                                style="font-size:0.8rem; border-color:#FEE2E2"
                                                onclick='confirmRestore(<?= json_encode($safeId . '_res') ?>)'>
                                            <i class="fa-solid fa-rotate-left"></i> Restore Point Ini
                                        </button>
                                    </form>
                                    <form action="<?= site_url('pengaturan/backup/delete/' . urlencode($h['filename'])) ?>"
                                          method="post" id="<?= esc($safeId) ?>_del" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button type="button"
                                                class="wh-btn wh-btn-danger py-1 px-2" style="font-size:0.8rem"
                                                onclick='confirmDelete(<?= json_encode($safeId . '_del') ?>)'>
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div class="restore-overlay" id="loadingOverlay">
    <div class="restore-spinner"></div>
    <div class="restore-title" id="loadingTitle">Memproses...</div>
    <div class="restore-desc" id="loadingDesc">Mohon tunggu dan jangan tutup halaman ini.</div>
    <button class="overlay-close-btn" id="overlayCloseBtn" onclick="hideLoader()">
        <i class="fa-solid fa-xmark"></i> Tutup
    </button>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/sweetalert2.min.js') ?>"></script>
<script>
// File input label update
document.getElementById('backup_file').addEventListener('change', function (e) {
    if (e.target.files.length > 0) {
        document.getElementById('fileNameDisplay').innerText = e.target.files[0].name;
    }
});

// Anti double-submit pada form backup
document.getElementById('formBackup').addEventListener('submit', function () {
    const btn = document.getElementById('btnBackup');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memproses...';
    showLoader('Memproses Backup...', 'Sedang menulis data ke file SQL...');
});

// ── Tab switcher ──
function switchTab(tab) {
    document.querySelectorAll('.wh-tab').forEach(e => e.classList.remove('active'));
    document.getElementById('tab_history').style.display       = 'none';
    document.getElementById('tab_restore_points').style.display = 'none';

    if (tab === 'history') {
        document.querySelectorAll('.wh-tab')[0].classList.add('active');
        document.getElementById('tab_history').style.display = 'block';
    } else {
        document.querySelectorAll('.wh-tab')[1].classList.add('active');
        document.getElementById('tab_restore_points').style.display = 'block';
    }
    sessionStorage.setItem('wh_active_tab', tab);
}

// Restore tab aktif setelah page reload
(function () {
    const saved = sessionStorage.getItem('wh_active_tab');
    if (saved && saved !== 'history') switchTab(saved);
})();

// ── Loader ──
let overlayTimeout = null;

function showLoader(title, desc) {
    document.getElementById('loadingTitle').innerText = title;
    document.getElementById('loadingDesc').innerText  = desc;
    document.getElementById('loadingOverlay').style.display = 'flex';
    document.getElementById('overlayCloseBtn').style.display = 'none';

    // Tampilkan tombol tutup setelah 15 detik (antisipasi hang)
    overlayTimeout = setTimeout(function () {
        document.getElementById('overlayCloseBtn').style.display = 'inline-block';
        document.getElementById('loadingDesc').innerText = 'Proses memakan waktu lebih lama dari biasanya. Klik Tutup jika ingin membatalkan.';
    }, 15000);
}

function hideLoader() {
    document.getElementById('loadingOverlay').style.display = 'none';
    if (overlayTimeout) clearTimeout(overlayTimeout);
}

// ── Confirm delete ──
function confirmDelete(formId) {
    Swal.fire({
        title: 'Hapus File Backup?',
        text: 'File yang dihapus tidak dapat dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!'
    }).then(result => {
        if (result.isConfirmed) document.getElementById(formId).submit();
    });
}

// ── Confirm restore ──
function confirmRestore(formId) {
    if (formId === 'formRestoreUp') {
        if (document.getElementById('backup_file').files.length === 0) {
            Swal.fire('Error', 'Silakan pilih file SQL terlebih dahulu!', 'error');
            return;
        }
    }

    Swal.fire({
        title: 'Apakah Anda yakin?',
        html: `<div style="text-align:left; background:#FEF2F2; padding:16px; border-radius:12px; margin-top:16px;">
               <strong>Restore akan:</strong><br>
               ✓ Membuat Restore Point Otomatis<br>
               ✓ Menghapus seluruh data saat ini<br>
               ✓ Menggantinya dengan isi backup<br>
               <span style="color:#DC2626; font-weight:700;">PROSES INI SANGAT BERISIKO</span>
               </div>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Ya, Eksekusi Restore',
        cancelButtonText: 'Batal'
    }).then(result => {
        if (result.isConfirmed) {
            showLoader('Memproses Restorasi Database...', 'Mengeksekusi instruksi SQL ke database...');
            document.getElementById(formId).submit();
        }
    });
}

// ── Verify file ──
function verifyFile(filename) {
    showLoader('Verifikasi Integritas...', 'Mengecek SHA256, Metadata, & Sintaks SQL');

    const controller = new AbortController();
    const timeoutId  = setTimeout(() => controller.abort(), 30000);

    fetch('<?= site_url('pengaturan/backup/verify/') ?>' + encodeURIComponent(filename), {
        signal: controller.signal
    })
    .then(res => {
        clearTimeout(timeoutId);
        if (!res.ok) throw new Error('Server error: ' + res.status);
        return res.json();
    })
    .then(data => {
        hideLoader();
        if (data.status) {
            const metaHtml = `<div style="text-align:left; background:#ECFDF5; padding:16px; border-radius:12px; margin-top:16px;">
                <strong>Aplikasi:</strong> ${escHtml(data.meta.application || '-')}<br>
                <strong>Versi:</strong> ${escHtml(data.meta.version || '-')}<br>
                <strong>Database:</strong> ${escHtml(data.meta.database || '-')}<br>
                <strong>Tgl Backup:</strong> ${escHtml(data.meta.date || '-')}<br>
                <hr style="margin:8px 0; border-color:#A7F3D0;">
                ✓ Metadata Valid<br>
                ${data.checksum_present ? '✓ Checksum Valid' : '⚠ Checksum Tidak Ditemukan'}<br>
                ✓ SQL Structure Valid<br>
                <strong style="color:#059669;">Ready to Restore</strong>
                </div>`;
            Swal.fire({ title: 'Backup Valid', html: metaHtml, icon: 'success' });
        } else {
            Swal.fire({ title: 'Backup Rusak', text: data.message, icon: 'error' });
        }
    })
    .catch(err => {
        hideLoader();
        if (err.name === 'AbortError') {
            Swal.fire('Timeout', 'Verifikasi memakan waktu terlalu lama. Silakan coba lagi.', 'warning');
        } else {
            Swal.fire('Error', 'Gagal memverifikasi file: ' + err.message, 'error');
        }
    });
}

// FIX (View): escaping HTML untuk data dari API response — cegah XSS di innerHTML
function escHtml(str) {
    return String(str)
        .replace(/&/g,  '&amp;')
        .replace(/</g,  '&lt;')
        .replace(/>/g,  '&gt;')
        .replace(/"/g,  '&quot;')
        .replace(/'/g,  '&#039;');
}

// FIX (View): Flash messages via json_encode — aman dari XSS
<?php if (session()->getFlashdata('success')): ?>
    Swal.fire('Sukses!', <?= json_encode(session()->getFlashdata('success')) ?>, 'success');
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    Swal.fire('Gagal!', <?= json_encode(session()->getFlashdata('error')) ?>, 'error');
<?php endif; ?>
</script>
<?= $this->endSection() ?>