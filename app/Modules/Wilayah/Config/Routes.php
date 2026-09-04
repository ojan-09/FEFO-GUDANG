<?php

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

// ── [FIX #6] Pecah jadi 3 group berdasarkan hak akses ──────────────────────
//
// Sebelumnya seluruh route wilayah hanya pakai 'filter' => 'login',
// artinya siapapun yang sudah login bisa akses & modifikasi data wilayah.
//
// Sekarang:
//  - Admin Only      → Administrator
//  - Write Access    → Administrator, Petugas Gudang
//  - Read-Only       → Administrator, Petugas Gudang, Pimpinan


// ── 1. Master Gudang — Administrator only ───────────────────────────────────
$routes->group('wilayah', [
    'namespace' => 'App\Modules\Wilayah\Controllers',
    'filter'    => 'rbac:Administrator',
], function ($routes) {
    $routes->get('master',                  'MasterGudang::index');
    $routes->post('master/store',           'MasterGudang::store');
    $routes->post('master/update/(:num)',   'MasterGudang::update/$1');
    $routes->get('master/delete/(:num)',    'MasterGudang::delete/$1');
    $routes->get('master/detail/(:num)',    'MasterGudang::detail/$1');
});


// ── 2. Write operations — Administrator & Petugas Gudang ────────────────────
$routes->group('wilayah', [
    'namespace' => 'App\Modules\Wilayah\Controllers',
    'filter'    => 'rbac:Administrator,Petugas Gudang',
], function ($routes) {
    // Master Barang Wilayah
    $routes->get('master-barang',               'MasterBarangWilayah::index');
    $routes->post('master-barang/ajax-list',    'MasterBarangWilayah::ajaxList');
    $routes->get('master-barang/create',        'MasterBarangWilayah::create');
    $routes->post('master-barang/store',        'MasterBarangWilayah::store');
    $routes->get('master-barang/edit/(:num)',   'MasterBarangWilayah::edit/$1');
    $routes->post('master-barang/update/(:num)','MasterBarangWilayah::update/$1');
    $routes->post('master-barang/delete/(:num)','MasterBarangWilayah::delete/$1');

    // Barang Masuk Wilayah
    $routes->get('masuk',                   'BarangMasukWilayah::index');
    $routes->post('masuk/ajaxData',         'BarangMasukWilayah::ajaxData');
    $routes->get('masuk/create',            'BarangMasukWilayah::create');
    $routes->post('masuk/store',            'BarangMasukWilayah::store');
    $routes->get('masuk/detail/(:num)',     'BarangMasukWilayah::detail/$1');
    $routes->post('masuk/delete/(:num)',    'BarangMasukWilayah::delete/$1');

    // Barang Keluar Wilayah
    $routes->get('keluar',                          'BarangKeluarWilayah::index');
    $routes->post('keluar/ajaxData',                'BarangKeluarWilayah::ajaxData');
    $routes->get('keluar/create',                   'BarangKeluarWilayah::create');
    $routes->post('keluar/store',                   'BarangKeluarWilayah::store');
    $routes->get('keluar/detail/(:num)',            'BarangKeluarWilayah::detail/$1');
    $routes->post('keluar/delete/(:num)',           'BarangKeluarWilayah::delete/$1');
    $routes->post('keluar/ajax-barang',             'BarangKeluarWilayah::ajaxGetBarangByGudang');
});


// ── 3. Read-only — Administrator, Petugas Gudang & Pimpinan ─────────────────
$routes->group('wilayah', [
    'namespace' => 'App\Modules\Wilayah\Controllers',
    'filter'    => 'rbac:Administrator,Petugas Gudang,Pimpinan',
], function ($routes) {
    // Dashboard
    $routes->get('dashboard',                           'DashboardWilayah::index');
    $routes->get('dashboard/ajax-summary/(:num)',       'DashboardWilayah::ajaxSummary/$1');
    $routes->post('dashboard/ajax-summary',             'DashboardWilayah::ajaxSummary');
    $routes->get('dashboard/detail/(:num)',             'DashboardWilayah::detail/$1');

    // Monitoring Stok
    $routes->get('stok',            'StokWilayah::index');
    $routes->post('stok/ajaxData',  'StokWilayah::ajaxData');

    // Laporan
    $routes->get('laporan',             'LaporanWilayah::index');
    $routes->post('laporan/ajaxData',   'LaporanWilayah::ajaxData');
    $routes->get('laporan/pdf',         'LaporanWilayah::pdf');
    $routes->get('laporan/excel',       'LaporanWilayah::excel');
});