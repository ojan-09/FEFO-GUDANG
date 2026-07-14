<?php

namespace App\Modules\Settings\Controllers;

use App\Controllers\BaseController;

class PengaturanSistem extends BaseController
{
    public function index(): string
    {
        return view('App/Modules/Settings/Views/pengaturan_sistem_index');
    }
}

