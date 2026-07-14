<?php

namespace App\Modules\Reports\Controllers;

use App\Controllers\BaseController;

class BarangMasuk extends BaseController
{
    public function index(): string
    {
        return view('App/Modules/Reports/Views/barang_masuk/index');
    }
}

