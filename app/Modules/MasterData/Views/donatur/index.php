<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<style>
.don-wrap {
    max-width: 1500px;
    margin: 0 auto;
    padding: 20px 28px;
    display: flex;
    flex-direction: column;
    gap: 20px;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

/* TOPBAR */
.don-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #fff;
    border: 1px solid #E2E8F0;
    border-radius: 18px;
    padding: 20px 26px;
    gap: 16px;
    box-shadow: 0 6px 20px rgba(15,23,42,.05);
}
.don-topbar-left { display: flex; align-items: center; gap: 14px; }
.don-icon-box {
    width: 40px; height: 40px;
    background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 16px; flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(37,99,235,.25);
}
.don-title  { font-size: 20px; font-weight: 700; color: #0F172A; margin: 0; line-height: 1.2; }
.don-subtitle { font-size: 13px; color: #64748B; display: block; margin-top: 2px; }

.don-btn-add {
    display: inline-flex; align-items: center; gap: 7px;
    height: 44px; padding: 0 22px;
    font-size: 14px; font-weight: 600;
    border-radius: 12px; border: none;
    background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
    color: #fff; cursor: pointer; text-decoration: none;
    box-shadow: 0 4px 12px rgba(37,99,235,.22);
    transition: background-color .18s ease, border-color .18s ease, color .18s ease, opacity .18s ease, transform .18s ease; white-space: nowrap;
}
.don-btn-add:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(37,99,235,.32);
    color: #fff;
    background: linear-gradient(135deg, #1D4ED8 0%, #1E40AF 100%);
}

/* FLASH */
.don-alert {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 16px; border-radius: 12px;
    font-size: 13px; font-weight: 500;
}
.don-alert-success { background: #F0FDF4; border: 1px solid #BBF7D0; color: #15803D; }

/* CARD */
.don-card {
    background: #fff;
    border: 1px solid #E2E8F0;
    border-radius: 18px;
    box-shadow: 0 8px 24px rgba(15,23,42,.07);
    padding: 20px;
}

/* ── DATATABLES TOOLBAR ── */
.don-card .dataTables_wrapper .dataTables_length,
.don-card .dataTables_wrapper .dataTables_filter {
    margin-bottom: 16px;
}

/* Length label */
.don-card .dataTables_wrapper .dataTables_length label {
    display: flex; align-items: center; gap: 8px;
    font-size: 13px; color: #475569; font-weight: 500; margin: 0;
}
.don-card .dataTables_wrapper .dataTables_length select {
    width: 72px; height: 40px;
    padding: 0 10px;
    font-size: 13px; color: #0F172A;
    background: #F8FAFC; border: 1px solid #E2E8F0;
    border-radius: 10px; outline: none;
    appearance: none; cursor: pointer;
    transition: border-color .15s, box-shadow .15s;
}
.don-card .dataTables_wrapper .dataTables_length select:focus {
    border-color: #2563EB; background: #fff;
    box-shadow: 0 0 0 3px rgba(37,99,235,.10);
}

/* Filter / search */
.don-card .dataTables_wrapper .dataTables_filter label {
    display: flex; align-items: center; gap: 8px;
    font-size: 13px; color: #475569; font-weight: 500; margin: 0;
    position: relative;
}
.don-card .dataTables_wrapper .dataTables_filter label::after {
    content: '\f002';
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    position: absolute;
    right: 12px;
    top: 50%; transform: translateY(-50%);
    font-size: 11px; color: #94A3B8;
    pointer-events: none;
}
.don-card .dataTables_wrapper .dataTables_filter input[type=search] {
    width: 240px; height: 40px;
    padding: 0 34px 0 12px;
    font-size: 13px; color: #0F172A;
    background: #F8FAFC; border: 1px solid #E2E8F0;
    border-radius: 10px; outline: none;
    transition: border-color .15s, box-shadow .15s, width .2s;
}
.don-card .dataTables_wrapper .dataTables_filter input[type=search]:focus {
    border-color: #2563EB; background: #fff;
    box-shadow: 0 0 0 3px rgba(37,99,235,.10);
    width: 280px;
}

/* Info */
.don-card .dataTables_wrapper .dataTables_info {
    font-size: 12px; color: #94A3B8; padding-top: 14px;
}

/* ── TABLE ── */
#tabelDonatur { width: 100% !important; border-collapse: collapse; margin: 0 !important; }

#tabelDonatur thead tr {
    height: 46px; background: #F8FAFC;
    border-top: 1px solid #E2E8F0;
    border-bottom: 1px solid #E2E8F0;
}
#tabelDonatur thead th {
    padding: 0 14px !important;
    font-size: 11px !important; font-weight: 700 !important;
    letter-spacing: .06em; text-transform: uppercase;
    color: #64748B !important; white-space: nowrap;
    border: none !important;
}

#tabelDonatur tbody tr {
    height: 54px; border-bottom: 1px solid #F1F5F9;
    transition: background .1s;
}
#tabelDonatur tbody tr:last-child { border-bottom: none; }
#tabelDonatur tbody tr:hover { background: #F8FAFC !important; }
#tabelDonatur tbody td {
    padding: 0 14px !important;
    font-size: 14px !important; color: #1E293B;
    vertical-align: middle !important;
    border: none !important;
}
#tabelDonatur tbody tr:nth-child(even) { background: transparent; }

