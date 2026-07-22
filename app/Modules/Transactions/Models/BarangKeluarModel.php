<?php

namespace App\Modules\Transactions\Models;

use CodeIgniter\Model;

class BarangKeluarModel extends Model
{
    protected $table            = 'barang_keluar';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nomor_transaksi', 'id_user', 'id_wilayah', 'tanggal_keluar', 'tujuan_penyaluran', 'keterangan'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // --- DataTables Variables ---
    protected $column_order  = [null, 'barang_keluar.nomor_transaksi', 'barang_keluar.tujuan_penyaluran', 'wilayah.nama_wilayah', 'barang_keluar.tanggal_keluar', 'jumlah_item', 'users.username', null];
    protected $column_search = ['barang_keluar.nomor_transaksi', 'barang_keluar.tujuan_penyaluran', 'wilayah.nama_wilayah', 'users.username'];
    protected $order         = ['barang_keluar.created_at' => 'DESC'];

    private function _getDatatablesQuery($postData)
    {
        $builder = $this->db->table($this->table)
            ->select('barang_keluar.id, barang_keluar.nomor_transaksi, barang_keluar.tujuan_penyaluran, barang_keluar.tanggal_keluar, wilayah.nama_wilayah, users.username as petugas')
            ->select('(SELECT COUNT(id) FROM detail_barang_keluar WHERE id_barang_keluar = barang_keluar.id) as jumlah_item')
            ->join('wilayah', 'wilayah.id = barang_keluar.id_wilayah', 'left')
            ->join('users', 'users.id = barang_keluar.id_user', 'left');

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

