<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<style>
    .topbar {
        padding: 12px 18px;
    }
    .topbar .page-title {
        font-size: 1.15rem;
    }
    .panel-card {
        padding: 14px 16px;
    }

    /* filter form */
    .panel-card form .form-label {
        font-size: 0.78rem;
        margin-bottom: 0.25rem;
    }
    .panel-card form .form-control,
    .panel-card form .form-select {
        font-size: 0.85rem;
        padding: 0.4rem 0.65rem;
        height: 36px;
    }
    .panel-card form .btn {
        font-size: 0.82rem;
        padding: 0.4rem 0.9rem;
        height: 36px;
    }

    /* table */
    #dataTable thead th {
        font-size: 0.72rem;
        padding: 0.55rem 0.6rem;
    }
    #dataTable tbody td {
        font-size: 0.8rem;
        padding: 0.45rem 0.6rem;
        vertical-align: middle;
    }
    #dataTable .badge {
        font-size: 0.68rem;
        padding: 0.32em 0.6em;
        font-weight: 500;
    }

    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        font-size: 0.82rem;
    }
    .dataTables_wrapper .dataTables_filter input {
        padding: 0.25rem 0.5rem;
        font-size: 0.82rem;
    }
    .dataTables_wrapper .dataTables_length select {
        font-size: 0.82rem;
        padding: 0.25rem 1.75rem 0.25rem 0.5rem;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.3rem 0.65rem;
        font-size: 0.8rem;
    }
</style>

<!-- Topbar -->
<div class="topbar d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-clock-rotate-left me-2"></i>Log Aktivitas (Audit Trail)</h1>
        <span class="subtle" style="font-size:0.8rem;">Rekam jejak seluruh aktivitas pengguna sistem</span>
    </div>
</div>

<!-- Filter Box -->
<div class="panel-card mb-4" style="background-color: #f8f9fa;">
    <form method="GET" action="<?= base_url('pengaturan/log-aktivitas') ?>" class="row align-items-end g-3">
        <div class="col-md-2">
            <label class="form-label fw-bold">Tanggal Mulai</label>
            <input type="date" class="form-control" name="tanggal_mulai" value="<?= esc($tanggal_mulai) ?>">
        </div>
        <div class="col-md-2">
            <label class="form-label fw-bold">Tanggal Selesai</label>
            <input type="date" class="form-control" name="tanggal_selesai" value="<?= esc($tanggal_selesai) ?>">
        </div>
        <div class="col-md-2">
            <label class="form-label fw-bold">Modul</label>
            <select class="form-select" name="modul">
                <option value="">Semua Modul</option>
                <?php foreach($moduls as $m): ?>
                    <option value="<?= $m ?>" <?= $filter_modul == $m ? 'selected' : '' ?>><?= $m ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label fw-bold">Role</label>
            <select class="form-select" name="role">
                <option value="">Semua Role</option>
                <?php foreach($roles as $r): ?>
                    <option value="<?= $r->name ?>" <?= $filter_role == $r->name ? 'selected' : '' ?>><?= $r->name ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-bold">Pencarian</label>
            <input type="text" class="form-control" name="search" placeholder="Cari nama, deskripsi..." value="<?= esc($search) ?>">
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i></button>
        </div>
    </form>
</div>

<!-- Table -->
<div class="panel-card">
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped table-sm align-middle mb-0" id="dataTable" width="100%" cellspacing="0">
            <thead class="table-light">
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th width="15%" class="text-nowrap">Tanggal & Waktu</th>
                    <th width="15%" class="text-nowrap">Pengguna</th>
                    <th width="10%" class="text-nowrap">Role</th>
                    <th width="20%" class="text-nowrap">Modul / Aktivitas</th>
                    <th width="35%">Deskripsi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)) : ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Tidak ada data log aktivitas ditemukan.</td>
                    </tr>
                <?php else : ?>
                    <?php $no = 1; foreach ($logs as $log) : ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td class="text-nowrap"><?= date('d/m/Y H:i:s', strtotime($log['created_at'])) ?></td>
                            <td class="text-nowrap"><?= esc($log['nama_user'] ?? 'Sistem / Guest') ?></td>
                            <td><span class="badge bg-secondary"><?= esc($log['role'] ?? '-') ?></span></td>
                            <td>
                                <strong><?= esc($log['modul']) ?></strong><br>
                                <small class="text-muted"><?= esc($log['aktivitas']) ?></small>
                            </td>
                            <td><?= esc($log['deskripsi']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $('#dataTable').DataTable({
        "order": [],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
        },
        "pageLength": 25,
        "columnDefs": [
            { "orderable": false, "targets": [0] }
        ]
    });
});
</script>
<?= $this->endSection() ?>