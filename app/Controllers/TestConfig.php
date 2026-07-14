<?php

namespace App\Controllers;

class TestConfig extends BaseController
{
    public function index()
    {
        $config = config('Auth');
        echo "Class: " . get_class($config) . "<br>";
        echo "View login: " . $config->views['login'] . "<br>";
        echo "allowRegistration: " . ($config->allowRegistration ? 'true' : 'false') . "<br>";
    }
}

