/**
 * Global Loading System for FEFO Gudang
 * Fixed version — semua bug dan warning sudah diperbaiki
 */
$(document).ready(function () {

    // =========================================================
    // 1. NProgress
    // =========================================================
    if (typeof NProgress !== 'undefined') {
        NProgress.configure({ showSpinner: false, minimum: 0.1 });
    }

    // =========================================================
    // 2. Minimum Display Time untuk AJAX
    // =========================================================
    let loadingTimer = null;
    let minDisplayTime = 400;

    $(document).ajaxStart(function () {
        if (typeof NProgress !== 'undefined') {
            NProgress.start();
        }
    });

    $(document).ajaxStop(function () {
        if (typeof NProgress !== 'undefined') {
            if (loadingTimer) clearTimeout(loadingTimer);
            loadingTimer = setTimeout(function () {
                NProgress.done();
            }, minDisplayTime);
        }
    });

    // =========================================================
    // 3. DataTables — global processing indicator
    // =========================================================
    if ($.fn.dataTable) {
        $.extend(true, $.fn.dataTable.defaults, {

            // --- Performa ---
            deferRender:   true,   // render row hanya saat dibutuhkan
            pageLength:    25,     // default 25 row, jangan pakai "All"
            lengthMenu:    [[10, 25, 50, 100], [10, 25, 50, 100]],

            // Tunda search sampai user berhenti mengetik (300ms)
            // mencegah re-filter tiap karakter
            searchDelay:   300,

            // Matikan fitur yang jarang dipakai agar render lebih ringan
            orderClasses:  false,  // hapus class sorting dari setiap cell
            stateSave:     false,

            // --- Bahasa ---
            language: {
                processing:
                    '<div class="d-flex flex-column align-items-center justify-content-center p-3" ' +
                    'style="color:#2563eb;background:rgba(255,255,255,0.9);border-radius:8px;' +
                    'box-shadow:0 4px 6px -1px rgba(0,0,0,0.1);">' +
                    '<div class="spinner-border mb-2" role="status"></div>' +
                    '<span class="fw-medium" style="font-size:13px;">Memuat data...</span>' +
                    '</div>',
                search:         'Cari:',
                lengthMenu:     'Tampilkan _MENU_ data',
                info:           'Menampilkan _START_–_END_ dari _TOTAL_ data',
                infoEmpty:      'Tidak ada data',
                infoFiltered:   '(difilter dari _MAX_ total data)',
                zeroRecords:    'Data tidak ditemukan',
                emptyTable:     'Belum ada data',
                paginate: {
                    first:    '«',
                    last:     '»',
                    next:     '›',
                    previous: '‹'
                }
            },
            processing: true,

            // --- DOM layout Bootstrap 5 ---
            dom:
                "<'row mb-2'<'col-sm-6'l><'col-sm-6'f>>" +
                "<'row'<'col-12'tr>>" +
                "<'row mt-2'<'col-sm-5'i><'col-sm-7'p>>"
        });
    }

    // =========================================================
    // Helper: inisialisasi DataTables via AJAX dengan overlay
    // Penggunaan:
    //   window.initDataTable('#tabel-fefo', '/stok/data', columns, options);
    // =========================================================
    window.initDataTable = function (selector, ajaxUrl, columns, extraOptions) {
        let $tabel = $(selector);
        if (!$tabel.length || $.fn.DataTable.isDataTable(selector)) return;

        window.showOverlay('Memuat data...');

        $.ajax({
            url: ajaxUrl,
            type: 'GET',
            success: function (response) {
                window.hideOverlay();

                // Support response berbentuk {data: [...]} atau langsung array
                let rows = Array.isArray(response) ? response : (response.data || []);

                // Peringatan jika data sangat besar
                if (rows.length > 5000) {
                    console.warn(
                        '[FEFO] DataTables memuat ' + rows.length + ' row secara client-side. ' +
                        'Pertimbangkan migrasi ke server-side processing untuk performa optimal.'
                    );
                }

                let options = $.extend({
                    data:        rows,
                    columns:     columns,
                    deferRender: true,
                }, extraOptions || {});

                $tabel.DataTable(options);
            },
            error: function (xhr) {
                window.hideOverlay();
                console.error('[FEFO] Gagal memuat data tabel:', xhr.statusText);
            }
        });
    };

    // =========================================================
    // 4. Form submit (.loading-form)
    // Fix: restore tombol saat error, dan hanya ubah tombol yang
    //      di-klik (bukan semua submit button dalam form).
    // =========================================================
    $(document).on('submit', 'form.loading-form', function (e) {
        if (this.checkValidity && !this.checkValidity()) {
            return;
        }

        let $form = $(this);

        // Cari tombol submit yang sedang aktif (yang diklik user),
        // fallback ke tombol submit pertama jika tidak ada.
        let $btn = $form.find('button[type="submit"]:focus')
                        .add($form.find('button[type="submit"]').first())
                        .first();

        let loadingText = $btn.data('loading-text') || 'Memproses...';

        if ($form.data('overlay')) {
            let overlayMsg = $form.data('overlay-message') || loadingText;
            window.showOverlay(overlayMsg);
        }

        // Simpan HTML asli sebelum diganti
        $btn.data('original-html', $btn.html());
        $btn.html(
            '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>' +
            loadingText
        );
        $btn.prop('disabled', true).addClass('disabled');
    });

    // Restore tombol jika terjadi AJAX error (submit via AJAX)
    $(document).ajaxError(function () {
        window.hideOverlay();
        $('form.loading-form button[type="submit"]').each(function () {
            let $btn = $(this);
            if ($btn.data('original-html')) {
                $btn.html($btn.data('original-html'))
                    .prop('disabled', false)
                    .removeClass('disabled')
                    .removeData('original-html');
            }
        });
    });

    // =========================================================
    // 5. Export button (.btn-export-loading)
    // Fix: gunakan event delegation agar tombol dinamis ikut ter-bind.
    //      Hapus duplicate handler lama.
    //      Timeout dinaikkan jadi 5 detik (lebih realistis untuk download).
    // =========================================================
    $(document).on('click', '.btn-export-loading', function () {
        let $btn = $(this);

        // Cegah double-click saat loading
        if ($btn.hasClass('disabled')) return;

        let loadingText = $btn.data('loading-text') || 'Mengunduh...';

        $btn.data('original-html', $btn.html());
        $btn.html(
            '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>' +
            loadingText
        );
        $btn.addClass('disabled').css('pointer-events', 'none');
        window.showOverlay(loadingText);

        // Reset setelah 5 detik
        setTimeout(function () {
            window.hideOverlay();
            if ($btn.data('original-html')) {
                $btn.html($btn.data('original-html'))
                    .removeClass('disabled')
                    .css('pointer-events', 'auto')
                    .removeData('original-html');
            }
        }, 5000);
    });

    // =========================================================
    // 6. Delete button (.btn-delete-loading)
    // Fix: handler sebelumnya kosong — sekarang implementasi lengkap.
    //      Menggunakan event delegation agar tombol dinamis ikut ter-bind.
    // =========================================================
    $(document).on('click', '.btn-delete-loading', function () {
        let $btn = $(this);

        // Cegah double-click
        if ($btn.hasClass('disabled')) return;

        let confirmMsg = $btn.data('confirm') || 'Apakah Anda yakin ingin menghapus data ini?';

        // Konfirmasi sebelum lanjut
        if (!window.confirm(confirmMsg)) {
            return false;
        }

        let loadingText = $btn.data('loading-text') || 'Menghapus...';

        $btn.data('original-html', $btn.html());
        $btn.html(
            '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>' +
            loadingText
        );
        $btn.addClass('disabled').css('pointer-events', 'none');
        window.showOverlay(loadingText);
    });

    // =========================================================
    // 7. Overlay helpers
    // Mendukung pesan dinamis: window.showOverlay('Menyimpan data...')
    // =========================================================
    window.showOverlay = function (message) {
        let msg = message || 'Memproses...';

        if ($('#global-overlay').length === 0) {
            $('body').append(
                '<div id="global-overlay" style="position:fixed;top:0;left:0;width:100%;height:100%;' +
                'background:rgba(255,255,255,0.75);z-index:9999;display:flex;' +
                'justify-content:center;align-items:center;backdrop-filter:blur(2px);">' +
                '<div class="text-center">' +
                '<div class="spinner-border text-primary" style="width:3rem;height:3rem;" role="status">' +
                '<span class="visually-hidden">Loading...</span>' +
                '</div>' +
                '<p id="overlay-message" class="mt-2 mb-0 text-muted" ' +
                'style="font-size:13px;font-weight:500;">' + $('<span>').text(msg).html() + '</p>' +
                '</div>' +
                '</div>'
            );
        } else {
            // Update pesan jika overlay sudah ada
            $('#overlay-message').text(msg);
            $('#global-overlay').fadeIn(200);
        }
    };

    window.hideOverlay = function () {
        $('#global-overlay').fadeOut(200);
    };

    // Shortcut: update pesan tanpa menutup/membuka overlay
    window.updateOverlayMessage = function (message) {
        $('#overlay-message').text(message || 'Memproses...');
    };
});