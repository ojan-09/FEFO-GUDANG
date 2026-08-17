<?php

namespace App\Modules\MasterData\Models;

use CodeIgniter\Model;

class BarangModel extends Model
{
    protected $table            = 'barang';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'kode_barang', 'id_kategori', 'nama_barang', 'satuan', 
        'berat_per_satuan', 'satuan_berat', 'minimum_stok', 'bisa_dipecah',
        'status', 'merged_to'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // --- DataTables Variables ---
    protected $column_order  = [null, 'barang.kode_barang', 'barang.nama_barang', 'kategori.nama_kategori', 'barang.satuan', 'barang.berat_per_satuan', 'barang.minimum_stok', 'barang.bisa_dipecah', null];
    protected $column_search = ['barang.kode_barang', 'barang.nama_barang', 'kategori.nama_kategori'];
    protected $order         = ['barang.id' => 'DESC'];

    private function _getDatatablesQuery($postData)
    {
        $builder = $this->db->table($this->table)
            ->select('barang.*, kategori.nama_kategori, target_barang.nama_barang as target_nama_barang')
            ->join('kategori', 'kategori.id = barang.id_kategori')
            ->join('barang as target_barang', 'target_barang.id = barang.merged_to', 'left');

        if (isset($postData['status_filter']) && in_array($postData['status_filter'], ['active', 'merged'])) {
            $builder->where('barang.status', $postData['status_filter']);
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
        $builder = $this->db->table($this->table);
        return $builder->countAllResults();
    }
}

