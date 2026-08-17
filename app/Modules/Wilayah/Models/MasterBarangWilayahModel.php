<?php

namespace App\Modules\Wilayah\Models;

use CodeIgniter\Model;

class MasterBarangWilayahModel extends Model
{
    protected $table            = 'master_barang_wilayah';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'kode_barang', 'nama_barang', 'id_kategori', 'satuan', 'berat_per_satuan', 'satuan_berat', 'status', 'keterangan'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // --- DataTables Variables ---
    protected $column_order  = [null, 'master_barang_wilayah.kode_barang', 'master_barang_wilayah.nama_barang', 'kategori.nama_kategori', 'master_barang_wilayah.satuan', 'master_barang_wilayah.berat_per_satuan', 'master_barang_wilayah.status', null];
    protected $column_search = ['master_barang_wilayah.kode_barang', 'master_barang_wilayah.nama_barang', 'kategori.nama_kategori'];
    protected $order         = ['id' => 'DESC'];

    private function _getDatatablesQuery($postData)
    {
        $this->select('master_barang_wilayah.*, kategori.nama_kategori as kategori_nama');
        $this->join('kategori', 'kategori.id = master_barang_wilayah.id_kategori', 'left');
        $this->where('master_barang_wilayah.deleted_at', null);

        $i = 0;
        foreach ($this->column_search as $item) {
            if (isset($postData['search']['value']) && $postData['search']['value'] != '') {
                if ($i === 0) {
                    $this->groupStart();
                    $this->like($item, $postData['search']['value']);
                } else {
                    $this->orLike($item, $postData['search']['value']);
                }
                if (count($this->column_search) - 1 == $i) {
                    $this->groupEnd();
                }
            }
            $i++;
        }

        if (isset($postData['order'])) {
            $this->orderBy($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->orderBy(key($order), $order[key($order)]);
        }
    }

    public function getDatatables($postData)
    {
        $this->_getDatatablesQuery($postData);
        if ($postData['length'] != -1) {
            $this->limit($postData['length'], $postData['start']);
        }
        return $this->get()->getResultArray();
    }

    public function countFiltered($postData)
    {
        $this->_getDatatablesQuery($postData);
        return $this->countAllResults();
    }

    public function countAllData()
    {
        return $this->where('deleted_at', null)->countAllResults();
    }
}
