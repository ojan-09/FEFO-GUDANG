<?php

namespace App\Modules\Transactions\Controllers;

use App\Controllers\BaseController;
use App\Modules\Transactions\Models\BatchModel;
use App\Modules\MasterData\Models\BarangModel;

class StokGudang extends BaseController
{
    protected $batchModel;
    protected $barangModel;

    public function __construct()
    {
        $this->batchModel  = new BatchModel();
        $this->barangModel = new BarangModel();
    }

    /**
     * Tampilkan data Stok Gudang per-batch (Spreadsheet Style)
     */
    public function index()
    {
        $db = \Config\Database::connect();
        
        // Dapatkan data utuh dari batch tanpa GROUP BY
        $builder = $db->table('batch');
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
            donatur.nama_donatur as donatur
        ');
        $builder->join('barang', 'barang.id = batch.id_barang');
        $builder->join('barang_masuk', 'barang_masuk.id = batch.id_barang_masuk', 'left');
        $builder->join('donatur', 'donatur.id = barang_masuk.id_donatur', 'left');
        $builder->where('batch.stok_saat_ini >', 0);
        
        // Filter pencarian
        $searchFilter = $this->request->getGet('search');
        $donaturFilter = $this->request->getGet('donatur');
        $kategoriFilter = $this->request->getGet('kategori');
        $statusFilter = $this->request->getGet('status');
        
        if (!empty($searchFilter)) {
            $builder->like('barang.nama_barang', $searchFilter);
        }
        if (!empty($donaturFilter)) {
            $builder->like('donatur.nama_donatur', $donaturFilter);
        }
        if (!empty($kategoriFilter)) {
            $builder->where('batch.kategori', $kategoriFilter);
        }

        // Sorting default sesuai instruksi FEFO
        $builder->orderBy('batch.tanggal_kedaluwarsa', 'ASC');
        
        $stokGudang = $builder->get()->getResultArray();

        // Hitung status dinamis
        $today = new \DateTime(date('Y-m-d'));
        
        $filteredData = [];
        foreach ($stokGudang as &$stok) {
            $status = 'Aman';
            $stokTotal = (int) $stok['stok_saat_ini'];
            
            if ($stokTotal > 0) {
                $expiredDate = new \DateTime($stok['tanggal_kedaluwarsa']);
                $diff = $today->diff($expiredDate);
                $days = (int) $diff->format('%R%a');
                
                if ($days < 0) {
                    $status = 'Expired';
                } elseif ($days <= 30) {
                    $status = 'Hampir Expired';
                }
            }
            
            $stok['status'] = $status;
            
            // Terapkan filter status jika dipilih
            if (!empty($statusFilter) && $status !== $statusFilter) {
                continue; // Skip jika tidak sesuai filter
            }
            
            $filteredData[] = $stok;
        }

        // Ambil daftar kategori untuk dropdown filter
        $kategoriList = $db->table('kategori')->select('nama_kategori')->orderBy('nama_kategori', 'ASC')->get()->getResultArray();

        $data = [
            'title'      => 'Monitoring Stok Gudang',
            'stokGudang' => $filteredData,
            'kategori'   => $kategoriList,
            'filters'    => [
                'search'   => $searchFilter,
                'donatur'  => $donaturFilter,
                'kategori' => $kategoriFilter,
                'status'   => $statusFilter,
            ]
        ];
        
        return view('App\Modules\Transactions\Views\stok_gudang\index', $data);
    }

    /**
     * Tampilkan daftar lengkap batch untuk suatu barang (Read-Only)
     */
    public function detail($idBarang)
    {
        $barang = $this->barangModel->find($idBarang);
        
        if (!$barang) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Barang tidak ditemukan.');
        }

        // Ambil batch lengkap dari barang terpilih yang stoknya lebih dari 0
        $batches = $this->batchModel
            ->select('batch.*, donatur.nama_donatur')
            ->join('barang_masuk', 'barang_masuk.id = batch.id_barang_masuk')
            ->join('donatur', 'donatur.id = barang_masuk.id_donatur')
            ->where('batch.id_barang', $idBarang)
            ->where('batch.stok_saat_ini >', 0)
            ->orderBy('batch.tanggal_kedaluwarsa', 'ASC')
            ->findAll();
            
        // Hitung status dinamis tiap batch
        $today = new \DateTime(date('Y-m-d'));
        foreach ($batches as &$batch) {
            $batchStatus = 'Aman';
            $stok = (int) $batch['stok_saat_ini'];
            
            if ($stok > 0) {
                $expiredDate = new \DateTime($batch['tanggal_kedaluwarsa']);
                $diff = $today->diff($expiredDate);
                $days = (int) $diff->format('%R%a');
                
                if ($days < 0) {
                    $batchStatus = 'Expired';
                } elseif ($days <= 30) {
                    $batchStatus = 'Hampir Expired';
                }
            }
            
            $batch['status_dinamis'] = $batchStatus;
        }

        $data = [
            'title'   => 'Detail Stok: ' . $barang['nama_barang'],
            'barang'  => $barang,
            'batches' => $batches,
        ];
        
        return view('App\Modules\Transactions\Views\stok_gudang\detail', $data);
    }
}

