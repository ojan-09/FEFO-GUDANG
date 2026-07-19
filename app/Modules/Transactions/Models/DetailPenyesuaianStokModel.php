<?php

namespace App\Modules\Transactions\Models;

use CodeIgniter\Model;

class DetailPenyesuaianStokModel extends Model
{
    protected $table            = 'detail_penyesuaian_stok';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'id_penyesuaian',
        'id_barang',
        'id_batch',
        'jumlah',
        'satuan',
        'stok_sebelum',
        'stok_sesudah',
        'keterangan'
    ];

    // Disable timestamps since not configured in migration for this table
    protected $useTimestamps = false;
}
