<?php

namespace App\Modules\Reports\Controllers;

use App\Controllers\BaseController;

class LaporanStok extends BaseController
{
    public function index(): string
    {
        return view('App/Modules/Reports/Views/stok/index');
    }
}

