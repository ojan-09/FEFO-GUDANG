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

    // --- DataTables Variables ---
    protected $column_order  = [null, 'penyesuaian_stok.nomor_penyesuaian', 'penyesuaian_stok.tanggal', 'penyesuaian_stok.jenis_penyesuaian', 'total_item', 'users.username', 'penyesuaian_stok.keterangan', null];
    protected $column_search = ['penyesuaian_stok.nomor_penyesuaian', 'penyesuaian_stok.jenis_penyesuaian', 'users.username', 'penyesuaian_stok.keterangan'];
    protected $order         = ['penyesuaian_stok.created_at' => 'DESC'];

    private function _getDatatablesQuery($postData)
    {
        $builder = $this->db->table($this->table)
            ->select('penyesuaian_stok.id, penyesuaian_stok.nomor_penyesuaian, penyesuaian_stok.tanggal, penyesuaian_stok.jenis_penyesuaian, penyesuaian_stok.keterangan, users.username')
            ->select('(SELECT COUNT(id) FROM detail_penyesuaian_stok WHERE id_penyesuaian = penyesuaian_stok.id) as total_item')
            ->join('users', 'users.id = penyesuaian_stok.id_user', 'left');

        $i = 0;
        if (isset($postData['search']['value']) && $postData['search']['value']) {
            foreach ($this->column_search as $item) {
                if ($i === 0) {
                    $builder->groupStart();
                    $builder->like($item, $postData['search']['value']);
                } else {
                    $builder->orLike($item, $postData['search']['value']);
                }
                if (count($this->column_search) - 1 == $i) {
                    $builder->groupEnd();
                }
                $i++;
            }
        }

        if (isset($postData['order'])) {
            $orderCol = $this->column_order[$postData['order']['0']['column']];
            if ($orderCol) {
                $builder->orderBy($orderCol, $postData['order']['0']['dir']);
            }
        } elseif (isset($this->order)) {
            $order = $this->order;
            $builder->orderBy(key($order), $order[key($order)]);
        }

        return $builder;
    }

    public function getDatatables($postData)
    {
        $builder = $this->_getDatatablesQuery($postData);
        if (isset($postData['length']) && $postData['length'] != -1) {
            $builder->limit($postData['length'], $postData['start']);
        }
        return $builder->get()->getResultArray();
    }

    public function countFiltered($postData)
    {
        $builder = $this->_getDatatablesQuery($postData);
        return $builder->countAllResults();
    }

    public function countAllData()
    {
        $builder = $this->db->table($this->table);
        return $builder->countAllResults();
    }
}
