<?php

namespace App\Modules\Reports\Models;

use CodeIgniter\Model;

class LaporanPenyaluranModel extends Model
{
    protected $table      = 'detail_barang_keluar';
    protected $primaryKey = 'id';

    protected $column_order = [
        null, // No
        'barang_keluar.tanggal_keluar',
        'barang_keluar.nomor_transaksi',
        'wilayah.nama_wilayah',
        'barang_keluar.tujuan_penyaluran',
        'nama_barang',
        'batch.nomor_batch',
        'detail_barang_keluar.jumlah_keluar',
        'batch.satuan',
        'total_berat',
        'barang_keluar.keterangan',
        'users.username'
    ];

    protected $column_search = [
        'barang_keluar.nomor_transaksi',
        'wilayah.nama_wilayah',
        'barang_keluar.tujuan_penyaluran',
        'batch.nama_barang',
        'barang.nama_barang',
        'batch.nomor_batch',
        'barang_keluar.keterangan',
        'users.username'
    ];

    protected $order = ['barang_keluar.tanggal_keluar' => 'DESC', 'barang_keluar.id' => 'DESC'];

    private function _getDatatablesQuery($postData)
    {
        $builder = $this->db->table('detail_barang_keluar');
        $builder->select('
            detail_barang_keluar.id,
            detail_barang_keluar.jumlah_keluar as jumlah,
            barang_keluar.nomor_transaksi,
            barang_keluar.tanggal_keluar,
            barang_keluar.tujuan_penyaluran as program,
            barang_keluar.keterangan,
            wilayah.nama_wilayah,
            COALESCE(batch.nama_barang, barang.nama_barang) as nama_barang,
            COALESCE(batch.satuan, barang.satuan) as satuan,
            COALESCE(batch.berat_per_satuan, barang.berat_per_satuan) as berat_per_satuan,
            COALESCE(batch.satuan_berat, barang.satuan_berat) as satuan_berat,
            COALESCE(batch.bisa_dipecah, barang.bisa_dipecah) as bisa_dipecah,
            batch.nomor_batch,
            batch.nilai_satuan,
            users.username as petugas
        ');
        $builder->join('barang_keluar', 'barang_keluar.id = detail_barang_keluar.id_barang_keluar');
        $builder->join('batch', 'batch.id = detail_barang_keluar.id_batch');
        $builder->join('barang', 'barang.id = batch.id_barang');
        $builder->join('wilayah', 'wilayah.id = barang_keluar.id_wilayah', 'left');
        $builder->join('users', 'users.id = barang_keluar.id_user', 'left');

        $startDateFilter = $postData['start_date'] ?? null;
        $endDateFilter   = $postData['end_date'] ?? null;
        $nomorFilter     = $postData['nomor_penyaluran'] ?? null;
        $wilayahFilter   = $postData['wilayah'] ?? null;
        $programFilter   = $postData['program'] ?? null;
        $searchFilter    = $postData['search_custom'] ?? null;

        if (!empty($startDateFilter)) {
            $builder->where('barang_keluar.tanggal_keluar >=', $startDateFilter);
        }
        if (!empty($endDateFilter)) {
            $builder->where('barang_keluar.tanggal_keluar <=', $endDateFilter);
        }
        if (!empty($nomorFilter)) {
            $builder->like('barang_keluar.nomor_transaksi', $nomorFilter);
        }
        if (!empty($wilayahFilter)) {
            $builder->like('wilayah.nama_wilayah', $wilayahFilter);
        }
        if (!empty($programFilter)) {
            $builder->like('barang_keluar.tujuan_penyaluran', $programFilter);
        }
        if (!empty($searchFilter)) {
            $builder->groupStart()
                ->like('batch.nama_barang', $searchFilter)
                ->orLike('barang.nama_barang', $searchFilter)
                ->orLike('barang_keluar.nomor_transaksi', $searchFilter)
                ->groupEnd();
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
        $builder = $this->db->table('detail_barang_keluar');
        $builder->join('barang_keluar', 'barang_keluar.id = detail_barang_keluar.id_barang_keluar');
        return $builder->countAllResults();
    }

    public function getSummaryData($postData)
    {
        $builder = $this->_getDatatablesQuery($postData);
        $data = $builder->get()->getResultArray();
        $totalPenyaluran = 0;
        $totalBarangUtuhPerSatuan = [];
        $totalBarangRepack = 0;
        $totalBerat = 0;
        $totalNilaiDonasi = 0;
        $transaksiUnik = [];

        foreach ($data as $row) {
            if (!in_array($row['nomor_transaksi'], $transaksiUnik)) {
                $transaksiUnik[] = $row['nomor_transaksi'];
                $totalPenyaluran++;
            }

            $bisaDipecah = (int)($row['bisa_dipecah'] ?? 0);
            if ($bisaDipecah === 1) {
                $totalBarangRepack += (float)$row['jumlah'];
                $totalBeratRow = (float)$row['jumlah'];
                if (in_array(strtolower(trim($row['satuan_berat'] ?? '')), ['gram', 'g', 'gr', 'ml'])) {
                    $totalBeratRow /= 1000;
                }
            } else {
                $satuan = $row['satuan'] ?: 'Pcs';
                $totalBarangUtuhPerSatuan[$satuan] = ($totalBarangUtuhPerSatuan[$satuan] ?? 0) + (float)$row['jumlah'];
                $beratPerSatuan = (float)$row['berat_per_satuan'];
                $totalBeratRow = $row['jumlah'] * $beratPerSatuan;
                if (in_array(strtolower(trim($row['satuan_berat'] ?? '')), ['gram', 'g', 'gr', 'ml'])) {
                    $totalBeratRow /= 1000;
                }
            }
            $totalBerat += $totalBeratRow;

            $nilaiSatuan = (float)($row['nilai_satuan'] ?? 0);
            $totalNilaiDonasi += ((float)$row['jumlah'] * $nilaiSatuan);
        }

        return [
            'total_penyaluran'             => $totalPenyaluran,
            'total_barang_utuh_per_satuan' => $totalBarangUtuhPerSatuan,
            'total_barang_repack'          => $totalBarangRepack,
            'total_berat'                  => $totalBerat,
            'total_nilai_donasi'           => $totalNilaiDonasi
        ];
    }
}
