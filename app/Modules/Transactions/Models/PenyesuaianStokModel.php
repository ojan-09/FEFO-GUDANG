<?php

namespace App\Modules\Transactions\Models;

use CodeIgniter\Model;

class PenyesuaianStokModel extends Model
{
    protected $table            = 'penyesuaian_stok';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'nomor_penyesuaian',
        'tanggal',
        'jenis_penyesuaian',
        'keterangan',
        'id_user'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function generateNomor()
    {
        $prefix = 'ADJ-' . date('Ymd') . '-';
        $builder = $this->db->table($this->table);
        $builder->select('nomor_penyesuaian');
        $builder->like('nomor_penyesuaian', $prefix, 'after');
        $builder->orderBy('nomor_penyesuaian', 'DESC');
        $builder->limit(1);
        $result = $builder->get()->getRowArray();

        if ($result) {
            $lastNumber = intval(substr($result['nomor_penyesuaian'], -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
