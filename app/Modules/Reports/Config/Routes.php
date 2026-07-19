<?php

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

$routes->group('laporan', ['namespace' => 'App\Modules\Reports\Controllers', 'filter' => 'rbac:Administrator,Petugas Gudang,Pimpinan'], function ($routes) {
    // === Laporan Penyesuaian ===
    $routes->get('penyesuaian', 'LaporanPenyesuaian::index');
    $routes->get('penyesuaian/export_pdf', 'LaporanPenyesuaian::export_pdf');
    $routes->get('penyesuaian/export_excel', 'LaporanPenyesuaian::export_excel');

    // === Laporan Stok ===
    $routes->get('stok', 'LaporanStokGudang::index');
    $routes->get('stok/pdf', 'LaporanStokGudang::pdf');
    $routes->get('stok/excel', 'LaporanStokGudang::excel');

    $routes->get('donasi', 'LaporanDonasi::index');
    $routes->get('donasi/pdf', 'LaporanDonasi::pdf');
    $routes->get('donasi/excel', 'LaporanDonasi::excel');

    $routes->get('penyaluran', 'LaporanPenyaluran::index');
    $routes->get('penyaluran/pdf', 'LaporanPenyaluran::pdf');
    $routes->get('penyaluran/excel', 'LaporanPenyaluran::excel');

    $routes->get('expired', 'LaporanExpired::index');
    $routes->get('expired/pdf', 'LaporanExpired::pdf');
    $routes->get('expired/excel', 'LaporanExpired::excel');
});

