<?php
// ============================================================
// PATCH: Ganti bagian route barang di Routes.php masterdata
// Hapus baris lama:
//   $routes->get('barang', 'Barang::index');
//   $routes->post('barang/ajaxData', 'Barang::ajaxData');
//   $routes->get('barang/create', 'Barang::create');
//   $routes->post('barang/store', 'Barang::store');
//   $routes->get('barang/edit/(:num)', 'Barang::edit/$1');
//   $routes->post('barang/update/(:num)', 'Barang::update/$1');
//   $routes->get('barang/delete/(:num)', 'Barang::delete/$1');
//
// Ganti SELURUH block $routes->group('masterdata', ...) dengan ini:
// ============================================================

// --- Route group untuk Administrator (akses penuh) ---
$routes->group('masterdata', [
    'namespace' => 'App\Modules\MasterData\Controllers',
    'filter'    => 'rbac:Administrator'
], function ($routes) {

    // === Kategori ===
    $routes->get('kategori', 'Kategori::index');
    $routes->post('kategori/ajaxData', 'Kategori::ajaxData');
    $routes->get('kategori/create', 'Kategori::create');
    $routes->post('kategori/store', 'Kategori::store');
    $routes->get('kategori/edit/(:num)', 'Kategori::edit/$1');
    $routes->post('kategori/update/(:num)', 'Kategori::update/$1');
    $routes->get('kategori/delete/(:num)', 'Kategori::delete/$1');

    // === Donatur ===
    $routes->get('donatur', 'Donatur::index');
    $routes->post('donatur/ajaxData', 'Donatur::ajaxData');
    $routes->get('donatur/create', 'Donatur::create');
    $routes->post('donatur/store', 'Donatur::store');
    $routes->get('donatur/edit/(:num)', 'Donatur::edit/$1');
    $routes->post('donatur/update/(:num)', 'Donatur::update/$1');
    $routes->get('donatur/delete/(:num)', 'Donatur::delete/$1');

    // === Wilayah ===
    $routes->get('wilayah', 'Wilayah::index');
    $routes->post('wilayah/ajaxData', 'Wilayah::ajaxData');
    $routes->get('wilayah/create', 'Wilayah::create');
    $routes->post('wilayah/store', 'Wilayah::store');
    $routes->get('wilayah/edit/(:num)', 'Wilayah::edit/$1');
    $routes->post('wilayah/update/(:num)', 'Wilayah::update/$1');
    $routes->get('wilayah/delete/(:num)', 'Wilayah::delete/$1');

    // === Maintenance Master Barang (Internal) ===
    $routes->get('maintenance-barang', 'MaintenanceBarang::index');
    $routes->post('maintenance-barang/rename/(:num)', 'MaintenanceBarang::rename/$1');
    $routes->post('maintenance-barang/merge', 'MaintenanceBarang::merge');

    // === Merge Barang — Administrator only ===
    $routes->post('barang/merge', 'Barang::merge');
    $routes->get('barang/merge-confirm', 'Barang::mergeConfirm');
    $routes->post('barang/merge-preview', 'Barang::mergePreview');
});

// --- Route group Barang — akses Administrator + Petugas Gudang ---
$routes->group('masterdata', [
    'namespace' => 'App\Modules\MasterData\Controllers',
    'filter'    => 'rbac:Administrator,Petugas Gudang'
], function ($routes) {
    $routes->get('barang', 'Barang::index');
    $routes->post('barang/ajaxData', 'Barang::ajaxData');
    $routes->get('barang/detail/(:num)', 'Barang::detail/$1');
    // create/store/edit/update/delete sengaja dihapus dari sini
    // karena sudah di-throw PageNotFoundException di controller
});