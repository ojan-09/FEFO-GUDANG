<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Topbar -->
<div class="topbar d-flex justify-content-between align-items-center">
    <div>
        <a href="<?= site_url('masterdata/barang') ?>" class="btn btn-sm btn-outline-secondary rounded-pill me-2">
            <i class="fa-solid fa-arrow-left me-1"></i>Kembali
        </a>
        <h1 class="page-title d-inline ms-1">
            <i class="fa-solid fa-code-merge me-2 text-warning"></i>Konfirmasi Merge Barang
        </h1>
        <span class="subtle d-block mt-1">Periksa informasi sebelum proses digabungkan</span>
    </div>
</div>

<!-- Peringatan -->
<div class="alert alert-warning d-flex align-items-start gap-3 rounded-3 mb-4">
    <i class="fa-solid fa-triangle-exclamation fa-lg mt-1 text-warning"></i>
    <div>
        <div class="fw-bold mb-1">Pastikan kedua barang merupakan barang yang sama sebelum melakukan merge.</div>
        <div style="font-size:0.9em;">
            Proses merge akan memindahkan seluruh batch dan stok dari <strong>Barang Sumber</strong> ke <strong>Barang Target</strong>.
            Histori transaksi (Barang Masuk, Barang Keluar, Penyaluran, Laporan) tetap dipertahankan dan tidak dihapus.
            Barang sumber akan berstatus <strong>Merged</strong> setelah proses selesai.
        </div>
    </div>
</div>

<!-- Target -->
<div class="panel-card mb-4">
    <div class="d-flex align-items-center gap-2 mb-3">
        <span class="badge bg-success px-3 py-2" style="font-size:0.85em;">TARGET (Utama)</span>
        <span class="fw-bold text-success" style="font-size:1em;">Barang yang menerima semua batch</span>
    </div>
    <?= view('App\Modules\MasterData\Views\barang\_merge_card', ['barang' => $target, 'type' => 'target']) ?>
</div>

<!-- Sumber -->
<div class="panel-card mb-4">
    <div class="d-flex align-items-center gap-2 mb-3">
        <span class="badge bg-warning text-dark px-3 py-2" style="font-size:0.85em;">SUMBER (Yang Digabungkan)</span>
        <span class="fw-bold" style="font-size:1em;">Barang yang akan berstatus <em>merged</em></span>
    </div>
    <?php foreach ($sumberList as $s): ?>
        <?= view('App\Modules\MasterData\Views\barang\_merge_card', ['barang' => $s, 'type' => 'sumber']) ?>
        <?php if (!$loop->last ?? true): ?><hr class="my-3"><?php endif; ?>
    <?php endforeach; ?>
</div>

<!-- Tombol Konfirmasi -->
<div class="panel-card">
    <form id="formMergeConfirm" action="<?= site_url('masterdata/barang/merge') ?>" method="POST">
        <?= csrf_field() ?>
        <input type="hidden" name="id_target" value="<?= esc($id_target) ?>">
        <?php foreach ($id_sumber as $sid): ?>
            <input type="hidden" name="id_sumber[]" value="<?= esc($sid) ?>">
        <?php endforeach; ?>

        <div class="d-flex justify-content-end gap-3">
            <a href="<?= site_url('masterdata/barang') ?>" class="btn btn-secondary rounded-pill px-4">
                <i class="fa-solid fa-xmark me-1"></i>Batal
            </a>
            <button type="button" id="btnMerge" class="btn btn-warning rounded-pill px-4 fw-bold">
                <i class="fa-solid fa-code-merge me-1"></i>Ya, Gabungkan Sekarang
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$('#btnMerge').on('click', function () {
    Swal.fire({
        title: 'Gabungkan Barang?',
        html: 'Semua batch dari <strong>barang sumber</strong> akan dipindahkan ke <strong>barang target</strong>.<br>Proses ini tidak dapat dibatalkan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#F59E0B',
        cancelButtonColor: '#64748B',
        confirmButtonText: '<i class="fa-solid fa-check me-1"></i>Ya, Gabungkan',
        cancelButtonText: 'Batal'
    }).then(function (result) {
        if (result.isConfirmed) {
            $('#formMergeConfirm').submit();
        }
    });
});
</script>
<?= $this->endSection() ?>