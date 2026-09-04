<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<style>
    :root {
        --wh-bg: #F8FAFC;
        --wh-card: #FFFFFF;
        --wh-border: #E5E7EB;
        --wh-primary: #2563EB;
        --wh-primary-soft: #EFF6FF;
        --wh-success: #22C55E;
        --wh-success-soft: #ECFDF3;
        --wh-warning: #F59E0B;
        --wh-warning-soft: #FFFBEB;
        --wh-danger: #EF4444;
        --wh-danger-soft: #FEF2F2;
        --wh-dark-soft: #F3F4F6;
        --wh-dark: #374151;
        --wh-text: #111827;
        --wh-text-soft: #6B7280;
    }

    .wh-page {
        background: var(--wh-bg);
        margin: -1.5rem -1.5rem 0 -1.5rem;
        padding: 20px 24px 40px 24px;
    }

    .wh-header {
        background: var(--wh-card);
        border: 1px solid var(--wh-border);
        border-radius: 18px;
        padding: 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 20px;
    }
    .wh-header h1 {
        font-size: 1.35rem; font-weight: 700; color: var(--wh-text);
        margin: 0 0 4px 0; display: flex; align-items: center; gap: 10px;
    }
    .wh-header h1 i { color: var(--wh-primary); }
    .wh-header p { margin: 0; font-size: 0.85rem; color: var(--wh-text-soft); }

    .wh-filter-card {
        background: var(--wh-card); border: 1px solid var(--wh-border);
        border-radius: 16px; padding: 20px; margin-bottom: 16px;
    }
    .wh-filter-card .form-label {
        font-size: 0.78rem; font-weight: 600; color: var(--wh-text); margin-bottom: 6px;
    }
    .wh-filter-card .form-select {
        height: 44px; border-radius: 10px; border: 1px solid var(--wh-border);
        font-size: 0.85rem; padding: 0.5rem 0.75rem;
    }
    .wh-filter-card .form-select:focus {
        border-color: var(--wh-primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    .wh-btn-primary {
        background: var(--wh-primary); border: 1px solid var(--wh-primary); color: #fff;
        height: 44px; border-radius: 10px; font-size: 0.85rem; font-weight: 600;
        padding: 0 18px; display: inline-flex; align-items: center; gap: 6px;
        transition: transform 120ms ease, background 120ms ease;
        text-decoration: none; cursor: pointer;
    }
    .wh-btn-primary:hover { background: #1D4ED8; color: #fff; }
    .wh-btn-primary:active { transform: scale(0.98); }
    .wh-btn-outline {
        background: #fff; border: 1px solid var(--wh-border); color: var(--wh-text);
        height: 44px; border-radius: 10px; font-size: 0.85rem; font-weight: 600;
        padding: 0 18px; display: inline-flex; align-items: center; gap: 6px;
        transition: transform 120ms ease, background 120ms ease; text-decoration: none;
        cursor: pointer;
    }
    .wh-btn-outline:hover { background: var(--wh-dark-soft); color: var(--wh-text); }
    .wh-btn-outline:active { transform: scale(0.98); }
    .wh-btn-success {
        background: #16A34A; border: 1px solid #16A34A; color: #fff;
        height: 44px; border-radius: 10px; font-size: 0.85rem; font-weight: 600;
        padding: 0 18px; display: inline-flex; align-items: center; gap: 6px;
        transition: transform 120ms ease, background 120ms ease; text-decoration: none;
        cursor: pointer;
    }
    .wh-btn-success:hover { background: #15803D; color: #fff; }
    .wh-btn-success:active { transform: scale(0.98); }
    .wh-btn-danger {
        background: #DC2626; border: 1px solid #DC2626; color: #fff;
        height: 44px; border-radius: 10px; font-size: 0.85rem; font-weight: 600;
        padding: 0 18px; display: inline-flex; align-items: center; gap: 6px;
        transition: transform 120ms ease, background 120ms ease; text-decoration: none;
        cursor: pointer;
    }
    .wh-btn-danger:hover { background: #B91C1C; color: #fff; }
    .wh-btn-danger:active { transform: scale(0.98); }

    .wh-table-card {
        background: var(--wh-card); border: 1px solid var(--wh-border);
        border-radius: 16px; padding: 8px 8px 4px 8px; overflow: hidden;
    }

    .dm-table-wrap {
        position: relative;
        min-height: 260px;
    }
    .dm-table-spinner {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background: var(--wh-card);
        z-index: 50;
        color: var(--wh-text-soft);
        gap: 10px;
        font-size: 13px;
        font-weight: 500;
        border-radius: 8px;
        transition: opacity 0.3s ease;
    }
    .dm-table-spinner i {
        font-size: 1.9rem;
        color: var(--wh-primary);
    }
    .dm-table-wrap.loaded .dm-table-spinner {
        opacity: 0;
        pointer-events: none;
    }
    .dm-table-wrap:not(.loaded) .dataTables_wrapper,
    .dm-table-wrap:not(.loaded) #tableExpired {
        opacity: 0;
    }

    #tableExpired { border-collapse: separate; border-spacing: 0; }
    #tableExpired thead th {
        background: var(--wh-bg); color: var(--wh-text-soft);
        font-size: 0.68rem; font-weight: 600; text-transform: uppercase;
        letter-spacing: 0.04em; padding: 12px 14px;
        border-bottom: 1px solid var(--wh-border); border-top: none; white-space: nowrap;
    }
    #tableExpired tbody td {
        font-size: 0.82rem; padding: 0 14px; height: 56px;
        vertical-align: middle; border-bottom: 1px solid var(--wh-border);
        border-top: none; color: var(--wh-text);
    }
    #tableExpired, #tableExpired th, #tableExpired td {
        border-left: none; border-right: none;
    }
    #tableExpired tbody tr { transition: background 120ms ease; }
    #tableExpired tbody tr:hover { background: #F3F4F6; }

    .wh-badge {
        display: inline-flex; align-items: center; gap: 5px;
        border-radius: 999px; padding: 4px 10px 4px 8px;
        font-size: 0.7rem; font-weight: 600; white-space: nowrap;
    }
    .wh-badge i { font-size: 0.62rem; }
    .wh-badge.danger  { background: var(--wh-danger-soft);  color: #B91C1C; }
    .wh-badge.warning { background: var(--wh-warning-soft); color: #B45309; }
    .wh-badge.success { background: var(--wh-success-soft); color: #15803D; }
    .wh-badge.info    { background: var(--wh-primary-soft); color: #1D4ED8; }

    .wh-action-btn {
        height: 28px; width: 28px; border-radius: 6px; border: 1px solid var(--wh-border);
        background: #fff; display: inline-flex; align-items: center; justify-content: center;
        font-size: 0.75rem; transition: all 120ms ease; cursor: pointer; text-decoration: none;
    }
    .wh-action-btn.btn-seen  { color: var(--wh-primary); }
    .wh-action-btn.btn-seen:hover  { background: var(--wh-primary-soft); border-color: var(--wh-primary); }
    .wh-action-btn.btn-use   { color: #16A34A; }
    .wh-action-btn.btn-use:hover   { background: var(--wh-success-soft); border-color: #16A34A; }

    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        font-size: 0.82rem; color: var(--wh-text-soft); padding: 10px 6px;
    }
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 10px; border: 1px solid var(--wh-border);
        padding: 0.4rem 0.65rem; font-size: 0.82rem;
    }
    .dataTables_wrapper .dataTables_length select {
        border-radius: 10px; border: 1px solid var(--wh-border);
        font-size: 0.82rem; padding: 0.3rem 1.75rem 0.3rem 0.6rem;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 8px !important; padding: 0.35rem 0.7rem !important;
        margin-left: 2px; border: 1px solid transparent !important;
        background: transparent !important; color: var(--wh-text) !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--wh-primary) !important; color: #fff !important;
        border-color: var(--wh-primary) !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
        background: var(--wh-dark-soft) !important; color: var(--wh-text) !important;
    }

    div.dataTables_wrapper { position: relative; }
    div.dataTables_wrapper div.dataTables_processing {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid var(--wh-border);
        border-radius: 12px;
        padding: 16px 28px;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,.1), 0 8px 10px -6px rgba(0,0,0,.1);
        z-index: 10;
        margin: 0;
    }

    @media (max-width: 768px) {
        .wh-page { padding: 12px 12px 40px 12px; }
        .wh-header { flex-direction: column; align-items: flex-start; padding: 20px; }
        .wh-header h1 { font-size: 1.1rem; }
        .header-actions { flex-direction: column; width: 100%; }
        .header-actions > button { width: 100%; justify-content: center; }
    }
</style>

<div class="wh-page">

    <!-- Header -->
    <div class="wh-header">
        <div>
            <h1><i class="fa-solid fa-clock-rotate-left"></i> Monitoring Expired</h1>
            <p>Kelola dan pantau barang kedaluwarsa di Gudang Internal</p>
        </div>
        <?php if (in_groups('Administrator')): ?>
        <div class="d-flex gap-2 flex-wrap header-actions">
            <button type="button" class="wh-btn-success" onclick="exportData('excel')">
                <i class="fa-solid fa-file-excel"></i> Export Excel
            </button>
            <button type="button" class="wh-btn-danger" onclick="exportData('pdf')">
                <i class="fa-solid fa-file-pdf"></i> Export PDF
            </button>
        </div>
        <?php endif; ?>
    </div>

    <!-- Filter Panel -->
    <div class="wh-filter-card">
        <div class="row g-3 align-items-end">
            <div class="col-6 col-md-3">
                <label class="form-label">Prioritas</label>
                <select class="form-select" id="filterPriority">
                    <option value="">Semua Prioritas</option>
                    <option value="CRITICAL">CRITICAL (&gt;30 hari lewat)</option>
                    <option value="HIGH">HIGH (1-30 hari lewat)</option>
                    <option value="WARNING">WARNING (&lt;30 hari lagi)</option>
                    <option value="INFO">INFO (&lt;90 hari lagi)</option>
                </select>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label">Kategori</label>
                <select class="form-select" id="filterKategori">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($kategori as $k): ?>
                        <option value="<?= $k['id'] ?>"><?= esc($k['nama_kategori']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="wh-table-card">
        <div class="dm-table-wrap">
            <div class="dm-table-spinner">
                <i class="fa-solid fa-spinner fa-spin"></i>
                <div>Memuat data monitoring expired...</div>
            </div>
            <table class="table table-hover align-middle mb-0 w-100" id="tableExpired">
                <thead>
                    <tr>
                        <th width="30" class="text-center">No</th>
                        <th>Nomor Batch</th>
                        <th style="min-width:150px;">Nama Barang</th>
                        <th>Kategori</th>
                        <th class="text-center">Stok Saat Ini</th>
                        <th class="text-center">Tgl Kedaluwarsa</th>
                        <th class="text-center">Sisa Waktu</th>
                        <th class="text-center">Prioritas</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function () {

    var table = $('#tableExpired').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "<?= site_url('transaksi/monitoring-expired/ajaxData') ?>",
            type: "POST",
            data: function (d) {
                d.priority = $('#filterPriority').val();
                d.kategori = $('#filterKategori').val();
                d.<?= csrf_token() ?> = $('meta[name="csrf-token"]').attr('content');
            }
        },
        drawCallback: function () {
            $('#tableExpired').closest('.dm-table-wrap').addClass('loaded');
        },
        columns: [
            { orderable: false, className: "text-center" },
            null,
            null,
            null,
            { className: "text-center" },
            { className: "text-center" },
            { className: "text-center" },
            { orderable: false, className: "text-center" },
            { orderable: false, className: "text-end" }
        ],
        order: [],
        dom: '<"d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3"lf><"table-responsive"rt><"d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3"ip>',
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
        }
    });

    $('#filterPriority, #filterKategori').on('change', function () {
        $('#tableExpired').closest('.dm-table-wrap').removeClass('loaded');
        table.draw();
    });

    $(document).on('click', '.btn-mark-seen', function (e) {
        e.preventDefault();
        var btn = $(this);
        var id = btn.data('id');
        var priority = btn.data('priority');
        btn.html('<i class="fa-solid fa-spinner fa-spin"></i>').prop('disabled', true);

        $.post('<?= site_url('api/notifications/read') ?>', {
            batch_id: id,
            priority: priority,
            <?= csrf_token() ?>: $('meta[name="csrf-token"]').attr('content')
        }, function (res) {
            if (res.status === 'success') {
                btn.replaceWith('<span class="text-success small"><i class="fa-solid fa-check-double"></i> Dilihat</span>');
            } else {
                btn.html('<i class="fa-solid fa-eye"></i>').prop('disabled', false);
            }
        });
    });

    $(document).on('click', '.btn-use-batch', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var sisa = parseInt($(this).data('sisa'));

        if (sisa < 0) {
            var label = (sisa < -30) ? 'CRITICAL' : 'HIGH';
            var sisaText = Math.abs(sisa) + ' hari yang lalu';

            Swal.fire({
                icon: 'warning',
                title: 'Barang Sudah Expired!',
                html: '<div style="text-align:left; font-size:0.9rem; line-height:1.7">'
                    + '<p>Batch ini memiliki status <b style="color:#DC2626">' + label + '</b>'
                    + ' dan telah melewati tanggal kedaluwarsa <b>' + sisaText + '</b>.</p>'
                    + '<p>Mendistribusikan barang expired dapat berisiko. Pastikan Anda sudah mendapat persetujuan yang diperlukan.</p>'
                    + '<p><b>Apakah Anda tetap ingin mendistribusikan batch ini?</b></p>'
                    + '</div>',
                showCancelButton: true,
                confirmButtonText: 'Ya, Tetap Distribusikan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#D97706',
                cancelButtonColor: '#6B7280',
                reverseButtons: true,
                focusCancel: true
            }).then(function (result) {
                if (result.isConfirmed) {
                    window.location.href = "<?= site_url('transaksi/barang-keluar/create') ?>?id_batch=" + id;
                }
            });

        } else if (sisa <= 30) {
            Swal.fire({
                icon: 'info',
                title: 'Barang Hampir Expired',
                html: 'Batch ini akan kedaluwarsa dalam <b>' + sisa + ' hari</b>.<br>Lanjutkan distribusi?',
                showCancelButton: true,
                confirmButtonText: 'Ya, Distribusikan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#2563EB',
                cancelButtonColor: '#6B7280',
                reverseButtons: true
            }).then(function (result) {
                if (result.isConfirmed) {
                    window.location.href = "<?= site_url('transaksi/barang-keluar/create') ?>?id_batch=" + id;
                }
            });

        } else {
            window.location.href = "<?= site_url('transaksi/barang-keluar/create') ?>?id_batch=" + id;
        }
    });

});

function exportData(type) {
    var priority = $('#filterPriority').val();
    var kategori = $('#filterKategori').val();
    var url = '';
    if (type === 'excel') url = '<?= site_url('transaksi/monitoring-expired/export/excel') ?>';
    if (type === 'pdf')   url = '<?= site_url('transaksi/monitoring-expired/export/pdf') ?>';
    url += '?priority=' + priority + '&kategori=' + kategori;
    window.open(url, '_blank');
}
</script>
<?= $this->endSection() ?>