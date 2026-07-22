<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<style>
/* ══════════════════════════════════════════════
   GLOBAL ANTI-OVERFLOW
   Pastikan tidak ada elemen yang melewati batas
   viewport secara horizontal.
══════════════════════════════════════════════ */
*, *::before, *::after {
    box-sizing: border-box;
}
html, body {
    overflow-x: hidden;
    max-width: 100%;
}

/* ══════════════════════════════════════════════
   SHELL — one-fit-page container
   Tinggalkan calc() agar fleksibel di semua
   layout (AdminLTE, Sneat, Bootstrap, dll).
   --ps-nav = tinggi top navbar. Ubah sesuai
   layout Anda. Untuk AdminLTE biasanya 57px.
══════════════════════════════════════════════ */
.ps-shell {
    --ps-nav : 57px;   /* ← sesuaikan tinggi navbar */

    display        : flex;
    flex-direction : column;
    height         : calc(100vh - var(--ps-nav));
    width          : 100%;
    max-width      : 100%;
    padding        : 10px 14px;
    gap            : 12px;
    box-sizing     : border-box;
    background     : #f1f5f9;
    font-size      : 13px;
    line-height    : 1.4;
    overflow       : hidden;   /* tidak ada scroll sama sekali */
}

/* ── HEADER ─────────────────────────────────── */
.ps-hdr {
    display         : flex;
    align-items     : center;
    justify-content : space-between;
    flex-wrap       : wrap;        /* wrap di layar kecil */
    gap             : 8px;
    background      : #fff;
    border          : 1px solid #e2e8f0;
    border-radius   : 14px;
    padding         : 0 18px;
    height          : 62px;
    flex-shrink     : 0;
    width           : 100%;
    max-width       : 100%;
    box-shadow      : 0 1px 3px rgba(15,23,42,.05);
}
.ps-hdr__l { display: flex; align-items: center; gap: 10px; min-width: 0; }
.ps-hdr__ico {
    width: 36px; height: 36px; border-radius: 10px;
    background: linear-gradient(135deg,#dbeafe,#bfdbfe);
    display: flex; align-items: center; justify-content: center;
    color: #2563eb; font-size: 15px; flex-shrink: 0;
}
.ps-hdr__ttl {
    font-size: 16px; font-weight: 700; color: #0f172a;
    margin: 0; white-space: nowrap; overflow: hidden;
    text-overflow: ellipsis;
}
.ps-hdr__sub { font-size: 11px; color: #64748b; margin: 1px 0 0; }
.ps-btn-back {
    height: 36px; padding: 0 14px; font-size: 12.5px; font-weight: 600;
    border-radius: 9px; display: inline-flex; align-items: center; gap: 6px;
    color: #374151; background: #f9fafb; border: 1.5px solid #e2e8f0;
    text-decoration: none; white-space: nowrap; flex-shrink: 0;
    transition: background .15s, border-color .15s;
}
.ps-btn-back:hover { background: #eff6ff; border-color: #93c5fd; color: #2563eb; }

/* ── BODY: dua kolom ────────────────────────── */
.ps-body {
    display    : flex;
    gap        : 12px;
    flex       : 1;
    min-height : 0;
    width      : 100%;
    max-width  : 100%;
    overflow   : hidden;
}

/* ── CARD base ──────────────────────────────── */
.ps-card {
    background    : #fff;
    border        : 1px solid #e2e8f0;
    border-radius : 14px;
    box-shadow    : 0 2px 8px rgba(15,23,42,.05);
    display       : flex;
    flex-direction: column;
    overflow      : hidden;
    min-width     : 0;      /* ← kritis: cegah flex child melar */
    min-height    : 0;
}
.ps-ch {
    display         : flex;
    align-items     : center;
    justify-content : space-between;
    background      : #f8fafc;
    border-bottom   : 1px solid #e2e8f0;
    padding         : 0 16px;
    height          : 44px;
    flex-shrink     : 0;
}
.ps-ch__l   { display: flex; align-items: center; gap: 8px; min-width: 0; }
.ps-ch__ico {
    width: 24px; height: 24px; border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; flex-shrink: 0;
}
.ps-ch__ico.blue  { background: #dbeafe; color: #2563eb; }
.ps-ch__ico.green { background: #dcfce7; color: #16a34a; }
.ps-ch__ttl { font-size: 12.5px; font-weight: 700; color: #0f172a; margin: 0; }

/* card body */
.ps-cb {
    padding        : 14px;
    display        : flex;
    flex-direction : column;
    flex           : 1;
    min-height     : 0;
    overflow       : hidden;
    gap            : 8px;
    width          : 100%;
    max-width      : 100%;
}

/* ── PANEL KIRI — fixed 360px ───────────────── */
.ps-left {
    width     : 360px;
    flex-shrink : 0;
    min-width : 0;
}

/* ── PANEL KANAN — sisa ruang ───────────────── */
.ps-right {
    flex      : 1;
    min-width : 0;   /* ← wajib agar bisa menyusut */
}
.ps-right .ps-cb { gap: 10px; }

/* ── 2-column grid di panel kiri ───────────── */
.ps-grid {
    display               : grid;
    grid-template-columns : 1fr 1fr;
    gap                   : 8px;
    width                 : 100%;
}
.ps-span2 { grid-column: 1 / -1; }

.ps-field { display: flex; flex-direction: column; gap: 3px; min-width: 0; }

.ps-lbl {
    font-size  : 11.5px;
    font-weight: 600;
    color      : #374151;
    margin     : 0;
    white-space: nowrap;
}
.ps-lbl .req { color: #ef4444; }

/* ── INPUT / SELECT — TIDAK boleh melar keluar ─ */
.form-control,
.form-select,
input[type="text"],
input[type="number"],
input[type="date"] {
    height      : 38px !important;
    font-size   : 12.5px !important;
    padding     : 0 9px !important;
    border      : 1.5px solid #cbd5e1 !important;
    border-radius: 8px !important;
    color       : #0f172a !important;
    background  : #fff !important;
    width       : 100% !important;
    max-width   : 100% !important;
    min-width   : 0 !important;
    box-sizing  : border-box !important;
    line-height : 38px !important;
    transition  : border-color .15s, box-shadow .15s !important;
    outline     : none !important;
    display     : block !important;
}
.form-control:focus, .form-select:focus {
    border-color: #2563eb !important;
    box-shadow  : 0 0 0 3px rgba(37,99,235,.09) !important;
}
.form-control[readonly] {
    background: #f8fafc !important;
    color     : #64748b !important;
}

/* ── SELECT2 — cegah melar dan tutupi sidebar ─ */
.select2-container {
    width    : 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
}
.select2-container--bootstrap-5 .select2-selection {
    height       : 38px !important;
    border       : 1.5px solid #cbd5e1 !important;
    border-radius: 8px !important;
    font-size    : 12.5px !important;
    display      : flex !important;
    align-items  : center !important;
    width        : 100% !important;
    max-width    : 100% !important;
    box-sizing   : border-box !important;
}
.select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
    line-height: 38px !important;
    padding    : 0 9px !important;
    font-size  : 12.5px !important;
    white-space: nowrap;
    overflow   : hidden;
    text-overflow: ellipsis;
}
.select2-container--bootstrap-5.select2-container--focus .select2-selection,
.select2-container--bootstrap-5.select2-container--open  .select2-selection {
    border-color: #2563eb !important;
    box-shadow  : 0 0 0 3px rgba(37,99,235,.09) !important;
}
/* Dropdown — muncul di atas (bukan di bawah sidebar) */
.select2-dropdown {
    z-index  : 9999 !important;   /* di atas sidebar */
    width    : auto !important;
    min-width: 200px;
    max-width: 400px;
    font-size: 12.5px !important;
    box-shadow: 0 8px 24px rgba(15,23,42,.12) !important;
    border   : 1.5px solid #e2e8f0 !important;
    border-radius: 10px !important;
    overflow : hidden;
}
.select2-results__option { font-size: 12.5px !important; padding: 7px 12px !important; }
.select2-search--dropdown .select2-search__field {
    font-size: 12.5px !important;
    border   : 1.5px solid #cbd5e1 !important;
    border-radius: 6px !important;
    padding  : 5px 8px !important;
    height   : auto !important;
    line-height: normal !important;
    outline  : none !important;
}

.ps-hint { font-size: 10.5px; color: #64748b; margin: 2px 0 0; }

/* ── TOMBOL TAMBAH ──────────────────────────── */
.ps-btn-add {
    width        : 100%;
    max-width    : 100%;
    height       : 40px;
    font-size    : 13px;
    font-weight  : 700;
    border-radius: 9px;
    display      : inline-flex;
    align-items  : center;
    justify-content: center;
    gap          : 7px;
    border       : none;
    cursor       : pointer;
    flex-shrink  : 0;
    box-sizing   : border-box;
    transition   : transform .15s, box-shadow .15s;
}
.ps-btn-add:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(37,99,235,.25); }

/* ── DAFTAR ITEM — panel kanan ──────────────── */
.ps-list {
    flex      : 1;
    min-height: 0;
    min-width : 0;
    border    : 1.5px solid #e2e8f0;
    border-radius: 10px;
    background: #fafafa;
    position  : relative;
    overflow  : hidden;   /* tidak ada scrollbar */
    display   : flex;
    flex-direction: column;
    width     : 100%;
    max-width : 100%;
}

/* empty state */
.ps-empty {
    position : absolute; inset: 0;
    display  : flex; flex-direction: column;
    align-items: center; justify-content: center;
    gap: 8px; padding: 16px; text-align: center;
}
.ps-empty i     { font-size: 28px; color: #94a3b8; opacity: .6; }
.ps-empty p     { font-size: 12.5px; color: #64748b; margin: 0; }
.ps-empty small { font-size: 11.5px; color: #94a3b8; }

/* cart items */
#daftar-barang { flex: 1; overflow: hidden; min-width: 0; }

.cart-item {
    background   : #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding      : 8px 36px 8px 12px;
    position     : relative;
    width        : 100%;
    max-width    : 100%;
    box-sizing   : border-box;
    transition   : background .1s;
}
.cart-item:last-child { border-bottom: none; }
.cart-item:hover { background: #f1f5f9; }
.cart-item .item-name {
    font-size  : 12.5px; font-weight: 600; color: #0f172a;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    max-width  : calc(100% - 70px);
}
.cart-item .item-qty  { font-size: 12.5px; font-weight: 700; white-space: nowrap; }
.cart-item .item-meta { font-size: 11px; color: #64748b; margin-top: 2px; }

.btn-remove-item {
    position     : absolute; top: 50%; right: 8px;
    transform    : translateY(-50%);
    width: 26px; height: 26px; border-radius: 6px;
    border       : 1.5px solid #fecaca; background: #fff; color: #dc2626;
    display      : flex; align-items: center; justify-content: center;
    font-size    : 11px; cursor: pointer; transition: background .15s;
}
.btn-remove-item:hover { background: #fee2e2; }

.badge-count-sm { font-size: 11px; padding: 3px 9px; border-radius: 999px; }

/* section label sebelum textarea */
.ps-sec-lbl {
    font-size  : 10.5px; font-weight: 700; color: #94a3b8;
    text-transform: uppercase; letter-spacing: .06em;
    display    : flex; align-items: center; gap: 6px;
    flex-shrink: 0; white-space: nowrap;
}
.ps-sec-lbl::after { content:''; flex:1; height:1px; background: #e2e8f0; }

/* textarea keterangan */
.ps-textarea {
    width        : 100%;
    max-width    : 100%;
    height       : 74px;
    font-size    : 12.5px;
    padding      : 8px 10px;
    border       : 1.5px solid #cbd5e1;
    border-radius: 8px;
    background   : #fff;
    color        : #0f172a;
    resize       : none;
    outline      : none;
    font-family  : inherit;
    line-height  : 1.5;
    box-sizing   : border-box;
    flex-shrink  : 0;
    display      : block;
    transition   : border-color .15s, box-shadow .15s;
}
.ps-textarea:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,.09); }

/* footer tombol */
.ps-footer {
    display        : flex;
    justify-content: flex-end;
    align-items    : center;
    gap            : 8px;
    flex-shrink    : 0;
    width          : 100%;
    max-width      : 100%;
}
.ps-btn-cancel {
    height       : 38px; padding: 0 16px; font-size: 12.5px; font-weight: 600;
    border-radius: 9px; border: 1.5px solid #d1d5db; background: #fff; color: #374151;
    cursor       : pointer; display: inline-flex; align-items: center; gap: 6px;
    text-decoration: none; white-space: nowrap; transition: background .15s;
    flex-shrink  : 0; box-sizing: border-box;
}
.ps-btn-cancel:hover { background: #f1f5f9; }
.ps-btn-save {
    height       : 38px; padding: 0 20px; font-size: 12.5px; font-weight: 700;
    border-radius: 9px; border: none; cursor: pointer;
    display      : inline-flex; align-items: center; gap: 6px;
    white-space  : nowrap; flex-shrink: 0; box-sizing: border-box;
    transition   : transform .15s, box-shadow .15s;
}
.ps-btn-save:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(22,163,74,.28); }
.ps-btn-save:disabled { opacity: .5; pointer-events: none; }

/* ── RESPONSIVE (tablet/mobile boleh scroll) ─ */
@media (max-width: 1199px) {
    .ps-shell {
        height        : auto;
        overflow      : visible;
        padding-bottom: 24px;
    }
    .ps-body {
        flex-direction: column;
        overflow      : visible;
    }
    .ps-left {
        width : 100%;
    }
    .ps-list {
        min-height: 160px;
        max-height: 240px;
        overflow-y: auto;
    }
    #daftar-barang { overflow-y: auto; }
}
</style>

<div class="ps-shell">

    <!-- ── HEADER ── -->
    <div class="ps-hdr">
        <div class="ps-hdr__l">
            <div class="ps-hdr__ico"><i class="fa-solid fa-scale-balanced"></i></div>
            <div>
                <h1 class="ps-hdr__ttl">Tambah Penyesuaian Stok</h1>
                <p class="ps-hdr__sub">Penyesuaian untuk barang rusak, hilang, kedaluwarsa, atau stock opname</p>
            </div>
        </div>
        <a href="<?= site_url('transaksi/penyesuaian') ?>" class="ps-btn-back">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- ── BODY ── -->
    <div class="ps-body">

        <!-- ════ KIRI 360px ════ -->
        <div class="ps-card ps-left">
            <div class="ps-ch">
                <div class="ps-ch__l">
                    <div class="ps-ch__ico blue"><i class="fa-solid fa-box"></i></div>
                    <span class="ps-ch__ttl">Pilih Barang</span>
                </div>
            </div>
            <div class="ps-cb">

                <div class="ps-grid">

                    <!-- Jenis -->
                    <div class="ps-field ps-span2">
                        <label class="ps-lbl" for="jenis_penyesuaian">Jenis Penyesuaian</label>
                        <select id="jenis_penyesuaian" class="form-select">
                            <option value="Barang Rusak">Barang Rusak (−)</option>
                            <option value="Barang Hilang">Barang Hilang (−)</option>
                            <option value="Barang Kedaluwarsa">Barang Kedaluwarsa (−)</option>
                            <option value="Hasil Stock Opname">Hasil Stock Opname (−)</option>
                            <option value="Koreksi Negatif">Koreksi Negatif (−)</option>
                            <option value="Koreksi Positif">Koreksi Positif (+)</option>
                        </select>
                    </div>

                    <!-- Barang (Select2) -->
                    <div class="ps-field ps-span2">
                        <label class="ps-lbl" for="id_barang">Barang</label>
                        <select id="id_barang" class="form-select select2" onchange="fetchBatches()">
                            <option value="">Pilih Barang...</option>
                            <?php foreach ($barang as $b): ?>
                                <option value="<?= $b['id'] ?>"
                                    data-nama="<?= htmlspecialchars($b['nama_barang']) ?>"
                                    data-satuan="<?= htmlspecialchars($b['satuan']) ?>"
                                    data-repack="<?= $b['bisa_dipecah'] ?>">
                                    <?= esc($b['nama_barang']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Batch -->
                    <div class="ps-field ps-span2" id="batch-container">
                        <label class="ps-lbl" for="id_batch">Pilih Batch <span class="req">*</span></label>
                        <select id="id_batch" class="form-select" onchange="updateStokTersedia()">
                            <option value="">Pilih barang terlebih dahulu...</option>
                        </select>
                        <small class="ps-hint" id="stok-info">Stok Tersedia: —</small>
                    </div>

                    <!-- Tanggal Kedaluwarsa -->
                    <div class="ps-field ps-span2" id="exp-container" style="display:none;">
                        <label class="ps-lbl" for="tanggal_kedaluwarsa">Tanggal Kedaluwarsa <span class="req">*</span></label>
                        <input type="date" id="tanggal_kedaluwarsa" class="form-control">
                        <small class="ps-hint">Tanggal expired untuk barang yang ditambahkan.</small>
                    </div>

                    <!-- Jumlah (kiri) + Satuan (kanan) -->
                    <div class="ps-field">
                        <label class="ps-lbl" for="jumlah">Jumlah</label>
                        <input type="number" id="jumlah" class="form-control" step="any" min="0" placeholder="0">
                    </div>
                    <div class="ps-field">
                        <label class="ps-lbl" for="satuan_tampil">Satuan</label>
                        <input type="text" id="satuan_tampil" class="form-control" readonly>
                    </div>

                    <!-- Catatan Item -->
                    <div class="ps-field ps-span2">
                        <label class="ps-lbl" for="keterangan_item">
                            Catatan Item <span style="font-weight:400;color:#64748b;">(opsional)</span>
                        </label>
                        <input type="text" id="keterangan_item" class="form-control" placeholder="Contoh: Kemasan robek">
                    </div>

                </div><!-- /.ps-grid -->

                <button type="button" class="ps-btn-add btn btn-primary" onclick="tambahKeDaftar()">
                    <i class="fa-solid fa-plus-circle"></i> Tambahkan ke Daftar
                </button>

            </div>
        </div><!-- /.ps-left -->

        <!-- ════ KANAN flex:1 ════ -->
        <form action="<?= site_url('transaksi/penyesuaian/store') ?>" method="POST"
              id="form-transaksi" class="loading-form ps-card ps-right" data-overlay="true">
            <?= csrf_field() ?>
            <input type="hidden" name="jenis_penyesuaian" id="form_jenis_penyesuaian" value="Barang Rusak">

            <div class="ps-ch">
                <div class="ps-ch__l">
                    <div class="ps-ch__ico green"><i class="fa-solid fa-list-check"></i></div>
                    <span class="ps-ch__ttl">Daftar Barang yang Disesuaikan</span>
                </div>
                <span class="badge bg-primary badge-count-sm" id="total-items">0 Item</span>
            </div>

            <div class="ps-cb">

                <!-- Daftar item — flex:1, no scrollbar -->
                <div class="ps-list">
                    <div id="daftar-barang-kosong" class="ps-empty">
                        <i class="fa-solid fa-box-open"></i>
                        <p>Belum ada barang yang ditambahkan.</p>
                        <small>Pilih barang di sebelah kiri lalu klik <strong>Tambahkan ke Daftar</strong>.</small>
                    </div>
                    <div id="daftar-barang"></div>
                </div>

                <!-- Keterangan -->
                <div class="ps-sec-lbl">
                    <i class="fa-regular fa-comment-dots" style="color:#94a3b8"></i> Keterangan / Alasan Umum
                </div>
                <textarea name="keterangan" id="keterangan_umum" class="ps-textarea"
                    placeholder="Sebutkan alasan penyesuaian stok ini dilakukan..." required></textarea>

                <!-- Aksi -->
                <div class="ps-footer">
                    <a href="<?= site_url('transaksi/penyesuaian') ?>" class="ps-btn-cancel">
                        <i class="fa-solid fa-xmark"></i> Batal
                    </a>
                    <button type="submit" class="ps-btn-save btn btn-success shadow-sm"
                            id="btn-submit" disabled>
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Penyesuaian
                    </button>
                </div>

            </div>
        </form><!-- /.ps-right -->

    </div><!-- /.ps-body -->
</div><!-- /.ps-shell -->

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/sweetalert2.min.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    function escHtml(str) {
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(str ?? ''));
        return div.innerHTML;
    }
    let items = [];
    let stokMax = 0;
    let currentJenis = $('#jenis_penyesuaian').val();

    $(document).ready(function () {
        /*
         * dropdownParent: arahkan dropdown ke body agar tidak
         * terpotong oleh overflow:hidden dan tidak menimpa sidebar.
         * z-index: 9999 di CSS sudah menangani layering.
         */
        $('.select2').select2({
            theme        : 'bootstrap-5',
            dropdownParent: $('body')
        });

        $('#jenis_penyesuaian').on('change', function () {
            const newJenis = $(this).val();
            if (items.length > 0) {
                Swal.fire({
                    title            : 'Ganti Jenis Penyesuaian?',
                    text             : 'Daftar barang yang sudah ditambahkan akan dihapus karena formatnya berbeda.',
                    icon             : 'warning',
                    showCancelButton : true,
                    confirmButtonText: 'Ya, Ganti',
                    cancelButtonText : 'Batal',
                    reverseButtons   : true
                }).then((result) => {
                    if (result.isConfirmed) {
                        currentJenis = newJenis;
                        handleJenisChangeCore(newJenis);
                    } else {
                        $(this).val(currentJenis);
                    }
                });
            } else {
                currentJenis = newJenis;
                handleJenisChangeCore(newJenis);
            }
        });
    });

    function handleJenisChangeCore(jenis) {
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
        const idBarang   = $('#id_barang').val();
        const jenis      = $('#jenis_penyesuaian').val();
        const batchSelect = $('#id_batch');
        const satuanEl   = $('#satuan_tampil');

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
                    const expDate = new Date(b.tanggal_kedaluwarsa).toLocaleDateString('id-ID', {
                        year: 'numeric', month: 'short', day: 'numeric'
                    });
                    const sat = (parseInt(b.bisa_dipecah) === 1) ? 'Kg' : (b.satuan || '');
                    batchSelect.append(
                        `<option value="${b.id}" data-stok="${b.stok_saat_ini}"
                            data-nomor="${b.nomor_batch}" data-exp="${b.tanggal_kedaluwarsa}"
                            data-satuan="${sat}">
                            [${b.nomor_batch}] Exp: ${expDate} | Stok: ${b.stok_saat_ini}
                        </option>`
                    );
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
            const batchSatuan = selected.data('satuan');
            if (batchSatuan && batchSatuan !== 'undefined' && batchSatuan !== '') {
                $('#satuan_tampil').val(batchSatuan);
            }
            $('#stok-info').text(`Stok Tersedia: ${stokMax} ${$('#satuan_tampil').val()}`);
        } else {
            stokMax = 0;
            $('#stok-info').text('Stok Tersedia: —');
        }
    }

    function swAlert(msg) {
        Swal.fire({
            icon: 'warning', title: msg, toast: false, position: 'center',
            showConfirmButton: true, confirmButtonText: 'OK', confirmButtonColor: '#2563eb'
        });
    }

    function tambahKeDaftar() {
        const jenis     = $('#jenis_penyesuaian').val();
        const barangOpt = $('#id_barang option:selected');
        const idBarang  = barangOpt.val();
        const namaBarang = barangOpt.data('nama');
        const isRepack  = barangOpt.data('repack') == 1;
        const satuan    = $('#satuan_tampil').val();
        let jumlah      = parseFloat($('#jumlah').val());
        const keterangan = $('#keterangan_item').val();

        let idBatch = '', namaBatch = '', expDate = '';

        if (!idBarang)            { swAlert('Pilih barang terlebih dahulu!'); return; }
        if (!jumlah || jumlah <= 0) { swAlert('Jumlah harus lebih dari 0!'); return; }
        if (!isRepack && !Number.isInteger(jumlah)) {
            swAlert('Barang utuh tidak boleh menggunakan angka desimal!'); return;
        }

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

        items.push({
            id_barang: idBarang, nama_barang: namaBarang, id_batch: idBatch,
            nama_batch: namaBatch, tanggal_kedaluwarsa: expDate, jumlah, satuan, keterangan
        });
        renderDaftar();
        $('#jumlah').val('');
        $('#keterangan_item').val('');
    }

    function hapusItem(index) {
        items.splice(index, 1);
        renderDaftar();
    }

    function renderDaftar() {
        const container = $('#daftar-barang');
        const kosong    = $('#daftar-barang-kosong');
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
            const sign  = isPositif ? '+' : '-';
            const color = isPositif ? 'text-success' : 'text-danger';
            const infoBatch = item.id_batch
                ? item.nama_batch
                : `Exp: ${item.tanggal_kedaluwarsa}`;

            container.append(`
            <div class="cart-item">
                <button type="button" class="btn-remove-item" onclick="hapusItem(${index})" title="Hapus">
                    <i class="fa-solid fa-xmark"></i>
                </button>

                <input type="hidden" name="items[${index}][id_barang]"           value="${escHtml(item.id_barang)}">
                <input type="hidden" name="items[${index}][id_batch]"            value="${escHtml(item.id_batch)}">
                <input type="hidden" name="items[${index}][tanggal_kedaluwarsa]" value="${escHtml(item.tanggal_kedaluwarsa)}">
                <input type="hidden" name="items[${index}][jumlah]"              value="${escHtml(String(item.jumlah))}">
                <input type="hidden" name="items[${index}][satuan]"              value="${escHtml(item.satuan)}">
                <input type="hidden" name="items[${index}][keterangan]"          value="${escHtml(item.keterangan)}">

                <div class="d-flex justify-content-between align-items-center pe-2" style="min-width:0">
                    <span class="item-name">${escHtml(item.nama_barang)}</span>
                    <span class="item-qty ${color} ms-2">${sign}${escHtml(String(item.jumlah))}
                        <small class="text-muted fw-normal" style="font-size:11px">${escHtml(item.satuan)}</small>
                    </span>
                </div>
                <div class="item-meta">
                    <span><i class="fa-solid fa-box me-1"></i>${escHtml(infoBatch)}</span>
                    ${item.keterangan
                        ? `<span><i class="fa-solid fa-comment-dots me-1"></i>${escHtml(item.keterangan)}</span>`
                        : ''}
                </div>
            </div>`);
        });
    }

    document.getElementById('form-transaksi').addEventListener('submit', function (e) {
        const btn = document.getElementById('btn-submit');
        if (btn.dataset.submitted === 'true') { e.preventDefault(); return false; }
        btn.dataset.submitted = 'true';
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';
        btn.style.pointerEvents = 'none';
        btn.style.opacity = '0.7';
    });

    window.addEventListener('pageshow', function (event) {
        if (event.persisted) {
            const btn = document.getElementById('btn-submit');
            btn.dataset.submitted = 'false';
            btn.innerHTML = '<i class="fa-solid fa-save"></i> Simpan Transaksi';
            btn.style.pointerEvents = 'auto';
            btn.style.opacity = '1';
        }
    });
</script>
<?= $this->endSection() ?>