<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 140px); margin: -1.5rem -1.5rem 0 -1.5rem; padding: 15px;">
    <div class="w-100" style="max-width: 480px;">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4 text-center">
                <div class="mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle" style="width: 64px; height: 64px;">
                        <i class="fa-solid fa-building-user" style="font-size: 1.8rem;"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-2 text-dark"><?= esc($title) ?></h4>
                <p class="text-muted small mb-3">Sebagai Administrator, Anda harus memilih Gudang Wilayah mana yang akan dituju sebelum melakukan pencatatan transaksi.</p>
                
                <form action="<?= esc($action) ?>" method="GET">
                    <div class="mb-3 text-start">
                        <label class="form-label small fw-bold text-secondary">Gudang Wilayah <span class="text-danger">*</span></label>
                        <select name="id_gudang" class="form-select select2" required>
                            <option value="">-- Pilih Gudang Wilayah --</option>
                            <?php foreach($gudang as $g): ?>
                                <option value="<?= $g['id'] ?>"><?= esc($g['nama']) ?> - <?= esc($g['kota']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold rounded-3 shadow-sm">
                        Lanjutkan <i class="fa-solid fa-arrow-right ms-1"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        if ($.fn.select2) {
            $('.select2').select2({ theme: 'bootstrap-5', width: '100%' });
        }
    });
</script>
<?= $this->endSection() ?>
