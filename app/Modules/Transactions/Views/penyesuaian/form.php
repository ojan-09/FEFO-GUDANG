<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<style>
    /* ═══════════════════════════════════════════════
   Tambah Penyesuaian Stok — Scale 100% friendly
═══════════════════════════════════════════════ */

    /* Page heading */
    .pny-heading {
        margin-bottom: 18px;
    }

    .pny-heading h2 {
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 3px;
        line-height: 1.2;
    }

    .pny-heading p {
        font-size: 13px;
        color: #64748b;
        margin: 0;
    }

    .btn-back {
        height: 38px;
        padding: 0 18px;
        font-size: 13.5px;
        font-weight: 500;
        border-radius: 20px;
    }

    /* Cards */
    .form-card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 14px rgba(15, 23, 42, .05);
        margin-bottom: 18px;
        overflow: hidden;
    }

    .form-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 13px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 50px;
    }

    .form-header h5 {
        font-size: 14.5px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }

    .form-body {
        padding: 18px;
    }

    /* Labels */
    .form-label {
        font-size: 13px !important;
        font-weight: 600 !important;
        color: #334155;
        margin-bottom: 6px !important;
    }

    /* Inputs */
    .form-control,
    .form-select {
        height: 40px;
        font-size: 14px;
        border-radius: 9px;
        border: 1px solid #cbd5e1;
        color: #0f172a;
        transition: border-color .15s, box-shadow .15s;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .09);
    }

    textarea.form-control {
        height: 100px;
        resize: vertical;
    }

    .form-control[readonly] {
        background: #f8fafc;
    }

    small.text-muted {
        font-size: 12px;
    }

    /* mb spacing tighter */
    .mb-3 {
        margin-bottom: 12px !important;
    }

    .mb-4 {
        margin-bottom: 16px !important;
    }

    /* Add button */
    .btn-add-item {
        height: 40px;
        font-size: 14px;
        font-weight: 600;
        border-radius: 9px;
        width: 100%;
    }

    /* Cart items */
    .cart-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 14px;
        margin-bottom: 10px;
        position: relative;
        transition: border-color .15s, box-shadow .15s;
    }

    .cart-item:hover {
        border-color: #cbd5e1;
        box-shadow: 0 2px 6px rgba(0, 0, 0, .05);
    }

    .cart-item .item-name {
        font-size: 14px;
        font-weight: 600;
        color: #0f172a;
    }

    .cart-item .item-qty {
        font-size: 15px;
        font-weight: 700;
    }

    .cart-item .item-meta {
        font-size: 12px;
        color: #64748b;
        margin-top: 4px;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .btn-remove-item {
        position: absolute;
        top: 10px;
        right: 12px;
        color: #94a3b8;
        background: none;
        border: none;
        padding: 3px 5px;
        cursor: pointer;
        font-size: 13px;
        line-height: 1;
        border-radius: 5px;
        transition: color .15s, background .15s;
    }

    .btn-remove-item:hover {
        color: #ef4444;
        background: #fee2e2;
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 36px 20px;
        color: #94a3b8;
    }

    .empty-state i {
        font-size: 36px;
        margin-bottom: 10px;
        display: block;
        opacity: .5;
    }

    .empty-state p {
        font-size: 14px;
        margin: 0 0 4px;
        color: #64748b;
    }

    .empty-state small {
        font-size: 12.5px;
    }

    /* Badge item count */
    .badge-count-sm {
        font-size: 12px;
        padding: 4px 10px;
        border-radius: 999px;
    }

    /* Action buttons */
    .btn-batal {
        height: 40px;
        padding: 0 20px;
        font-size: 14px;
        border-radius: 9px;
    }

    .btn-simpan {
        height: 40px;
        padding: 0 28px;
        font-size: 14px;
        font-weight: 600;
        border-radius: 9px;
    }

    hr.my-3 {
        margin: 14px 0 !important;
    }

    /* ── RESPONSIVE MOBILE ── */
    @media (max-width: 768px) {
        .pny-heading { flex-direction: column; align-items: flex-start !important; gap: 10px; margin-bottom: 16px; }
        .pny-heading h2 { font-size: 18px; }
        .btn-back { width: 100%; justify-content: center; }
        .form-card { padding: 16px; }
        .row.g-3 { flex-direction: column; }
        .col-lg-4, .col-lg-8 { width: 100%; }
        .btn-simpan, .btn-batal { width: 100%; margin-bottom: 8px; }
        .d-flex.justify-content-end.gap-2 { flex-direction: column-reverse; }
    }
</style>

<!-- ── PAGE HEADING ── -->
<div class="d-flex justify-content-between align-items-center pny-heading">
    <div>
        <h2><i class="fa-solid fa-scale-balanced text-primary me-2" style="font-size:18px"></i>Tambah Penyesuaian Stok
        </h2>
        <p>Lakukan penyesuaian untuk barang rusak, hilang, atau stock opname.</p>
    </div>
    <a href="<?= site_url('transaksi/penyesuaian') ?>" class="btn btn-outline-secondary btn-back">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row g-3">

    <!-- ── KOLOM KIRI: Pilih Barang ── -->
    <div class="col-lg-4">
        <div class="form-card">
            <div class="form-header">
                <h5><i class="fa-solid fa-box me-2 text-primary" style="font-size:13px"></i>Pilih Barang</h5>
            </div>
            <div class="form-body">

                <div class="mb-3">
                    <label class="form-label">Jenis Penyesuaian</label>
                    <select id="jenis_penyesuaian" class="form-select" onchange="handleJenisChange()">
                        <option value="Barang Rusak">Barang Rusak (-)</option>
                        <option value="Barang Hilang">Barang Hilang (-)</option>
                        <option value="Barang Kedaluwarsa">Barang Kedaluwarsa (-)</option>
                        <option value="Hasil Stock Opname">Hasil Stock Opname (-)</option>
                        <option value="Koreksi Negatif">Koreksi Negatif (-)</option>
                        <option value="Koreksi Positif">Koreksi Positif (+)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Barang</label>
                    <select id="id_barang" class="form-select select2" onchange="fetchBatches()">
                        <option value="">Pilih Barang...</option>
                        <?php foreach ($barang as $b): ?>
                            <option value="<?= $b['id'] ?>" data-nama="<?= htmlspecialchars($b['nama_barang']) ?>"
                                data-satuan="<?= htmlspecialchars($b['satuan']) ?>" data-repack="<?= $b['bisa_dipecah'] ?>">
                                <?= esc($b['nama_barang']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3" id="batch-container">
                    <label class="form-label">Pilih Batch <span class="text-danger">*</span></label>
                    <select id="id_batch" class="form-select" onchange="updateStokTersedia()">
                        <option value="">Pilih barang terlebih dahulu...</option>
                    </select>
                    <small class="text-muted mt-1 d-block" id="stok-info">Stok Tersedia: —</small>
                </div>

                <div class="mb-3" id="exp-container" style="display:none">
                    <label class="form-label">Tanggal Kedaluwarsa <span class="text-danger">*</span></label>
                    <input type="date" id="tanggal_kedaluwarsa" class="form-control">
                    <small class="text-muted mt-1 d-block">Tanggal expired untuk barang yang ditambahkan.</small>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-7">
                        <label class="form-label">Jumlah</label>
                        <input type="number" id="jumlah" class="form-control" step="any" min="0" placeholder="0">
                    </div>
                    <div class="col-5">
                        <label class="form-label">Satuan</label>
                        <input type="text" id="satuan_tampil" class="form-control" readonly>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Catatan Item <span class="text-muted fw-normal">(opsional)</span></label>
                    <input type="text" id="keterangan_item" class="form-control" placeholder="Contoh: Kemasan robek">
                </div>

                <button type="button" class="btn btn-primary btn-add-item" onclick="tambahKeDaftar()">
                    <i class="fa-solid fa-plus me-1"></i> Tambahkan ke Daftar
                </button>
            </div>
        </div>
    </div>

    <!-- ── KOLOM KANAN: Daftar & Simpan ── -->
    <div class="col-lg-8">
        <form action="<?= site_url('transaksi/penyesuaian/store') ?>" method="POST" id="form-transaksi"
            onsubmit="document.getElementById('btn-submit').disabled=true; document.getElementById('btn-submit').innerHTML='<i class=\'fa-solid fa-spinner fa-spin me-1\'></i> Menyimpan...';">
            <?= csrf_field() ?>
            <input type="hidden" name="jenis_penyesuaian" id="form_jenis_penyesuaian" value="Barang Rusak">

            <div class="form-card">
                <div class="form-header">
                    <h5><i class="fa-solid fa-list-check me-2 text-primary" style="font-size:13px"></i>Daftar Barang
                        yang Disesuaikan</h5>
                    <span class="badge bg-primary badge-count-sm" id="total-items">0 Item</span>
                </div>
                <div class="form-body">

                    <div id="daftar-barang-kosong" class="empty-state">
                        <i class="fa-solid fa-box-open"></i>
                        <p>Belum ada barang yang ditambahkan.</p>
                        <small>Pilih barang di sebelah kiri lalu klik <strong>Tambahkan ke Daftar</strong>.</small>
                    </div>

                    <div id="daftar-barang"></div>

                    <hr class="my-3">

                    <div class="mb-3">
                        <label class="form-label">Keterangan / Alasan Umum <span class="text-danger">*</span></label>
                        <textarea name="keterangan" id="keterangan_umum" class="form-control"
                            placeholder="Sebutkan alasan penyesuaian stok ini dilakukan..." required></textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-2">
                        <a href="<?= site_url('transaksi/penyesuaian') ?>"
                            class="btn btn-light border btn-batal">Batal</a>
                        <button type="submit" class="btn btn-success btn-simpan shadow-sm" id="btn-submit" disabled>
                            <i class="fa-solid fa-save me-1"></i> Simpan Penyesuaian
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    let items = [];
    let stokMax = 0;

    $(document).ready(function () {
        $('.select2').select2({ theme: 'bootstrap-5' });
    });

    function handleJenisChange() {
        const jenis = $('#jenis_penyesuaian').val();
        $('#form_jenis_penyesuaian').val(jenis);
        items = [];
        renderDaftar();

        if (jenis === 'Koreksi Positif') {
            $('#batch-container').hide();
            $('#exp-container').show();
            stokMax = 9999999;
            $('#stok-info').text('Stok Tersedia: - (Penambahan stok tidak dibatasi)');
        } else {
            $('#batch-container').show();
            $('#exp-container').hide();
            fetchBatches();
        }

        if (jenis === 'Barang Kedaluwarsa') {
            $('#keterangan_umum').val('Penyesuaian otomatis untuk barang kedaluwarsa.');
        } else {
            $('#keterangan_umum').val('');
        }
    }

    function fetchBatches() {
        const idBarang = $('#id_barang').val();
        const jenis = $('#jenis_penyesuaian').val();
        const batchSelect = $('#id_batch');
        const satuanEl = $('#satuan_tampil');

        batchSelect.empty().append('<option value="">Pilih Batch...</option>');
        $('#stok-info').text('Stok Tersedia: —');
        stokMax = 0;

        if (!idBarang) { satuanEl.val(''); return; }

        const selectedOption = $('#id_barang option:selected');
        const isRepack = selectedOption.data('repack') == 1;
        satuanEl.val(isRepack ? 'Kg' : selectedOption.data('satuan'));

        if (jenis === 'Koreksi Positif') { stokMax = 9999999; return; }

        $.get('<?= site_url('transaksi/penyesuaian/getBatches/') ?>' + idBarang, function (res) {
            if (res.batches && res.batches.length > 0) {
                res.batches.forEach(b => {
                    const expDate = new Date(b.tanggal_kedaluwarsa).toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric' });
                    batchSelect.append(`<option value="${b.id}" data-stok="${b.stok_saat_ini}" data-nomor="${b.nomor_batch}" data-exp="${b.tanggal_kedaluwarsa}">[${b.nomor_batch}] Exp: ${expDate} | Stok: ${b.stok_saat_ini}</option>`);
                });
            } else {
                batchSelect.html('<option value="">Tidak ada batch aktif untuk barang ini</option>');
            }
        });
    }

    function updateStokTersedia() {
        const selected = $('#id_batch option:selected');
        if (selected.val()) {
            stokMax = parseFloat(selected.data('stok'));
            $('#stok-info').text(`Stok Tersedia: ${stokMax} ${$('#satuan_tampil').val()}`);
        } else {
            stokMax = 0;
            $('#stok-info').text('Stok Tersedia: —');
        }
    }

    // SweetAlert2 helper — warning toast
    function swAlert(msg) {
        Swal.fire({
            icon: 'warning',
            title: msg,
            toast: false,
            position: 'center',
            showConfirmButton: true,
            confirmButtonText: 'OK',
            confirmButtonColor: '#2563eb'
        });
    }

    function tambahKeDaftar() {
        const jenis = $('#jenis_penyesuaian').val();
        const barangOpt = $('#id_barang option:selected');
        const idBarang = barangOpt.val();
        const namaBarang = barangOpt.data('nama');
        const isRepack = barangOpt.data('repack') == 1;
        const satuan = $('#satuan_tampil').val();
        let jumlah = parseFloat($('#jumlah').val());
        const keterangan = $('#keterangan_item').val();

        let idBatch = '', namaBatch = '', expDate = '';

        if (!idBarang) { swAlert('Pilih barang terlebih dahulu!'); return; }
        if (!jumlah || jumlah <= 0) { swAlert('Jumlah harus lebih dari 0!'); return; }
        if (!isRepack && !Number.isInteger(jumlah)) { swAlert('Barang utuh tidak boleh menggunakan angka desimal!'); return; }

        if (jenis === 'Koreksi Positif') {
            expDate = $('#tanggal_kedaluwarsa').val();
            if (!expDate) { swAlert('Tanggal kedaluwarsa wajib diisi untuk Koreksi Positif!'); return; }
            namaBatch = 'Akan dicarikan/dibuatkan batch baru';
        } else {
            const batchOpt = $('#id_batch option:selected');
            idBatch = batchOpt.val();
            if (!idBatch) { swAlert('Pilih batch terlebih dahulu!'); return; }

            let currentTotal = 0;
            items.forEach(i => { if (i.id_batch === idBatch) currentTotal += i.jumlah; });
            if ((currentTotal + jumlah) > stokMax) {
                swAlert(`Total melebihi stok tersedia! (Tersedia: ${stokMax}, Diinput: ${currentTotal + jumlah})`);
                return;
            }
            namaBatch = batchOpt.data('nomor') + ' (Exp: ' + batchOpt.data('exp') + ')';
        }

        items.push({ id_barang: idBarang, nama_barang: namaBarang, id_batch: idBatch, nama_batch: namaBatch, tanggal_kedaluwarsa: expDate, jumlah, satuan, keterangan });
        renderDaftar();
        $('#jumlah').val('');
        $('#keterangan_item').val('');
        $('#jenis_penyesuaian').prop('disabled', true);
    }

    function hapusItem(index) {
        items.splice(index, 1);
        if (items.length === 0) $('#jenis_penyesuaian').prop('disabled', false);
        renderDaftar();
    }

    function renderDaftar() {
        const container = $('#daftar-barang');
        const kosong = $('#daftar-barang-kosong');
        const totalLabel = $('#total-items');
        const btnSubmit = $('#btn-submit');

        container.empty();

        if (items.length === 0) {
            kosong.show();
            btnSubmit.prop('disabled', true);
            totalLabel.text('0 Item');
            return;
        }

        kosong.hide();
        btnSubmit.prop('disabled', false);
        totalLabel.text(items.length + ' Item');

        items.forEach((item, index) => {
            const isPositif = $('#jenis_penyesuaian').val() === 'Koreksi Positif';
            const sign = isPositif ? '+' : '-';
            const color = isPositif ? 'text-success' : 'text-danger';
            const infoBatch = item.id_batch ? item.nama_batch : `Exp: ${item.tanggal_kedaluwarsa}`;

            container.append(`
            <div class="cart-item">
                <button type="button" class="btn-remove-item" onclick="hapusItem(${index})" title="Hapus"><i class="fa-solid fa-xmark"></i></button>

                <input type="hidden" name="items[${index}][id_barang]"           value="${item.id_barang}">
                <input type="hidden" name="items[${index}][id_batch]"            value="${item.id_batch}">
                <input type="hidden" name="items[${index}][tanggal_kedaluwarsa]" value="${item.tanggal_kedaluwarsa}">
                <input type="hidden" name="items[${index}][jumlah]"              value="${item.jumlah}">
                <input type="hidden" name="items[${index}][satuan]"              value="${item.satuan}">
                <input type="hidden" name="items[${index}][keterangan]"          value="${item.keterangan}">

                <div class="d-flex justify-content-between align-items-center pe-4">
                    <span class="item-name">${item.nama_barang}</span>
                    <span class="item-qty ${color}">${sign}${item.jumlah} <small class="text-muted fw-normal" style="font-size:12px">${item.satuan}</small></span>
                </div>
                <div class="item-meta">
                    <span><i class="fa-solid fa-box me-1"></i>${infoBatch}</span>
                    ${item.keterangan ? `<span><i class="fa-solid fa-comment-dots me-1"></i>${item.keterangan}</span>` : ''}
                </div>
            </div>
        `);
        });
    }
</script>
<?= $this->endSection() ?>