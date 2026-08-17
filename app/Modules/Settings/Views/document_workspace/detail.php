<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap');

.dw-root {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px 24px 48px;
    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
    color: #0F172A;
}

/* ── HEADER ── */
.dw-header {
    background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
    border-radius: 20px;
    padding: 24px 32px;
    color: #fff;
    margin-bottom: 24px;
    box-shadow: 0 12px 32px rgba(15, 23, 42, 0.12);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
}
.dw-header-left { display: flex; align-items: center; gap: 16px; }
.dw-header-icon {
    width: 48px; height: 48px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; color: #818CF8;
}
.dw-h1 { font-size: 20px; font-weight: 800; margin: 0; color: #fff; }
.dw-p { font-size: 13px; color: #94A3B8; margin: 3px 0 0 0; }

/* ── LAYOUT ── */
.dw-layout {
    display: grid;
    grid-template-columns: 260px 1fr;
    gap: 24px;
}
@media (max-width: 992px) {
    .dw-layout { grid-template-columns: 1fr; }
}

/* ── SIDEBAR ── */
.dw-sidebar {
    background: #fff;
    border: 1px solid #E2E8F0;
    border-radius: 18px;
    padding: 16px;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);
    height: fit-content;
}
.dw-nav-title {
    font-size: 11px; font-weight: 800; color: #94A3B8;
    text-transform: uppercase; letter-spacing: 0.8px;
    padding: 8px 12px 6px; margin-top: 8px;
}
.dw-nav-title:first-child { margin-top: 0; }

.dw-nav-item {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 14px; border-radius: 12px;
    color: #475569; font-size: 13.5px; font-weight: 600;
    text-decoration: none; transition: all 0.18s ease; margin-bottom: 3px;
}
.dw-nav-item i { font-size: 15px; color: #64748B; margin-right: 10px; width: 18px; text-align: center; }
.dw-nav-item:hover { background: #F1F5F9; color: #0F172A; }
.dw-nav-item.active { background: #EFF6FF; color: #2563EB; font-weight: 700; }
.dw-nav-item.active i { color: #2563EB; }

/* ── MAIN WORKSPACE CONTAINER ── */
.dw-panel {
    background: #fff;
    border: 1px solid #E2E8F0;
    border-radius: 18px;
    padding: 24px;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);
}

.dw-tabs {
    display: flex; gap: 8px; border-bottom: 1px solid #E2E8F0;
    padding-bottom: 12px; margin-bottom: 24px; flex-wrap: wrap;
}
.dw-tab-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 18px; border-radius: 12px; font-size: 13.5px; font-weight: 700;
    color: #64748B; background: transparent; border: none; cursor: pointer;
    transition: all 0.15s ease; text-decoration: none;
}
.dw-tab-btn:hover { background: #F8FAFC; color: #0F172A; }
.dw-tab-btn.active { background: #2563EB; color: #fff; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25); }

.dw-form-group { margin-bottom: 18px; }
.dw-label { font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px; display: block; }
.dw-sub-label { font-size: 12px; color: #64748B; margin-bottom: 6px; display: block; }
.dw-input {
    width: 100%; height: 42px; padding: 0 14px;
    border: 1px solid #CBD5E1; border-radius: 10px;
    font-size: 13.5px; color: #0F172A; outline: none;
    line-height: 40px;
}
select.dw-input {
    padding-top: 0;
    padding-bottom: 0;
    line-height: normal;
}
.dw-input:focus { border-color: #2563EB; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15); }

.dw-preview-box {
    background: #0F172A; color: #38BDF8; font-family: 'JetBrains Mono', monospace;
    padding: 16px; border-radius: 12px; font-size: 15px; font-weight: 700;
    display: flex; align-items: center; justify-content: space-between;
}

.dw-table { width: 100%; border-collapse: collapse; margin-top: 12px; }
.dw-table th { background: #F8FAFC; padding: 12px 14px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; border-bottom: 1px solid #E2E8F0; text-align: left; }
.dw-table td { padding: 14px; font-size: 13.5px; border-bottom: 1px solid #F1F5F9; color: #1E293B; vertical-align: middle; }
</style>

<div class="dw-root">

    <!-- HEADER -->
    <div class="dw-header">
        <div class="dw-header-left">
            <div class="dw-header-icon">
                <i class="fa-solid fa-sliders"></i>
            </div>
            <div>
                <h1 class="dw-h1">Workspace <?= esc($config['title']) ?></h1>
                <p class="dw-p"><?= esc($config['description']) ?></p>
            </div>
        </div>
        <div style="display:flex; gap:10px;">
            <a href="<?= site_url('pengaturan/dokumen/' . $config['document_key'] . '/preview') ?>" target="_blank" class="btn btn-primary rounded-3 px-3 py-2 fw-bold btn-sm">
                <i class="fa-solid fa-eye me-1"></i> Live Preview PDF
            </a>
            <a href="<?= site_url('pengaturan/dokumen') ?>" class="btn btn-outline-light rounded-3 px-3 py-2 fw-semibold btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- MAIN LAYOUT -->
    <div class="dw-layout">

        <!-- SIDEBAR -->
        <div class="dw-sidebar">
            <div class="dw-nav-title">Menu Workspace</div>
            <a href="<?= site_url('pengaturan/dokumen') ?>" class="dw-nav-item">
                <span><i class="fa-solid fa-grid-2"></i> Dashboard Dokumen</span>
            </a>

            <div class="dw-nav-title">Dokumen Kelola</div>
            <a href="<?= site_url('pengaturan/dokumen/berita_acara') ?>" class="dw-nav-item <?= $activeDocKey === 'berita_acara' ? 'active' : '' ?>">
                <span><i class="fa-solid fa-file-signature"></i> Berita Acara</span>
            </a>
            <a href="<?= site_url('pengaturan/dokumen/barang_keluar') ?>" class="dw-nav-item <?= $activeDocKey === 'barang_keluar' ? 'active' : '' ?>">
                <span><i class="fa-solid fa-truck-ramp-box"></i> Barang Keluar</span>
            </a>

            <div class="dw-nav-title">Audit & Histori</div>
            <a href="<?= site_url('pengaturan/dokumen/riwayat') ?>" class="dw-nav-item">
                <span><i class="fa-solid fa-list-check"></i> Riwayat Terbit</span>
            </a>
            <a href="<?= site_url('pengaturan/dokumen/activity-log') ?>" class="dw-nav-item">
                <span><i class="fa-solid fa-user-clock"></i> Activity Log</span>
            </a>
        </div>

        <!-- MAIN PANEL WITH TABS -->
        <div class="dw-panel">

            <!-- TABS HEADER -->
            <div class="dw-tabs nav nav-tabs" id="docTabs" role="tablist">
                <button class="dw-tab-btn active" id="tab-numbering-btn" data-bs-toggle="tab" data-bs-target="#tab-numbering" type="button">
                    <i class="fa-solid fa-hashtag"></i> Penomoran Otomatis Bersama
                </button>
                <?php if($activeDocKey === 'berita_acara'): ?>
                <button class="dw-tab-btn" id="tab-provisions-btn" data-bs-toggle="tab" data-bs-target="#tab-provisions" type="button">
                    <i class="fa-solid fa-list-ol"></i> Ketentuan Berita Acara (v<?= $config['current_version'] ?? 1 ?>)
                </button>
                <?php endif; ?>
                <button class="dw-tab-btn" id="tab-history-btn" data-bs-toggle="tab" data-bs-target="#tab-history" type="button">
                    <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Terbit (<?= count($history) ?>)
                </button>
                <button class="dw-tab-btn" id="tab-logs-btn" data-bs-toggle="tab" data-bs-target="#tab-logs" type="button">
                    <i class="fa-solid fa-receipt"></i> Activity Log
                </button>
            </div>

            <!-- TAB CONTENTS -->
            <div class="tab-content">

                <!-- 1. TAB PENOMORAN OTOMATIS BERSAMA -->
                <div class="tab-pane fade show active" id="tab-numbering">
                    <form action="<?= site_url('pengaturan/dokumen/' . $config['document_key'] . '/update-numbering') ?>" method="POST">
                        <?= csrf_field() ?>

                        <div class="alert alert-primary rounded-3 d-flex align-items-center mb-4" role="alert">
                            <i class="fa-solid fa-circle-info fs-4 me-3"></i>
                            <div>
                                <strong>Nomor Dokumen Bersama (Single Counter Global)</strong><br>
                                Berita Acara dan Barang Keluar menggunakan 1 counter nomor urut global yang sama secara berurutan.
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <div class="dw-preview-box">
                                    <div>
                                        <div style="font-size: 11px; text-transform: uppercase; color: #94A3B8; letter-spacing: 0.6px; margin-bottom: 2px;">Simulasi Nomor Dokumen Berikutnya (Counter DB Tidak Berubah Saat Preview)</div>
                                        <div style="color: #38BDF8; font-size: 20px; font-weight: 800;"><?= esc($nextFormatted) ?></div>
                                    </div>
                                    <button type="button" onclick="window.open('<?= site_url('pengaturan/dokumen/' . $config['document_key'] . '/preview') ?>', '_blank')" class="btn btn-outline-info btn-sm fw-bold rounded-3">
                                        <i class="fa-solid fa-eye me-1"></i> Preview PDF
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="dw-form-group">
                                    <label class="dw-label">Format Pattern Penomoran Bersama (Terkunci Standard)</label>
                                    <input type="text" class="dw-input bg-light text-muted fw-bold" value="{nomor}/{semester}/{bulan}/{tahun}" readonly>
                                    <input type="hidden" name="number_format" value="{nomor}/{semester}/{bulan}/{tahun}">
                                    <span class="dw-sub-label mt-1">Format baku sistem: {nomor}/{semester}/{bulan}/{tahun} (Contoh: 401/SEM2/8/26)</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="dw-form-group">
                                    <label class="dw-label">Nomor Terakhir Terbit (Global Counter)</label>
                                    <input type="number" name="current_number" class="dw-input" value="<?= esc($globalConfig['current_number']) ?>" required>
                                    <span class="dw-sub-label mt-1">Nomor berikutnya yang diterbitkan: <strong><?= $globalConfig['current_number'] + 1 ?></strong></span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="dw-form-group">
                                    <label class="dw-label">Semester Aktif</label>
                                    <select name="active_semester" class="dw-input">
                                        <option value="SEM1" <?= ($globalConfig['active_semester'] === 'SEM1' || $globalConfig['active_semester'] === '1' || strtolower($globalConfig['active_semester']) === 'sem 1') ? 'selected' : '' ?>>Semester 1 (SEM1)</option>
                                        <option value="SEM2" <?= ($globalConfig['active_semester'] === 'SEM2' || $globalConfig['active_semester'] === '2' || strtolower($globalConfig['active_semester']) === 'sem 2') ? 'selected' : '' ?>>Semester 2 (SEM2)</option>
                                    </select>
                                    <span class="dw-sub-label mt-1">Digunakan untuk tag {semester}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="dw-form-group">
                                    <label class="dw-label">Bulan Aktif</label>
                                    <select name="active_month" class="dw-input">
                                        <?php
                                        $namaBulan = [
                                            '1' => 'Januari (1)', '2' => 'Februari (2)', '3' => 'Maret (3)', '4' => 'April (4)',
                                            '5' => 'Mei (5)', '6' => 'Juni (6)', '7' => 'Juli (7)', '8' => 'Agustus (8)',
                                            '9' => 'September (9)', '10' => 'Oktober (10)', '11' => 'November (11)', '12' => 'Desember (12)'
                                        ];
                                        $currMonthVal = ltrim($globalConfig['active_month'], '0');
                                        foreach ($namaBulan as $val => $label):
                                        ?>
                                            <option value="<?= $val ?>" <?= $currMonthVal == $val ? 'selected' : '' ?>><?= $label ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span class="dw-sub-label mt-1">Digunakan untuk tag {bulan}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="dw-form-group">
                                    <label class="dw-label">Tahun Aktif</label>
                                    <input type="text" name="active_year" class="dw-input" value="<?= esc($globalConfig['active_year']) ?>" placeholder="Misal: 26 atau 2026" required>
                                    <span class="dw-sub-label mt-1">Digunakan untuk tag {tahun} / {tahun_full}</span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="dw-form-group">
                                    <label class="dw-label">Aturan Reset Nomor Bersama</label>
                                    <select name="reset_rule" class="dw-input">
                                        <option value="none" <?= $globalConfig['reset_rule'] === 'none' ? 'selected' : '' ?>>Tidak Pernah Reset (Counter Lanjut)</option>
                                        <option value="monthly" <?= $globalConfig['reset_rule'] === 'monthly' ? 'selected' : '' ?>>Reset Setiap Bulan (Kembali ke 1)</option>
                                        <option value="semester" <?= $globalConfig['reset_rule'] === 'semester' ? 'selected' : '' ?>>Reset Setiap Semester (Kembali ke 1)</option>
                                        <option value="yearly" <?= $globalConfig['reset_rule'] === 'yearly' ? 'selected' : '' ?>>Reset Setiap Tahun (Kembali ke 1)</option>
                                    </select>
                                    <span class="dw-sub-label mt-1">Reset nomor berlaku untuk counter bersama. Dokumen lama tidak berubah.</span>
                                </div>
                            </div>
                        </div>

                        <?php if(in_groups('Administrator')): ?>
                        <div class="mt-4 pt-2 border-top">
                            <button type="submit" class="btn btn-primary rounded-3 px-4 py-2 fw-bold">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Pengaturan Penomoran Bersama
                            </button>
                        </div>
                        <?php endif; ?>
                    </form>
                </div>

                <!-- 2. TAB KETENTUAN BERITA ACARA -->
                <?php if($activeDocKey === 'berita_acara'): ?>
                <div class="tab-pane fade" id="tab-provisions">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold mb-1">Ketentuan Berita Acara (Versi <?= $config['current_version'] ?? 1 ?>)</h5>
                            <p class="text-muted small mb-0">Ketentuan yang otomatis tertera pada Berita Acara baru.</p>
                        </div>
                        <?php if(in_groups('Administrator')): ?>
                        <div class="d-flex gap-2">
                            <form action="<?= site_url('pengaturan/dokumen/berita_acara/new-version') ?>" method="POST" onsubmit="return confirm('Rilis versi ketentuan Berita Acara baru?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-outline-dark rounded-3 px-3 py-2 fw-bold btn-sm">
                                    <i class="fa-solid fa-code-branch me-1"></i> Versi Baru (v<?= ($config['current_version'] ?? 1) + 1 ?>)
                                </button>
                            </form>
                            <button type="button" class="btn btn-success rounded-3 px-3 py-2 fw-bold btn-sm" data-bs-toggle="modal" data-bs-target="#addProvisionModal">
                                <i class="fa-solid fa-plus me-1"></i> Tambah Ketentuan
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>

                    <table class="dw-table">
                        <thead>
                            <tr>
                                <th style="width: 60px;">Urutan</th>
                                <th>Isi Ketentuan</th>
                                <th style="width: 100px;">Status</th>
                                <?php if(in_groups('Administrator')): ?>
                                <th style="width: 130px; text-align: center;">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($provisions)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Belum ada ketentuan khusus.</td>
                            </tr>
                            <?php else: ?>
                            <?php foreach($provisions as $p): ?>
                            <tr>
                                <td class="fw-bold text-center"><?= $p['sort_order'] ?></td>
                                <td><?= esc($p['content']) ?></td>
                                <td>
                                    <?php if($p['is_active'] == 1): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-1">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <?php if(in_groups('Administrator')): ?>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary rounded-2 me-1" onclick="editProvision(<?= htmlspecialchars(json_encode($p)) ?>)">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <a href="<?= site_url('pengaturan/dokumen/berita_acara/provision/delete/' . $p['id']) ?>" onclick="return confirm('Hapus ketentuan ini?')" class="btn btn-sm btn-outline-danger rounded-2">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>

                <!-- 3. TAB RIWAYAT TERBIT -->
                <div class="tab-pane fade" id="tab-history">
                    <h5 class="fw-bold mb-1">Riwayat Penerbitan Dokumen (<?= esc($config['title']) ?>)</h5>
                    <p class="text-muted small mb-3">Dokumen terbit disimpan permanen.</p>

                    <table class="dw-table">
                        <thead>
                            <tr>
                                <th>Nomor Dokumen</th>
                                <th>Jenis Dokumen</th>
                                <th>Dibuat Oleh</th>
                                <th>Tanggal Terbit</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($history)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada riwayat dokumen terbit.</td>
                            </tr>
                            <?php else: ?>
                            <?php foreach($history as $h): ?>
                            <tr>
                                <td class="fw-bold text-primary font-monospace"><?= esc($h['document_number']) ?></td>
                                <td><?= esc($h['document_name']) ?></td>
                                <td><?= esc($h['created_by']) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($h['created_at'])) ?></td>
                                <td><span class="badge bg-primary rounded-pill px-3 py-1"><?= esc($h['status']) ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- 4. TAB ACTIVITY LOG -->
                <div class="tab-pane fade" id="tab-logs">
                    <h5 class="fw-bold mb-1">Activity Audit Log Pengaturan Penomoran & Ketentuan</h5>
                    <p class="text-muted small mb-3">Jejak audit perubahan oleh Administrator.</p>

                    <table class="dw-table">
                        <thead>
                            <tr>
                                <th>Tanggal & Waktu</th>
                                <th>Administrator</th>
                                <th>Aksi Perubahan</th>
                                <th>Nilai Sebelum</th>
                                <th>Nilai Sesudah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($activityLogs)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada catatan aktivitas.</td>
                            </tr>
                            <?php else: ?>
                            <?php foreach($activityLogs as $log): ?>
                            <tr>
                                <td><?= date('d/m/Y H:i:s', strtotime($log['created_at'])) ?></td>
                                <td class="fw-bold"><?= esc($log['username']) ?></td>
                                <td><span class="badge bg-info-subtle text-info border border-info-subtle rounded-2 px-2 py-1"><?= esc($log['action_type']) ?></span></td>
                                <td class="small text-muted font-monospace"><?= esc($log['before_value'] ?? '-') ?></td>
                                <td class="small text-dark fw-semibold font-monospace"><?= esc($log['after_value'] ?? '-') ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>

        </div>

    </div>

</div>

<!-- MODAL TAMBAH KETENTUAN BERITA ACARA -->
<?php if($activeDocKey === 'berita_acara'): ?>
<div class="modal fade" id="addProvisionModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= site_url('pengaturan/dokumen/berita_acara/provision/add') ?>" method="POST" class="modal-content rounded-4 border-0">
            <?= csrf_field() ?>
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold">Tambah Ketentuan Berita Acara</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold small">Urutan Tampil</label>
                    <input type="number" name="sort_order" class="form-control rounded-3" value="<?= count($provisions) + 1 ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Isi Teks Ketentuan</label>
                    <textarea name="content" class="form-control rounded-3" rows="4" placeholder="Tuliskan isi ketentuan baru..." required></textarea>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-light rounded-3 fw-bold" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success rounded-3 fw-bold">Simpan Ketentuan</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT KETENTUAN -->
<div class="modal fade" id="editProvisionModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editProvisionForm" method="POST" class="modal-content rounded-4 border-0">
            <?= csrf_field() ?>
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold">Edit Ketentuan Berita Acara</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold small">Urutan Tampil</label>
                    <input type="number" id="edit_sort_order" name="sort_order" class="form-control rounded-3" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Isi Teks Ketentuan</label>
                    <textarea id="edit_content" name="content" class="form-control rounded-3" rows="4" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Status Aktif</label>
                    <select id="edit_is_active" name="is_active" class="form-select rounded-3">
                        <option value="1">Aktif (Tampil di PDF Berita Acara Baru)</option>
                        <option value="0">Nonaktif (Sembunyikan)</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-light rounded-3 fw-bold" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary rounded-3 fw-bold">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
function editProvision(p) {
    document.getElementById('edit_sort_order').value = p.sort_order;
    document.getElementById('edit_content').value = p.content;
    document.getElementById('edit_is_active').value = p.is_active;
    document.getElementById('editProvisionForm').action = '<?= site_url("pengaturan/dokumen/berita_acara/provision/update/") ?>' + p.id;
    new bootstrap.Modal(document.getElementById('editProvisionModal')).show();
}
</script>
<?php endif; ?>

<?= $this->endSection() ?>
