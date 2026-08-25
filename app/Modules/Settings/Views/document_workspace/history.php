<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

:root {
    --bg:          #F8FAFC;
    --surface:     #FFFFFF;
    --border:      #E5E7EB;
    --text:        #111827;
    --text-soft:   #6B7280;
    --text-subtle: #9CA3AF;
    --primary:     #2563EB;
    --primary-bg:  #EFF6FF;
    --indigo:      #6366F1;
    --indigo-bg:   #EEF2FF;
    --success:     #16A34A;
    --success-bg:  #ECFDF5;
    --muted-bg:    #F3F4F6;
    --shadow:      0 1px 3px rgba(0,0,0,.05), 0 4px 12px rgba(0,0,0,.04);
    --radius:      16px;
    --radius-lg:   20px;
    --radius-sm:   10px;
}

.rw {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    background: var(--bg);
    margin: -1.5rem -1.5rem 0;
    padding: 20px 24px 48px;
    -webkit-font-smoothing: antialiased;
    min-height: 100vh;
}

/* ── Header ──────────────────────────────────────────────── */
.rw-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 20px;
}
.rw-header h1 {
    font-size: 22px;
    font-weight: 700;
    color: var(--text);
    margin: 0 0 4px;
    letter-spacing: -0.3px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.rw-header h1 i { color: var(--indigo); font-size: 19px; }
.rw-header p    { font-size: 13px; color: var(--text-soft); margin: 0; }

.rw-btn-outline {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    height: 36px;
    padding: 0 14px;
    border-radius: var(--radius-sm);
    font-size: 13px;
    font-weight: 500;
    font-family: 'Inter', sans-serif;
    background: var(--surface);
    border: 1px solid var(--border);
    color: var(--text-soft);
    text-decoration: none;
    transition: background .13s, color .13s, transform .12s;
    white-space: nowrap;
}
.rw-btn-outline:hover {
    background: var(--muted-bg);
    color: var(--text);
    transform: translateY(-1px);
}

/* ── Table Card ──────────────────────────────────────────── */
.rw-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow);
    overflow: hidden;
}

.rw-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 18px;
    border-bottom: 1px solid var(--border);
    background: var(--bg);
    flex-wrap: wrap;
    gap: 10px;
}
.rw-card-title {
    font-size: 13px;
    font-weight: 600;
    color: var(--text);
    display: flex;
    align-items: center;
    gap: 7px;
    margin: 0;
}
.rw-card-title i { color: var(--indigo); font-size: 14px; }
.rw-total-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: var(--indigo-bg);
    color: var(--indigo);
    border: 1px solid #C7D2FE;
    font-size: 11.5px;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 999px;
}

/* ── Table ───────────────────────────────────────────────── */
.rw-table {
    width: 100%;
    border-collapse: collapse;
    font-family: 'Inter', sans-serif;
    font-size: 13px;
}
.rw-table thead th {
    background: var(--bg);
    color: var(--text-subtle);
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .05em;
    padding: 10px 16px;
    border-bottom: 1px solid var(--border);
    text-align: left;
    white-space: nowrap;
}
.rw-table tbody td {
    padding: 13px 16px;
    border-bottom: 1px solid var(--border);
    color: var(--text);
    vertical-align: middle;
}
.rw-table tbody tr:last-child td { border-bottom: none; }
.rw-table tbody tr:nth-child(even) td { background: #FAFBFC; }
.rw-table tbody tr:hover td { background: #F3F4F6 !important; }

.rw-doc-name { font-weight: 600; color: var(--text); }
.rw-doc-num  {
    font-family: 'SF Mono', 'Fira Code', monospace;
    font-size: 12.5px;
    font-weight: 600;
    color: var(--indigo);
}
.rw-date     { color: var(--text-soft); white-space: nowrap; }
.rw-author   { color: var(--text-soft); }

.rw-badge {
    display: inline-flex;
    align-items: center;
    font-size: 11px;
    font-weight: 600;
    padding: 3px 9px;
    border-radius: 999px;
    white-space: nowrap;
}
.rw-badge.version { background: var(--muted-bg);    color: var(--text-soft); }
.rw-badge.status  { background: var(--success-bg);  color: var(--success); }

/* ── Empty state ─────────────────────────────────────────── */
.rw-empty {
    text-align: center;
    padding: 48px 20px;
    color: var(--text-subtle);
}
.rw-empty i { font-size: 32px; margin-bottom: 12px; display: block; color: var(--border); }
.rw-empty p { font-size: 13.5px; margin: 0; }

/* ── Responsive ──────────────────────────────────────────── */
@media (max-width: 768px) {
    .rw { padding: 12px 14px 40px; }
    .rw-header h1 { font-size: 17px; }
}
</style>

<div class="rw">

    <!-- Header -->
    <div class="rw-header">
        <div>
            <h1><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Penerbitan Dokumen</h1>
            <p>Histori permanen seluruh dokumen &amp; PDF yang diterbitkan dalam sistem</p>
        </div>
        <a href="<?= site_url('pengaturan/dokumen') ?>" class="rw-btn-outline">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Workspace
        </a>
    </div>

    <!-- Table Card -->
    <div class="rw-card">
        <div class="rw-card-header">
            <p class="rw-card-title">
                <i class="fa-solid fa-list-check"></i> Semua Riwayat Dokumen
            </p>
            <?php if (!empty($history)): ?>
            <span class="rw-total-pill">
                <?= count($history) ?> dokumen
            </span>
            <?php endif; ?>
        </div>

        <div class="table-responsive">
            <table class="rw-table">
                <thead>
                    <tr>
                        <th>Jenis Dokumen</th>
                        <th>Nomor Dokumen</th>
                        <th>Tanggal Terbit</th>
                        <th>Dibuat Oleh</th>
                        <th>Versi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($history)): ?>
                    <tr>
                        <td colspan="6">
                            <div class="rw-empty">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                                <p>Belum ada riwayat dokumen terbit.</p>
                            </div>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($history as $h): ?>
                    <tr>
                        <td><span class="rw-doc-name"><?= esc($h['document_name']) ?></span></td>
                        <td><span class="rw-doc-num"><?= esc($h['document_number']) ?></span></td>
                        <td><span class="rw-date"><?= date('d/m/Y H:i', strtotime($h['created_at'])) ?></span></td>
                        <td><span class="rw-author"><?= esc($h['created_by']) ?></span></td>
                        <td><span class="rw-badge version">v<?= esc($h['config_version']) ?></span></td>
                        <td><span class="rw-badge status"><?= esc($h['status']) ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?= $this->endSection() ?>  