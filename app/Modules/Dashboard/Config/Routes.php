<?php

namespace App\Modules\Dashboard\Config;

$routes->group('', ['namespace' => 'App\Modules\Dashboard\Controllers'], function ($routes) {
    // Arahkan root URL (/) ke Dashboard::index
    $routes->get('/', 'Dashboard::index');
    
    // Arahkan juga /dashboard ke Dashboard::index
    $routes->get('dashboard', 'Dashboard::index');

    // API Notifikasi Expired
    $routes->get('api/notifications/bell', 'NotificationAPI::getBellData');
    $routes->post('api/notifications/read', 'NotificationAPI::markAsRead');

    // API Live Data Auto Refresh Dashboard
    $routes->get('api/dashboard/live-data', 'Dashboard::liveData');
});

