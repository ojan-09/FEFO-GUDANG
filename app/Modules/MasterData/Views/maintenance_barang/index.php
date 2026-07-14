<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="topbar d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-broom me-2"></i><?= esc($title) ?></h1>
        <span class="subtle">Data Cleansing Master Barang (Master Internal Sistem)</span>
    </div>
</div>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-check-circle me-1"></i> <?= esc(session()->getFlashdata('success')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-triangle-exclamation me-1"></i> <?= esc(session()->getFlashdata('error')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="panel-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="panel-title mb-0">Daftar Master Barang (Internal)</h5>
        <div>
            <button type="button" class="btn btn-warning rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#mergeModal">
                <i class="fa-solid fa-code-merge me-1"></i> Merge Barang
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped align-middle" id="dataTable">
            <thead class="table-light">
                <tr>
                    <th width="50">No</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Total Batch (Histori)</th>
                    <th width="150" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($barang)) : ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Belum ada master barang.</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($barang as $key => $b) : ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td><span class="badge bg-secondary"><?= esc($b['kode_barang']) ?></span></td>
                            <td class="fw-medium text-dark"><?= esc($b['nama_barang']) ?></td>
                            <td><?= esc($b['jumlah_batch']) ?> Batch</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill btn-rename" 
                                    data-id="<?= $b['id'] ?>" 
                                    data-nama="<?= esc($b['nama_barang']) ?>"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#renameModal" title="Perbaiki Nama">
                                    <i class="fa-solid fa-pen"></i> Rename
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Rename -->
<div class="modal fade" id="renameModal" tabindex="-1" aria-labelledby="renameModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST" id="formRename">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="renameModalLabel"><i class="fa-solid fa-pen me-2"></i>Perbaiki Nama Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">Histori batch tidak akan hilang karena menggunakan relasi ID.</p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_barang" id="inputRename" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill"><i class="fa-solid fa-save me-1"></i>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Merge -->
<div class="modal fade" id="mergeModal" tabindex="-1" aria-labelledby="mergeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="<?= site_url('masterdata/maintenance-barang/merge') ?>" method="POST" id="formMerge">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="mergeModalLabel"><i class="fa-solid fa-code-merge me-2"></i>Merge Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        Pilih satu <strong>Target Utama</strong>. Seluruh batch dari barang sumber yang dicentang akan dipindahkan ke Target Utama, lalu master sumber akan dihapus.
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Target Utama (Barang yang dipertahankan)</label>
                        <select class="form-select" name="target_id" id="selectTarget" required>
                            <option value="">-- Pilih Target Utama --</option>
                            <?php foreach ($barang as $b) : ?>
                                <option value="<?= $b['id'] ?>"><?= esc($b['nama_barang']) ?> (<?= esc($b['kode_barang']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Sumber (Barang yang akan digabung & dihapus)</label>
                        <div style="max-height: 250px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 6px; padding: 10px;">
                            <?php foreach ($barang as $b) : ?>
                                <div class="form-check mb-2 source-item" id="source-wrap-<?= $b['id'] ?>">
                                    <input class="form-check-input check-source" type="checkbox" name="source_ids[]" value="<?= $b['id'] ?>" id="source-<?= $b['id'] ?>">
                                    <label class="form-check-label" for="source-<?= $b['id'] ?>">
                                        <?= esc($b['nama_barang']) ?> <span class="text-muted">(<?= esc($b['kode_barang']) ?>)</span>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning rounded-pill" onclick="return confirm('Apakah Anda yakin ingin menggabungkan barang ini? Tindakan ini tidak dapat dibatalkan.')">
                        <i class="fa-solid fa-check me-1"></i>Proses Merge
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const renameButtons = document.querySelectorAll('.btn-rename');
        const formRename = document.getElementById('formRename');
        const inputRename = document.getElementById('inputRename');

        renameButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                
                formRename.action = `<?= site_url('masterdata/maintenance-barang/rename') ?>/${id}`;
                inputRename.value = nama;
            });
        });

        const selectTarget = document.getElementById('selectTarget');
        const sourceItems = document.querySelectorAll('.source-item');

        selectTarget.addEventListener('change', function () {
            const targetId = this.value;
            
            sourceItems.forEach(item => {
                const checkbox = item.querySelector('.check-source');
                if (checkbox.value === targetId) {
                    item.style.display = 'none';
                    checkbox.checked = false;
                } else {
                    item.style.display = 'block';
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>

