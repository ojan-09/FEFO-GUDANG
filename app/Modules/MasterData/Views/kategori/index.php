<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<style>
.kat-wrap {
    max-width: 1500px;
    margin: 0 auto;
    padding: 20px 28px;
    display: flex;
    flex-direction: column;
    gap: 18px;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

/* TOPBAR */
.kat-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #fff;
    border: 1px solid #E2E8F0;
    border-radius: 18px;
    padding: 18px 24px;
    gap: 16px;
    box-shadow: 0 6px 20px rgba(15,23,42,.05);
}
.kat-topbar-left { display: flex; align-items: center; gap: 14px; }
.kat-icon-box {
    width: 40px; height: 40px;
    background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 16px; flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(37,99,235,.25);
}
.kat-title { font-size: 20px; font-weight: 700; color: #0F172A; margin: 0; line-height: 1.2; }
.kat-subtitle { font-size: 13px; color: #64748B; display: block; margin-top: 2px; }

.kat-btn-add {
    display: inline-flex; align-items: center; gap: 7px;
    height: 44px; padding: 0 24px;
    font-size: 14px; font-weight: 600;
    border-radius: 12px; border: none;
    background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
    color: #fff; cursor: pointer; text-decoration: none;
    box-shadow: 0 4px 12px rgba(37,99,235,.22);
    transition: background-color .18s ease, border-color .18s ease, color .18s ease, opacity .18s ease, transform .18s ease; white-space: nowrap;
}
.kat-btn-add:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(37,99,235,.30);
    color: #fff;
}

/* FLASH */
.kat-alert {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 16px; border-radius: 12px;
    font-size: 13px; font-weight: 500;
}
.kat-alert-success { background: #F0FDF4; border: 1px solid #BBF7D0; color: #15803D; }

/* CARD */
.kat-card {
    background: #fff;
    border: 1px solid #E2E8F0;
    border-radius: 18px;
    box-shadow: 0 6px 20px rgba(15,23,42,.05);
    overflow: hidden;
    padding: 20px;
}

/* DATATABLES OVERRIDE */
.kat-card .dataTables_wrapper .dataTables_length,
.kat-card .dataTables_wrapper .dataTables_filter {
    margin-bottom: 14px;
}
.kat-card .dataTables_wrapper .dataTables_length label,
.kat-card .dataTables_wrapper .dataTables_filter label {
    font-size: 13px;
    color: #475569;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0;
}
.kat-card .dataTables_wrapper .dataTables_length select,
.kat-card .dataTables_wrapper .dataTables_filter input {
    height: 40px;
    padding: 0 12px;
    font-size: 13px;
    color: #0F172A;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    outline: none;
    transition: border-color .15s, box-shadow .15s;
}
.kat-card .dataTables_wrapper .dataTables_length select {
    padding: 0 32px 0 12px;
}
.kat-card .dataTables_wrapper .dataTables_filter input:focus,
.kat-card .dataTables_wrapper .dataTables_length select:focus {
    border-color: #2563EB;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(37,99,235,.10);
}
.kat-card .dataTables_wrapper .dataTables_info {
    font-size: 12px;
    color: #94A3B8;
    padding-top: 10px;
}
/* TABLE */
#tabelKategori {
    width: 100% !important;
    border-collapse: collapse;
    margin: 0 !important;
}
#tabelKategori thead tr {
    height: 46px;
    background: #F8FAFC;
    border-bottom: 1px solid #E2E8F0;
}
#tabelKategori thead th {
    padding: 0 14px !important;
    font-size: 12px !important;
    font-weight: 700 !important;
    letter-spacing: .05em;
    text-transform: uppercase;
    color: #64748B !important;
    white-space: nowrap;
    border: none !important;
    border-bottom: 1px solid #E2E8F0 !important;
}
#tabelKategori tbody tr {
    height: 56px;
    border-bottom: 1px solid #F1F5F9;
    transition: background .12s;
}
#tabelKategori tbody tr:last-child { border-bottom: none; }
#tabelKategori tbody tr:hover { background: #F8FAFC !important; }
#tabelKategori tbody td {
    padding: 10px 14px !important;
    font-size: 14px !important;
    color: #1E293B;
    vertical-align: middle !important;
    border: none !important;
}
#tabelKategori tbody tr:nth-child(even) { background: transparent; }

/* NAMA BOLD */
.kat-name { font-weight: 600; color: #0F172A; }
.kat-date { font-size: 12px; color: #94A3B8; }

/* BADGE */
.kat-badge {
    display: inline-flex; align-items: center;
    height: 26px; padding: 0 12px;
    font-size: 12px; font-weight: 600;
    border-radius: 999px;
}
.kat-badge-kategori { background: #EFF6FF; color: #2563EB; }

/* ACTION BUTTONS */
.kat-actions { display: flex; align-items: center; justify-content: center; gap: 6px; }
.kat-action-btn {
    width: 36px; height: 36px;
    display: inline-flex; align-items: center; justify-content: center;
    border-radius: 10px; border: none;
    font-size: 13px; cursor: pointer;
    text-decoration: none;
    transition: background-color .15s ease, border-color .15s ease, color .15s ease, opacity .15s ease, transform .15s ease;
}
.kat-action-edit  { background: #EFF6FF; color: #2563EB; }
.kat-action-edit:hover  { background: #DBEAFE; color: #1D4ED8; transform: translateY(-2px); }
.kat-action-delete { background: #FEF2F2; color: #DC2626; }
.kat-action-delete:hover { background: #FEE2E2; color: #B91C1C; transform: translateY(-2px); }

/* RESPONSIVE */
@media (max-width: 768px) {
    .kat-wrap { padding: 14px 16px; }
    .kat-topbar { flex-direction: column; align-items: flex-start; }
    .kat-card { padding: 14px; }
}
</style>

<div class="kat-wrap">

    <!-- TOPBAR -->
    <div class="kat-topbar">
        <div class="kat-topbar-left">
            <div class="kat-icon-box">
                <i class="fa-solid fa-tags"></i>
            </div>
            <div>
                <h1 class="kat-title"><?= esc($title) ?></h1>
                <span class="kat-subtitle">Kelola kategori barang gudang</span>
            </div>
        </div>
        <a href="<?= site_url('masterdata/kategori/create') ?>" class="kat-btn-add">
            <i class="fa-solid fa-plus"></i> Tambah Kategori
        </a>
    </div>

    <!-- FLASH -->
    <?php if (session()->getFlashdata('success')) : ?>
    <div class="kat-alert kat-alert-success">
        <i class="fa-solid fa-circle-check"></i>
        <?= session()->getFlashdata('success') ?>
    </div>
    <?php endif; ?>

    <!-- CARD -->
    <div class="kat-card">
        <div>
            <table class="table mb-0" id="tabelKategori" style="width: 100%;">
                <thead>
                    <tr>
                        <th width="60" class="text-center">No</th>
                        <th>Nama Kategori</th>
                        <th>Dibuat</th>
                        <th width="90" class="text-center">Aksi</th>
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
        $('#tabelKategori').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?= site_url('masterdata/kategori/ajaxData') ?>",
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
                { orderable: false, targets: [0, 3] },
                { className: "text-center", targets: [0, 3] }
            ],
            dom: '<"d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2"lf>rt<"d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3"ip>'
        });
    });
</script>
<?= $this->endSection() ?>