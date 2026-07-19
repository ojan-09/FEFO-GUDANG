<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="wil-wrap">

    <!-- TOPBAR -->
    <div class="wil-topbar">
        <div class="wil-topbar-left">
            <div class="wil-icon-box">
                <i class="fa-solid fa-map-location-dot"></i>
            </div>
            <div>
                <h1 class="wil-title"><?= esc($title) ?></h1>
                <span class="wil-subtitle">Kelola data referensi wilayah untuk distribusi barang</span>
            </div>
        </div>
        <a href="<?= site_url('masterdata/wilayah/create') ?>" class="wil-btn wil-btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah Wilayah
        </a>
    </div>

    <!-- FLASH -->
    <?php if (session()->getFlashdata('success')) : ?>
    <div class="wil-alert wil-alert-success">
        <i class="fa-solid fa-circle-check"></i>
        <?= session()->getFlashdata('success') ?>
    </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
    <div class="wil-alert wil-alert-error">
        <i class="fa-solid fa-circle-exclamation"></i>
        <?= session()->getFlashdata('error') ?>
    </div>
    <?php endif; ?>

    <!-- CARD -->
    <div class="wil-card">

        <!-- TOOLBAR -->
        <div class="wil-toolbar">
            <div class="wil-toolbar-left">
                <select class="wil-select-sm" id="wilStatusFilter">
                    <option value="">Semua Status</option>
                    <option value="Aktif">Aktif</option>
                    <option value="Nonaktif">Nonaktif</option>
                </select>
            </div>
            <div class="wil-search-wrap">
                <i class="fa-solid fa-magnifying-glass wil-search-icon"></i>
                <input type="text" class="wil-search" id="wilSearch" placeholder="Cari wilayah...">
            </div>
        </div>

        <!-- TABLE -->
        <div class="table-responsive">
            <table class="wil-table" id="tabelWilayah">
                <thead>
                    <tr>
                        <th style="width:60px" class="text-center">No</th>
                        <th>Nama Wilayah</th>
                        <th style="width:140px">Status</th>
                        <th style="width:100px" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($wilayah as $w) : ?>
                    <tr>
                        <td class="text-center wil-cell-no"><?= $no++ ?></td>
                        <td class="wil-cell-name"><?= esc($w['nama_wilayah']) ?></td>
                        <td>
                            <?php if ($w['status'] == 'Aktif') : ?>
                                <span class="wil-badge wil-badge-aktif">Aktif</span>
                            <?php else : ?>
                                <span class="wil-badge wil-badge-nonaktif">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="wil-actions">
                                <a href="<?= site_url('masterdata/wilayah/edit/' . $w['id']) ?>" class="wil-action-btn wil-action-edit" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <button type="button" class="wil-action-btn wil-action-delete" title="Hapus" onclick="confirmDelete(<?= $w['id'] ?>)">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- FOOTER / PAGINATION INFO -->
        <div class="wil-table-footer">
            <span class="wil-count" id="wilCount"></span>
        </div>
    </div>

</div>

