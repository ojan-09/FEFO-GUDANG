<?php

namespace App\Modules\Wilayah\Models;

use CodeIgniter\Model;

class StokGudangWilayahModel extends Model
{
    protected $table            = 'stok_gudang_wilayah';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_gudang',
        'id_barang',
        'jumlah'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = '';
    protected $updatedField  = 'updated_at';
}
