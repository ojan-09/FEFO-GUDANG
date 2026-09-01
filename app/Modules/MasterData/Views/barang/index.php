<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap');

.mb-wrap * { box-sizing: border-box; }
.mb-wrap {
    --bg: #F8FAFC;
    --card: #fff;
    --border: #E5E7EB;
    --border-strong: #D1D5DB;
    --text-1: #0F172A;
    --text-2: #475569;
    --text-3: #94A3B8;
    --blue: #2563EB;
    --blue-bg: #EFF6FF;
    --blue-text: #1D4ED8;
    --green: #16A34A;
    --green-bg: #F0FDF4;
    --green-text: #15803D;
    --amber: #D97706;
    --amber-bg: #FFFBEB;
    --amber-text: #B45309;
    --slate: #64748B;
    --r: 16px;
    --r-sm: 10px;
    --shadow: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    font-family: 'Inter', system-ui, sans-serif;
    background: var(--bg);
    padding: 28px 24px;
    color: var(--text-1);
    min-height: 100vh;
}

/* ── Page header ── */
.mb-page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 20px;
}
.mb-page-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--text-1);
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 3px;
}
.mb-page-title i { color: var(--blue); font-size: 17px; }
.mb-page-sub { font-size: 13px; color: var(--text-2); }

/* ── Flash alerts ── */
.mb-alert {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 12px 16px;
    border-radius: var(--r-sm);
    font-size: 13px;
    margin-bottom: 16px;
    border: 1px solid;
}
.mb-alert.success { background: var(--green-bg); border-color: #BBF7D0; color: var(--green-text); }
.mb-alert.danger  { background: #FEF2F2;          border-color: #FECACA; color: #B91C1C; }
.mb-alert i { font-size: 15px; margin-top: 1px; flex-shrink: 0; }
.mb-alert-close {
    margin-left: auto;
    background: none;
    border: none;
    cursor: pointer;
    color: inherit;
    opacity: .6;
    font-size: 16px;
    line-height: 1;
    padding: 0;
    flex-shrink: 0;
}
.mb-alert-close:hover { opacity: 1; }

/* ── Card ── */
.mb-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--r);
    box-shadow: var(--shadow);
    overflow: hidden;
}

/* ── Toolbar inside card ── */
.mb-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    padding: 16px 20px;
    border-bottom: 1px solid var(--border);
}
.mb-filter-row {
    display: flex;
    align-items: center;
    gap: 8px;
}
.mb-filter-label {
    font-size: 12px;
    font-weight: 600;
    color: var(--text-3);
    text-transform: uppercase;
    letter-spacing: .05em;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 5px;
}
.mb-filter-label i { font-size: 13px; }
.mb-filter-select {
    height: 32px;
    border: 1px solid var(--border-strong);
    border-radius: var(--r-sm);
    padding: 0 30px 0 10px;
    font-size: 12px;
    font-family: 'Inter', sans-serif;
    color: var(--text-1);
    background: #fff;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2394A3B8' stroke-width='2'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 8px center;
    cursor: pointer;
    min-width: 150px;
}
.mb-filter-select:focus { outline: none; border-color: var(--blue); box-shadow: 0 0 0 3px #DBEAFE; }
.mb-toolbar-hint {
    font-size: 12px;
    color: var(--text-3);
    display: flex;
    align-items: center;
    gap: 5px;
}

/* ── TABLE LOADING SPINNER ── */
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
    background: var(--card);
    z-index: 50;
    color: var(--text-3);
    gap: 10px;
    font-size: 13px;
    font-weight: 500;
    transition: opacity 0.3s ease;
}
.dm-table-spinner i {
    font-size: 1.9rem;
    color: var(--blue);
}
.dm-table-wrap.loaded .dm-table-spinner {
    opacity: 0;
    pointer-events: none;
}
.dm-table-wrap:not(.loaded) .dataTables_wrapper,
.dm-table-wrap:not(.loaded) #tabelBarang {
    opacity: 0;
}

