<?php

namespace App\Modules\Reports\Controllers;

use App\Controllers\BaseController;

class Stok extends BaseController
{
    public function index(): string
    {
        return view('App/Modules/Reports/Views/stok/index');
    }
}

