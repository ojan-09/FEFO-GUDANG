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
<div class="panel-card">

    <h5 class="fw-bold mb-4">
        <i class="fa-solid fa-tag me-2 text-primary"></i>
        Informasi Kategori
    </h5>

    <form action="<?= $action ?>" method="POST">
        <?= csrf_field() ?>

        <div class="mb-4">
            <label class="form-label fw-semibold">
                Nama Kategori <span class="text-danger">*</span>
            </label>

            <input
                type="text"
                class="form-control"
                name="nama_kategori"
                value="<?= old('nama_kategori', $isEdit ? $kategori['nama_kategori'] : '') ?>"
                placeholder="Masukkan nama kategori"
                required>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="<?= site_url('masterdata/kategori') ?>"
               class="btn btn-outline-secondary rounded-pill px-4">
                Batal
            </a>

            <button type="submit"
                    class="btn btn-primary rounded-pill px-4">
                <i class="fa-solid fa-floppy-disk me-2"></i>
                Simpan Perubahan
            </button>
        </div>

    </form>

</div>
<style>
.panel-card{
    width:100%;
    background:#fff;
    border-radius:22px;
    padding:32px;
    margin-top:28px; /* tambah */
    border:1px solid #e9ecef;
    box-shadow:0 10px 30px rgba(0,0,0,.05);
}

.form-control{
    height:52px;
    border-radius:12px;
}

.form-control:focus{
    box-shadow:none;
    border-color:#3b82f6;
}

.btn{
    min-width:160px;
    height:48px;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    font-weight:600;
    font-size:15px;
    line-height:1;
}

.btn i{
    font-size:15px;
    line-height:1;
    margin:0;
}
</style>
<?= $this->endSection() ?>
