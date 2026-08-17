<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap');

.mg-wrap * { box-sizing: border-box; }
.mg-wrap {
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
    --red: #DC2626;
    --red-bg: #FEF2F2;
    --red-text: #B91C1C;
    --cyan: #0891B2;
    --cyan-bg: #ECFEFF;
    --cyan-text: #0E7490;
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
.mg-page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 20px;
}
.mg-page-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--text-1);
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 3px;
}
.mg-page-title i { color: var(--blue); font-size: 17px; }
.mg-page-sub { font-size: 13px; color: var(--text-2); }

/* ── Buttons ── */
.mg-btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    height: 36px;
    padding: 0 16px;
    border-radius: var(--r-sm);
    border: none;
    background: var(--blue);
    color: #fff;
    font-size: 13px;
    font-weight: 500;
    font-family: 'Inter', sans-serif;
    cursor: pointer;
    text-decoration: none;
    transition: opacity .12s;
    white-space: nowrap;
}
.mg-btn-primary:hover { opacity: .88; color: #fff; }
.mg-btn-primary i { font-size: 13px; }

.mg-btn-outline {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    height: 36px;
    padding: 0 14px;
    border-radius: var(--r-sm);
    border: 1px solid var(--border-strong);
    background: var(--card);
    color: var(--text-2);
    font-size: 13px;
    font-weight: 500;
    font-family: 'Inter', sans-serif;
    cursor: pointer;
    text-decoration: none;
    transition: background .12s;
    white-space: nowrap;
}
.mg-btn-outline:hover { background: var(--bg); color: var(--text-1); }

/* ── Flash alerts ── */
.mg-alert {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 12px 16px;
    border-radius: var(--r-sm);
    font-size: 13px;
    margin-bottom: 16px;
    border: 1px solid;
}
.mg-alert.success { background: var(--green-bg); border-color: #BBF7D0; color: var(--green-text); }
.mg-alert.danger  { background: var(--red-bg);   border-color: #FECACA; color: var(--red-text); }
.mg-alert i { font-size: 15px; margin-top: 1px; flex-shrink: 0; }
.mg-alert-close {
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
.mg-alert-close:hover { opacity: 1; }

/* ── Filter bar (Admin only) ── */
.mg-filter-bar {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    padding: 16px 20px;
    background: #ffffff;
    border: 1px solid var(--border);
    border-radius: var(--r);
    margin-bottom: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
}
.mg-filter-label {
    font-size: 12px;
    font-weight: 600;
    color: var(--text-2);
    text-transform: uppercase;
    letter-spacing: .04em;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 7px;
}
.mg-filter-label i { font-size: 14px; color: var(--blue); }
.mg-filter-select {
    flex: 1;
    min-width: 220px;
    max-width: 360px;
    border: 1px solid var(--border-strong);
    border-radius: var(--r-sm);
    padding: 0 32px 0 12px;
    height: 38px;
    font-size: 13px;
    font-family: 'Inter', sans-serif;
    color: var(--text-1);
    background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748B' stroke-width='2'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E") no-repeat right 10px center;
    appearance: none;
    cursor: pointer;
    transition: border-color .15s, box-shadow .15s;
}
.mg-filter-select:focus {
    outline: none;
    border-color: var(--blue);
    box-shadow: 0 0 0 3px #DBEAFE;
}
.mg-filter-active {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    height: 26px;
    padding: 0 10px;
    border-radius: 999px;
    background: #EFF6FF;
    color: var(--blue-text);
    border: 1px solid #BFDBFE;
    font-size: 11px;
    font-weight: 600;
}
.mg-filter-active i { font-size: 10px; }

/* ── Card ── */
.mg-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--r);
    box-shadow: var(--shadow);
}

/* ── Processing / Loading Overlay (Dashboard Wilayah Style) ── */
div.dataTables_wrapper { position: relative; }
div.dataTables_wrapper div.dataTables_processing {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;          /* ← hapus !important */
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.88);
    backdrop-filter: blur(2px);
    z-index: 10;
    margin: 0;
    height: 100%;
    min-height: 120px;
    border-radius: var(--r);
}

/* ── Table ── */
.mg-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}
.mg-table th {
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
.mg-table th.center { text-align: center; }
.mg-table td {
    padding: 12px 16px;
    border-bottom: 1px solid var(--border);
    color: var(--text-1);
    vertical-align: middle;
}
.mg-table tr:last-child td { border-bottom: none; }
.mg-table tr:hover td { background: #FAFAFA; }

.mg-name   { font-weight: 500; color: var(--text-1); }
.mg-sub    { font-size: 11px; color: var(--text-3); margin-top: 2px; }
.mg-center { text-align: center; }

/* badge */
.mg-badge {
    display: inline-flex;
    align-items: center;
    height: 22px;
    padding: 0 9px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
}
.mg-badge.amber { background: var(--amber-bg); color: var(--amber-text); }
.mg-badge.cyan  { background: var(--cyan-bg);  color: var(--cyan-text); }
.mg-badge.slate { background: #F1F5F9; color: var(--text-2); }
.mg-badge.red   { background: var(--red-bg);   color: var(--red-text); }

/* action buttons */
.mg-actions { display: flex; gap: 6px; justify-content: center; }
.mg-icon-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border-radius: 8px;
    border: 1px solid var(--border-strong);
    background: var(--card);
    cursor: pointer;
    font-size: 13px;
    transition: background .12s;
    text-decoration: none;
}
.mg-icon-btn:hover { background: var(--bg); }
.mg-icon-btn.view  { color: var(--cyan); }
.mg-icon-btn.del   { color: var(--red); }

/* ── DataTables toolbar ── */
.mg-card .dataTables_wrapper { padding: 0; }
.mg-card .dataTables_wrapper > .dataTables_length,
.mg-card .dataTables_wrapper > .dataTables_filter {
    padding: 14px 16px 12px;
    font-size: 13px;
    color: var(--text-2);
    font-family: 'Inter', sans-serif;
}
.mg-card .dataTables_wrapper > .dataTables_length { float: left; }
.mg-card .dataTables_wrapper > .dataTables_filter { float: right; text-align: right; }
.mg-card .dataTables_wrapper .dataTables_length select,
.mg-card .dataTables_wrapper .dataTables_filter input {
    border: 1px solid var(--border-strong);
    border-radius: var(--r-sm);
    padding: 4px 8px;
    font-size: 13px;
    font-family: 'Inter', sans-serif;
    color: var(--text-1);
    background: #fff;
}
.mg-card .dataTables_wrapper .dataTables_filter input:focus {
    outline: none;
    border-color: var(--blue);
    box-shadow: 0 0 0 3px #DBEAFE;
}
.mg-card .dataTables_wrapper .dataTables_info,
.mg-card .dataTables_wrapper .dataTables_paginate {
    padding: 12px 16px 14px;
    font-size: 13px;
    color: var(--text-2);
    font-family: 'Inter', sans-serif;
}
.mg-card .dataTables_wrapper .dataTables_info     { float: left; }
.mg-card .dataTables_wrapper .dataTables_paginate { float: right; }
.mg-card .dataTables_wrapper table.dataTable       { clear: both; }
</style>

<div class="mg-wrap">

    <!-- ── Page header ── -->
    <div class="mg-page-header">
        <div>
            <div class="mg-page-title">
                <i class="fa-solid fa-arrow-up-from-line" aria-hidden="true"></i>
                <?= esc($title) ?>
            </div>
            <p class="mg-page-sub"><?= esc($subtitle ?? 'Data riwayat barang keluar Gudang Wilayah') ?></p>
        </div>
        <a href="<?= site_url('wilayah/keluar/create') ?><?= !empty($idGudang) ? '?id_gudang='.$idGudang : '' ?>" class="mg-btn-primary">
            <i class="fa-solid fa-plus" aria-hidden="true"></i> Catat Barang Keluar
        </a>
    </div>

    <!-- ── Flash messages ── -->
    <?php if (session()->getFlashdata('success')): ?>
    <div class="mg-alert success">
        <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
        <span><?= session()->getFlashdata('success') ?></span>
        <button class="mg-alert-close" onclick="this.parentElement.remove()" aria-label="Tutup">&times;</button>
    </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
    <div class="mg-alert danger">
        <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
        <span><?= session()->getFlashdata('error') ?></span>
        <button class="mg-alert-close" onclick="this.parentElement.remove()" aria-label="Tutup">&times;</button>
    </div>
    <?php endif; ?>

    <!-- ── Filter bar (Admin only) ── -->
    <?php if ($isAdmin): ?>
    <div class="mg-filter-bar">
        <span class="mg-filter-label">
            <i class="fa-solid fa-warehouse" aria-hidden="true"></i>
            Gudang Wilayah
        </span>
        <form method="get" action="<?= site_url('wilayah/keluar') ?>" style="display:contents">
            <select name="id_gudang" class="mg-filter-select" onchange="this.form.submit()">
                <option value="">— Semua Gudang —</option>
                <?php foreach ($listGudang ?? [] as $gw): ?>
                <option value="<?= $gw['id'] ?>" <?= ($idGudang == $gw['id']) ? 'selected' : '' ?>>
                    <?= esc($gw['nama']) ?> — <?= esc($gw['kota']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </form>
        <?php if (!empty($idGudang)): ?>
        <span class="mg-filter-active">
            <i class="fa-solid fa-filter" aria-hidden="true"></i>
            Difilter
        </span>
        <a href="<?= site_url('wilayah/keluar') ?>" class="mg-btn-outline">
            <i class="fa-solid fa-xmark" aria-hidden="true"></i> Reset
        </a>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- ── Table card ── -->
    <div class="mg-card">
        <table class="mg-table" id="tblKeluar" style="width:100%">
            <thead>
                <tr>
                    <th>Kode Transaksi</th>
                    <th>Tanggal</th>
                    <th>Gudang Wilayah</th>
                    <th>Tujuan</th>
                    <th class="center">Total Item</th>
                    <th class="center">Total Qty</th>
                    <th>Keterangan</th>
                    <th class="center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

</div><!-- end .mg-wrap -->

<!-- ════════════════════════════════
     MODAL DETAIL (Bootstrap — tidak diubah)
════════════════════════════════ -->
<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light border-0 pb-0">
                <h5 class="modal-title fw-bold text-gray-800">
                    <i class="fa-solid fa-file-lines me-2 text-primary"></i>Detail Barang Keluar Wilayah
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-4">
                <div class="row mb-4 g-3">
                    <div class="col-md-6">
                        <label class="text-muted small fw-bold mb-1">KODE TRANSAKSI BARANG KELUAR</label>
                        <div id="det_dokumen" class="fw-bold fs-6"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small fw-bold mb-1">TANGGAL</label>
                        <div id="det_tanggal" class="fw-bold fs-6"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small fw-bold mb-1">GUDANG WILAYAH</label>
                        <div id="det_gudang" class="fw-bold fs-6"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small fw-bold mb-1">TUJUAN / DONATUR</label>
                        <div id="det_tujuan" class="fw-bold fs-6"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small fw-bold mb-1">OPERATOR</label>
                        <div id="det_operator" class="fw-bold fs-6"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small fw-bold mb-1">KETERANGAN</label>
                        <div id="det_keterangan" class="fs-6"></div>
                    </div>
                </div>

                <h6 class="fw-bold text-gray-800 mb-3">
                    <i class="fa-solid fa-boxes-stacked me-2"></i>Item Barang
                </h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" style="font-size: 13px;">
                        <thead class="table-light">
                            <tr>
                                <th>KODE</th>
                                <th>NAMA BARANG</th>
                                <th>KATEGORI</th>
                                <th class="text-end">JUMLAH</th>
                                <th>SATUAN</th>
                            </tr>
                        </thead>
                        <tbody id="det_items"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    let csrfName = '<?= csrf_token() ?>';
    let csrfHash = '<?= csrf_hash() ?>';

    let table = $('#tblKeluar').DataTable({
        processing: true,
        serverSide: true,
        stateSave: true,
        dom: '<"mg-dt-top"lf>rt<"mg-dt-bot"ip>',
        order: [[1, 'desc']],
        ajax: {
            url: "<?= site_url('wilayah/keluar/ajaxData') ?>",
            type: "POST",
            data: function (d) {
                d[csrfName] = csrfHash;
                d.id_gudang = '<?= esc($idGudang) ?>';
            }
        },
        drawCallback: function(settings) {
            let response = settings.json;
            if (response && response.csrf_hash) {
                csrfHash = response.csrf_hash;
                $('input[name="' + csrfName + '"]').val(csrfHash);
            }
        },
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json',
            processing: '<div class="py-1 text-center"><div class="spinner-border text-primary" role="status" style="width: 2.2rem; height: 2.2rem; border-width: 0.22em;"><span class="visually-hidden">Memuat...</span></div><div class="mt-2 text-secondary fw-medium" style="font-size: 13px;">Memuat data barang keluar…</div></div>'
        },
        columns: [
            { data: 'nomor_dokumen', render: function(d) { return `<span class="mg-badge slate">${d}</span>`; } },
            { data: 'tanggal', render: function(d) { return `<span style="color:var(--text-2)">${d}</span>`; } },
            { data: 'nama_gudang', render: function(d, type, row) { return `<div class="mg-name">${d}</div><div class="mg-sub">${row.kota}</div>`; } },
            { data: 'tujuan', render: function(d) { return `<span style="color:var(--text-2)">${d}</span>`; } },
            { data: 'total_item', className: 'mg-center', render: function(d) { return `<span class="mg-badge cyan">${d} Item</span>`; } },
            { data: 'total_qty', className: 'mg-center', render: function(d) {
                let fmt = parseFloat(d).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                return `<span class="mg-badge amber">- ${fmt}</span>`;
            } },
            { data: 'keterangan', render: function(d) { return `<span style="color:var(--text-2); font-size:12px">${d}</span>`; } },
            { data: null, orderable: false, render: function(d, type, row) {
                let html = `<div class="mg-actions">
                    <button class="mg-icon-btn view btn-detail" data-id="${row.id}" title="Detail Transaksi" aria-label="Detail ${row.nomor_dokumen}">
                        <i class="fa-solid fa-eye" aria-hidden="true"></i>
                    </button>`;
                if (row.is_admin) {
                    html += `<button class="mg-icon-btn del btn-delete" data-id="${row.id}" title="Hapus Transaksi" aria-label="Hapus ${row.nomor_dokumen}">
                        <i class="fa-solid fa-trash-can" aria-hidden="true"></i>
                    </button>`;
                }
                html += `</div>`;
                return html;
            } }
        ],
        initComplete: function() {
            $('.mg-dt-top').css({
                'display': 'flex',
                'justify-content': 'space-between',
                'align-items': 'center',
                'padding': '14px 16px 12px',
                'border-bottom': '1px solid #E5E7EB'
            });
            $('.mg-dt-bot').css({
                'display': 'flex',
                'justify-content': 'space-between',
                'align-items': 'center',
                'padding': '12px 16px 14px',
                'border-top': '1px solid #E5E7EB'
            });
            $('.dataTables_filter, .dataTables_length').css('margin', '0');
        }
    });

    /* ── Detail ── */
    $(document).on('click', '.btn-detail', function() {
        let id = $(this).data('id');
        $.ajax({
            url: '<?= site_url("wilayah/keluar/detail/") ?>' + id,
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    let h = res.header;
                    $('#det_dokumen').text(h.nomor_dokumen);
                    $('#det_tanggal').text(h.tanggal);
                    $('#det_gudang').text(h.nama_gudang);
                    $('#det_tujuan').text(h.tujuan || '-');
                    $('#det_operator').text(h.operator || '-');
                    $('#det_keterangan').text(h.keterangan || '-');

                    let rows = '';
                    res.details.forEach(function(item) {
                        rows += `<tr>
                            <td>${item.kode_barang}</td>
                            <td>${item.nama_barang}</td>
                            <td>${item.nama_kategori || '-'}</td>
                            <td class="text-end fw-bold text-warning">-${item.jumlah}</td>
                            <td>${item.satuan}</td>
                        </tr>`;
                    });
                    $('#det_items').html(rows);
                    new bootstrap.Modal(document.getElementById('modalDetail')).show();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Gagal memuat detail transaksi.', 'error');
            }
        });
    });

    /* ── Delete ── */
    $(document).on('click', '.btn-delete', function() {
        let id = $(this).data('id');
        const url = '<?= site_url("wilayah/keluar/delete/") ?>' + id;

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Transaksi akan dihapus permanen. Stok Gudang Wilayah akan otomatis ditambahkan kembali (retur).',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DC2626',
            cancelButtonColor: '#64748B',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (!result.isConfirmed) return;

            const csrfName  = document.querySelector('meta[name="csrf-token-name"]').content;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const formData  = new FormData();
            formData.append(csrfName, csrfToken);

            fetch(url, { method: 'POST', body: formData })
                .then(function(response) {
                    if (response.redirected) {
                        window.location.href = response.url;
                    } else if (response.ok) {
                        window.location.reload();
                    } else {
                        Swal.fire('Error', 'Gagal menghapus. Status: ' + response.status, 'error');
                    }
                })
                .catch(function() {
                    Swal.fire('Error', 'Gagal menghapus transaksi.', 'error');
                });
        });
    });
});
</script>
<?= $this->endSection() ?>