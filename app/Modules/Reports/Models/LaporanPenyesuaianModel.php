<?php

namespace App\Modules\Reports\Models;

use CodeIgniter\Model;

class LaporanPenyesuaianModel extends Model
{
    protected $table = 'detail_penyesuaian_stok';
    protected $primaryKey = 'id';
    
    // Setup for Server-Side DataTables
    protected $column_order = [
        null, // No
        'ps.tanggal',
        null, // No. Transaksi / Jenis (combined, not sortable)
        'b.nama_barang',
        null, // Batch (not sortable)
        null, // Tgl Expired (not sortable)
        'detail_penyesuaian_stok.jumlah',
        null, // Satuan (not sortable)
        null  // Keterangan (not sortable)
    ];
    
    protected $column_search = [
        'ps.nomor_penyesuaian',
        'b.nama_barang',
        'batch.nomor_batch',
        'ps.keterangan',
        'detail_penyesuaian_stok.keterangan'
    ];
    
    protected $order = ['ps.tanggal' => 'DESC', 'detail_penyesuaian_stok.id' => 'DESC'];

    private function _getDatatablesQuery($filters = [])
    {
        $builder = $this->db->table('detail_penyesuaian_stok dps');
        $builder->select('
            dps.*, 
            ps.nomor_penyesuaian, 
            ps.tanggal, 
            ps.jenis_penyesuaian, 
            ps.keterangan as ket_umum, 
            b.nama_barang, 
            b.satuan, 
            COALESCE(batch.bisa_dipecah, b.bisa_dipecah) as bisa_dipecah, 
            batch.nomor_batch, 
            batch.tanggal_kedaluwarsa
        ');
        $builder->join('penyesuaian_stok ps', 'ps.id = dps.id_penyesuaian');
        $builder->join('barang b', 'b.id = dps.id_barang');
        $builder->join('batch', 'batch.id = dps.id_batch', 'left');

        // Apply filters
        if (!empty($filters['start_date'])) {
            $builder->where('ps.tanggal >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $builder->where('ps.tanggal <=', $filters['end_date']);
        }
        if (!empty($filters['jenis'])) {
            $builder->where('ps.jenis_penyesuaian', $filters['jenis']);
        }

        // Apply Search
        $searchValue = $filters['search']['value'] ?? null;
        if (!empty($searchValue)) {
            $builder->groupStart();
            foreach ($this->column_search as $i => $item) {
                if ($i === 0) {
                    $builder->like($item, $searchValue);
                } else {
                    $builder->orLike($item, $searchValue);
                }
            }
            $builder->groupEnd();
        }

        // Apply Order
        $order = $filters['order'] ?? null;
        if (isset($order[0]['column']) && isset($this->column_order[$order[0]['column']])) {
            $colIndex = $order[0]['column'];
            $orderCol = $this->column_order[$colIndex];
            if ($orderCol !== null) {
                $dir = (isset($order[0]['dir']) && strtolower($order[0]['dir']) === 'desc') ? 'DESC' : 'ASC';
                $builder->orderBy($orderCol, $dir);
            }
        } elseif (isset($this->order)) {
            $orderDef = $this->order;
            $builder->orderBy(key($orderDef), $orderDef[key($orderDef)]);
        }

        return $builder;
    }

    public function getDatatables($filters = [], $length = -1, $start = 0)
    {
        $builder = $this->_getDatatablesQuery($filters);
        if ($length != -1) {
            $builder->limit($length, $start);
        }
        return $builder->get()->getResultArray();
    }

    public function countFiltered($filters = [])
    {
        $builder = $this->_getDatatablesQuery($filters);
        return $builder->countAllResults();
    }

    public function countAllData()
    {
        return $this->db->table('detail_penyesuaian_stok')->countAllResults();
    }
}
