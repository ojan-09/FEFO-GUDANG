<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php
    $isEdit = isset($kategori);
    $action = $isEdit ? site_url('masterdata/kategori/update/' . $kategori['id']) : site_url('masterdata/kategori/store');
?>

<!-- Topbar -->
<div class="topbar d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-tags me-2"></i><?= $title ?></h1>
        <span class="subtle"><?= $isEdit ? 'Ubah data kategori' : 'Tambah kategori baru' ?></span>
    </div>
    <a href="<?= site_url('masterdata/kategori') ?>" class="btn btn-outline-secondary rounded-pill px-4">
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
<div class="panel-card" style="max-width: 600px;">
    <form action="<?= $action ?>" method="POST">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="nama_kategori" class="form-label fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nama_kategori" name="nama_kategori"
                placeholder="Contoh: Makanan Pokok"
                value="<?= old('nama_kategori', $isEdit ? $kategori['nama_kategori'] : '') ?>" required>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary rounded-pill px-4">
                <i class="fa-solid fa-<?= $isEdit ? 'save' : 'plus' ?> me-1"></i> <?= $isEdit ? 'Simpan Perubahan' : 'Tambah Kategori' ?>
            </button>
            <a href="<?= site_url('masterdata/kategori') ?>" class="btn btn-outline-secondary rounded-pill px-4">Batal</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