<style>
.wil-wrap {
    max-width: 1500px;
    margin: 0 auto;
    padding: 20px 28px;
    display: flex;
    flex-direction: column;
    gap: 20px;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

/* TOPBAR */
.wil-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #fff;
    border: 1px solid #E2E8F0;
    border-radius: 18px;
    padding: 20px 24px;
    gap: 16px;
    box-shadow: 0 6px 20px rgba(15,23,42,.05);
}
.wil-topbar-left { display: flex; align-items: center; gap: 16px; }
.wil-icon-box {
    width: 40px; height: 40px;
    background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 16px; flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(37,99,235,.25);
}
.wil-title { font-size: 20px; font-weight: 700; color: #0F172A; margin: 0; line-height: 1.2; }
.wil-subtitle { font-size: 13px; color: #64748B; display: block; margin-top: 2px; }

/* BUTTONS */
.wil-btn {
    display: inline-flex; align-items: center; gap: 7px;
    height: 38px; padding: 0 18px;
    font-size: 13px; font-weight: 600;
    border-radius: 999px; border: none;
    cursor: pointer; text-decoration: none;
    transition: all .18s ease; white-space: nowrap; line-height: 1;
}
.wil-btn-primary {
    background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
    color: #fff;
    box-shadow: 0 4px 12px rgba(37,99,235,.20);
}
.wil-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(37,99,235,.28);
    color: #fff;
}

/* ALERTS */
.wil-alert {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 16px; border-radius: 12px;
    font-size: 13px; font-weight: 500;
}
.wil-alert-success { background: #F0FDF4; border: 1px solid #BBF7D0; color: #15803D; }
.wil-alert-error   { background: #FEF2F2; border: 1px solid #FECACA; color: #DC2626; }

/* CARD */
.wil-card {
    background: #fff;
    border: 1px solid #E2E8F0;
    border-radius: 18px;
    box-shadow: 0 6px 20px rgba(15,23,42,.05);
    overflow: hidden;
}

/* TOOLBAR */
.wil-toolbar {
    display: flex; align-items: center;
    justify-content: space-between;
    padding: 14px 18px;
    border-bottom: 1px solid #F1F5F9;
    gap: 16px;
    flex-wrap: wrap;
}
.wil-toolbar-left { display: flex; align-items: center; gap: 10px; }
.wil-select-sm {
    height: 40px; padding: 0 12px;
    font-size: 13px; color: #334155;
    background: #F8FAFC; border: 1px solid #E2E8F0;
    border-radius: 10px; outline: none;
    cursor: pointer;
    transition: border-color .15s;
}
.wil-select-sm:focus { border-color: #2563EB; box-shadow: 0 0 0 3px rgba(37,99,235,.10); }
.wil-search-wrap { position: relative; }
.wil-search-icon {
    position: absolute; left: 12px; top: 50%;
    transform: translateY(-50%);
    font-size: 12px; color: #94A3B8; pointer-events: none;
}
.wil-search {
    width: 240px; height: 40px;
    padding: 0 12px 0 34px;
    font-size: 13px; color: #0F172A;
    background: #F8FAFC; border: 1px solid #E2E8F0;
    border-radius: 10px; outline: none;
    transition: border-color .15s, box-shadow .15s;
}
.wil-search:focus {
    border-color: #2563EB; background: #fff;
    box-shadow: 0 0 0 3px rgba(37,99,235,.10);
    width: 280px;
    transition: width .2s ease, border-color .15s, box-shadow .15s;
}

/* TABLE */
.wil-table { width: 100%; border-collapse: collapse; }
.wil-table thead tr {
    height: 46px;
    background: #F8FAFC;
    border-bottom: 1px solid #E2E8F0;
}
.wil-table thead th {
    padding: 0 14px;
    font-size: 12px; font-weight: 700;
    letter-spacing: .05em; text-transform: uppercase;
    color: #64748B; white-space: nowrap;
}
.wil-table tbody tr {
    height: 56px;
    border-bottom: 1px solid #F1F5F9;
    transition: background .12s;
}
.wil-table tbody tr:last-child { border-bottom: none; }
.wil-table tbody tr:hover { background: #F8FAFC; }
.wil-table td { padding: 12px 14px; font-size: 14px; color: #1E293B; vertical-align: middle; }
.wil-cell-no   { color: #94A3B8; font-size: 13px; }
.wil-cell-name { font-weight: 500; color: #0F172A; }

/* BADGE */
.wil-badge {
    display: inline-flex; align-items: center;
    height: 26px; padding: 0 12px;
    font-size: 12px; font-weight: 600;
    border-radius: 999px;
}
.wil-badge-aktif    { background: #DCFCE7; color: #15803D; }
.wil-badge-nonaktif { background: #FEE2E2; color: #DC2626; }

/* ACTION BUTTONS */
.wil-actions { display: flex; align-items: center; justify-content: center; gap: 6px; }
.wil-action-btn {
    width: 38px; height: 38px;
    display: inline-flex; align-items: center; justify-content: center;
    border-radius: 10px; border: none;
    font-size: 14px; cursor: pointer;
    text-decoration: none;
    transition: all .15s ease;
}
.wil-action-edit  { background: #EFF6FF; color: #2563EB; }
.wil-action-edit:hover  { background: #DBEAFE; color: #1D4ED8; transform: translateY(-2px); }
.wil-action-delete { background: #FEF2F2; color: #DC2626; }
.wil-action-delete:hover { background: #FEE2E2; color: #B91C1C; transform: translateY(-2px); }

/* FOOTER */
.wil-table-footer {
    padding: 12px 18px;
    border-top: 1px solid #F1F5F9;
    display: flex; align-items: center; justify-content: flex-end;
}
.wil-count { font-size: 12px; color: #94A3B8; }

/* HIDDEN ROW */
.wil-row-hidden { display: none; }

/* RESPONSIVE */
@media (max-width: 768px) {
    .wil-toolbar { flex-direction: column; align-items: stretch; }
    .wil-search, .wil-search:focus { width: 100%; }
    .wil-topbar { flex-direction: column; align-items: flex-start; }
    .wil-wrap { padding: 14px 16px; }
}
</style>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(function () {
    const searchInput  = document.getElementById('wilSearch');
    const statusFilter = document.getElementById('wilStatusFilter');
    const tbody        = document.querySelector('#tabelWilayah tbody');
    const countEl      = document.getElementById('wilCount');

    function filterTable() {
        const q      = searchInput.value.toLowerCase().trim();
        const status = statusFilter.value;
        const rows   = tbody.querySelectorAll('tr');
        let visible  = 0;

        rows.forEach(row => {
            const name   = row.querySelector('.wil-cell-name')?.textContent.toLowerCase() || '';
            const badge  = row.querySelector('.wil-badge')?.textContent.trim() || '';
            const matchQ = !q || name.includes(q);
            const matchS = !status || badge === status;

            if (matchQ && matchS) {
                row.classList.remove('wil-row-hidden');
                visible++;
            } else {
                row.classList.add('wil-row-hidden');
            }
        });

        const total = rows.length;
        countEl.textContent = visible === total
            ? `${total} wilayah`
            : `${visible} dari ${total} wilayah`;
    }

    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);

    // Init count
    filterTable();
})();

function confirmDelete(id) {
    Swal.fire({
        title: 'Hapus Wilayah?',
        text: "Data yang sudah dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= site_url("masterdata/wilayah/delete/") ?>' + id;
        }
    });
}
</script>
<?= $this->endSection() ?>