<?php

namespace App\Modules\Wilayah\Models;

use CodeIgniter\Model;

class DetailBarangMasukWilayahModel extends Model
{
    protected $table            = 'detail_barang_masuk_wilayah';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_masuk',
        'id_barang',
        'jumlah',
        'satuan',
        'berat_per_satuan',
        'satuan_berat',
        'harga_satuan',
        'subtotal_nilai'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