/* Cells */
.don-no   { color: #CBD5E1; font-size: 13px; font-weight: 500; }
.don-name { font-weight: 700; color: #0F172A; display: block; max-width: 260px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.don-meta { display: flex; align-items: center; gap: 6px; color: #64748B; font-size: 13px; }
.don-meta i { font-size: 10px; color: #94A3B8; flex-shrink: 0; }
.don-empty { color: #CBD5E1; font-size: 16px; }

/* Badge */
.don-badge {
    display: inline-flex; align-items: center; gap: 5px;
    height: 24px; padding: 0 10px;
    font-size: 11px; font-weight: 600; border-radius: 999px;
}
.don-badge-perusahaan { background: #EFF6FF; color: #2563EB; }
.don-badge-individu   { background: #F0FDF4; color: #16A34A; }

/* Action buttons */
.don-actions { display: flex; align-items: center; justify-content: center; gap: 6px; }
.don-action-btn {
    width: 36px; height: 36px;
    display: inline-flex; align-items: center; justify-content: center;
    border-radius: 10px; border: none; font-size: 13px;
    cursor: pointer; text-decoration: none;
    transition: background-color .15s ease, border-color .15s ease, color .15s ease, opacity .15s ease, transform .15s ease;
}
.don-action-edit   { background: #EFF6FF; color: #2563EB; }
.don-action-edit:hover   { background: #2563EB; color: #fff; transform: translateY(-2px); box-shadow: 0 4px 10px rgba(37,99,235,.25); }
.don-action-delete { background: #FEF2F2; color: #DC2626; }
.don-action-delete:hover { background: #DC2626; color: #fff; transform: translateY(-2px); box-shadow: 0 4px 10px rgba(220,38,38,.25); }

/* Responsive */
@media (max-width: 900px) {
    .don-card .dataTables_wrapper .dataTables_filter input[type=search] { width: 180px; }
}
@media (max-width: 768px) {
    .don-wrap { padding: 14px 16px; }
    .don-topbar { flex-direction: column; align-items: flex-start; }
    .don-card { padding: 14px; }
    .don-card .dataTables_wrapper .dataTables_filter input[type=search],
    .don-card .dataTables_wrapper .dataTables_filter input[type=search]:focus { width: 100%; }
}
</style>

<div class="don-wrap">

    <!-- TOPBAR -->
    <div class="don-topbar">
        <div class="don-topbar-left">
            <div class="don-icon-box">
                <i class="fa-solid fa-people-group"></i>
            </div>
            <div>
                <h1 class="don-title"><?= esc($title) ?></h1>
                <span class="don-subtitle">Kelola data donatur Foodbank Indonesia</span>
            </div>
        </div>
        <a href="<?= site_url('masterdata/donatur/create') ?>" class="don-btn-add">
            <i class="fa-solid fa-plus"></i> Tambah Donatur
        </a>
    </div>

    <!-- FLASH -->
    <?php if (session()->getFlashdata('success')) : ?>
    <div class="don-alert don-alert-success">
        <i class="fa-solid fa-circle-check"></i>
        <?= session()->getFlashdata('success') ?>
    </div>
    <?php endif; ?>

    <!-- CARD -->
    <div class="don-card">
        <div>
            <table class="table mb-0" id="tabelDonatur" style="width: 100%;">
                <thead>
                    <tr>
                        <th width="50"  class="text-center">No</th>
                        <th style="width:28%">Nama Donatur</th>
                        <th style="width:14%">Jenis</th>
                        <th style="width:18%">Kontak</th>
                        <th style="width:22%">Email</th>
                        <th width="100" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <!-- DataTables will populate this tbody via AJAX -->
                </tbody>
            </table>
        </div>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    var csrfName = '<?= csrf_token() ?>';
    var csrfHash = '<?= csrf_hash() ?>';

    $(document).ready(function () {
        $('#tabelDonatur').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?= site_url('masterdata/donatur/ajaxData') ?>",
                type: "POST",
                data: function (d) {
                    d[csrfName] = csrfHash;
                }
            },
            drawCallback: function (settings) {
                var response = settings.json;
                if (response && response[csrfName]) {
                    csrfHash = response[csrfName];
                }
            },
            language: {
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
            order: [],
            columnDefs: [
                { orderable: false, targets: [0, 5] },
                { className: "text-center", targets: [0, 5] }
            ],
            dom: '<"d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2"lf><"table-responsive"rt><"d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3"ip>',
            responsive: true
        });
    });
</script>
<?= $this->endSection() ?>
