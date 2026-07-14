<?php

namespace App\Modules\Dashboard\Config;

$routes->group('', ['namespace' => 'App\Modules\Dashboard\Controllers'], function ($routes) {
    // Arahkan root URL (/) ke Dashboard::index
    $routes->get('/', 'Dashboard::index');
    
    // Arahkan juga /dashboard ke Dashboard::index
    $routes->get('dashboard', 'Dashboard::index');
});

