<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php 
    $isEdit = isset($wilayah);
    $actionUrl = $isEdit ? site_url('masterdata/wilayah/update/' . $wilayah['id']) : site_url('masterdata/wilayah/store');
?>

<div class="topbar d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-map-location-dot me-2"></i><?= esc($title) ?></h1>
        <span class="subtle"><?= $isEdit ? 'Ubah data referensi wilayah' : 'Tambah data referensi wilayah baru' ?></span>
    </div>
    <a href="<?= site_url('masterdata/wilayah') ?>" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali
    </a>
</div>

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

<div class="panel-card" style="max-width: 800px;">
    <form action="<?= $actionUrl ?>" method="POST" id="formWilayah">
        <?= csrf_field() ?>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-4">
                    <label for="nama_wilayah" class="form-label fw-semibold">Nama Wilayah <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nama_wilayah" name="nama_wilayah" value="<?= old('nama_wilayah', $wilayah['nama_wilayah'] ?? '') ?>" placeholder="Contoh: Jakarta Timur" required maxlength="150">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-4">
                    <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                    <select class="form-select" id="status" name="status" required>
                        <?php $oldStatus = old('status', $wilayah['status'] ?? 'Aktif'); ?>
                        <option value="Aktif" <?= $oldStatus == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                        <option value="Nonaktif" <?= $oldStatus == 'Nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                    </select>
                </div>
            </div>
        </div>

        <hr class="mb-4 border-light">
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary rounded-pill px-4">
                <i class="fa-solid fa-save me-1"></i> <?= $isEdit ? 'Simpan Perubahan' : 'Simpan Wilayah' ?>
            </button>
            <a href="<?= site_url('masterdata/wilayah') ?>" class="btn btn-outline-secondary rounded-pill px-4">Batal</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
</script>
<?= $this->endSection() ?>

