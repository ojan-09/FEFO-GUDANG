<?php

namespace App\Modules\Reports\Controllers;

use App\Controllers\BaseController;

class BarangKeluar extends BaseController
{
    public function index(): string
    {
        return view('App/Modules/Reports/Views/barang_keluar/index');
    }
}

