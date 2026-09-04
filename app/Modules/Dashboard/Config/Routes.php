<?php

namespace App\Modules\Dashboard\Config;

// ── [FIX #1] Semua route dashboard dilindungi filter 'login' ────────────────
$routes->group('', [
    'namespace' => 'App\Modules\Dashboard\Controllers',
    'filter'    => 'login',
], function ($routes) {

    // Halaman utama
    $routes->get('/',          'Dashboard::index');
    $routes->get('dashboard',  'Dashboard::index');

    // API Notifikasi Expired
    $routes->get('api/notifications/bell',  'NotificationAPI::getBellData');
    $routes->post('api/notifications/read', 'NotificationAPI::markAsRead');

    // API Live Data Auto Refresh Dashboard
    $routes->get('api/dashboard/live-data', 'Dashboard::liveData');
});