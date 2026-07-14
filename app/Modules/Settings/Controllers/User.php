<?php

namespace App\Modules\Settings\Controllers;

use App\Controllers\BaseController;

class User extends BaseController
{
    public function index()
    {
        return view('App/Modules/Settings/Views/user/index');
    }
}

