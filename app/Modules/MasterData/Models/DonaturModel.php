<?php

namespace App\Modules\MasterData\Models;

use CodeIgniter\Model;

class DonaturModel extends Model
{
    protected $table            = 'donatur';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_donatur', 'jenis_donatur', 'kontak', 'email', 'alamat'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}

