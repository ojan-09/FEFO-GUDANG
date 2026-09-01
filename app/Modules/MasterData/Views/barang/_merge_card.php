<?php
// Partial: _merge_card.php
// Variables: $barang (array), $type ('target'|'sumber')
$borderColor = $type === 'target' ? '#22c55e' : '#f59e0b';
$bgColor     = $type === 'target' ? '#f0fdf4' : '#fffbeb';
?>
<div class="p-3 rounded-3 border" style="border-color:<?= $borderColor ?> !important; background:<?= $bgColor ?>;">
    <div class="row g-2">
        <div class="col-6 col-md-3">
            <div class="info-label">Kode Barang</div>
            <div class="fw-bold text-primary"><?= esc($barang['kode_barang']) ?></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="info-label">Nama Barang</div>
            <div class="fw-semibold"><?= esc($barang['nama_barang']) ?></div>
        </div>
        <div class="col-6 col-md-2">
            <div class="info-label">Kategori</div>
            <div><?= esc($barang['nama_kategori'] ?? '—') ?></div>
        </div>
        <div class="col-6 col-md-1">
            <div class="info-label">Satuan</div>
            <div><?= esc($barang['satuan']) ?></div>
        </div>
        <div class="col-6 col-md-2">
            <div class="info-label">Total Stok</div>
            <div class="fw-bold"><?= number_format($barang['total_stok'] ?? 0, 0, ',', '.') ?> <?= esc($barang['satuan']) ?></div>
        </div>
        <div class="col-6 col-md-1">
            <div class="info-label">Batch</div>
            <div><span class="badge bg-info text-dark"><?= $barang['jumlah_batch'] ?? 0 ?></span></div>
        </div>
    </div>
</div>

<style>
.info-label { font-size: 0.75em; text-transform: uppercase; letter-spacing: 0.04em; color: #64748b; font-weight: 600; margin-bottom: 2px; }
</style>