<?php

namespace App\Modules\Transactions\Models;

use CodeIgniter\Model;

class DetailBarangKeluarModel extends Model
{
    protected $table            = 'detail_barang_keluar';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_barang_keluar', 'id_batch', 'jumlah_keluar'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}

