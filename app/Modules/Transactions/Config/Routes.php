<?php

namespace App\Modules\Transactions\Config;

$routes->group('transaksi', ['namespace' => 'App\Modules\Transactions\Controllers', 'filter' => 'rbac:Administrator,Petugas Gudang'], function ($routes) {

    // === Barang Masuk ===
    $routes->get('barang-masuk', 'BarangMasuk::index');
    $routes->post('barang-masuk/ajaxData', 'BarangMasuk::ajaxData');
    $routes->get('barang-masuk/create', 'BarangMasuk::create');
    $routes->post('barang-masuk/store', 'BarangMasuk::store');
    $routes->get('barang-masuk/detail/(:num)', 'BarangMasuk::detail/$1');
    $routes->get('barang-masuk/edit/(:num)', 'BarangMasuk::edit/$1');
    $routes->post('barang-masuk/update/(:num)', 'BarangMasuk::update/$1');
    $routes->get('barang-masuk/delete/(:num)', 'BarangMasuk::delete/$1');

    // === Penyesuaian Stok ===
    $routes->get('penyesuaian', 'PenyesuaianStok::index');
    $routes->post('penyesuaian/ajaxData', 'PenyesuaianStok::ajaxData');
    $routes->get('penyesuaian/create', 'PenyesuaianStok::create');
    $routes->post('penyesuaian/store', 'PenyesuaianStok::store');
    $routes->get('penyesuaian/detail/(:num)', 'PenyesuaianStok::detail/$1');
    $routes->get('penyesuaian/delete/(:num)', 'PenyesuaianStok::delete/$1');
    $routes->get('penyesuaian/getBatches/(:num)', 'PenyesuaianStok::getBatches/$1');

    // === Barang Keluar ===
    $routes->get('barang-keluar', 'BarangKeluar::index');
    $routes->post('barang-keluar/ajaxData', 'BarangKeluar::ajaxData');
    $routes->get('barang-keluar/create', 'BarangKeluar::create');
    $routes->post('barang-keluar/store', 'BarangKeluar::store');
    $routes->post('barang-keluar/validateExpired', 'BarangKeluar::validateExpired');
    $routes->get('barang-keluar/detail/(:num)', 'BarangKeluar::detail/$1');
    $routes->get('barang-keluar/berita-acara/(:num)', 'BarangKeluar::downloadBeritaAcara/$1');
    $routes->get('barang-keluar/edit/(:num)', 'BarangKeluar::edit/$1');
    $routes->post('barang-keluar/update/(:num)', 'BarangKeluar::update/$1');
    $routes->get('barang-keluar/delete/(:num)', 'BarangKeluar::delete/$1');

    // === API untuk JavaScript (ambil stok barang) ===
    $routes->get('api/stok-barang/(:num)', 'BarangKeluar::getStok/$1');
});

$routes->group('transaksi', ['namespace' => 'App\Modules\Transactions\Controllers', 'filter' => 'rbac:Administrator,Petugas Gudang,Pimpinan'], function ($routes) {
    // === Stok Gudang (Read-Only Monitoring) ===
    $routes->get('stok-gudang', 'StokGudang::index');
    $routes->post('stok-gudang/ajaxData', 'StokGudang::ajaxData');
    $routes->get('stok-gudang/detail/(:num)', 'StokGudang::detail/$1');
});

