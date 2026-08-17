<?php

namespace App\Modules\Reports\Models;

use CodeIgniter\Model;

class LaporanExpiredModel extends Model
{
    protected $table      = 'batch';
    protected $primaryKey = 'id';

    protected $column_order = [
        null, // No
        'batch.tanggal_kedaluwarsa',
        null, // Status Badge / Sisa Hari
        'nama_barang',
        'batch.kategori',
        'donatur.nama_donatur',
        'batch.stok_saat_ini',
        'batch.satuan',
        'batch.jumlah_ctn',
        'berat_bersih',
        'total_berat',
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
        $builder = $this->db->table('batch');
        $builder->select('
            batch.id,
            batch.stok_saat_ini as jumlah,
            batch.jumlah_ctn,
            batch.tanggal_kedaluwarsa,
            batch.kategori as kategori_batch,
            barang_masuk.keterangan,
            COALESCE(batch.nama_barang, barang.nama_barang) as nama_barang,
            COALESCE(batch.satuan, barang.satuan) as satuan,
            COALESCE(batch.berat_per_satuan, barang.berat_per_satuan) as berat_per_satuan,
            COALESCE(batch.satuan_berat, barang.satuan_berat) as satuan_berat,
            COALESCE(batch.bisa_dipecah, barang.bisa_dipecah) as bisa_dipecah,
            donatur.nama_donatur
        ');
        $builder->join('barang_masuk', 'barang_masuk.id = batch.id_barang_masuk', 'left');
        $builder->join('barang', 'barang.id = batch.id_barang');
        $builder->join('donatur', 'donatur.id = barang_masuk.id_donatur', 'left');

        $builder->where('batch.stok_saat_ini >', 0);

        $statusFilter  = $postData['status'] ?? 'Semua';
        $donaturFilter = $postData['donatur'] ?? null;
        $searchFilter  = $postData['search_custom'] ?? null;
        $kategoriFilter = $postData['kategori'] ?? null;

        $todayStr = date('Y-m-d');
        if ($statusFilter === 'Sudah Expired') {
            $builder->where('batch.tanggal_kedaluwarsa <', $todayStr);
        } elseif ($statusFilter === 'Akan Expired (<= 30 Hari)') {
            $exp30 = date('Y-m-d', strtotime('+30 days'));
            $builder->where('batch.tanggal_kedaluwarsa >=', $todayStr)
                    ->where('batch.tanggal_kedaluwarsa <=', $exp30);
        } elseif ($statusFilter === 'Akan Expired (<= 60 Hari)') {
            $exp60 = date('Y-m-d', strtotime('+60 days'));
            $builder->where('batch.tanggal_kedaluwarsa >=', $todayStr)
                    ->where('batch.tanggal_kedaluwarsa <=', $exp60);
        } elseif ($statusFilter === 'Akan Expired (<= 90 Hari)') {
            $exp90 = date('Y-m-d', strtotime('+90 days'));
            $builder->where('batch.tanggal_kedaluwarsa >=', $todayStr)
                    ->where('batch.tanggal_kedaluwarsa <=', $exp90);
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
        if (!empty($kategoriFilter)) {
            $builder->where('batch.kategori', $kategoriFilter);
        }

        // Global Search
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

        // Order
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

    public function countAllData($postData = [])
    {
        $builder = $this->db->table('batch');
        $builder->where('batch.stok_saat_ini >', 0);
        return $builder->countAllResults();
    }

    public function getSummaryData($postData)
    {
        $builder = $this->_getDatatablesQuery($postData);
        $data = $builder->get()->getResultArray();

        $totalBatch = 0;
        $totalBarang = 0;
        $totalBerat = 0;
        $totalExpired = 0;
        $totalHampirExpired = 0;

        $todayStr = date('Y-m-d');
        $todayTime = strtotime($todayStr);

        foreach ($data as $row) {
            $totalBatch++;
            $totalBarang += (float)$row['jumlah'];

            $bisaDipecah = (int)($row['bisa_dipecah'] ?? 0);
            $beratPerSatuan = (float)$row['berat_per_satuan'];
            if ($bisaDipecah === 1) {
                $weightInKg = (float)$row['jumlah'];
                if (in_array(strtolower(trim($row['satuan_berat'] ?? '')), ['gram', 'g', 'gr', 'ml'])) {
                    $weightInKg /= 1000;
                }
            } else {
                $totalBeratRow = $row['jumlah'] * $beratPerSatuan;
                $weightInKg = (in_array(strtolower(trim($row['satuan_berat'] ?? '')), ['gram', 'g', 'gr', 'ml'])) ? ($totalBeratRow / 1000) : $totalBeratRow;
            }
            $totalBerat += $weightInKg;

            if ($row['tanggal_kedaluwarsa']) {
                $expTime = strtotime($row['tanggal_kedaluwarsa']);
                $sisaHari = floor(($expTime - $todayTime) / (60 * 60 * 24));
                if ($sisaHari < 0) {
                    $totalExpired += (float)$row['jumlah'];
                } elseif ($sisaHari <= 30) {
                    $totalHampirExpired += (float)$row['jumlah'];
                }
            }
        }

        return [
            'total_batch'          => $totalBatch,
            'total_barang'         => $totalBarang,
            'total_berat'          => $totalBerat,
            'total_expired'        => $totalExpired,
            'total_hampir_expired' => $totalHampirExpired
        ];
    }
}
