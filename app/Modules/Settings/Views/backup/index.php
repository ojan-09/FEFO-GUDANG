<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<style>
    :root {
        --wh-bg: #F8FAFC; --wh-card: #FFFFFF; --wh-border: #E5E7EB;
        --wh-primary: #2563EB; --wh-primary-soft: #EFF6FF;
        --wh-success: #16A34A; --wh-success-soft: #DCFCE7;
        --wh-danger: #DC2626; --wh-danger-soft: #FEE2E2;
        --wh-text: #111827; --wh-text-soft: #6B7280;
        --wh-dark-soft: #F3F4F6;
    }

    .wh-page {
        background: var(--wh-bg);
        margin: -1.5rem -1.5rem 0 -1.5rem;
        padding: 20px 24px 40px 24px;
        min-height: 100vh;
    }

    /* ── Header ── */
    .wh-header {
        background: var(--wh-card); border: 1px solid var(--wh-border);
        border-radius: 18px; padding: 28px;
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 16px; margin-bottom: 20px;
    }
    .wh-header h1 {
        font-size: 1.35rem; font-weight: 700; color: var(--wh-text);
        margin: 0 0 4px 0; display: flex; align-items: center; gap: 10px;
    }
    .wh-header h1 i { color: var(--wh-primary); }
    .wh-header p { margin: 0; font-size: 0.85rem; color: var(--wh-text-soft); }

    /* ── Stats ── */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 12px; margin-bottom: 20px;
    }
    .stat-card {
        background: var(--wh-card); border: 1px solid var(--wh-border);
        border-radius: 16px; padding: 18px 20px;
        box-shadow: 0 1px 2px rgba(16,24,40,0.04);
        transition: transform 180ms ease, box-shadow 180ms ease;
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(16,24,40,0.08); }
    .stat-val { font-size: 1.4rem; font-weight: 800; color: var(--wh-text); line-height: 1.2; }
    .stat-val span { font-size: 0.85rem; font-weight: 600; color: var(--wh-text-soft); }
    .stat-lbl { font-size: 0.72rem; font-weight: 700; color: var(--wh-text-soft); text-transform: uppercase; letter-spacing: 0.05em; margin-top: 6px; }

    /* ── Action Cards ── */
    .action-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px; }
    .act-card {
        background: var(--wh-card); border: 1px solid var(--wh-border);
        border-radius: 16px; padding: 24px;
        box-shadow: 0 1px 2px rgba(16,24,40,0.04);
    }
    .act-card h3 {
        font-size: 1rem; font-weight: 700; margin: 0 0 10px 0;
        display: flex; align-items: center; gap: 8px; color: var(--wh-text);
    }
    .act-card p { font-size: 0.83rem; color: var(--wh-text-soft); margin-bottom: 16px; line-height: 1.6; }

    /* ── Buttons ── */
    .wh-btn {
        padding: 0 18px; height: 44px; border-radius: 10px;
        font-size: 0.85rem; font-weight: 600; cursor: pointer; border: none;
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        transition: transform 120ms ease, background 120ms ease; text-decoration: none;
    }
    .wh-btn:active { transform: scale(0.98); }
    .wh-btn-primary { background: var(--wh-primary); color: #fff; border: 1px solid var(--wh-primary); }
    .wh-btn-primary:hover { background: #1D4ED8; color: #fff; }
    .wh-btn-primary:disabled { background: #93C5FD; cursor: not-allowed; border-color: #93C5FD; }
    .wh-btn-outline { background: #fff; border: 1px solid var(--wh-border); color: var(--wh-text); }
    .wh-btn-outline:hover { background: var(--wh-dark-soft); color: var(--wh-text); }
    .wh-btn-success { background: var(--wh-success-soft); color: var(--wh-success); border: 1px solid #BBF7D0; }
    .wh-btn-success:hover { background: #BBF7D0; color: var(--wh-success); }
    .wh-btn-danger { background: var(--wh-danger-soft); color: var(--wh-danger); border: 1px solid #FECACA; }
    .wh-btn-danger:hover { background: #FECACA; color: var(--wh-danger); }
    .wh-btn-sm { height: 36px; padding: 0 12px; font-size: 0.78rem; border-radius: 8px; }

    /* ── Form ── */
    .wh-input {
        height: 44px; border-radius: 10px; border: 1px solid var(--wh-border);
        font-size: 0.85rem; padding: 0.5rem 0.75rem; width: 100%;
        transition: border-color 120ms ease, box-shadow 120ms ease;
    }
    .wh-input:focus {
        outline: none; border-color: var(--wh-primary);
        box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
    }
    .wh-label { font-size: 0.78rem; font-weight: 600; color: var(--wh-text); margin-bottom: 6px; display: block; }

    /* ── File Drop ── */
    .file-drop-area {
        border: 2px dashed var(--wh-border); border-radius: 12px;
        padding: 20px; text-align: center; background: var(--wh-bg);
        transition: border-color 180ms ease, background 180ms ease; cursor: pointer;
        display: block;
    }
    .file-drop-area:hover { border-color: var(--wh-primary); background: var(--wh-primary-soft); }
    .file-drop-area i { font-size: 1.8rem; color: var(--wh-text-soft); display: block; margin-bottom: 8px; }
    .file-drop-area span { font-size: 0.83rem; font-weight: 600; color: var(--wh-text); }
    .file-drop-area small { font-size: 0.75rem; color: var(--wh-text-soft); display: block; margin-top: 4px; }

    /* ── Tabs ── */
    .wh-tabs { display: flex; gap: 4px; border-bottom: 1px solid var(--wh-border); padding: 12px 16px 0 16px; overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .wh-tab {
        padding: 10px 16px; font-weight: 600; font-size: 0.85rem;
        color: var(--wh-text-soft); cursor: pointer; border-bottom: 2px solid transparent;
        transition: color 120ms ease; white-space: nowrap; flex-shrink: 0;
    }
    .wh-tab.active { color: var(--wh-primary); border-bottom-color: var(--wh-primary); }
    .wh-tab:hover:not(.active) { color: var(--wh-text); }

    /* ── Table ── */
    .wh-table-wrap { padding: 16px; overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .wh-table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 600px; }
    .wh-table th {
        background: var(--wh-bg); color: var(--wh-text-soft);
        font-size: 0.68rem; font-weight: 600; text-transform: uppercase;
        letter-spacing: 0.04em; padding: 12px 14px;
        border-bottom: 1px solid var(--wh-border); white-space: nowrap;
    }
    .wh-table td {
        padding: 0 14px; height: 56px; border-bottom: 1px solid var(--wh-border);
        font-size: 0.83rem; vertical-align: middle; color: var(--wh-text);
    }
    .wh-table, .wh-table th, .wh-table td { border-left: none; border-right: none; }
    .wh-table tbody tr { transition: background 120ms ease; }
    .wh-table tbody tr:hover td { background: var(--wh-dark-soft); }
    .wh-table tbody tr:last-child td { border-bottom: none; }

    /* ── Badges ── */
    .badge-latest {
        background: var(--wh-success-soft); color: var(--wh-success);
        font-size: 0.65rem; font-weight: 700; padding: 2px 8px;
        border-radius: 6px; margin-left: 6px; vertical-align: middle;
    }
    .badge-old {
        background: var(--wh-dark-soft); color: var(--wh-text-soft);
        font-size: 0.65rem; font-weight: 700; padding: 2px 8px;
        border-radius: 6px; margin-left: 6px; vertical-align: middle;
    }

    /* ── Action buttons row ── */
    .act-btns { display: flex; gap: 6px; justify-content: flex-end; flex-wrap: nowrap; }

    /* ── Loading Overlay ── */
    .restore-overlay {
        position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
        background: rgba(15,23,42,0.85); z-index: 9999;
        display: none; align-items: center; justify-content: center;
        flex-direction: column; color: #fff; padding: 24px; text-align: center;
    }
    .restore-spinner {
        width: 56px; height: 56px; border: 4px solid rgba(255,255,255,0.2);
        border-top-color: #fff; border-radius: 50%;
        animation: spin 1s linear infinite; margin-bottom: 20px;
    }
    .restore-title { font-size: 1.3rem; font-weight: 800; margin-bottom: 8px; }
    .restore-desc { font-size: 0.9rem; color: #CBD5E1; max-width: 320px; }
    .overlay-close-btn {
        margin-top: 20px; padding: 8px 20px; border-radius: 10px;
        background: rgba(255,255,255,0.15); color: #fff;
        border: 1px solid rgba(255,255,255,0.3); cursor: pointer;
        font-size: 0.85rem; font-weight: 600; display: none;
    }
    .overlay-close-btn:hover { background: rgba(255,255,255,0.25); }

    @keyframes spin { 100% { transform: rotate(360deg); } }

    /* ── Mobile ── */
    @media (max-width: 1024px) {
        .stat-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 768px) {
        .wh-page { padding: 12px 12px 40px 12px; }
        .wh-header { flex-direction: column; align-items: flex-start; padding: 20px; }
        .wh-header h1 { font-size: 1.1rem; }
        .stat-grid { grid-template-columns: repeat(2, 1fr); }
        .action-grid { grid-template-columns: 1fr; }
        .act-btns { flex-wrap: wrap; justify-content: flex-start; }
        .act-btns .wh-btn { flex: 1; min-width: 0; justify-content: center; }
    }
    @media (max-width: 480px) {
        .stat-grid { grid-template-columns: 1fr 1fr; }
        .stat-card:last-child { grid-column: span 2; }
    }
</style>

<div class="wh-page">

    <!-- Header -->
    <div class="wh-header">
        <div>
            <h1><i class="fa-solid fa-database"></i> Backup & Restore</h1>
            <p>Manajemen data operasional FEFO Gudang</p>
        </div>
    </div>

    <!-- DB Stats -->
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-val"><?= esc($dbInfo['name']) ?></div>
            <div class="stat-lbl">Database</div>
        </div>
        <div class="stat-card">
            <div class="stat-val"><?= esc($dbInfo['size_mb']) ?> <span>MB</span></div>
            <div class="stat-lbl">Ukuran DB</div>
        </div>
        <div class="stat-card">
            <div class="stat-val"><?= esc($dbInfo['tables']) ?></div>
            <div class="stat-lbl">Jumlah Tabel</div>
        </div>
        <div class="stat-card">
            <div class="stat-val"><?= esc($totalFiles) ?> <span>(<?= esc($folderSizeMb) ?> MB)</span></div>
            <div class="stat-lbl">Total Backup</div>
        </div>
        <div class="stat-card">
            <div class="stat-val" style="font-size:1rem; margin-top:4px;"><?= esc($lastBackup) ?></div>
            <div class="stat-lbl">Backup Terakhir</div>
        </div>
    </div>

    <!-- Action Cards -->
    <div class="action-grid">

        <!-- Buat Backup -->
        <div class="act-card">
            <h3><i class="fa-solid fa-cloud-arrow-down" style="color:var(--wh-primary)"></i> Buat Backup Baru</h3>
            <p>Amankan seluruh struktur dan data aplikasi ke dalam file terenkripsi SHA256 secara real-time.</p>
            <form action="<?= site_url('pengaturan/backup/doBackup') ?>" method="post" id="formBackup">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="wh-label">Keterangan / Backup Notes <small style="font-weight:400;">(Opsional)</small></label>
                    <input type="text" name="notes" class="wh-input" maxlength="150"
                           placeholder="Misal: Backup bulanan sebelum update v2...">
                </div>
                <button type="submit" class="wh-btn wh-btn-primary w-100" id="btnBackup">
                    <i class="fa-solid fa-cloud-arrow-down"></i> Eksekusi Backup Sekarang
                </button>
            </form>
        </div>

        <!-- Restore dari Upload -->
        <div class="act-card">
            <h3><i class="fa-solid fa-upload" style="color:var(--wh-danger)"></i> Restore dari Komputer</h3>
            <p>Unggah file <code>.sql</code> untuk memulihkan seluruh data. <strong style="color:var(--wh-danger)">Peringatan:</strong> Data saat ini akan ditimpa!</p>
            <form action="<?= site_url('pengaturan/backup/restore') ?>" method="post"
                  enctype="multipart/form-data" id="formRestoreUp">
                <?= csrf_field() ?>
                <label class="file-drop-area w-100 mb-3" for="backup_file">
                    <i class="fa-solid fa-file-arrow-up"></i>
                    <span id="fileNameDisplay">Pilih atau Seret File .sql</span>
                    <small>Hanya file .sql yang diterima</small>
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
        <div class="wh-tabs">
            <div class="wh-tab active" onclick="switchTab('history')">
                <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Backup
            </div>
            <div class="wh-tab" onclick="switchTab('restore_points')">
                <i class="fa-solid fa-shield-halved"></i> Restore Points (Auto)
            </div>
        </div>

        <!-- Tab: Riwayat Backup -->
        <div id="tab_history" class="wh-table-wrap">
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
                        <tr>
                            <td colspan="5" style="text-align:center; padding:40px 20px;">
                                <i class="fa-solid fa-box-open" style="font-size:2rem; color:var(--wh-border); display:block; margin-bottom:10px;"></i>
                                <span style="color:var(--wh-text-soft); font-size:0.85rem;">Belum ada riwayat backup.</span>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $i = 0; foreach ($history as $h):
                            $isLatest = ($i === 0); $i++;
                            $safeId = 'f_' . preg_replace('/[^a-zA-Z0-9]/', '_', $h['filename']);
                        ?>
                        <tr>
                            <td>
                                <strong style="font-size:0.8rem;"><?= esc($h['filename']) ?></strong>
                                <?= $isLatest ? '<span class="badge-latest">LATEST</span>' : '<span class="badge-old">OLD</span>' ?>
                            </td>
                            <td style="color:var(--wh-text-soft); font-size:0.8rem;"><?= esc($h['metadata']['note'] ?? '-') ?></td>
                            <td style="white-space:nowrap;"><?= esc(round($h['size'] / 1024 / 1024, 2)) ?> MB</td>
                            <td style="white-space:nowrap; font-size:0.8rem;"><?= esc(date('d M Y, H:i', $h['date'])) ?></td>
                            <td>
                                <div class="act-btns">
                                    <button class="wh-btn wh-btn-success wh-btn-sm"
                                            onclick='verifyFile(<?= json_encode($h['filename']) ?>)'
                                            title="Verify Integrity">
                                        <i class="fa-solid fa-check-double"></i>
                                    </button>
                                    <a href="<?= site_url('pengaturan/backup/download/' . urlencode($h['filename'])) ?>"
                                       class="wh-btn wh-btn-outline wh-btn-sm" title="Download">
                                        <i class="fa-solid fa-download"></i>
                                    </a>
                                    <form action="<?= site_url('pengaturan/backup/restore') ?>" method="post"
                                          id="<?= esc($safeId) ?>_res" class="d-inline">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="filename" value="<?= esc($h['filename']) ?>">
                                        <button type="button"
                                                class="wh-btn wh-btn-outline wh-btn-sm"
                                                style="color:var(--wh-danger); border-color:#FEE2E2;"
                                                onclick='confirmRestore(<?= json_encode($safeId . '_res') ?>)'
                                                title="Restore">
                                            <i class="fa-solid fa-rotate-left"></i>
                                        </button>
                                    </form>
                                    <form action="<?= site_url('pengaturan/backup/delete/' . urlencode($h['filename'])) ?>"
                                          method="post" id="<?= esc($safeId) ?>_del" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button type="button"
                                                class="wh-btn wh-btn-danger wh-btn-sm"
                                                onclick='confirmDelete(<?= json_encode($safeId . '_del') ?>)'
                                                title="Hapus">
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
        <div id="tab_restore_points" class="wh-table-wrap" style="display:none;">
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
                        <tr>
                            <td colspan="5" style="text-align:center; padding:40px 20px;">
                                <i class="fa-solid fa-box-open" style="font-size:2rem; color:var(--wh-border); display:block; margin-bottom:10px;"></i>
                                <span style="color:var(--wh-text-soft); font-size:0.85rem;">Belum ada Restore Point.</span>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($restorePoints as $h):
                            $safeId = 'rp_' . preg_replace('/[^a-zA-Z0-9]/', '_', $h['filename']);
                        ?>
                        <tr>
                            <td><strong style="font-size:0.8rem;"><?= esc($h['filename']) ?></strong></td>
                            <td style="color:var(--wh-text-soft); font-size:0.8rem;"><?= esc($h['metadata']['note'] ?? '-') ?></td>
                            <td style="white-space:nowrap;"><?= esc(round($h['size'] / 1024 / 1024, 2)) ?> MB</td>
                            <td style="white-space:nowrap; font-size:0.8rem;"><?= esc(date('d M Y, H:i', $h['date'])) ?></td>
                            <td>
                                <div class="act-btns">
                                    <form action="<?= site_url('pengaturan/backup/restore') ?>" method="post"
                                          id="<?= esc($safeId) ?>_res" class="d-inline">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="filename" value="<?= esc($h['filename']) ?>">
                                        <button type="button"
                                                class="wh-btn wh-btn-outline wh-btn-sm"
                                                style="color:var(--wh-danger); border-color:#FEE2E2;"
                                                onclick='confirmRestore(<?= json_encode($safeId . '_res') ?>)'>
                                            <i class="fa-solid fa-rotate-left"></i> Restore
                                        </button>
                                    </form>
                                    <form action="<?= site_url('pengaturan/backup/delete/' . urlencode($h['filename'])) ?>"
                                          method="post" id="<?= esc($safeId) ?>_del" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button type="button"
                                                class="wh-btn wh-btn-danger wh-btn-sm"
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
    document.getElementById('tab_history').style.display        = 'none';
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

function escHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;').replace(/</g, '&lt;')
        .replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
}

<?php if (session()->getFlashdata('success')): ?>
    Swal.fire('Sukses!', <?= json_encode(session()->getFlashdata('success')) ?>, 'success');
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    Swal.fire('Gagal!', <?= json_encode(session()->getFlashdata('error')) ?>, 'error');
<?php endif; ?>
</script>
<?= $this->endSection() ?>