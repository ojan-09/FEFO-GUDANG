<?php

namespace App\Modules\MasterData\Config;

$routes->group('masterdata', ['namespace' => 'App\Modules\MasterData\Controllers', 'filter' => 'rbac:Administrator'], function ($routes) {

    // === Kategori ===
    $routes->get('kategori', 'Kategori::index');
    $routes->post('kategori/ajaxData', 'Kategori::ajaxData');
    $routes->get('kategori/create', 'Kategori::create');
    $routes->post('kategori/store', 'Kategori::store');
    $routes->get('kategori/edit/(:num)', 'Kategori::edit/$1');
    $routes->post('kategori/update/(:num)', 'Kategori::update/$1');
    $routes->get('kategori/delete/(:num)', 'Kategori::delete/$1');

    // === Barang ===
    $routes->get('barang', 'Barang::index');
    $routes->post('barang/ajaxData', 'Barang::ajaxData');
    $routes->get('barang/create', 'Barang::create');
    $routes->post('barang/store', 'Barang::store');
    $routes->get('barang/edit/(:num)', 'Barang::edit/$1');
    $routes->post('barang/update/(:num)', 'Barang::update/$1');
    $routes->get('barang/delete/(:num)', 'Barang::delete/$1');

    // === Maintenance Master Barang (Internal) ===
    $routes->get('maintenance-barang', 'MaintenanceBarang::index');
    $routes->post('maintenance-barang/rename/(:num)', 'MaintenanceBarang::rename/$1');
    $routes->post('maintenance-barang/merge', 'MaintenanceBarang::merge');

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
});