/* ── Table ── */
.mb-overflow { overflow-x: auto; }
.mb-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}
.mb-table th {
    font-size: 10px;
    font-weight: 600;
    color: var(--text-3);
    text-transform: uppercase;
    letter-spacing: .05em;
    padding: 11px 16px;
    background: var(--bg);
    border-bottom: 1px solid var(--border);
    text-align: left;
    white-space: nowrap;
}
.mb-table th.center { text-align: center; }
.mb-table td {
    padding: 12px 16px;
    border-bottom: 1px solid var(--border);
    color: var(--text-1);
    vertical-align: middle;
}
.mb-table tr:last-child td { border-bottom: none; }
.mb-table tr:hover td { background: #FAFAFA; }
.mb-no { color: var(--text-3); font-size: 12px; }

/* code pill */
.mb-code {
    display: inline-block;
    font-size: 11px;
    font-weight: 500;
    color: var(--text-2);
    background: var(--bg);
    border: 1px solid var(--border);
    padding: 2px 8px;
    border-radius: 5px;
    font-family: monospace;
}

/* status badge */
.mb-badge {
    display: inline-flex;
    align-items: center;
    height: 22px;
    padding: 0 9px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
}
.mb-badge.green  { background: var(--green-bg); color: var(--green-text); }
.mb-badge.amber  { background: var(--amber-bg); color: var(--amber-text); }
.mb-badge.slate  { background: #F1F5F9; color: var(--slate); }

/* stok number */
.mb-stok  { font-weight: 600; color: var(--text-1); }
.mb-batch { font-size: 12px; color: var(--text-2); }

/* action button */
.mb-detail-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    height: 28px;
    padding: 0 10px;
    border-radius: 7px;
    border: 1px solid var(--border-strong);
    background: var(--blue-bg);
    color: var(--blue-text);
    font-size: 11px;
    font-weight: 500;
    font-family: 'Inter', sans-serif;
    cursor: pointer;
    text-decoration: none;
    transition: opacity .12s;
}
.mb-detail-btn:hover { opacity: .8; color: var(--blue-text); }

/* ── Modal ── */
.mb-modal-backdrop {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(15,23,42,.35);
    z-index: 1050;
    align-items: center;
    justify-content: center;
    padding: 20px;
}
.mb-modal-backdrop.open { display: flex; }
.mb-modal {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--r);
    box-shadow: 0 8px 32px rgba(0,0,0,.12);
    width: 100%;
    max-width: 520px;
    max-height: 90vh;
    overflow-y: auto;
    animation: mbSlide .15s ease;
}
@keyframes mbSlide {
    from { opacity: 0; transform: translateY(-8px); }
    to   { opacity: 1; transform: translateY(0); }
}
.mb-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 22px 14px;
    border-bottom: 1px solid var(--border);
}
.mb-modal-title { font-size: 15px; font-weight: 600; color: var(--text-1); display: flex; align-items: center; gap: 8px; }
.mb-modal-title i { color: var(--amber); font-size: 16px; }
.mb-modal-close {
    background: none; border: none; cursor: pointer;
    color: var(--text-3); font-size: 18px; line-height: 1;
    padding: 2px; transition: color .12s;
}
.mb-modal-close:hover { color: var(--text-1); }
.mb-modal-body   { padding: 20px 22px; }
.mb-modal-footer {
    padding: 14px 22px 18px;
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    border-top: 1px solid var(--border);
}

/* info banner inside modal */
.mb-info-banner {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    padding: 10px 12px;
    border-radius: var(--r-sm);
    background: var(--blue-bg);
    border: 1px solid #BFDBFE;
    color: var(--blue-text);
    font-size: 12px;
    margin-bottom: 18px;
    line-height: 1.5;
}
.mb-info-banner i { font-size: 14px; flex-shrink: 0; margin-top: 1px; }

