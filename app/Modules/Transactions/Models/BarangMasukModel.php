<?php

namespace App\Modules\Transactions\Models;

use CodeIgniter\Model;

class BarangMasukModel extends Model
{
    protected $table            = 'barang_masuk';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nomor_transaksi', 'id_donatur', 'id_wilayah', 'id_user', 'tanggal_masuk', 'eta', 'keterangan'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // --- DataTables Variables ---
    protected $column_order  = [null, 'barang_masuk.nomor_transaksi', 'donatur.nama_donatur', 'jumlah_item', 'barang_masuk.tanggal_masuk', 'users.username', null];
    protected $column_search = ['barang_masuk.nomor_transaksi', 'donatur.nama_donatur', 'users.username', 'barang_masuk.tanggal_masuk'];
    protected $order         = ['barang_masuk.created_at' => 'DESC'];

    /**
     * Membangun query utama dengan filter dan sorting
     */
    private function _getDatatablesQuery($postData)
    {
        $builder = $this->db->table($this->table)
            ->select('barang_masuk.id, barang_masuk.nomor_transaksi, barang_masuk.tanggal_masuk, donatur.nama_donatur, users.username as petugas')
            ->select('(SELECT COUNT(id) FROM batch WHERE id_barang_masuk = barang_masuk.id) as jumlah_item')
            ->select('EXISTS(SELECT 1 FROM detail_barang_keluar dbk JOIN batch b ON dbk.id_batch = b.id WHERE b.id_barang_masuk = barang_masuk.id) as is_used')
            ->join('donatur', 'donatur.id = barang_masuk.id_donatur', 'left')
            ->join('users', 'users.id = barang_masuk.id_user', 'left');

        // Pencarian (Search)
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

        // Pengurutan (Order)
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

    /**
     * Mengeksekusi limitasi (Pagination) dari query utama
     */
    public function getDatatables($postData)
    {
        $builder = $this->_getDatatablesQuery($postData);
        if (isset($postData['length']) && $postData['length'] != -1) {
            $builder->limit($postData['length'], $postData['start']);
        }
        return $builder->get()->getResultArray();
    }

    /**
     * Hitung total data berdasarkan filter
     */
    public function countFiltered($postData)
    {
        $builder = $this->_getDatatablesQuery($postData);
        return $builder->countAllResults();
    }

    /**
     * Hitung total data keseluruhan (tanpa filter)
     */
    public function countAllData()
    {
        $builder = $this->db->table($this->table);
        return $builder->countAllResults();
    }
}

