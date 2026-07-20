<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<style>
    :root {
        --wh-bg: #F8FAFC;
        --wh-card: #FFFFFF;
        --wh-border: #E5E7EB;
        --wh-separator: #EEF2F7;
        --wh-primary: #2563EB;
        --wh-primary-soft: #EFF6FF;
        --wh-warning: #F59E0B;
        --wh-warning-soft: #FEF3C7;
        --wh-success-soft: #ECFDF5;
        --wh-danger-soft: #FEF2F2;
        --wh-dark-soft: #F3F4F6;
        --wh-text: #111827;
        --wh-text-soft: #6B7280;
    }

    .wh-page {
        background: var(--wh-bg);
        margin: -1.5rem -1.5rem 0 -1.5rem;
        padding: 20px 24px 40px 24px;
    }

    /* ---------- Header ---------- */
    .wh-header {
        background: var(--wh-card); border: 1px solid var(--wh-border);
        border-radius: 18px; padding: 28px;
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 16px; margin-bottom: 20px;
    }
    .wh-header h1 { font-size: 1.35rem; font-weight: 700; color: var(--wh-text); margin: 0 0 4px 0; }
    .wh-header p  { margin: 0; font-size: 0.85rem; color: var(--wh-text-soft); }
    .wh-count-badge {
        background: var(--wh-primary-soft); border: 1px solid #BFDBFE;
        border-radius: 999px; padding: 8px 16px; white-space: nowrap;
    }
    .wh-count-badge .lbl { font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: var(--wh-primary); }
    .wh-count-badge .val { font-size: 1.1rem; font-weight: 700; color: var(--wh-primary); }

    /* ---------- Alert ---------- */
    .wh-alert {
        border-radius: 12px; border: 1px solid; padding: 12px 16px;
        font-size: 0.85rem; margin-bottom: 16px; display: flex; align-items: center; gap: 10px;
    }
    .wh-alert.success { background: var(--wh-success-soft); border-color: #BBF7D0; color: #15803D; }
    .wh-alert.danger  { background: var(--wh-danger-soft);  border-color: #FECACA; color: #B91C1C; }
    .wh-alert .btn-close { margin-left: auto; opacity: 0.6; }

    /* ---------- Buttons ---------- */
    .wh-btn-primary {
        background: var(--wh-primary); border: 1px solid var(--wh-primary); color: #fff;
        height: 44px; border-radius: 12px; font-size: 0.85rem; font-weight: 600;
        padding: 0 22px; display: inline-flex; align-items: center; gap: 7px;
        transition: transform 120ms ease, background 120ms ease; cursor: pointer;
    }
    .wh-btn-primary:hover { background: #1D4ED8; color: #fff; }
    .wh-btn-primary:active { transform: scale(0.98); }
    .wh-btn-warning {
        background: var(--wh-warning); border: 1px solid var(--wh-warning); color: #fff;
        height: 44px; border-radius: 12px; font-size: 0.85rem; font-weight: 600;
        padding: 0 22px; display: inline-flex; align-items: center; gap: 7px;
        transition: transform 120ms ease, background 120ms ease; cursor: pointer;
    }
    .wh-btn-warning:hover { background: #D97706; color: #fff; }
    .wh-btn-warning:active { transform: scale(0.98); }
    .wh-btn-outline {
        background: #fff; border: 1px solid var(--wh-border); color: var(--wh-text);
        height: 42px; border-radius: 10px; font-size: 0.85rem; font-weight: 600;
        padding: 0 18px; display: inline-flex; align-items: center; gap: 7px;
        transition: transform 120ms ease, background 120ms ease; cursor: pointer;
    }
    .wh-btn-outline:hover { background: var(--wh-dark-soft); }
    .wh-btn-outline:active { transform: scale(0.98); }

    /* ---------- Action Bar ---------- */
    .wh-action-bar {
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 12px; margin-bottom: 16px;
    }
    .wh-action-bar .count-block .num { font-size: 1.1rem; font-weight: 700; color: var(--wh-text); }
    .wh-action-bar .count-block .lbl { font-size: 0.8rem; color: var(--wh-text-soft); margin-left: 6px; }
    .wh-action-right { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }

    /* ---------- Table Card ---------- */
    .wh-table-card {
        background: var(--wh-card); border: 1px solid var(--wh-border);
        border-radius: 18px; box-shadow: 0 4px 18px rgba(15,23,42,.05);
        overflow: hidden; padding: 0;
    }

    /* DT Toolbar */
    .wh-dt-toolbar {
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 12px; padding: 16px 20px;
        border-bottom: 1px solid var(--wh-separator);
    }
    .wh-dt-toolbar .dt-length-wrap {
        display: flex; align-items: center; gap: 8px;
        font-size: 0.82rem; color: var(--wh-text-soft);
    }
    .wh-dt-toolbar .dt-length-wrap select {
        width: 80px; height: 42px; border-radius: 10px;
        border: 1px solid var(--wh-border); font-size: 0.82rem; padding: 0 8px;
    }
    .wh-dt-search { position: relative; }
    .wh-dt-search i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--wh-text-soft); font-size: 0.82rem; }
    .wh-dt-search input {
        height: 42px; width: 260px; border-radius: 10px;
        border: 1px solid var(--wh-border); font-size: 0.85rem;
        padding: 0 12px 0 34px; color: var(--wh-text);
        transition: border-color 150ms ease, box-shadow 150ms ease;
    }
    .wh-dt-search input:focus { outline: none; border-color: var(--wh-primary); box-shadow: 0 0 0 3px rgba(37,99,235,.12); }

    /* Table */
    #dataTable { border-collapse: separate; border-spacing: 0; width: 100%; }
    #dataTable thead th {
        background: var(--wh-bg); color: var(--wh-text-soft);
        font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em;
        padding: 0 20px; height: 54px;
        border-bottom: 1px solid var(--wh-separator); border-top: none; white-space: nowrap;
    }
    #dataTable tbody td {
        padding: 0 20px; height: 58px; vertical-align: middle;
        border-bottom: 1px solid var(--wh-separator); border-top: none;
        font-size: 0.83rem; color: var(--wh-text);
    }
    #dataTable, #dataTable th, #dataTable td { border-left: none; border-right: none; }
    #dataTable tbody tr { transition: background 150ms ease; }
    #dataTable tbody tr:hover { background: #F9FAFB; }
    #dataTable tbody tr:last-child td { border-bottom: none; }

    /* Kode badge */
    .wh-kode-badge {
        background: #EEF4FF; color: var(--wh-primary);
        border-radius: 999px; padding: 5px 12px;
        font-size: 0.75rem; font-weight: 600; white-space: nowrap;
    }

    /* Nama barang */
    .wh-nama-main { font-weight: 600; font-size: 0.85rem; color: var(--wh-text); }
    .wh-nama-sub  { font-size: 0.75rem; color: #64748B; margin-top: 2px; }

    /* Batch badge */
    .wh-batch-badge {
        display: inline-flex; align-items: center; gap: 5px;
        border-radius: 999px; padding: 4px 11px;
        font-size: 0.72rem; font-weight: 600; white-space: nowrap;
    }
    .wh-batch-badge.zero  { background: var(--wh-dark-soft); color: var(--wh-text-soft); }
    .wh-batch-badge.few   { background: #DBEAFE; color: #1D4ED8; }
    .wh-batch-badge.many  { background: var(--wh-success-soft); color: #15803D; }

    /* Rename button */
    .wh-rename-btn {
        height: 36px; padding: 0 14px; border-radius: 10px;
        background: var(--wh-primary-soft); color: var(--wh-primary);
        border: none; font-size: 0.8rem; font-weight: 600;
        display: inline-flex; align-items: center; gap: 6px;
        transition: background 120ms ease, transform 120ms ease; cursor: pointer;
        min-width: 44px; min-height: 36px;
    }
    .wh-rename-btn:hover  { background: #DBEAFE; }
    .wh-rename-btn:active { transform: scale(0.98); }

    /* DT bottom */
    .wh-dt-bottom {
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 12px; padding: 14px 20px;
        border-top: 1px solid var(--wh-separator);
        font-size: 0.82rem; color: var(--wh-text-soft);
    }
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter { display: none; }
    .dataTables_wrapper .dataTables_info { font-size: 0.82rem; color: var(--wh-text-soft); }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        width: 40px; height: 40px; border-radius: 10px !important;
        padding: 0 !important; margin-left: 3px;
        display: inline-flex !important; align-items: center; justify-content: center;
        border: 1px solid transparent !important; background: transparent !important;
        color: var(--wh-text) !important; font-size: 0.82rem;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--wh-primary) !important; color: #fff !important; border-color: var(--wh-primary) !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current):not(.disabled) {
        background: var(--wh-primary-soft) !important; color: var(--wh-primary) !important;
    }

    /* ---------- Modal ---------- */
    .modal-content {
        border-radius: 18px; border: 1px solid var(--wh-border);
        box-shadow: 0 20px 60px rgba(15,23,42,.12);
    }
    .modal-header {
        border-bottom: 1px solid var(--wh-separator);
        padding: 18px 24px; align-items: center;
    }
    .modal-header .btn-close {
        width: 32px; height: 32px; background-color: var(--wh-bg);
        border-radius: 8px; opacity: 1; padding: 0; background-size: 12px;
        transition: background 150ms ease;
    }
    .modal-header .btn-close:hover { background-color: #FEE2E2; }
    .modal-title {
        font-size: 1rem; font-weight: 700; color: var(--wh-text);
        display: flex; align-items: center; gap: 8px;
    }
    .modal-body { padding: 24px; }
    .modal-body .form-label {
        font-size: 0.78rem; font-weight: 600; color: var(--wh-text); margin-bottom: 6px;
    }
    .modal-body .form-control,
    .modal-body .form-select {
        border-radius: 10px; border: 1px solid var(--wh-border); font-size: 0.85rem;
        padding: 0.6rem 0.85rem;
        transition: border-color 150ms ease, box-shadow 150ms ease;
    }
    .modal-body .form-control { height: 44px; }
    .modal-body .form-select  { height: 44px; }
    .modal-body .form-control:focus,
    .modal-body .form-select:focus {
        border-color: var(--wh-primary); box-shadow: 0 0 0 3px rgba(37,99,235,.12);
    }
    .modal-footer {
        border-top: 1px solid var(--wh-separator);
        padding: 16px 24px; gap: 8px;
    }
    .modal-footer .btn-secondary {
        background: var(--wh-bg); border: 1px solid var(--wh-border);
        color: var(--wh-text-soft); border-radius: 10px; font-size: 0.83rem; font-weight: 600;
        height: 40px; padding: 0 18px; transition: background 150ms ease, color 150ms ease;
    }
    .modal-footer .btn-secondary:hover { background: var(--wh-dark-soft); color: var(--wh-text); }
    .modal-footer .btn-primary {
        background: var(--wh-primary); border-color: var(--wh-primary);
        border-radius: 10px; font-size: 0.83rem; font-weight: 600; height: 40px; padding: 0 18px;
    }
    .modal-footer .btn-warning {
        background: var(--wh-warning); border-color: var(--wh-warning); color: #fff;
        border-radius: 10px; font-size: 0.83rem; font-weight: 600; height: 40px; padding: 0 18px;
    }

    /* ---------- Merge info banner ---------- */
    .wh-merge-info {
        background: #FFFBEB; border: 1px solid #FDE68A;
        border-radius: 12px; padding: 14px 16px; font-size: 0.82rem; color: #92400E;
        display: flex; gap: 10px; align-items: flex-start; margin-bottom: 20px;
        line-height: 1.55;
    }
    .wh-merge-info i { margin-top: 2px; flex-shrink: 0; font-size: 0.9rem; }

    /* ---------- Section label ---------- */
    .wh-section-label {
        font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.08em; color: var(--wh-text-soft); margin-bottom: 8px;
    }

    /* ---------- Source list ---------- */
    .wh-source-list {
        max-height: 220px; overflow-y: auto; border: 1px solid var(--wh-border);
        border-radius: 12px; padding: 4px 8px;
        scrollbar-width: thin; scrollbar-color: var(--wh-border) transparent;
    }
    .wh-source-list::-webkit-scrollbar { width: 4px; }
    .wh-source-list::-webkit-scrollbar-track { background: transparent; }
    .wh-source-list::-webkit-scrollbar-thumb { background: var(--wh-border); border-radius: 10px; }
    .wh-source-list .form-check {
        padding: 10px 10px 10px 36px; border-radius: 8px;
        transition: background 120ms ease; margin: 0; cursor: pointer;
        border-bottom: 1px solid var(--wh-separator);
    }
    .wh-source-list .form-check:last-child { border-bottom: none; }
    .wh-source-list .form-check:hover { background: var(--wh-primary-soft); }
    .wh-source-list .form-check-input {
        width: 16px; height: 16px; margin-top: 0; cursor: pointer;
        border: 1.5px solid #CBD5E1; border-radius: 4px;
    }
    .wh-source-list .form-check-input:checked {
        background-color: var(--wh-primary); border-color: var(--wh-primary);
    }
    .wh-source-list .form-check-label {
        font-size: 0.83rem; color: var(--wh-text); cursor: pointer;
        display: flex; align-items: center; gap: 6px; font-weight: 500;
    }
    .wh-source-list .form-check-label span {
        color: var(--wh-text-soft); font-weight: 400; font-size: 0.78rem;
    }

    /* ---------- Responsive ---------- */
    @media (max-width: 768px) {
        .wh-header { flex-direction: column; align-items: flex-start; }
        .wh-action-bar { flex-direction: column; align-items: flex-start; }
        .wh-dt-toolbar { flex-direction: column; align-items: flex-start; }
        .wh-dt-search input { width: 100%; }
        .modal-body { padding: 16px; }
        .modal-header { padding: 14px 16px; }
        .modal-footer { padding: 12px 16px; }
    }
</style>

<div class="wh-page">

    <!-- Header -->
    <div class="wh-header">
        <div>
            <h1>Maintenance Master Barang</h1>
            <p>Kelola master barang internal, lakukan rename serta merge data barang dengan aman.</p>
        </div>
        <div class="wh-count-badge">
            <div class="lbl">Master Barang</div>
            <div class="val"><?= number_format(count($barang), 0, ',', '.') ?> Data</div>
        </div>
    </div>

    <!-- Alerts -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="wh-alert success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check"></i>
            <span><?= esc(session()->getFlashdata('success')) ?></span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="wh-alert danger alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span><?= esc(session()->getFlashdata('error')) ?></span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Action Bar -->
    <div class="wh-action-bar">
        <div class="count-block">
            <span class="num"><?= number_format(count($barang), 0, ',', '.') ?></span>
            <span class="lbl">Total Master Barang</span>
        </div>
        <div class="wh-action-right">
            <button type="button" class="wh-btn-warning" data-bs-toggle="modal" data-bs-target="#mergeModal">
                <i class="fa-solid fa-code-merge"></i> Merge Barang
            </button>
        </div>
    </div>

    <!-- Table Card -->
    <div class="wh-table-card">

        <!-- Toolbar -->
        <div class="wh-dt-toolbar">
            <div class="dt-length-wrap">
                Tampilkan
                <select id="dtLengthSelect">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                entri
            </div>
            <div class="wh-dt-search">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="dtSearchInput" placeholder="Cari kode atau nama barang...">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0" id="dataTable">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Total Batch</th>
                        <th class="text-center" width="140">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($barang)): ?>
                        <tr>
                            <td colspan="5" style="text-align:center; padding: 60px 20px;">
                                <i class="fa-solid fa-box-open" style="font-size:2.5rem; color:var(--wh-border);"></i>
                                <p style="margin-top:14px; color:var(--wh-text); font-weight:600;">Master Barang Kosong</p>
                                <p style="color:var(--wh-text-soft); font-size:0.85rem;">Belum terdapat master barang internal.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($barang as $key => $b): ?>
                            <?php
                                $jBatch   = (int) $b['jumlah_batch'];
                                $batchCls = 'zero';
                                if ($jBatch >= 1 && $jBatch <= 5) $batchCls = 'few';
                                elseif ($jBatch > 5)               $batchCls = 'many';
                            ?>
                            <tr>
                                <td><?= $key + 1 ?></td>
                                <td><span class="wh-kode-badge"><?= esc($b['kode_barang']) ?></span></td>
                                <td>
                                    <div class="wh-nama-main"><?= esc($b['nama_barang']) ?></div>
                                    <div class="wh-nama-sub">Master Barang Internal</div>
                                </td>
                                <td>
                                    <span class="wh-batch-badge <?= $batchCls ?>">
                                        <i class="fa-solid fa-layer-group" style="font-size:0.6rem;"></i>
                                        <?= $jBatch ?> Batch Aktif
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="wh-rename-btn btn-rename"
                                        data-id="<?= $b['id'] ?>"
                                        data-nama="<?= esc($b['nama_barang']) ?>"
                                        data-bs-toggle="modal"
                                        data-bs-target="#renameModal"
                                        title="Rename Barang">
                                        <i class="fa-solid fa-pen-to-square"></i> Rename
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Bottom -->
        <div class="wh-dt-bottom">
            <div id="dtInfo"></div>
            <div id="dtPaginate"></div>
        </div>
    </div>
</div>

<!-- Modal Rename -->
<div class="modal fade" id="renameModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST" id="formRename">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Rename Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p style="font-size:0.83rem; color:var(--wh-text-soft); margin-bottom:16px;">
                        <i class="fa-solid fa-circle-info me-1"></i> Histori batch tidak akan hilang karena menggunakan relasi ID.
                    </p>
                    <div class="mb-3">
                        <label class="form-label">Nama Barang <span style="color:#EF4444;">*</span></label>
                        <input type="text" class="form-control" name="nama_barang" id="inputRename" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Merge -->
<div class="modal fade" id="mergeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="<?= site_url('masterdata/maintenance-barang/merge') ?>" method="POST" id="formMerge">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-code-merge me-2" style="color:var(--wh-warning);"></i>Merge Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="wh-merge-info">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <div>Pilih satu <strong>Target Utama</strong>. Seluruh batch dari barang sumber yang dicentang akan dipindahkan ke Target Utama, lalu master sumber akan dihapus. <strong>Tindakan ini tidak dapat dibatalkan.</strong></div>
                    </div>

                    <div class="mb-4">
                        <div class="wh-section-label">Target Utama (Barang yang dipertahankan)</div>
                        <select class="form-select" name="target_id" id="selectTarget" required>
                            <option value="">-- Pilih Target Utama --</option>
                            <?php foreach ($barang as $b): ?>
                                <option value="<?= $b['id'] ?>"><?= esc($b['nama_barang']) ?> (<?= esc($b['kode_barang']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-1">
                        <div class="wh-section-label">Sumber (Barang yang akan digabung &amp; dihapus)</div>
                        <div class="wh-source-list">
                            <?php foreach ($barang as $b): ?>
                                <div class="form-check mb-1 source-item" id="source-wrap-<?= $b['id'] ?>">
                                    <input class="form-check-input check-source" type="checkbox" name="source_ids[]" value="<?= $b['id'] ?>" id="source-<?= $b['id'] ?>">
                                    <label class="form-check-label" for="source-<?= $b['id'] ?>">
                                        <?= esc($b['nama_barang']) ?> <span>(<?= esc($b['kode_barang']) ?>)</span>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning"
                        onclick="return confirm('Apakah Anda yakin ingin menggabungkan barang ini? Tindakan ini tidak dapat dibatalkan.')">
                        <i class="fa-solid fa-code-merge me-1"></i> Proses Merge
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Rename — identik dengan aslinya
    const renameButtons = document.querySelectorAll('.btn-rename');
    const formRename    = document.getElementById('formRename');
    const inputRename   = document.getElementById('inputRename');

    renameButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const id   = this.getAttribute('data-id');
            const nama = this.getAttribute('data-nama');
            formRename.action = `<?= site_url('masterdata/maintenance-barang/rename') ?>/${id}`;
            inputRename.value = nama;
        });
    });

    // Merge — identik dengan aslinya
    const selectTarget = document.getElementById('selectTarget');
    const sourceItems  = document.querySelectorAll('.source-item');

    selectTarget.addEventListener('change', function () {
        const targetId = this.value;
        sourceItems.forEach(item => {
            const checkbox = item.querySelector('.check-source');
            if (checkbox.value === targetId) {
                item.style.display = 'none';
                checkbox.checked = false;
            } else {
                item.style.display = 'block';
            }
        });
    });
});

// DataTables
$(document).ready(function () {
    var table = $('#dataTable').DataTable({
        order: [],
        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
        pageLength:10,
        dom: '<"d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2"lf>rt<"d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3"ip>',
        initComplete: function () {
            $('#dataTable_info').appendTo('#dtInfo');
            $('#dataTable_paginate').appendTo('#dtPaginate');
        }
    });

    $('#dtLengthSelect').on('change', function () {
        table.page.len(parseInt(this.value)).draw();
    });

    $('#dtSearchInput').on('keyup input', function () {
        table.search(this.value).draw();
    });

    table.on('draw', function () {
        var info     = $('#dataTable_info');
        var paginate = $('#dataTable_paginate');
        if (info.parent().attr('id')     !== 'dtInfo')     info.appendTo('#dtInfo');
        if (paginate.parent().attr('id') !== 'dtPaginate') paginate.appendTo('#dtPaginate');
    });
});
</script>
<?= $this->endSection() ?>