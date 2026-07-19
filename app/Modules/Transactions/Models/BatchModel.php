<?php

namespace App\Modules\Transactions\Models;

use CodeIgniter\Model;

class BatchModel extends Model
{
    protected $table            = 'batch';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_barang_masuk', 'id_barang', 'nomor_batch', 'nama_barang', 'kategori',
        'tanggal_masuk', 'tanggal_kedaluwarsa', 'jumlah_awal', 'stok_saat_ini',
        'jumlah_ctn', 'satuan', 'berat_per_satuan', 'satuan_berat', 'status', 'bisa_dipecah'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}

