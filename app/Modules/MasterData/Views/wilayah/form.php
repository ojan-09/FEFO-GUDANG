<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php
    $isEdit    = isset($wilayah);
    $actionUrl = $isEdit
        ? site_url('masterdata/wilayah/update/' . $wilayah['id'])
        : site_url('masterdata/wilayah/store');
    $errors    = session()->getFlashdata('errors') ?? [];
?>

<div class="panel-card topbar-card d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">
            <i class="fa-solid fa-map-location-dot me-2"></i><?= esc($title) ?>
        </h1>
        <span class="subtle">
            <?= $isEdit ? 'Ubah data referensi wilayah' : 'Tambah data referensi wilayah baru' ?>
        </span>
    </div>
    <a href="<?= site_url('masterdata/wilayah') ?>" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<?php if (!empty($errors)) : ?>
    <div class="alert alert-danger rounded-3 mt-3">
        <i class="fa-solid fa-triangle-exclamation me-2"></i><strong>Terjadi Kesalahan:</strong>
        <ul class="mb-0 mt-1">
            <?php foreach ($errors as $err) : ?>
                <li><?= esc($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="panel-card panel-form">
    <form action="<?= $actionUrl ?>" method="POST" id="formWilayah" class="loading-form" data-overlay="true" novalidate>
        <?= csrf_field() ?>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-4">
                    <label for="nama_wilayah" class="form-label fw-semibold">
                        Nama Wilayah <span class="text-danger">*</span>
                    </label>
                    <input
                        type="text"
                        class="form-control <?= isset($errors['nama_wilayah']) ? 'is-invalid' : '' ?>"
                        id="nama_wilayah"
                        name="nama_wilayah"
                        value="<?= old('nama_wilayah', $wilayah['nama_wilayah'] ?? '') ?>"
                        placeholder="Contoh: Jakarta Timur"
                        maxlength="150"
                        required
                    >
                    <?php if (isset($errors['nama_wilayah'])) : ?>
                        <div class="invalid-feedback"><?= esc($errors['nama_wilayah']) ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-4">
                    <label for="status" class="form-label fw-semibold">
                        Status <span class="text-danger">*</span>
                    </label>
                    <?php $oldStatus = old('status', $wilayah['status'] ?? 'Aktif'); ?>
                    <select
                        class="form-select <?= isset($errors['status']) ? 'is-invalid' : '' ?>"
                        id="status"
                        name="status"
                        required
                    >
                        <option value="Aktif" <?= $oldStatus === 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                        <option value="Nonaktif" <?= $oldStatus === 'Nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                    </select>
                    <?php if (isset($errors['status'])) : ?>
                        <div class="invalid-feedback"><?= esc($errors['status']) ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <hr class="form-divider mb-4">

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary rounded-pill px-4">
                <i class="fa-solid fa-save me-1"></i>
                <?= $isEdit ? 'Simpan Perubahan' : 'Simpan Wilayah' ?>
            </button>
            <a href="<?= site_url('masterdata/wilayah') ?>" class="btn btn-outline-secondary rounded-pill px-4">
                Batal
            </a>
        </div>
    </form>
</div>

<style>
    /* ===== Card umum ===== */
    .panel-card {
        width:100%;
            margin-top:28px; /* tambah */
        background: #ffffff;
        border-radius: 18px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.06);
        padding: 24px 28px;
    }

    /* ===== Topbar card ===== */
    .topbar-card {
        margin-bottom: 20px;
    }

    .page-title {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 4px;
        color: #0f172a;
    }

    .subtle {
        font-size: 14px;
        color: #64748b;
    }

    /* ===== Form card ===== */
        .panel-form {
        width: 100%;
        margin-top: 20px;
        padding: 32px;
    }

    .form-label {
        font-size: 15px;
        color: #0f172a;
        margin-bottom: 8px;
    }

    .form-control,
    .form-select {
        height: 52px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        font-size: 16px;
        padding: 0 16px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }

    .form-divider {
        border: none;
        border-top: 1px solid #eef1f5;
    }

    .invalid-feedback {
        font-size: 13px;
    }

    /* ===== Tombol ===== */
    .btn-primary {
        background-color: #2563eb;
        border-color: #2563eb;
    }

    .btn-primary:hover {
        background-color: #1d4ed8;
        border-color: #1d4ed8;
    }

    .btn-outline-secondary {
        color: #475569;
        border-color: #e2e8f0;
    }

    .btn-outline-secondary:hover {
        background-color: #f8fafc;
        color: #0f172a;
    }
</style>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.getElementById('formWilayah').addEventListener('submit', function (e) {
        const nama = document.getElementById('nama_wilayah');

        if (!nama.value.trim()) {
            e.preventDefault();
            nama.classList.add('is-invalid');
            nama.focus();
        } else {
            nama.classList.remove('is-invalid');
        }
    });
</script>
<?= $this->endSection() ?>