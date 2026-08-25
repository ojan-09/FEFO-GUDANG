<?php

namespace App\Modules\Transactions\Models;

use CodeIgniter\Model;

class BatchModel extends Model
{
    protected $table            = 'batch';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_barang_masuk', 'id_barang', 'nomor_batch', 'nama_barang', 'kategori',
        'tanggal_masuk', 'tanggal_kedaluwarsa', 'jumlah_awal', 'stok_saat_ini',
        'menggunakan_kemasan', 'jumlah_ctn', 'isi_per_ctn', 'satuan', 'berat_per_satuan', 'satuan_berat', 'status', 'bisa_dipecah',
        'nilai_satuan'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $column_order  = [null, null, 'donatur.nama_donatur', 'batch.kategori', 'batch.tanggal_kedaluwarsa', 'umur_stok_hari', 'barang.nama_barang', null, null, null, 'batch.stok_saat_ini', 'barang_masuk.keterangan', null];
    protected $column_search = ['batch.nama_barang', 'barang.nama_barang', 'donatur.nama_donatur', 'batch.kategori', 'barang_masuk.keterangan'];
    protected $order         = ['batch.tanggal_kedaluwarsa' => 'ASC'];

    private function _getDatatablesQuery($postData)
    {
        $builder = $this->db->table($this->table);
        $builder->select('
            batch.id,
            batch.id_barang,
            batch.kategori,
            batch.stok_saat_ini,
            batch.tanggal_masuk,
            batch.tanggal_kedaluwarsa,
            DATEDIFF(CURDATE(), batch.tanggal_masuk) AS umur_stok_hari,
            batch.menggunakan_kemasan,
            batch.jumlah_ctn,
            batch.isi_per_ctn,
            batch.jumlah_awal,
            batch.bisa_dipecah,
            barang_masuk.keterangan as catatan,
            COALESCE(batch.nama_barang, barang.nama_barang) as nama_barang,
            COALESCE(batch.satuan, barang.satuan) as satuan,
            COALESCE(batch.berat_per_satuan, barang.berat_per_satuan) as berat_per_satuan,
            COALESCE(batch.satuan_berat, barang.satuan_berat) as satuan_berat,
            donatur.nama_donatur as donatur
        ');
        $builder->join('barang', 'barang.id = batch.id_barang');
        $builder->join('barang_masuk', 'barang_masuk.id = batch.id_barang_masuk', 'left');
        $builder->join('donatur', 'donatur.id = barang_masuk.id_donatur', 'left');
        $builder->where('batch.stok_saat_ini >', 0);

        if (!empty($postData['kategori'])) {
            $builder->where('batch.kategori', $postData['kategori']);
        }
        if (!empty($postData['donatur'])) {
            $builder->like('donatur.nama_donatur', $postData['donatur']);
        }
        if (!empty($postData['status'])) {
            $todayStr = date('Y-m-d');
            $hampirExpiredStr = date('Y-m-d', strtotime('+30 days'));
            if ($postData['status'] === 'Expired') {
                $builder->where('batch.tanggal_kedaluwarsa <', $todayStr);
            } elseif ($postData['status'] === 'Hampir Expired') {
                $builder->where('batch.tanggal_kedaluwarsa >=', $todayStr);
                $builder->where('batch.tanggal_kedaluwarsa <=', $hampirExpiredStr);
            } elseif ($postData['status'] === 'Aman') {
                $builder->where('batch.tanggal_kedaluwarsa >', $hampirExpiredStr);
            }
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
        $builder = $this->db->table($this->table)->where('stok_saat_ini >', 0);
        return $builder->countAllResults();
    }

    public function getRiwayatPenyaluranByBarang(int $idBarang): array
    {
        return $this->db->table('detail_barang_keluar dkl')
            ->select('
                bk.id AS id_barang_keluar,
                bk.nomor_transaksi,
                bk.tanggal_keluar,
                bk.tujuan_penyaluran,
                bk.jenis_penyaluran,
                bk.penerima_relawan,
                bk.unit_internal,
                b.nomor_batch,
                b.tanggal_kedaluwarsa,
                b.satuan,
                b.bisa_dipecah,
                dkl.jumlah_keluar
            ')
            ->join('batch b', 'b.id = dkl.id_batch')
            ->join('barang_keluar bk', 'bk.id = dkl.id_barang_keluar')
            ->where('b.id_barang', $idBarang)
            ->orderBy('bk.tanggal_keluar', 'DESC')
            ->get()
            ->getResultArray();
    }
}