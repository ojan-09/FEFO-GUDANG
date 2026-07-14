<?php

namespace App\Modules\MasterData\Config;

$routes->group('masterdata', ['namespace' => 'App\Modules\MasterData\Controllers', 'filter' => 'rbac:Administrator'], function ($routes) {

    // === Kategori ===
    $routes->get('kategori', 'Kategori::index');
    $routes->get('kategori/create', 'Kategori::create');
    $routes->post('kategori/store', 'Kategori::store');
    $routes->get('kategori/edit/(:num)', 'Kategori::edit/$1');
    $routes->post('kategori/update/(:num)', 'Kategori::update/$1');
    $routes->get('kategori/delete/(:num)', 'Kategori::delete/$1');

    // === Maintenance Master Barang (Internal) ===
    $routes->get('maintenance-barang', 'MaintenanceBarang::index');
    $routes->post('maintenance-barang/rename/(:num)', 'MaintenanceBarang::rename/$1');
    $routes->post('maintenance-barang/merge', 'MaintenanceBarang::merge');

    // === Donatur ===
    $routes->get('donatur', 'Donatur::index');
    $routes->get('donatur/create', 'Donatur::create');
    $routes->post('donatur/store', 'Donatur::store');
    $routes->get('donatur/edit/(:num)', 'Donatur::edit/$1');
    $routes->post('donatur/update/(:num)', 'Donatur::update/$1');
    $routes->get('donatur/delete/(:num)', 'Donatur::delete/$1');

    // === Wilayah ===
    $routes->get('wilayah', 'Wilayah::index');
    $routes->get('wilayah/create', 'Wilayah::create');
    $routes->post('wilayah/store', 'Wilayah::store');
    $routes->get('wilayah/edit/(:num)', 'Wilayah::edit/$1');
    $routes->post('wilayah/update/(:num)', 'Wilayah::update/$1');
    $routes->get('wilayah/delete/(:num)', 'Wilayah::delete/$1');
});

