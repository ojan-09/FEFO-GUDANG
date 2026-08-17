<?php

namespace App\Modules\Reports\Models;

use CodeIgniter\Model;

class LaporanStokGudangModel extends Model
{
    protected $table      = 'batch';
    protected $primaryKey = 'id';

    protected $column_order = [
        null, // No
        null, // Status (computed)
        'donatur.nama_donatur',
        'batch.kategori',
        'batch.tanggal_kedaluwarsa',
        'nama_barang',
        'batch.stok_saat_ini',
        'batch.satuan',
        'berat_per_satuan',
        'total_berat',
        'batch.jumlah_ctn',
        'barang_masuk.keterangan'
    ];

    protected $column_search = [
        'batch.nama_barang',
        'barang.nama_barang',
        'donatur.nama_donatur',
        'batch.kategori',
        'barang_masuk.keterangan'
    ];

    protected $order = ['batch.tanggal_kedaluwarsa' => 'ASC'];

    private function _getDatatablesQuery($postData)
    {
        $todayStr = date('Y-m-d');

        $builder = $this->db->table('batch');
        $builder->select('
            batch.id,
            batch.id_barang,
            batch.kategori,
            batch.stok_saat_ini,
            batch.tanggal_kedaluwarsa,
            batch.jumlah_ctn,
            barang_masuk.keterangan as catatan,
            COALESCE(batch.nama_barang, barang.nama_barang) as nama_barang,
            COALESCE(batch.satuan, barang.satuan) as satuan,
            COALESCE(batch.berat_per_satuan, barang.berat_per_satuan) as berat_per_satuan,
            COALESCE(batch.satuan_berat, barang.satuan_berat) as satuan_berat,
            COALESCE(batch.bisa_dipecah, barang.bisa_dipecah) as bisa_dipecah,
            donatur.nama_donatur as donatur,
            DATEDIFF(batch.tanggal_kedaluwarsa, "' . $todayStr . '") as sisa_hari
        ');
        $builder->join('barang', 'barang.id = batch.id_barang');
        $builder->join('barang_masuk', 'barang_masuk.id = batch.id_barang_masuk', 'left');
        $builder->join('donatur', 'donatur.id = barang_masuk.id_donatur', 'left');
        $builder->where('batch.stok_saat_ini >', 0);

        // Custom Filters
        $kategoriFilter = $postData['kategori'] ?? null;
        $statusFilter   = $postData['status'] ?? null;
        $donaturFilter  = $postData['donatur'] ?? null;
        $searchFilter   = $postData['search_custom'] ?? null;
        $startDate      = $postData['start_date'] ?? null;
        $endDate        = $postData['end_date'] ?? null;

        if (!empty($kategoriFilter)) {
            $builder->where('batch.kategori', $kategoriFilter);
        }

        if (!empty($donaturFilter)) {
            $builder->like('donatur.nama_donatur', $donaturFilter);
        }

        if (!empty($searchFilter)) {
            $builder->groupStart()
                ->like('batch.nama_barang', $searchFilter)
                ->orLike('barang.nama_barang', $searchFilter)
                ->groupEnd();
        }

        if (!empty($startDate)) {
            $builder->where('batch.tanggal_kedaluwarsa >=', $startDate);
        }
        if (!empty($endDate)) {
            $builder->where('batch.tanggal_kedaluwarsa <=', $endDate);
        }

        if (!empty($statusFilter)) {
            $hampirExpiredStr = date('Y-m-d', strtotime('+30 days'));
            if ($statusFilter === 'Expired') {
                $builder->where('batch.tanggal_kedaluwarsa <', $todayStr);
            } elseif ($statusFilter === 'Hampir Expired') {
                $builder->where('batch.tanggal_kedaluwarsa >=', $todayStr);
                $builder->where('batch.tanggal_kedaluwarsa <=', $hampirExpiredStr);
            } elseif ($statusFilter === 'Aman') {
                $builder->where('batch.tanggal_kedaluwarsa >', $hampirExpiredStr);
            }
        }

        // Global Search (DataTables built-in search box)
        if (isset($postData['search']['value']) && $postData['search']['value'] != '') {
            $searchValue = $postData['search']['value'];
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

        // Ordering
        if (isset($postData['order'])) {
            $colIndex = $postData['order'][0]['column'];
            $colDir   = $postData['order'][0]['dir'];
            if (isset($this->column_order[$colIndex]) && $this->column_order[$colIndex] !== null) {
                $builder->orderBy($this->column_order[$colIndex], $colDir);
            }
        } else if (isset($this->order)) {
            foreach ($this->order as $key => $val) {
                $builder->orderBy($key, $val);
            }
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

    public function countAllData()
    {
        $builder = $this->db->table('batch');
        $builder->where('batch.stok_saat_ini >', 0);
        return $builder->countAllResults();
    }

    /**
     * Hitung summary total berat dalam query
     */
    public function getSummaryData($postData)
    {
        $builder = $this->_getDatatablesQuery($postData);
        $data = $builder->get()->getResultArray();

        $totalBerat = 0;
        foreach ($data as $row) {
            $bisaDipecah = (int)($row['bisa_dipecah'] ?? 0);
            $beratPerSatuan = (float)$row['berat_per_satuan'];
            if ($bisaDipecah === 1) {
                $weightInKg = (float)$row['stok_saat_ini'];
                if (strtolower($row['satuan_berat'] ?? '') === 'gram') {
                    $weightInKg /= 1000;
                }
            } else {
                $totalBeratRow = $row['stok_saat_ini'] * $beratPerSatuan;
                $weightInKg = (strtolower($row['satuan_berat'] ?? '') === 'gram') ? ($totalBeratRow / 1000) : $totalBeratRow;
            }
            $totalBerat += $weightInKg;
        }

        return [
            'total_berat' => $totalBerat
        ];
    }
}
