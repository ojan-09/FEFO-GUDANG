<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800"><?= esc($title) ?></h1>
            <p class="text-muted mb-0">Lengkapi form di bawah ini untuk mengelola Master Barang Wilayah</p>
        </div>
        <a href="<?= site_url('wilayah/master-barang') ?>" class="btn btn-secondary shadow-sm">
            <i class="fa-solid fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <?php if (session()->getFlashdata('errors')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Terjadi Kesalahan!</strong>
            <ul class="mb-0 mt-2">
                <?php foreach (session()->getFlashdata('errors') as $err) : ?>
                    <li><?= esc($err) ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <?php 
                $isEdit = isset($barang);
                $action = $isEdit ? site_url('wilayah/master-barang/update/' . $barang['id']) : site_url('wilayah/master-barang/store');
            ?>
            <form action="<?= $action ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Kode Barang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="kode_barang" value="<?= old('kode_barang', $barang['kode_barang'] ?? '') ?>" required placeholder="Misal: BW-0001">
                        <small class="text-muted">Kode barang harus unik.</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_barang" value="<?= old('nama_barang', $barang['nama_barang'] ?? '') ?>" required placeholder="Misal: Beras Premium Wilayah">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-5">
                        <label class="form-label fw-bold">Kategori Barang <span class="text-danger">*</span></label>
                        <select class="form-select" name="id_kategori" required>
                            <option value="">Pilih Kategori...</option>
                            <?php foreach ($kategori as $kat) : ?>
                                <option value="<?= $kat['id'] ?>" <?= old('id_kategori', $barang['id_kategori'] ?? '') == $kat['id'] ? 'selected' : '' ?>>
                                    <?= esc($kat['nama_kategori']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Satuan <span class="text-danger">*</span></label>
                        <select class="form-select" name="satuan" required>
                            <option value="">-- Pilih Satuan --</option>
                            <?php
                                $satuanOpsi = ['Karung', 'Dus', 'Box', 'Pack', 'Pcs', 'Botol', 'Kaleng', 'Sak', 'Tray', 'Pouch', 'Kg', 'Gram'];
                                foreach ($satuanOpsi as $s) :
                            ?>
                                <option value="<?= $s ?>" <?= old('satuan', $barang['satuan'] ?? '') == $s ? 'selected' : '' ?>><?= $s ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                        <select class="form-select" name="status" required>
                            <option value="Aktif" <?= old('status', $barang['status'] ?? '') == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                            <option value="Nonaktif" <?= old('status', $barang['status'] ?? '') == 'Nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Berat per Satuan</label>
                        <input type="number" step="0.01" class="form-control" name="berat_per_satuan" value="<?= old('berat_per_satuan', $barang['berat_per_satuan'] ?? '') ?>" placeholder="Misal: 5.00">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Satuan Berat</label>
                        <select class="form-select" name="satuan_berat">
                            <option value="">-- Pilih Satuan Berat --</option>
                            <option value="Gram" <?= old('satuan_berat', $barang['satuan_berat'] ?? '') == 'Gram' ? 'selected' : '' ?>>Gram</option>
                            <option value="Kg" <?= old('satuan_berat', $barang['satuan_berat'] ?? '') == 'Kg' ? 'selected' : '' ?>>Kg</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Keterangan</label>
                    <textarea class="form-control" name="keterangan" rows="3" placeholder="Opsional"><?= old('keterangan', $barang['keterangan'] ?? '') ?></textarea>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fa-solid fa-save me-2"></i>Simpan Master Barang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
