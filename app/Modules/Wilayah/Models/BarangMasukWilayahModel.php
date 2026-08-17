<?php

namespace App\Modules\Wilayah\Models;

use CodeIgniter\Model;

class BarangMasukWilayahModel extends Model
{
    protected $table            = 'barang_masuk_wilayah';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nomor_dokumen',
        'id_gudang',
        'id_donatur',
        'tanggal',
        'keterangan',
        'created_by',
        'created_ip',
        'updated_ip'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // --- DataTables Variables ---
    protected $column_order  = ['b.nomor_dokumen', 'b.tanggal', 'm.nama', 'dn.nama_donatur', 'total_item', 'total_qty', 'b.keterangan', null];
    protected $column_search = ['b.nomor_dokumen', 'b.tanggal', 'm.nama', 'dn.nama_donatur', 'b.keterangan'];
    protected $order         = ['b.created_at' => 'DESC'];

    private function _getDatatablesQuery($postData)
    {
        $builder = $this->db->table('barang_masuk_wilayah b')
            ->select('
                b.*, 
                m.nama as nama_gudang, 
                m.kota, 
                dn.nama_donatur, 
                COUNT(d.id) as total_item, 
                COALESCE(SUM(d.jumlah), 0) as total_qty,
                NOT EXISTS (
                    SELECT 1 
                    FROM detail_barang_masuk_wilayah d 
                    LEFT JOIN stok_gudang_wilayah s ON s.id_gudang = b.id_gudang AND s.id_barang = d.id_barang 
                    WHERE d.id_masuk = b.id AND COALESCE(s.jumlah, 0) < d.jumlah
                ) as can_delete
            ')
            ->join('master_gudang_wilayah m', 'm.id = b.id_gudang', 'left')
            ->join('donatur dn', 'dn.id = b.id_donatur', 'left')
            ->join('detail_barang_masuk_wilayah d', 'd.id_masuk = b.id', 'left')
            ->where('b.deleted_at', null)
            ->groupBy('b.id');

        // Filter Gudang Wilayah (Admin / User specific)
        if (!empty($postData['id_gudang'])) {
            $builder->where('b.id_gudang', $postData['id_gudang']);
        }

        // Filter Donatur
        if (!empty($postData['id_donatur'])) {
            $builder->where('b.id_donatur', $postData['id_donatur']);
        }

        // Filter Tanggal
        if (!empty($postData['start_date'])) {
            $builder->where('b.tanggal >=', $postData['start_date']);
        }
        if (!empty($postData['end_date'])) {
            $builder->where('b.tanggal <=', $postData['end_date']);
        }

        // Search Global
        if (isset($postData['search']['value']) && $postData['search']['value'] !== '') {
            $searchVal = $postData['search']['value'];
            $builder->groupStart();
            $i = 0;
            foreach ($this->column_search as $item) {
                if ($i === 0) {
                    $builder->like($item, $searchVal);
                } else {
                    $builder->orLike($item, $searchVal);
                }
                $i++;
            }
            $builder->groupEnd();
        }

        // Sorting
        if (isset($postData['order'])) {
            $colIndex = (int)$postData['order']['0']['column'];
            $colName = $this->column_order[$colIndex] ?? null;
            if ($colName) {
                $builder->orderBy($colName, $postData['order']['0']['dir']);
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
            $builder->limit((int)$postData['length'], (int)$postData['start']);
        }
        return $builder->get()->getResultArray();
    }

    public function countFiltered($postData)
    {
        $builder = $this->_getDatatablesQuery($postData);
        return $builder->countAllResults();
    }

    public function countAllData($idGudang = null)
    {
        $builder = $this->db->table($this->table)->where('deleted_at', null);
        if ($idGudang) {
            $builder->where('id_gudang', $idGudang);
        }
        return $builder->countAllResults();
    }
}
