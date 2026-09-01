<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<style>
    :root {
        --wh-bg: #F8FAFC;
        --wh-card: #FFFFFF;
        --wh-border: #E5E7EB;
        --wh-primary: #2563EB;
        --wh-primary-soft: #EFF6FF;
        --wh-success-soft: #ECFDF5;
        --wh-warning-soft: #FEF3C7;
        --wh-danger-soft: #FEF2F2;
        --wh-dark-soft: #F3F4F6;
        --wh-text: #111827;
        --wh-text-soft: #6B7280;
        --wh-separator: #EEF2F7;
    }

    .wh-page {
        background: var(--wh-bg);
        margin: -1.5rem -1.5rem 0 -1.5rem;
        padding: 20px 24px 40px 24px;
    }

    /* ---------- Header ---------- */
    .wh-header {
        background: var(--wh-card);
        border: 1px solid var(--wh-border);
        border-radius: 18px;
        padding: 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 20px;
    }

    .wh-header h1 {
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--wh-text);
        margin: 0 0 4px 0;
    }

    .wh-header p {
        margin: 0;
        font-size: 0.85rem;
        color: var(--wh-text-soft);
    }

    .wh-btn-primary {
        background: var(--wh-primary);
        border: 1px solid var(--wh-primary);
        color: #fff;
        height: 42px;
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0 20px;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: transform 120ms ease, background 120ms ease;
        text-decoration: none;
        cursor: pointer;
    }

    .wh-btn-primary:hover {
        background: #1D4ED8;
        color: #fff;
    }

    .wh-btn-primary:active {
        transform: scale(0.98);
    }

    /* ---------- Alert ---------- */
    .wh-alert {
        border-radius: 12px;
        border: 1px solid;
        padding: 12px 16px;
        font-size: 0.85rem;
        margin-bottom: 16px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }

    .wh-alert.success {
        background: var(--wh-success-soft);
        border-color: #BBF7D0;
        color: #15803D;
    }

    .wh-alert.danger {
        background: var(--wh-danger-soft);
        border-color: #FECACA;
        color: #B91C1C;
    }

    .wh-alert .btn-close {
        margin-left: auto;
        opacity: 0.6;
    }

    /* ---------- Table Card ---------- */
    .wh-table-card {
        background: var(--wh-card);
        border: 1px solid var(--wh-border);
        border-radius: 18px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05);
        overflow: hidden;
        padding: 0;
    }

    /* DataTables toolbar override */
    .wh-dt-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        padding: 16px 20px;
        border-bottom: 1px solid var(--wh-separator);
    }

    .wh-dt-toolbar .dt-length-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.82rem;
        color: var(--wh-text-soft);
    }

    .wh-dt-toolbar .dt-length-wrap select {
        width: 80px;
        height: 42px;
        border-radius: 10px;
        border: 1px solid var(--wh-border);
        font-size: 0.82rem;
        padding: 0 8px;
        color: var(--wh-text);
    }

    .wh-dt-search {
        position: relative;
    }

    .wh-dt-search i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--wh-text-soft);
        font-size: 0.82rem;
    }

    .wh-dt-search input {
        height: 42px;
        width: 260px;
        border-radius: 10px;
        border: 1px solid var(--wh-border);
        font-size: 0.85rem;
        padding: 0 12px 0 34px;
        color: var(--wh-text);
    }

    .wh-dt-search input:focus {
        outline: none;
        border-color: var(--wh-primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    /* Table */
    #tableUsers {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }

    #tableUsers thead th {
        background: var(--wh-bg);
        color: var(--wh-text-soft);
        font-size: 0.68rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 0 20px;
        height: 54px;
        border-bottom: 1px solid var(--wh-separator);
        border-top: none;
        white-space: nowrap;
    }

    #tableUsers tbody td {
        padding: 0 20px;
        height: 58px;
        vertical-align: middle;
        border-bottom: 1px solid var(--wh-separator);
        border-top: none;
        font-size: 0.83rem;
        color: var(--wh-text);
    }

    #tableUsers,
    #tableUsers th,
    #tableUsers td {
        border-left: none;
        border-right: none;
    }

    #tableUsers tbody tr {
        transition: background 150ms ease;
    }

    #tableUsers tbody tr:hover {
        background: var(--wh-bg);
    }

    #tableUsers tbody tr:last-child td {
        border-bottom: none;
    }

    /* Avatar */
    .wh-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.82rem;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
        background: linear-gradient(135deg, #2563EB, #7C3AED);
    }

    .wh-user-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .wh-user-cell .name {
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--wh-text);
    }

    .wh-user-cell .email {
        font-size: 0.76rem;
        color: var(--wh-text-soft);
        margin-top: 1px;
    }

    /* Badges */
    .wh-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border-radius: 999px;
        padding: 5px 12px;
        font-size: 0.72rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .wh-badge.admin {
        background: #EEF2FF;
        color: #4338CA;
    }

    .wh-badge.petugas {
        background: #ECFDF5;
        color: #047857;
    }

    .wh-badge.supervisor {
        background: var(--wh-warning-soft);
        color: #B45309;
    }

    .wh-badge.aktif {
        background: #ECFDF5;
        color: #15803D;
    }

    .wh-badge.nonaktif {
        background: var(--wh-danger-soft);
        color: #B91C1C;
    }

    /* Last login */
    .wh-login-date {
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--wh-text);
    }

    .wh-login-time {
        font-size: 0.72rem;
        color: var(--wh-text-soft);
        margin-top: 1px;
    }

    .wh-login-null {
        color: var(--wh-text-soft);
        font-size: 0.82rem;
    }

    /* Action buttons */
    .wh-actions {
        display: flex;
        gap: 6px;
        justify-content: center;
    }

    .wh-action-btn {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        font-size: 0.8rem;
        cursor: pointer;
        transition: transform 120ms ease, background 120ms ease;
        min-width: 44px;
        min-height: 44px;
    }

    .wh-action-btn:active {
        transform: scale(0.98);
    }

    .wh-action-btn.edit {
        background: var(--wh-primary-soft);
        color: var(--wh-primary);
    }

    .wh-action-btn.edit:hover {
        background: #DBEAFE;
    }

    .wh-action-btn.reset {
        background: var(--wh-warning-soft);
        color: #B45309;
    }

    .wh-action-btn.reset:hover {
        background: #FDE68A;
    }

    .wh-action-btn.danger {
        background: var(--wh-danger-soft);
        color: #B91C1C;
    }

    .wh-action-btn.danger:hover {
        background: #FECACA;
    }

    .wh-action-btn.success {
        background: var(--wh-success-soft);
        color: #047857;
    }

    .wh-action-btn.success:hover {
        background: #BBF7D0;
    }

    /* DT bottom bar */
    .wh-dt-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        padding: 14px 20px;
        border-top: 1px solid var(--wh-separator);
        font-size: 0.82rem;
        color: var(--wh-text-soft);
    }

    /* Hide default DT controls */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        display: none;
    }

    .dataTables_wrapper .dataTables_info {
        font-size: 0.82rem;
        color: var(--wh-text-soft);
    }

    /* Modal overrides */
    .modal-content {
        border-radius: 16px;
        border: 1px solid var(--wh-border);
    }

    .modal-header {
        border-bottom: 1px solid var(--wh-separator);
        padding: 20px 24px;
    }

    .modal-footer {
        border-top: 1px solid var(--wh-separator);
        padding: 16px 24px;
    }

    .modal-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--wh-text);
    }

    .modal-body {
        padding: 20px 24px;
    }

    .modal-body .form-label {
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--wh-text);
        margin-bottom: 6px;
    }

    .modal-body .form-control,
    .modal-body .form-select {
        height: 44px;
        border-radius: 10px;
        border: 1px solid var(--wh-border);
        font-size: 0.85rem;
        padding: 0.5rem 0.75rem;
    }

    .modal-body .form-control:focus,
    .modal-body .form-select:focus {
        border-color: var(--wh-primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    .modal-body .form-text {
        font-size: 0.75rem;
        color: var(--wh-text-soft);
    }

    .modal-body .alert {
        border-radius: 10px;
        font-size: 0.83rem;
        background: var(--wh-warning-soft);
        border-color: #FDE68A;
        color: #B45309;
    }

    .modal-footer .btn-secondary {
        background: var(--wh-dark-soft);
        border: 1px solid var(--wh-border);
        color: var(--wh-text);
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        height: 42px;
        padding: 0 18px;
    }

    .modal-footer .btn-primary {
        background: var(--wh-primary);
        border-color: var(--wh-primary);
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        height: 42px;
        padding: 0 18px;
    }

    .modal-footer .btn-warning {
        background: var(--wh-warning-soft);
        border-color: #FDE68A;
        color: #B45309;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        height: 42px;
        padding: 0 18px;
    }

    @media (max-width: 768px) {
        .wh-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .wh-dt-search input {
            width: 100%;
        }

        .wh-dt-toolbar {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<div class="wh-page">

    <!-- Header -->
    <div class="wh-header">
        <div>
            <h1>Manajemen User</h1>
            <p>Kelola akun pengguna dan hak akses sistem.</p>
        </div>
        <button class="wh-btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fa-solid fa-plus"></i> Tambah User
        </button>
    </div>

    <!-- Alerts -->
    <?php if (session('success')): ?>
        <div class="wh-alert success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check"></i>
            <span><?= session('success') ?></span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (session('error')): ?>
        <div class="wh-alert danger alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span><?= session('error') ?></span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (session('errors')): ?>
        <div class="wh-alert danger alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-exclamation"></i>
            <ul class="mb-0 ps-3">
                <?php foreach (session('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Table Card -->
    <div class="wh-table-card">

        <!-- Custom Toolbar (connected to DataTables via JS) -->
        <div class="wh-dt-toolbar">
            <div class="dt-length-wrap">
                Tampilkan
                <select id="dtLengthSelect">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                entri
            </div>
            <div class="wh-dt-search">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="dtSearchInput" placeholder="Cari nama atau email...">
            </div>
        </div>

        <table class="table align-middle mb-0" id="tableUsers">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>Pengguna</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Last Login</th>
                    <th class="text-center" width="140">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($users as $row): ?>
                    <?php
                    // Avatar inisial dari nama
                    $nameParts = explode(' ', trim($row['username']));
                    $initials = strtoupper(substr($nameParts[0], 0, 1));
                    if (count($nameParts) > 1)
                        $initials .= strtoupper(substr(end($nameParts), 0, 1));

                    // Badge role
                    $roleBadge = 'petugas';
                    $roleLabel = esc($row['role_name']);
                    if ($row['role_name'] == 'Administrator')
                        $roleBadge = 'admin';
                    elseif (stripos($row['role_name'], 'supervisor') !== false)
                        $roleBadge = 'supervisor';

                    // Last login
                    $lastLogin = '-';
                    if ($row['last_login_at']) {
                        $lastLogin = '<div class="wh-login-date">' . date('d M Y', strtotime($row['last_login_at'])) . '</div>'
                            . '<div class="wh-login-time">' . date('H:i', strtotime($row['last_login_at'])) . ' WIB</div>';
                    } else {
                        $lastLogin = '<span class="wh-login-null">—</span>';
                    }
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <div class="wh-user-cell">
                                <div class="wh-avatar"><?= $initials ?></div>
                                <div>
                                    <div class="name"><?= esc($row['username']) ?></div>
                                    <div class="email"><?= esc($row['email']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td><span class="wh-badge <?= $roleBadge ?>"><?= $roleLabel ?></span></td>
                        <td>
                            <?php if ($row['active']): ?>
                                <span class="wh-badge aktif"><i class="fa-solid fa-circle" style="font-size:0.5rem;"></i>
                                    Aktif</span>
                            <?php else: ?>
                                <span class="wh-badge nonaktif"><i class="fa-solid fa-circle" style="font-size:0.5rem;"></i>
                                    Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $lastLogin ?></td>
                        <td>
                            <div class="wh-actions">
                                <button class="wh-action-btn edit" title="Edit" data-bs-toggle="modal"
                                    data-bs-target="#modalEdit<?= $row['id'] ?>">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button class="wh-action-btn reset" title="Reset Password" data-bs-toggle="modal"
                                    data-bs-target="#modalReset<?= $row['id'] ?>">
                                    <i class="fa-solid fa-key"></i>
                                </button>
                                <?php if ($row['active']): ?>
                                    <form action="<?= site_url('manajemen-user/toggle-status/' . $row['id']) ?>" method="post"
                                        class="d-inline form-delete-swal" data-confirm-title="Nonaktifkan akun ini?"
                                        data-confirm-button="Ya, Nonaktifkan"
                                        data-confirm-text="Akun ini tidak dapat digunakan sampai diaktifkan kembali.">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="wh-action-btn danger" title="Nonaktifkan">
                                            <i class="fa-solid fa-ban"></i>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <form action="<?= site_url('manajemen-user/toggle-status/' . $row['id']) ?>" method="post"
                                        class="d-inline form-delete-swal" data-confirm-title="Aktifkan kembali akun ini?"
                                        data-confirm-button="Ya, Aktifkan"
                                        data-confirm-text="Akun ini akan dapat digunakan kembali oleh pengguna tersebut.">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="wh-action-btn success" title="Aktifkan">
                                            <i class="fa-solid fa-circle-check"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="modalEdit<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <form action="<?= site_url('manajemen-user/update/' . $row['id']) ?>" method="post"
                                class="loading-form" data-overlay="true">
                                <?= csrf_field() ?>
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" value="<?= esc($row['email']) ?>"
                                                disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Nama Lengkap</label>
                                            <input type="text" name="username" class="form-control"
                                                value="<?= esc($row['username']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Role</label>
                                            <select name="role" class="form-select" required>
                                                <?php foreach ($roles as $role): ?>
                                                    <option value="<?= $role->id ?>" <?= ($role->name == $row['role_name']) ? 'selected' : '' ?>><?= esc($role->name) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Gudang Wilayah (Opsional untuk Operator Gudang
                                                Wilayah)</label>
                                            <select name="id_gudang_wilayah" class="form-select">
                                                <option value="">-- Tidak Ada / Akses Semua --</option>
                                                <?php foreach ($gudang as $g): ?>
                                                    <option value="<?= $g['id'] ?>" <?= ($g['id'] == $row['id_gudang_wilayah']) ? 'selected' : '' ?>><?= esc($g['nama']) ?> - <?= esc($g['kota']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Modal Reset Password -->
                    <div class="modal fade" id="modalReset<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <form action="<?= site_url('manajemen-user/reset/' . $row['id']) ?>" method="post"
                                class="loading-form" data-overlay="true">
                                <?= csrf_field() ?>
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Reset Password — <?= esc($row['username']) ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="alert">
                                            <i class="fa-solid fa-triangle-exclamation me-2"></i> Aksi ini akan mengubah
                                            password pengguna tersebut.
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Password Baru</label>
                                            <input type="text" name="password" class="form-control" required minlength="8"
                                                placeholder="Minimal 8 karakter">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-warning"><i class="fa-solid fa-key me-2"></i>
                                            Reset Password</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="<?= site_url('manajemen-user/store') ?>" method="post" class="loading-form" data-overlay="true">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah User Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="username" class="form-control" value="<?= old('username') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="">-- Pilih Role --</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= $role->id ?>"><?= esc($role->name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gudang Wilayah (Opsional untuk Operator Gudang Wilayah)</label>
                        <select name="id_gudang_wilayah" class="form-select">
                            <option value="">-- Tidak Ada / Akses Semua --</option>
                            <?php foreach ($gudang as $g): ?>
                                <option value="<?= $g['id'] ?>"><?= esc($g['nama']) ?> - <?= esc($g['kota']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Default</label>
                        <input type="text" name="password" class="form-control" value="foi12345" required minlength="8">
                        <div class="form-text">Password default minimal 8 karakter.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah User</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function () {
        // Init DataTables — struktur identik dengan aslinya
        var table = $('#tableUsers').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
            // Pindahkan info & paginate ke container kustom
            dom: 'rt<"wh-dt-bottom"ip>'
        });

        // Toolbar: length
        $('#dtLengthSelect').on('change', function () {
            table.page.len(parseInt(this.value)).draw();
        });

        // Toolbar: search
        $('#dtSearchInput').on('keyup input', function () {
            table.search(this.value).draw();
        });
    });
</script>
<?= $this->endSection() ?>