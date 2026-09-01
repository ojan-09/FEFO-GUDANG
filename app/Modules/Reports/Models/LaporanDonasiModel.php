<?php

namespace App\Modules\Reports\Models;

use CodeIgniter\Model;

class LaporanDonasiModel extends Model
{
    protected $table      = 'batch';
    protected $primaryKey = 'id';

    protected $column_order = [
        null, // No
        'barang_masuk.tanggal_masuk',
        'barang_masuk.nomor_transaksi',
        'donatur.nama_donatur',
        'nama_barang',
        'batch.kategori',
        'batch.jumlah_awal',
        'batch.satuan',
        'batch.jumlah_ctn',
        'batch.berat_per_satuan',
        'total_berat',
        'batch.tanggal_kedaluwarsa',
        'barang_masuk.keterangan',
        'users.username'
    ];

    protected $column_search = [
        'barang_masuk.nomor_transaksi',
        'batch.nama_barang',
        'barang.nama_barang',
        'donatur.nama_donatur',
        'batch.kategori',
        'barang_masuk.keterangan',
        'users.username'
    ];

    protected $order = ['barang_masuk.tanggal_masuk' => 'DESC', 'barang_masuk.id' => 'DESC'];

    private function _getDatatablesQuery($postData)
    {
        $builder = $this->db->table('batch');
        $builder->select('
            batch.id,
            batch.jumlah_awal as jumlah,
            batch.jumlah_ctn,
            batch.tanggal_kedaluwarsa,
            batch.kategori as kategori_batch,
            COALESCE(batch.nama_barang, barang.nama_barang) as nama_barang,
            COALESCE(batch.satuan, barang.satuan) as satuan,
            COALESCE(batch.berat_per_satuan, barang.berat_per_satuan) as berat_per_satuan,
            COALESCE(batch.satuan_berat, barang.satuan_berat) as satuan_berat,
            COALESCE(batch.bisa_dipecah, barang.bisa_dipecah) as bisa_dipecah,
            barang_masuk.nomor_transaksi,
            barang_masuk.tanggal_masuk,
            barang_masuk.keterangan,
            batch.nilai_satuan,
            donatur.nama_donatur,
            users.username as petugas
        ');
        $builder->join('barang_masuk', 'barang_masuk.id = batch.id_barang_masuk');
        $builder->join('barang', 'barang.id = batch.id_barang', 'left');
        $builder->join('donatur', 'donatur.id = barang_masuk.id_donatur', 'left');
        $builder->join('users', 'users.id = barang_masuk.id_user', 'left');

        // Apply filters
        $bulanFilter     = $postData['bulan'] ?? '';
        $tahunFilter     = $postData['tahun'] ?? null;
        $startDateFilter = $postData['start_date'] ?? null;
        $endDateFilter   = $postData['end_date'] ?? null;
        $donaturFilter   = $postData['donatur'] ?? null;
        $searchFilter    = $postData['search_custom'] ?? null;
        $kategoriFilter  = $postData['kategori'] ?? null;
        $nomorFilter     = $postData['nomor_donasi'] ?? null;

        if (!empty($startDateFilter) && !empty($endDateFilter)) {
            $builder->where('barang_masuk.tanggal_masuk >=', $startDateFilter);
            $builder->where('barang_masuk.tanggal_masuk <=', $endDateFilter);
        } else {
            // Filter bulan hanya kalau bukan "Semua Bulan"
            if (!empty($bulanFilter)) {
                $builder->where('MONTH(barang_masuk.tanggal_masuk)', $bulanFilter);
            }
            // Selalu filter tahun
            if (!empty($tahunFilter)) {
                $builder->where('YEAR(barang_masuk.tanggal_masuk)', $tahunFilter);
            }
        }

        if (!empty($donaturFilter)) {
            $builder->like('donatur.nama_donatur', $donaturFilter);
        }
        if (!empty($searchFilter)) {
            $builder->groupStart()
                ->like('batch.nama_barang', $searchFilter)
                ->orLike('barang.nama_barang', $searchFilter)
                ->orLike('barang_masuk.nomor_transaksi', $searchFilter)
                ->groupEnd();
        }
        if (!empty($kategoriFilter)) {
            $builder->where('batch.kategori', $kategoriFilter);
        }
        if (!empty($nomorFilter)) {
            $builder->like('barang_masuk.nomor_transaksi', $nomorFilter);
        }

        // Global Search dari DataTables search box
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
        } else {
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
        // Gunakan query yang sama supaya recordsTotal konsisten dengan filter
        $builder = $this->_getDatatablesQuery($postData);
        return $builder->countAllResults();
    }

    public function getSummaryData($postData)
    {
        $builder = $this->_getDatatablesQuery($postData);
        $data    = $builder->get()->getResultArray();

        $totalTransaksi   = 0;
        $totalBarang      = 0;
        $totalBerat       = 0;
        $totalNilaiDonasi = 0;
        $transaksiUnik    = [];

        foreach ($data as $row) {
            if (!in_array($row['nomor_transaksi'], $transaksiUnik)) {
                $transaksiUnik[] = $row['nomor_transaksi'];
                $totalTransaksi++;
            }
            $totalBarang += (float)$row['jumlah'];

            $bisaDipecah    = (int) ($row['bisa_dipecah'] ?? 0);
            $beratPerSatuan = (float) $row['berat_per_satuan'];
            if ($bisaDipecah === 1) {
                $totalBeratRow = (float) $row['jumlah'];
                if (in_array(strtolower(trim($row['satuan_berat'] ?? '')), ['gram', 'g', 'gr', 'ml'])) {
                    $totalBeratRow /= 1000;
                }
            } else {
                $totalBeratRow = $row['jumlah'] * $beratPerSatuan;
                if (in_array(strtolower(trim($row['satuan_berat'] ?? '')), ['gram', 'g', 'gr', 'ml'])) {
                    $totalBeratRow /= 1000;
                }
            }
            $totalBerat += $totalBeratRow;

            $nilaiSatuan       = (float)($row['nilai_satuan'] ?? 0);
            $totalNilaiDonasi += ((float)$row['jumlah'] * $nilaiSatuan);
        }

        return [
            'total_transaksi'    => $totalTransaksi,
            'total_barang'       => $totalBarang,
            'total_berat'        => $totalBerat,
            'total_nilai_donasi' => $totalNilaiDonasi
        ];
    }
}