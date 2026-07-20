<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php
    $isEdit = isset($donatur);
    $action = $isEdit ? site_url('masterdata/donatur/update/' . $donatur['id']) : site_url('masterdata/donatur/store');
?>

<!-- Topbar -->
<div class="topbar d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-people-group me-2"></i><?= $title ?></h1>
        <span class="subtle"><?= $isEdit ? 'Ubah data donatur' : 'Tambah donatur baru' ?></span>
    </div>
    <a href="<?= site_url('masterdata/donatur') ?>" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<!-- Validation Errors -->
<?php if (session()->getFlashdata('errors')) : ?>
    <div class="alert alert-danger rounded-3">
        <i class="fa-solid fa-triangle-exclamation me-2"></i><strong>Terjadi Kesalahan:</strong>
        <ul class="mb-0 mt-1">
            <?php foreach (session()->getFlashdata('errors') as $err) : ?>
                <li><?= esc($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- Form -->
<div class="panel-card">
    <form action="<?= $action ?>" method="POST" class="loading-form" data-overlay="true">
        <?= csrf_field() ?>
        <div class="row">
            <!-- Kolom Kiri -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="nama_donatur" class="form-label fw-semibold">Nama Donatur <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nama_donatur" name="nama_donatur"
                        placeholder="Contoh: PT Sejahtera Abadi"
                        value="<?= old('nama_donatur', $isEdit ? $donatur['nama_donatur'] : '') ?>" required>
                </div>
                <div class="mb-3">
                    <label for="jenis_donatur" class="form-label fw-semibold">Jenis Donatur <span class="text-danger">*</span></label>
                    <select class="form-select" id="jenis_donatur" name="jenis_donatur" required>
                        <option value="">-- Pilih Jenis --</option>
                        <option value="Individu" <?= old('jenis_donatur', $isEdit ? $donatur['jenis_donatur'] : '') == 'Individu' ? 'selected' : '' ?>>Individu</option>
                        <option value="Perusahaan/Organisasi" <?= old('jenis_donatur', $isEdit ? $donatur['jenis_donatur'] : '') == 'Perusahaan/Organisasi' ? 'selected' : '' ?>>Perusahaan / Organisasi</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="kontak" class="form-label fw-semibold">Kontak / No. Telepon <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="kontak" name="kontak"
                        placeholder="Contoh: 08123456789"
                        value="<?= old('kontak', $isEdit ? $donatur['kontak'] : '') ?>" required>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <input type="email" class="form-control" id="email" name="email"
                        placeholder="Contoh: donatur@email.com"
                        value="<?= old('email', $isEdit ? $donatur['email'] : '') ?>">
                </div>
                <div class="mb-3">
                    <label for="alamat" class="form-label fw-semibold">Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="4"
                        placeholder="Alamat lengkap donatur"><?= old('alamat', $isEdit ? $donatur['alamat'] : '') ?></textarea>
                </div>
            </div>
        </div>

        <hr>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary rounded-pill px-4">
                <i class="fa-solid fa-<?= $isEdit ? 'save' : 'plus' ?> me-1"></i> <?= $isEdit ? 'Simpan Perubahan' : 'Tambah Donatur' ?>
            </button>
            <a href="<?= site_url('masterdata/donatur') ?>" class="btn btn-outline-secondary rounded-pill px-4">Batal</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
