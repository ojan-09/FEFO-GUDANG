<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap');

.dw-root {
    max-width: 1500px;
    margin: 0 auto;
    padding: 20px 24px 48px;
    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
    color: #0F172A;
}

.dw-header {
    background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
    border-radius: 20px;
    padding: 24px 32px;
    color: #fff;
    margin-bottom: 24px;
    box-shadow: 0 12px 32px rgba(15, 23, 42, 0.12);
    display: flex; justify-content: space-between; align-items: center; gap: 20px; flex-wrap: wrap;
}
.dw-h1 { font-size: 20px; font-weight: 800; margin: 0; color: #fff; }
.dw-p { font-size: 13px; color: #94A3B8; margin: 3px 0 0 0; }

.dw-card {
    background: #fff; border: 1px solid #E2E8F0; border-radius: 18px; padding: 24px;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);
}

.dw-table { width: 100%; border-collapse: collapse; margin-top: 12px; }
.dw-table th { background: #F8FAFC; padding: 12px 14px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; border-bottom: 1px solid #E2E8F0; text-align: left; }
.dw-table td { padding: 14px; font-size: 13.5px; border-bottom: 1px solid #F1F5F9; color: #1E293B; vertical-align: middle; }
</style>

<div class="dw-root">
    <div class="dw-header">
        <div>
            <h1 class="dw-h1">Riwayat Penerbitan Dokumen</h1>
            <p class="dw-p">Histori permanen seluruh dokumen & PDF yang diterbitkan dalam sistem.</p>
        </div>
        <a href="<?= site_url('pengaturan/dokumen') ?>" class="btn btn-outline-light rounded-3 px-3 py-2 fw-semibold btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Workspace
        </a>
    </div>

    <div class="dw-card">
        <table class="dw-table">
            <thead>
                <tr>
                    <th>Jenis Dokumen</th>
                    <th>Nomor Dokumen</th>
                    <th>Tanggal Terbit</th>
                    <th>Dibuat Oleh</th>
                    <th>Versi Konfig</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($history)): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Belum ada riwayat dokumen terbit.</td>
                </tr>
                <?php else: ?>
                <?php foreach($history as $h): ?>
                <tr>
                    <td class="fw-bold"><?= esc($h['document_name']) ?></td>
                    <td class="fw-bold text-primary font-monospace"><?= esc($h['document_number']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($h['created_at'])) ?></td>
                    <td><?= esc($h['created_by']) ?></td>
                    <td><span class="badge bg-secondary-subtle text-secondary rounded-pill px-3">v<?= esc($h['config_version']) ?></span></td>
                    <td><span class="badge bg-success rounded-pill px-3 py-1"><?= esc($h['status']) ?></span></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
