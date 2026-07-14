<?php

namespace App\Modules\Settings\Controllers;

use App\Controllers\BaseController;

class Role extends BaseController
{
    public function index(): string
    {
        return view('App/Modules/Settings/Views/role/index');
    }

    public function create(): string
    {
        return view('App/Modules/Settings/Views/role/form');
    }
}

