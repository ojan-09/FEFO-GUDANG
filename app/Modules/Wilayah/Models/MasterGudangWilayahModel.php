<?php

namespace App\Modules\Wilayah\Models;

use CodeIgniter\Model;

class MasterGudangWilayahModel extends Model
{
    protected $table            = 'master_gudang_wilayah';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama',
        'provinsi',
        'kota',
        'alamat',
        'pic',
        'telepon',
        'status',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
