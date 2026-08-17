<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php helper('format'); ?>

<!-- Topbar -->
<div class="topbar d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-layer-group me-2"></i><?= esc($title) ?></h1>
        <span class="subtle">Daftar rincian batch untuk barang <strong><?= esc($barang['nama_barang']) ?></strong></span>
    </div>
    <a href="<?= site_url('transaksi/stok-gudang') ?>" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<!-- Table -->
<div class="panel-card">
    <div class="table-responsive">
        <table class="table table-hover table-striped align-middle" id="tabelBatch">
            <thead class="table-light">
                <tr>
                    <th width="50" class="text-center">No</th>
                    <th>Nomor Batch</th>
                    <th>Donatur</th>
                    <th class="text-center">Tgl Masuk</th>
                    <th class="text-center">Tgl Expired</th>
                    <th class="text-center">Berat/Satuan</th>
                    <th class="text-center">Jml Awal</th>
                    <th class="text-end">Stok Saat Ini</th>
                    <th class="text-end">Total Berat</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($batches as $batch) : ?>
                    <?php 
                        $bisaDipecah = (int)($batch['bisa_dipecah'] ?? 0);
                        $beratPerSatuan = (float)$batch['berat_per_satuan'];
                        
                        $isDesimal = ($bisaDipecah === 1 && floor($batch['stok_saat_ini']) != $batch['stok_saat_ini']);
                        $decimals = $isDesimal ? 2 : 0;
                        
                        if ($bisaDipecah === 1) {
                            $totalBeratRow = (float)$batch['stok_saat_ini'];
                            if (in_array(strtolower(trim($batch['satuan_berat'] ?? '')), ['gram', 'g', 'gr', 'ml'])) $totalBeratRow *= 1000;
                        } else {
                            $totalBeratRow = $batch['stok_saat_ini'] * $beratPerSatuan;
                        }
                    ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><span class="badge bg-secondary"><?= esc($batch['nomor_batch']) ?></span></td>
                        <td><?= esc($batch['nama_donatur'] ?? '-') ?></td>
                        <td class="text-center"><?= date('d M Y', strtotime($batch['tanggal_masuk'])) ?></td>
                        <td class="text-center text-danger fw-bold"><?= date('d M Y', strtotime($batch['tanggal_kedaluwarsa'])) ?></td>
                        <td class="text-center"><?= $beratPerSatuan > 0 ? $beratPerSatuan . ' ' . esc($batch['satuan_berat']) : '-' ?></td>
                        <td class="text-center"><?= number_format($batch['jumlah_awal'], $decimals, ',', '.') ?> <?= esc($batch['satuan']) ?></td>
                        <td class="text-end fw-bold" style="font-size: 1.1rem;">
                            <?= number_format($batch['stok_saat_ini'], $decimals, ',', '.') ?> <small class="text-muted fw-normal"><?= esc($batch['satuan']) ?></small>
                            <?php if (!empty($batch['menggunakan_kemasan']) && !empty($batch['jumlah_ctn']) && !empty($batch['isi_per_ctn'])) : ?>
                                <?php
                                    $isiCtn  = (int)$batch['isi_per_ctn'];
                                    $stkAkt  = (float)$batch['stok_saat_ini'];
                                    $ctnSisa = floor($stkAkt / $isiCtn);
                                    $pcsSisa = fmod($stkAkt, $isiCtn);

                                    if ($ctnSisa > 0 && $pcsSisa > 0) {
                                        $sisaStr = number_format($ctnSisa, 0, ',', '.') . ' CTN + ' . number_format($pcsSisa, 0, ',', '.') . ' ' . esc($batch['satuan']);
                                    } elseif ($ctnSisa > 0) {
                                        $sisaStr = number_format($ctnSisa, 0, ',', '.') . ' CTN';
                                    } elseif ($pcsSisa > 0) {
                                        $sisaStr = number_format($pcsSisa, 0, ',', '.') . ' ' . esc($batch['satuan']);
                                    } else {
                                        $sisaStr = '0 CTN';
                                    }
                                ?>
                                <div class="text-secondary fw-normal mt-1" style="font-size: 11px;">
                                    <i class="fa-solid fa-box text-muted me-1"></i>Awal: <?= (int)$batch['jumlah_ctn'] ?> CTN &times; <?= (int)$batch['isi_per_ctn'] ?> <?= esc($batch['satuan']) ?><br>
                                    <i class="fa-solid fa-box-open text-primary me-1"></i>Sisa Kemasan: <strong><?= $sisaStr ?></strong>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="text-end text-muted">
                            <?= $totalBeratRow > 0 ? format_berat($totalBeratRow, $batch['satuan_berat']) : '-' ?>
                        </td>
                        <td class="text-center">
                            <?php 
                                $badgeClass = 'bg-secondary';
                                if ($batch['status_dinamis'] == 'Aman') $badgeClass = 'bg-success';
                                elseif ($batch['status_dinamis'] == 'Hampir Expired') $badgeClass = 'bg-warning text-dark';
                                elseif ($batch['status_dinamis'] == 'Expired') $badgeClass = 'bg-danger';
                            ?>
                            <span class="badge <?= $badgeClass ?> px-3 py-2 rounded-pill"><?= esc($batch['status_dinamis']) ?></span>
                        </td>
                    </tr>
                <?php endforeach; ?>
                
                <?php if(empty($batches)): ?>
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">
                        <i class="fa-solid fa-box-open fs-3 mb-2 d-block"></i>
                        Belum ada batch barang.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        $('#tabelBatch').DataTable({
            "language": {
                emptyTable: "Tidak ada data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                lengthMenu: "Tampilkan _MENU_ data",
                loadingRecords: "Memuat...",
                processing: "Memproses...",
                search: "Cari:",
                zeroRecords: "Tidak ditemukan data yang sesuai",
                paginate: { first: "Pertama", last: "Terakhir", next: "Selanjutnya", previous: "Sebelumnya" }
            },
            "order": [[4, "asc"]], // Sort by Tgl Expired ASC
            "paging": false,
            "info": false,
            "searching": false
        });
    });
</script>
<?= $this->endSection() ?>

