<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="topbar d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-users-gear me-2"></i> Manajemen User</h1>
        <div class="subtle">Kelola akun pengguna dan hak akses aplikasi</div>
    </div>
    <div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fa-solid fa-plus me-2"></i> Tambah User Baru
        </button>
    </div>
</div>

<div class="panel-card">
    <?php if (session('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= session('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif ?>

    <?php if (session('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= session('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif ?>

    <?php if (session('errors')) : ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                <?php foreach (session('errors') as $error) : ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif ?>

    <div class="table-responsive">
        <table class="table table-hover align-middle" id="tableUsers">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Last Login</th>
                    <th width="15%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($users as $row) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= esc($row['username']) ?></td>
                        <td><?= esc($row['email']) ?></td>
                        <td>
                            <?php if ($row['role_name'] == 'Administrator') : ?>
                                <span class="badge bg-primary">Administrator</span>
                            <?php else : ?>
                                <span class="badge bg-info">Petugas Gudang</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($row['active']) : ?>
                                <span class="badge bg-success">Aktif</span>
                            <?php else : ?>
                                <span class="badge bg-secondary">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= $row['last_login_at'] ? date('d M Y H:i', strtotime($row['last_login_at'])) : '-' ?>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary" title="Edit" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id'] ?>"><i class="fa-solid fa-edit"></i></button>
                            <button class="btn btn-sm btn-outline-warning" title="Reset Password" data-bs-toggle="modal" data-bs-target="#modalReset<?= $row['id'] ?>"><i class="fa-solid fa-key"></i></button>
                            
                            <form action="<?= site_url('manajemen-user/toggle-status/' . $row['id']) ?>" method="post" class="d-inline">
                                <?= csrf_field() ?>
                                <?php if ($row['active']) : ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Nonaktifkan" onclick="return confirm('Apakah Anda yakin ingin menonaktifkan akun ini?')"><i class="fa-solid fa-ban"></i></button>
                                <?php else : ?>
                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Aktifkan" onclick="return confirm('Apakah Anda yakin ingin mengaktifkan kembali akun ini?')"><i class="fa-solid fa-check-circle"></i></button>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="modalEdit<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="<?= site_url('manajemen-user/update/' . $row['id']) ?>" method="post">
                                <?= csrf_field() ?>
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Email</label>
                                            <input type="email" class="form-control" value="<?= esc($row['email']) ?>" disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Nama Lengkap</label>
                                            <input type="text" name="username" class="form-control" value="<?= esc($row['username']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Role</label>
                                            <select name="role" class="form-select" required>
                                                <?php foreach ($roles as $role) : ?>
                                                    <option value="<?= $role->id ?>" <?= ($role->name == $row['role_name']) ? 'selected' : '' ?>><?= esc($role->name) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Modal Reset Password -->
                    <div class="modal fade" id="modalReset<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="<?= site_url('manajemen-user/reset/' . $row['id']) ?>" method="post">
                                <?= csrf_field() ?>
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Reset Password - <?= esc($row['username']) ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="alert alert-warning">
                                            <i class="fa-solid fa-triangle-exclamation me-2"></i> Aksi ini akan mengubah password pengguna tersebut.
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Password Baru</label>
                                            <input type="text" name="password" class="form-control" required minlength="8" placeholder="Minimal 8 karakter">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-warning text-dark"><i class="fa-solid fa-key me-2"></i> Reset Password</button>
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

<!-- Modal Tambah User -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= site_url('manajemen-user/store') ?>" method="post">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah User Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" name="username" class="form-control" value="<?= old('username') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="">-- Pilih Role --</option>
                            <?php foreach ($roles as $role) : ?>
                                <option value="<?= $role->id ?>"><?= esc($role->name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Password Default</label>
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
    $(document).ready(function() {
        $('#tableUsers').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            }
        });
    });
</script>
<?= $this->endSection() ?>

