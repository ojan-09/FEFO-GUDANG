<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<style>
    /* --- compact sizing pass for this page (sizes only, no colors changed) --- */
    .topbar {
        padding: 12px 18px;
    }
    .topbar .page-title {
        font-size: 1.15rem;
    }
    .topbar .subtle {
        font-size: 0.8rem;
    }
    .panel-card {
        padding: 16px 18px;
    }
    .panel-card .form-label {
        font-size: 0.82rem;
        margin-bottom: 0.3rem;
    }
    .panel-card .form-control {
        font-size: 0.85rem;
        padding: 0.45rem 0.7rem;
        height: 38px;
    }
    .panel-card .form-text {
        font-size: 0.72rem;
    }
    .panel-card h5 {
        font-size: 0.92rem;
        font-weight: 700;
    }
    .panel-card .btn {
        font-size: 0.85rem;
        padding: 0.45rem 1.1rem;
    }
    .panel-card .alert {
        font-size: 0.82rem;
        padding: 0.6rem 0.85rem;
    }
</style>

<div class="topbar d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-id-card me-2"></i> Profil Pengguna</h1>
        <div class="subtle">Kelola informasi akun Anda</div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="panel-card">
            <?php if (session('success')) : ?>
                <div class="alert alert-success"><?= session('success') ?></div>
            <?php endif ?>

            <?php if (session('errors')) : ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach (session('errors') as $error) : ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endif ?>

            <form action="<?= site_url('profil/update') ?>" method="post">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" class="form-control" value="<?= esc($user->email) ?>" readonly>
                    <div class="form-text">Email digunakan untuk login dan tidak dapat diubah.</div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Role (Hak Akses)</label>
                    <input type="text" class="form-control" value="<?= esc(get_user_role()) ?>" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Lengkap</label>
                    <input type="text" name="username" class="form-control" value="<?= old('username', $user->username) ?>" required>
                </div>

                <hr class="my-4">
                <h5 class="mb-3">Ubah Password <small class="text-muted fs-6 fw-normal">(Opsional)</small></h5>

                <div class="mb-3">
                    <label class="form-label fw-bold">Password Baru</label>
                    <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin diubah">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Konfirmasi Password Baru</label>
                    <input type="password" name="pass_confirm" class="form-control" placeholder="Ulangi password baru">
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-2"></i> Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>