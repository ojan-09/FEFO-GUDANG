<?php

namespace App\Modules\MasterData\Models;

use CodeIgniter\Model;

class WilayahModel extends Model
{
    protected $table            = 'wilayah';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama_wilayah', 'status'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // --- DataTables Variables ---
    protected $column_order  = [null, 'nama_wilayah', 'status', null];
    protected $column_search = ['nama_wilayah', 'status'];
    protected $order         = ['id' => 'DESC'];

    private function _getDatatablesQuery($postData)
    {
        $builder = $this->db->table($this->table)->where('deleted_at', null);

        if (isset($postData['status']) && $postData['status'] !== '') {
            $builder->where('status', $postData['status']);
        }

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
        $builder = $this->db->table($this->table)->where('deleted_at', null);
        return $builder->countAllResults();
    }
}

