<?php

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

// Routes for Profil (Accessible by all logged in users)
$routes->group('', ['namespace' => 'App\Modules\Settings\Controllers'], function($routes) {
    $routes->get('profil', 'Profil::index');
    $routes->post('profil/update', 'Profil::update');
});

// Routes for Manajemen User (Accessible only by Administrator)
$routes->group('manajemen-user', ['namespace' => 'App\Modules\Settings\Controllers', 'filter' => 'rbac:Administrator'], function($routes) {
    $routes->get('/', 'ManajemenUser::index');
    $routes->post('store', 'ManajemenUser::store');
    $routes->post('update/(:num)', 'ManajemenUser::update/$1');
    $routes->post('reset/(:num)', 'ManajemenUser::resetPassword/$1');
    $routes->post('toggle-status/(:num)', 'ManajemenUser::toggleStatus/$1');
});

// Route for Log Aktivitas
$routes->group('log-aktivitas', ['namespace' => 'App\Modules\Settings\Controllers', 'filter' => 'rbac:Administrator'], function($routes) {
    $routes->get('/', 'LogAktivitas::index');
});

