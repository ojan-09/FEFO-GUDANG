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

$routes->get('system-health', '\App\Modules\Settings\Controllers\SystemHealth::index', ['filter' => 'rbac:Administrator']);

// Route for Log Aktivitas
$routes->group('log-aktivitas', ['namespace' => 'App\Modules\Settings\Controllers', 'filter' => 'rbac:Administrator'], function($routes) {
    $routes->get('/', 'LogAktivitas::index');
    $routes->post('ajaxData', 'LogAktivitas::ajaxData');
});

// Route for Backup & Restore
$routes->group('pengaturan/backup', ['namespace' => 'App\Modules\Settings\Controllers', 'filter' => 'rbac:Administrator'], function($routes) {
    $routes->get('/', 'Backup::index');
    $routes->post('doBackup', 'Backup::doBackup');
    $routes->post('restore', 'Backup::restore');
    $routes->get('download/(:segment)', 'Backup::download/$1');
    $routes->post('delete/(:segment)', 'Backup::delete/$1');
    $routes->get('verify/(:segment)', 'Backup::verify/$1');
});

// Route for System Health
$routes->group('pengaturan/system/health', ['namespace' => 'App\Modules\Settings\Controllers', 'filter' => 'rbac:Administrator'], function($routes) {
    $routes->get('/', 'SystemHealth::index');
});

// Route for Workspace Dokumen & PDF (Accessible only by Administrator)
$routes->group('pengaturan/dokumen', ['namespace' => 'App\Modules\Settings\Controllers', 'filter' => 'rbac:Administrator'], function($routes) {
    $routes->get('/', 'DocumentWorkspace::index');
    $routes->get('riwayat', 'DocumentWorkspace::history');
    $routes->get('activity-log', 'DocumentWorkspace::activityLog');
    $routes->get('(:segment)', 'DocumentWorkspace::detail/$1');
    $routes->post('(:segment)/update-numbering', 'DocumentWorkspace::updateNumbering/$1');
    $routes->post('(:segment)/provision/add', 'DocumentWorkspace::addProvision/$1');
    $routes->post('(:segment)/provision/update/(:num)', 'DocumentWorkspace::updateProvision/$1/$2');
    $routes->get('(:segment)/provision/delete/(:num)', 'DocumentWorkspace::deleteProvision/$1/$2');
    $routes->post('(:segment)/new-version', 'DocumentWorkspace::newVersion/$1');
    $routes->get('(:segment)/preview', 'DocumentWorkspace::previewPdf/$1');
});