/* form fields */
.mb-field { margin-bottom: 14px; }
.mb-field:last-child { margin-bottom: 0; }
.mb-label {
    display: block;
    font-size: 11px;
    font-weight: 600;
    color: var(--text-3);
    text-transform: uppercase;
    letter-spacing: .05em;
    margin-bottom: 5px;
}
.mb-req { color: #DC2626; }
.mb-input,
.mb-select {
    width: 100%;
    height: 36px;
    border: 1px solid var(--border-strong);
    border-radius: var(--r-sm);
    padding: 0 11px;
    font-size: 13px;
    font-family: 'Inter', sans-serif;
    color: var(--text-1);
    background: #fff;
    transition: border-color .12s, box-shadow .12s;
}
.mb-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2394A3B8' stroke-width='2'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
    cursor: pointer;
}
.mb-select[multiple] { height: auto; padding: 6px 11px; background-image: none; }
.mb-input:focus,
.mb-select:focus { outline: none; border-color: var(--blue); box-shadow: 0 0 0 3px #DBEAFE; }
.mb-hint { font-size: 11px; color: var(--text-3); margin-top: 4px; }

/* modal footer buttons */
.mb-btn-cancel {
    display: inline-flex; align-items: center;
    height: 34px; padding: 0 14px;
    border-radius: var(--r-sm);
    border: 1px solid var(--border-strong);
    background: var(--bg);
    font-size: 13px; font-weight: 500;
    font-family: 'Inter', sans-serif;
    color: var(--text-2); cursor: pointer;
    transition: background .12s;
}
.mb-btn-cancel:hover { background: var(--border); }
.mb-btn-merge {
    display: inline-flex; align-items: center; gap: 6px;
    height: 34px; padding: 0 16px;
    border-radius: var(--r-sm); border: none;
    background: var(--amber); color: #fff;
    font-size: 13px; font-weight: 500;
    font-family: 'Inter', sans-serif;
    cursor: pointer; transition: opacity .12s;
}
.mb-btn-merge:hover { opacity: .88; }

/* ── Merge trigger button ── */
.mb-btn-amber {
    display: inline-flex; align-items: center; gap: 7px;
    height: 36px; padding: 0 16px;
    border-radius: var(--r-sm);
    border: 1px solid #FDE68A;
    background: var(--amber-bg); color: var(--amber-text);
    font-size: 13px; font-weight: 500;
    font-family: 'Inter', sans-serif;
    cursor: pointer; transition: opacity .12s;
    white-space: nowrap;
}
.mb-btn-amber:hover { opacity: .85; }
</style>

<div class="mb-wrap">

    <!-- ── Page header ── -->
    <div class="mb-page-header">
        <div>
            <div class="mb-page-title">
                <i class="fa-solid fa-boxes-stacked" aria-hidden="true"></i>
                Informasi Master Barang
            </div>
            <p class="mb-page-sub">Katalog barang beserta informasi batch dan stok</p>
        </div>
    </div>

    <!-- ── Flash messages ── -->
    <?php if (session()->getFlashdata('success')): ?>
    <div class="mb-alert success">
        <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
        <span><?= session()->getFlashdata('success') ?></span>
        <button class="mb-alert-close" onclick="this.parentElement.remove()" aria-label="Tutup">&times;</button>
    </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
    <div class="mb-alert danger">
        <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
        <span><?= session()->getFlashdata('error') ?></span>
        <button class="mb-alert-close" onclick="this.parentElement.remove()" aria-label="Tutup">&times;</button>
    </div>
    <?php endif; ?>

    <!-- ── Table card ── -->
    <div class="mb-card">
        <div class="mb-toolbar">
            <div class="mb-filter-row">
                <span class="mb-filter-label">
                    <i class="fa-solid fa-filter" aria-hidden="true"></i> Filter status
                </span>
                <select id="filterStatus" class="mb-filter-select">
                    <option value="">Semua barang</option>
                    <option value="active">Hanya aktif</option>
                    <option value="merged">Hanya merged</option>
                </select>
            </div>
            <span class="mb-toolbar-hint">
                <i class="fa-solid fa-circle-info" aria-hidden="true"></i>
                Klik <strong>Detail</strong> untuk melihat batch dan stok setiap barang
            </span>
        </div>

        <!-- Spinner hanya di area tabel -->
        <div class="dm-table-wrap">
            <div class="dm-table-spinner">
                <i class="fa-solid fa-spinner fa-spin"></i>
                <div>Memuat data barang...</div>
            </div>
            <div class="mb-overflow">
                <table class="mb-table" id="tabelBarang" style="width:100%;">
                    <thead>
                        <tr>
                            <th style="width:50px">#</th>
                            <th>Kode</th>
                            <th>Nama barang</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th>Total stok</th>
                            <th>Jumlah batch</th>
                            <th class="center">Status</th>
                            <th class="center" style="width:90px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

</div><!-- end .mb-wrap -->

<!-- ════════════════════════════════
     MODAL MERGE (Admin only)
════════════════════════════════ -->
<?php if (in_groups('Administrator')): ?>
<div class="mb-modal-backdrop" id="modalMerge" role="dialog" aria-modal="true" aria-labelledby="titleMerge">
    <div class="mb-modal">
        <div class="mb-modal-header">
            <span class="mb-modal-title">
                <i class="fa-solid fa-code-merge" aria-hidden="true"></i>
                Merge barang
            </span>
            <button class="mb-modal-close" onclick="closeMbModal('modalMerge')" aria-label="Tutup">&times;</button>
        </div>
        <form id="formMerge" action="<?= site_url('masterdata/barang/merge-preview') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="mb-modal-body">
                <div class="mb-info-banner">
                    <i class="fa-solid fa-circle-info" aria-hidden="true"></i>
                    Pilih barang target (utama) dan satu atau lebih barang sumber yang akan digabungkan.
                    Kamu akan melihat ringkasan sebelum proses dijalankan.
                </div>
                <div class="mb-field">
                    <label class="mb-label" for="merge_target">
                        Barang target <span class="mb-req">*</span>
                    </label>
                    <select id="merge_target" name="id_target" class="mb-select select2-merge" style="width:100%;" required>
                        <option value="">— Pilih barang target —</option>
                        <?php foreach ($active_barang as $b): ?>
                            <option value="<?= $b['id'] ?>"><?= esc($b['kode_barang']) ?> — <?= esc($b['nama_barang']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <p class="mb-hint">Barang yang akan menjadi penerima semua batch dari sumber.</p>
                </div>
                <div class="mb-field">
                    <label class="mb-label" for="merge_sumber">
                        Barang sumber <span class="mb-req">*</span>
                    </label>
                    <select id="merge_sumber" name="id_sumber[]" class="mb-select select2-merge" style="width:100%;" multiple required>
                        <?php foreach ($active_barang as $b): ?>
                            <option value="<?= $b['id'] ?>"><?= esc($b['kode_barang']) ?> — <?= esc($b['nama_barang']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <p class="mb-hint">Bisa pilih lebih dari satu. Barang sumber akan berstatus <em>merged</em>.</p>
                </div>
            </div>
            <div class="mb-modal-footer">
                <button type="button" class="mb-btn-cancel" onclick="closeMbModal('modalMerge')">Batal</button>
                <button type="submit" class="mb-btn-merge">
                    <i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Lanjut &amp; pratinjau
                </button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
var csrfName = '<?= csrf_token() ?>';
var csrfHash = '<?= csrf_hash() ?>';

$(document).ready(function () {

    var table = $('#tabelBarang').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= site_url('masterdata/barang/ajaxData') ?>',
            type: 'POST',
            data: function (d) {
                d[csrfName] = csrfHash;
                d['status_filter'] = $('#filterStatus').val();
            }
        },
        drawCallback: function (settings) {
            var json = settings.json;
            if (json && json[csrfName]) csrfHash = json[csrfName];

            // Spinner hilang setelah data selesai dimuat
            $('#tabelBarang').closest('.dm-table-wrap').addClass('loaded');
        },
        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
        order: [],
        columnDefs: [{ orderable: false, targets: [0, 8] }],
        dom: '<"d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2"lf><"table-responsive"rt><"d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3"ip>',
        responsive: true
    });

    // Spinner muncul lagi saat filter berubah
    $('#filterStatus').on('change', function () {
        $('#tabelBarang').closest('.dm-table-wrap').removeClass('loaded');
        table.ajax.reload();
    });

    /* ── Select2 inside modal ── */
    if ($.fn.select2) {
        $('.select2-merge').select2({ theme: 'bootstrap-5', dropdownParent: $('#modalMerge .mb-modal') });
    }
});

/* ── Modal helpers ── */
function openMbModal(id) {
    document.getElementById(id).classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeMbModal(id) {
    document.getElementById(id).classList.remove('open');
    document.body.style.overflow = '';
}
document.querySelectorAll('.mb-modal-backdrop').forEach(function(backdrop) {
    backdrop.addEventListener('click', function(e) {
        if (e.target === backdrop) closeMbModal(backdrop.id);
    });
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.mb-modal-backdrop.open').forEach(function(m) {
            closeMbModal(m.id);
        });
    }
});
</script>
<?= $this->endSection() ?>  