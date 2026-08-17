<?= $this->extend('layout/main') ?>

<?= $this->section('styles') ?>
<style>
:root {
    --dm-primary: #4f46e5;
    --dm-bg: #f8fafc;
    --dm-border: #e2e8f0;
    --dm-text-main: #1e293b;
    --dm-text-muted: #64748b;
    --dm-radius: 12px;
    --dm-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
}
.dm-page { background: var(--dm-bg); min-height: calc(100vh - 60px); padding: 24px; font-family: 'Inter', system-ui, sans-serif; color: var(--dm-text-main); }
.dm-topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 2px dashed var(--dm-border); }
.dm-topbar__title { font-size: 1.5rem; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 12px; }
.dm-topbar__title i { color: var(--dm-primary); background: #eef2ff; padding: 10px; border-radius: 10px; font-size: 1.1rem; }
.dm-topbar__sub { color: var(--dm-text-muted); font-size: 0.9rem; margin: 4px 0 0 0; }
.dm-card { background: #fff; border-radius: var(--dm-radius); box-shadow: var(--dm-shadow); border: 1px solid #f1f5f9; margin-bottom: 24px; overflow: hidden; }
.dm-card__header { background: #f8fafc; padding: 16px 20px; border-bottom: 1px solid var(--dm-border); display: flex; justify-content: space-between; align-items: center; }
.dm-card__title { font-size: 1rem; font-weight: 600; margin: 0; display: flex; align-items: center; gap: 8px; color: #334155; }
.dm-card__body { padding: 20px; }
.dm-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 16px; }
.dm-grid-12 { display: grid; grid-template-columns: 1fr 2fr; gap: 16px; margin-bottom: 16px; }
.dm-label { display: block; font-size: 11.5px; font-weight: 600; color: #374151; margin-bottom: 4px; }
.dm-input, .dm-select { width: 100%; height: 36px; padding: 0 10px; font-size: 13px; border: 1px solid #d1d5db; border-radius: 8px; background: #fff; color: #111827; outline: none; }
.dm-textarea { width: 100%; height: 36px; padding: 8px 10px; font-size: 13px; border: 1px solid #d1d5db; border-radius: 8px; background: #fff; resize: none; outline: none; }
.dm-input.readonly { background: #f8fafc; color: #6b7280; }

.dm-table-wrap { overflow-x: auto; }
#tabelItem { width: 100%; table-layout: fixed; min-width: 700px; border-collapse: collapse; font-size: 12px; }
#tabelItem thead tr { background: #f8fafc; }
#tabelItem thead th { font-size: 11px; font-weight: 700; color: #6b7280; padding: 9px 8px; border: 1px solid #e5e7eb; }
#tabelItem tbody td { padding: 8px 7px; border: 1px solid #e5e7eb; vertical-align: top; }
#tabelItem .tc-no   { width: 42px;  text-align: center; }
#tabelItem .tc-brg  { width: 250px; }
#tabelItem .tc-stok { width: 100px; }
#tabelItem .tc-jml  { width: 120px; }
#tabelItem .tc-sat  { width: 120px; }
#tabelItem .tc-aksi { width: 52px;  text-align: center; }

#tabelItem .dm-input, #tabelItem .dm-select { height: 34px; font-size: 12px; padding: 0 8px; border-radius: 6px; }
.btn-tambah-item { height: 36px; padding: 0 16px; font-size: 12.5px; border-radius: 999px; display: inline-flex; align-items: center; gap: 5px; }
.btn-hapus-row { width: 28px; height: 28px; padding: 0; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; }
.dm-footer { display: flex; gap: 8px; margin-bottom: 8px; align-items: center; }
.btn-simpan { height: 38px; padding: 0 22px; font-size: 13px; border-radius: 999px; }
</style>

<div class="dm-page">
    <div class="dm-topbar">
        <div>
            <h1 class="dm-topbar__title"><i class="fa-solid fa-truck-ramp-box"></i><?= esc($title) ?></h1>
            <p class="dm-topbar__sub">Keluarkan barang dari stok gudang wilayah</p>
        </div>
        <a href="<?= site_url('wilayah/keluar') ?>" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    <?php if (session()->getFlashdata('errors')) : ?>
        <div class="alert alert-danger rounded-3 mb-3" style="font-size:12px;">
            <i class="fa-solid fa-triangle-exclamation me-2"></i><strong>Terjadi Kesalahan:</strong>
            <ul class="mb-0 mt-1">
                <?php foreach (session()->getFlashdata('errors') as $err) : ?>
                    <li><?= esc($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger rounded-3 mb-3" style="font-size:12px;">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <form action="<?= site_url('wilayah/keluar/store') ?>" method="POST" id="formBarangKeluar">
        <?= csrf_field() ?>
        
        <div class="dm-card">
            <div class="dm-card__header">
                <h5 class="dm-card__title"><i class="fa-solid fa-file-lines"></i>Header Transaksi</h5>
            </div>
            <div class="dm-card__body">
                <div class="dm-grid-3">
                    <div>
                        <label class="dm-label">Kode Transaksi Barang Keluar <span class="text-danger">*</span></label>
                        <input type="text" class="dm-input bg-light" name="nomor_dokumen" value="<?= old('nomor_dokumen', $autoDoc) ?>" readonly>
                    </div>
                    <div>
                        <label class="dm-label">Tanggal Keluar <span class="text-danger">*</span></label>
                        <input type="date" class="dm-input" name="tanggal" value="<?= old('tanggal', date('Y-m-d')) ?>" required>
                    </div>
                    <div>
                        <label class="dm-label">Gudang Wilayah <span class="text-danger">*</span></label>
                        <?php if (!empty($isAdmin)): ?>
                            <select name="id_gudang" id="id_gudang" class="dm-select select2" required>
                                <?php foreach ($allGudang as $g): ?>
                                    <option value="<?= $g['id'] ?>" <?= ($g['id'] == $userGudangId) ? 'selected' : '' ?>><?= esc($g['nama']) ?> - <?= esc($g['kota']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php else: ?>
                            <select name="id_gudang" id="id_gudang" class="dm-select readonly" style="pointer-events: none; background-color: #f8fafc;">
                                <?php foreach ($gudang as $g): ?>
                                    <option value="<?= $g['id'] ?>" selected><?= esc($g['nama']) ?> - <?= esc($g['kota']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="dm-grid-12">
                    <div>
                        <label class="dm-label">Tujuan Penyaluran <span class="text-danger">*</span></label>
                        <input type="text" class="dm-input" name="tujuan" value="<?= old('tujuan') ?>" placeholder="Panti Asuhan, Dll." required>
                    </div>
                    <div>
                        <label class="dm-label">Keterangan</label>
                        <textarea class="dm-textarea" name="keterangan" placeholder="Opsional"><?= old('keterangan') ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="dm-card">
            <div class="dm-card__header">
                <h5 class="dm-card__title"><i class="fa-solid fa-boxes-stacked"></i>Detail Barang</h5>
                <button type="button" class="btn btn-success text-white btn-tambah-item" id="btnTambahItem">
                    <i class="fa-solid fa-plus"></i> Tambah Item
                </button>
            </div>
            <div class="dm-card__body">
                <div class="dm-table-wrap">
                    <table id="tabelItem">
                        <thead>
                            <tr>
                                <th class="tc-no">NO</th>
                                <th class="tc-brg">NAMA BARANG <span class="text-danger">*</span></th>
                                <th class="tc-stok">STOK</th>
                                <th class="tc-jml">JUMLAH <span class="text-danger">*</span></th>
                                <th class="tc-sat">SATUAN</th>
                                <th class="tc-aksi">AKSI</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyItem"></tbody>
                    </table>
                </div>
                <div id="errorItem" class="text-danger mt-2" style="display:none;">
                    <i class="fa-solid fa-circle-exclamation me-1"></i> Minimal harus ada 1 barang.
                </div>
            </div>
        </div>

        <div class="dm-footer">
            <button type="submit" class="btn btn-primary btn-simpan" id="btnSimpan">
                <i class="fa-solid fa-save"></i> Simpan Transaksi
            </button>
            <a href="<?= site_url('wilayah/keluar') ?>" class="btn btn-outline-secondary btn-batal">Batal</a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const baseUrl       = '<?= base_url() ?>';
    let availableBarang = <?= json_encode($barang_tersedia ?? []) ?>;
    const oldItems      = <?= json_encode(array_values($oldItems ?? [])) ?>;
    let rowCount        = 0;

    function renderOptions(selectedValue = '') {
        let html = '<option value="">-- Pilih Barang --</option>';
        availableBarang.forEach(b => {
            const sel = String(selectedValue) === String(b.id) ? 'selected' : '';
            html += `<option value="${b.id}" data-stok="${b.jumlah}" data-satuan="${b.satuan}" ${sel}>${b.kode_barang} - ${b.nama_barang}</option>`;
        });
        return html;
    }

    function reindexNames() {
        document.querySelectorAll('#tbodyItem tr').forEach((tr, i) => {
            const idx = i + 1;
            tr.querySelectorAll('[name]').forEach(el => {
                el.name = el.name.replace(/items\[\d+\]/, `items[${idx}]`);
            });
            const noCell = tr.querySelector('.row-number');
            if (noCell) noCell.textContent = idx;
        });
    }

    function addRow(data = {}) {
        rowCount++;
        const idRow = 'row_' + Date.now() + Math.random().toString(36).substr(2, 5);
        const html = `
            <tr id="${idRow}">
                <td class="tc-no row-number">${rowCount}</td>
                <td class="tc-brg">
                    <select class="dm-select select-barang select2-item" name="items[${rowCount}][id_barang]" required>
                        ${renderOptions(data.id_barang)}
                    </select>
                </td>
                <td class="tc-stok">
                    <input type="text" class="dm-input input-stok readonly" readonly value="">
                </td>
                <td class="tc-jml">
                    <input type="number" class="dm-input input-jumlah" name="items[${rowCount}][jumlah]" value="${data.jumlah || ''}" min="0.01" step="0.01" required>
                </td>
                <td class="tc-sat">
                    <input type="text" class="dm-input input-satuan readonly" name="items[${rowCount}][satuan]" readonly value="${data.satuan || ''}">
                </td>
                <td class="tc-aksi">
                    <button type="button" class="btn btn-danger btn-hapus-row"><i class="fa-solid fa-trash-can"></i></button>
                </td>
            </tr>
        `;
        document.getElementById('tbodyItem').insertAdjacentHTML('beforeend', html);
        const tr = document.getElementById(idRow);
        const selectBarang = tr.querySelector('.select-barang');
        const inputStok = tr.querySelector('.input-stok');
        const inputSatuan = tr.querySelector('.input-satuan');
        const inputJumlah = tr.querySelector('.input-jumlah');

        $(selectBarang).select2({ theme: 'bootstrap-5', width: '100%' });
        
        if (data.id_barang) {
            setTimeout(() => $(selectBarang).trigger('change'), 50);
        }

        tr.querySelector('.btn-hapus-row').addEventListener('click', function() {
            tr.remove();
            reindexNames();
        });
    }

    // ✅ Event delegation terpusat untuk semua select-barang
    $(document).on('change select2:select select2:clear', '.select-barang', function() {
        const tr = $(this).closest('tr');
        const val = $(this).val();
        const b = availableBarang.find(x => String(x.id) === String(val));
        
        const inputStok = tr.find('.input-stok');
        const inputSatuan = tr.find('.input-satuan');
        const inputJumlah = tr.find('.input-jumlah');
        
        let stok = '';
        let satuan = '';

        if (b) {
            stok = b.jumlah || '';
            satuan = b.satuan || '';
        } else {
            const opt = $(this).find('option:selected');
            if (opt.length && opt.val()) {
                stok = opt.attr('data-stok') || '';
                satuan = opt.attr('data-satuan') || '';
            }
        }

        if (val && stok !== '') {
            inputStok.val(stok ? (stok + ' ' + satuan) : '');
            inputSatuan.val(satuan);
            inputStok.css('color', parseFloat(stok) <= 0 ? '#dc2626' : '#16a34a');
            inputJumlah.attr('max', stok);
        } else {
            inputStok.val('');
            inputSatuan.val('');
            inputStok.css('color', '');
            inputJumlah.removeAttr('max');
        }
    });

    function updateAllDropdowns() {
        document.querySelectorAll('#tbodyItem tr').forEach(tr => {
            const select = $(tr.querySelector('.select-barang'));
            const val = select.val();
            select.html(renderOptions(val));
            select.trigger('change');
        });
    }

    $('#id_gudang').on('change', function() {
        const idGudang = $(this).val();
        if (!idGudang) return;

        // ✅ Ambil token terbaru dari meta tag
        const csrfName  = document.querySelector('meta[name="csrf-token-name"]').content;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        $.ajax({
            url: '<?= site_url("wilayah/keluar/ajax-barang") ?>',
            type: 'POST',
            data: { id_gudang: idGudang, [csrfName]: csrfToken },
            dataType: 'json',
            success: function(res) {
                availableBarang = res || [];
                updateAllDropdowns();
            },
            error: function(xhr) {
                console.error('Gagal load barang:', xhr.status, xhr.responseText);
            }
        });
    });

    document.getElementById('btnTambahItem').addEventListener('click', () => addRow());

    document.getElementById('formBarangKeluar').addEventListener('submit', function(e) {
        if (document.querySelectorAll('#tbodyItem tr').length === 0) {
            e.preventDefault();
            const err = document.getElementById('errorItem');
            err.style.display = 'block';
            setTimeout(() => err.style.display = 'none', 3000);
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        if (oldItems && oldItems.length > 0) {
            oldItems.forEach(item => addRow(item));
        } else {
            addRow();
        }
        if ($.fn.select2) {
            $('.select2').select2({ theme: 'bootstrap-5', width: '100%' });
        }
    });
</script>
<?= $this->endSection() ?>
