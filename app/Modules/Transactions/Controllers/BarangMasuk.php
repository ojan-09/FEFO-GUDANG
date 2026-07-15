<?php

namespace App\Modules\Transactions\Controllers;

use App\Controllers\BaseController;
use App\Modules\Transactions\Models\BarangMasukModel;
use App\Modules\Transactions\Models\BatchModel;
use App\Modules\MasterData\Models\DonaturModel;
use App\Modules\MasterData\Models\KategoriModel;
use App\Modules\MasterData\Models\BarangModel;

class BarangMasuk extends BaseController
{
    protected $barangMasukModel;
    protected $batchModel;
    protected $donaturModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->barangMasukModel = new BarangMasukModel();
        $this->batchModel       = new BatchModel();
        $this->donaturModel     = new DonaturModel();
        $this->kategoriModel    = new KategoriModel();
    }

    /**
     * Daftar transaksi Donasi Masuk
     */
    public function index()
    {
        $barangMasuk = $this->barangMasukModel
            ->select('barang_masuk.*, donatur.nama_donatur, users.username as petugas')
            ->join('donatur', 'donatur.id = barang_masuk.id_donatur')
            ->join('users', 'users.id = barang_masuk.id_user')
            ->orderBy('barang_masuk.id', 'DESC')
            ->findAll();

        $bmIds = array_column($barangMasuk, 'id');
        $batchStats = [];

        if (!empty($bmIds)) {
            // Hitung jumlah item (batch) per transaksi dan cek status penggunaan dalam 1 query
            $stats = $this->batchModel
                ->select('id_barang_masuk, COUNT(id) as total_item, SUM(IF(stok_saat_ini < jumlah_awal, 1, 0)) as total_terpakai')
                ->whereIn('id_barang_masuk', $bmIds)
                ->groupBy('id_barang_masuk')
                ->findAll();
                
            foreach ($stats as $stat) {
                $batchStats[$stat['id_barang_masuk']] = $stat;
            }
        }

        foreach ($barangMasuk as &$bm) {
            $stat = $batchStats[$bm['id']] ?? ['total_item' => 0, 'total_terpakai' => 0];
            $bm['jumlah_item'] = $stat['total_item'];
            $bm['is_used'] = ($stat['total_terpakai'] > 0);
        }

        $data = [
            'title'       => 'Transaksi Donasi Masuk',
            'barangMasuk' => $barangMasuk,
        ];
        return view('App\Modules\Transactions\Views\barang_masuk\index', $data);
    }

    /**
     * Form tambah Donasi Masuk
     */
    public function create()
    {
        $data = [
            'title'           => 'Tambah Donasi Masuk',
            'nomor_transaksi' => $this->generateNomorTransaksi(),
            'donatur'         => $this->donaturModel->orderBy('nama_donatur', 'ASC')->findAll(),
            'kategori'        => $this->kategoriModel->orderBy('nama_kategori', 'ASC')->findAll(),
        ];
        return view('App\Modules\Transactions\Views\barang_masuk\form', $data);
    }

    /**
     * Simpan transaksi + batch
     */
    public function store()
    {
        $rules = [
            'tanggal_masuk' => 'required|valid_date',
            'id_donatur'    => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $items = $this->request->getPost('items');
        if (empty($items) || !is_array($items)) {
            return redirect()->back()->withInput()->with('errors', ['items' => 'Minimal harus ada 1 barang.']);
        }

        $tanggalMasuk = $this->request->getPost('tanggal_masuk');
        $kategoriValid = array_column($this->kategoriModel->findAll(), 'nama_kategori');
        $satuanValid = ['Box', 'Dus', 'Pcs', 'Karung', 'Botol', 'Pack', 'Tray', 'Kaleng', 'Pouch', 'Sak'];
        $satuanBeratValid = ['Gram', 'Kg'];
        $cleanItems = [];

        foreach ($items as $key => $item) {
            $namaBarang = trim($item['nama_barang'] ?? '');
            $kategori = trim($item['kategori'] ?? '');
            $jumlahCtn = trim($item['jumlah_ctn'] ?? '');
            $jumlah = (int) ($item['jumlah'] ?? 0);
            $satuan = trim($item['satuan'] ?? '');
            $beratPerSatuan = (float) ($item['berat_per_satuan'] ?? 0);
            $satuanBerat = trim($item['satuan_berat'] ?? '');
            $tanggalKedaluwarsa = $item['tanggal_kedaluwarsa'] ?? '';

            if ($namaBarang === '') {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Nama Barang wajib diisi."]);
            }
            if ($kategori === '') {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Kategori wajib diisi."]);
            }
            if (!in_array($kategori, $kategoriValid, true)) {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Kategori tidak valid."]);
            }
            if ($jumlah <= 0) {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Jumlah harus lebih besar dari 0."]);
            }
            if ($satuan === '') {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Satuan wajib diisi."]);
            }
            if (!in_array($satuan, $satuanValid, true)) {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Satuan tidak valid."]);
            }
            if ($beratPerSatuan <= 0) {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Berat per Satuan harus lebih besar dari 0."]);
            }
            if ($satuanBerat === '') {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Satuan Berat wajib diisi."]);
            }
            if (!in_array($satuanBerat, $satuanBeratValid, true)) {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Satuan Berat tidak valid."]);
            }
            if ($tanggalKedaluwarsa === '') {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Tanggal Kedaluwarsa wajib diisi."]);
            }
            if (!strtotime($tanggalKedaluwarsa)) {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Tanggal Kedaluwarsa tidak valid."]);
            }
            if ($tanggalKedaluwarsa <= $tanggalMasuk) {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Tanggal Kedaluwarsa harus lebih besar dari Tanggal Masuk."]);
            }

            $cleanItems[] = [
                'nama_barang'         => $namaBarang,
                'kategori'            => $kategori,
                'jumlah_ctn'          => $jumlahCtn === '' ? null : (int) $jumlahCtn,
                'jumlah'              => $jumlah,
                'satuan'              => $satuan,
                'berat_per_satuan'    => $beratPerSatuan,
                'satuan_berat'        => $satuanBerat,
                'tanggal_kedaluwarsa' => $tanggalKedaluwarsa,
            ];
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $nomorTransaksi = $this->generateNomorTransaksi();
        $this->barangMasukModel->insert([
            'nomor_transaksi' => $nomorTransaksi,
            'id_donatur'      => $this->request->getPost('id_donatur'),
            'id_user'         => user()->id,
            'tanggal_masuk'   => $tanggalMasuk,
            'eta'             => $this->request->getPost('eta') ?: null,
            'keterangan'      => $this->request->getPost('keterangan'),
        ]);

        $idBarangMasuk = $this->barangMasukModel->getInsertID();
        $barangModel = new BarangModel();

        // 1. Bulk Load Kategori
        $kategoriList = $this->kategoriModel->findAll();
        $kategoriMap = [];
        foreach ($kategoriList as $k) {
            $kategoriMap[$k['nama_kategori']] = $k['id'];
        }

        // 2. Bulk Load existing Barang
        $namaBarangUnik = array_unique(array_column($cleanItems, 'nama_barang'));
        $namaBarangUnikLower = array_map('strtolower', $namaBarangUnik);
        
        $barangList = $barangModel->whereIn('LOWER(nama_barang)', $namaBarangUnikLower)->findAll();
        $barangMap = [];
        foreach ($barangList as $b) {
            $barangMap[strtolower($b['nama_barang'])] = $b['id'];
        }

        // 3. Prepare Batch Number Generator in-memory
        $today = date('Ymd');
        $prefix = "BT-{$today}-";
        $lastBatch = $this->batchModel->like('nomor_batch', $prefix, 'after')->orderBy('id', 'DESC')->first();
        $lastNumber = $lastBatch ? (int) substr($lastBatch['nomor_batch'], -4) : 0;

        // 4. Prepare Master Barang Number Generator in-memory
        $lastBarang = $barangModel->orderBy('id', 'DESC')->first();
        $lastBrgNumber = ($lastBarang && preg_match('/BRG-(\d+)/', $lastBarang['kode_barang'], $matches)) ? (int) $matches[1] : 0;

        $batchInsertData = [];

        foreach ($cleanItems as $item) {
            $namaBarangKey = strtolower($item['nama_barang']);
            
            // Find id_kategori
            $idKategori = $kategoriMap[$item['kategori']] ?? 1;

            // Auto Grouping / Auto Generate Master Barang
            if (isset($barangMap[$namaBarangKey])) {
                $idBarang = $barangMap[$namaBarangKey];
            } else {
                $lastBrgNumber++;
                $kodeBarang = 'BRG-' . str_pad($lastBrgNumber, 6, '0', STR_PAD_LEFT);
                
                $barangModel->insert([
                    'kode_barang'      => $kodeBarang,
                    'id_kategori'      => $idKategori,
                    'nama_barang'      => $item['nama_barang'],
                    'satuan'           => $item['satuan'],
                    'berat_per_satuan' => $item['berat_per_satuan'],
                    'satuan_berat'     => $item['satuan_berat'],
                    'minimum_stok'     => 0,
                ]);
                $idBarang = $barangModel->getInsertID();
                $barangMap[$namaBarangKey] = $idBarang; // Cache it for next iteration if duplicated
            }

            $lastNumber++;
            $nomorBatch = $prefix . str_pad($lastNumber, 4, '0', STR_PAD_LEFT);

            $batchInsertData[] = [
                'id_barang_masuk'     => $idBarangMasuk,
                'id_barang'           => $idBarang,
                'nomor_batch'         => $nomorBatch,
                'nama_barang'         => $item['nama_barang'],
                'kategori'            => $item['kategori'],
                'tanggal_masuk'       => $tanggalMasuk,
                'tanggal_kedaluwarsa' => $item['tanggal_kedaluwarsa'],
                'jumlah_awal'         => $item['jumlah'],
                'stok_saat_ini'       => $item['jumlah'],
                'jumlah_ctn'          => $item['jumlah_ctn'],
                'satuan'              => $item['satuan'],
                'berat_per_satuan'    => $item['berat_per_satuan'],
                'satuan_berat'        => $item['satuan_berat'],
                'status'              => 'Aktif',
            ];
        }

        if (!empty($batchInsertData)) {
            $this->batchModel->insertBatch($batchInsertData);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('errors', ['db' => 'Gagal menyimpan transaksi. Silakan coba lagi.']);
        }

        \App\Libraries\ActivityLogger::log(
            'Tambah Donasi',
            'Donasi Masuk',
            "Menambahkan transaksi Donasi Masuk {$nomorTransaksi}."
        );

        return redirect()->to('/transaksi/barang-masuk')->with('success', 'Transaksi Barang Masuk berhasil disimpan.');
    }

    /**
     * Form edit Donasi Masuk
     */
    public function edit($id)
    {
        $barangMasuk = $this->barangMasukModel->find($id);
        if (!$barangMasuk) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Transaksi tidak ditemukan.');
        }

        $batchTerpakai = $this->batchModel
            ->where('id_barang_masuk', $id)
            ->where('stok_saat_ini < jumlah_awal')
            ->countAllResults();

        if ($batchTerpakai > 0) {
            return redirect()->to('/transaksi/barang-masuk')->with('error', 'Transaksi tidak dapat diubah karena sebagian atau seluruh barang dari donasi ini sudah digunakan pada proses Penyaluran Barang.');
        }

        $batches = $this->batchModel
            ->select('batch.*, COALESCE(batch.nama_barang, barang.nama_barang) AS nama_barang, COALESCE(batch.satuan, barang.satuan) AS satuan, COALESCE(batch.berat_per_satuan, barang.berat_per_satuan) AS berat_per_satuan, COALESCE(batch.satuan_berat, barang.satuan_berat) AS satuan_berat')
            ->join('barang', 'barang.id = batch.id_barang', 'left')
            ->where('batch.id_barang_masuk', $id)
            ->findAll();

        $data = [
            'title'           => 'Edit Donasi Masuk',
            'barangMasuk'     => $barangMasuk,
            'nomor_transaksi' => $barangMasuk['nomor_transaksi'],
            'batches'         => $batches,
            'donatur'         => $this->donaturModel->orderBy('nama_donatur', 'ASC')->findAll(),
            'kategori'        => $this->kategoriModel->orderBy('nama_kategori', 'ASC')->findAll(),
        ];
        return view('App\Modules\Transactions\Views\barang_masuk\form', $data);
    }

    /**
     * Proses update Donasi Masuk
     */
    public function update($id)
    {
        $barangMasuk = $this->barangMasukModel->find($id);
        if (!$barangMasuk) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Transaksi tidak ditemukan.');
        }

        $batchTerpakai = $this->batchModel
            ->where('id_barang_masuk', $id)
            ->where('stok_saat_ini < jumlah_awal')
            ->countAllResults();

        if ($batchTerpakai > 0) {
            return redirect()->to('/transaksi/barang-masuk')->with('error', 'Transaksi tidak dapat diubah karena sebagian atau seluruh barang dari donasi ini sudah digunakan pada proses Penyaluran Barang.');
        }

        $rules = [
            'tanggal_masuk' => 'required|valid_date',
            'id_donatur'    => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $items = $this->request->getPost('items');
        if (empty($items) || !is_array($items)) {
            return redirect()->back()->withInput()->with('errors', ['items' => 'Minimal harus ada 1 barang.']);
        }

        $tanggalMasuk = $this->request->getPost('tanggal_masuk');
        $kategoriValid = array_column($this->kategoriModel->findAll(), 'nama_kategori');
        $satuanValid = ['Box', 'Dus', 'Pcs', 'Karung', 'Botol', 'Pack', 'Tray', 'Kaleng', 'Pouch', 'Sak'];
        $satuanBeratValid = ['Gram', 'Kg'];
        $cleanItems = [];

        foreach ($items as $key => $item) {
            $namaBarang = trim($item['nama_barang'] ?? '');
            $kategori = trim($item['kategori'] ?? '');
            $jumlahCtn = trim($item['jumlah_ctn'] ?? '');
            $jumlah = (int) ($item['jumlah'] ?? 0);
            $satuan = trim($item['satuan'] ?? '');
            $beratPerSatuan = (float) ($item['berat_per_satuan'] ?? 0);
            $satuanBerat = trim($item['satuan_berat'] ?? '');
            $tanggalKedaluwarsa = $item['tanggal_kedaluwarsa'] ?? '';

            if ($namaBarang === '') {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Nama Barang wajib diisi."]);
            }
            if ($kategori === '') {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Kategori wajib diisi."]);
            }
            if (!in_array($kategori, $kategoriValid, true)) {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Kategori tidak valid."]);
            }
            if ($jumlah <= 0) {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Jumlah harus lebih besar dari 0."]);
            }
            if ($satuan === '') {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Satuan wajib diisi."]);
            }
            if (!in_array($satuan, $satuanValid, true)) {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Satuan tidak valid."]);
            }
            if ($beratPerSatuan <= 0) {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Berat per Satuan harus lebih besar dari 0."]);
            }
            if ($satuanBerat === '') {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Satuan Berat wajib diisi."]);
            }
            if (!in_array($satuanBerat, $satuanBeratValid, true)) {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Satuan Berat tidak valid."]);
            }
            if ($tanggalKedaluwarsa === '') {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Tanggal Kedaluwarsa wajib diisi."]);
            }
            if (!strtotime($tanggalKedaluwarsa)) {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Tanggal Kedaluwarsa tidak valid."]);
            }
            if ($tanggalKedaluwarsa <= $tanggalMasuk) {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Tanggal Kedaluwarsa harus lebih besar dari Tanggal Masuk."]);
            }

            $idBatch = trim($item['id'] ?? '');

            $cleanItems[] = [
                'id'                  => $idBatch === '' ? null : (int) $idBatch,
                'nama_barang'         => $namaBarang,
                'kategori'            => $kategori,
                'jumlah_ctn'          => $jumlahCtn === '' ? null : (int) $jumlahCtn,
                'jumlah'              => $jumlah,
                'satuan'              => $satuan,
                'berat_per_satuan'    => $beratPerSatuan,
                'satuan_berat'        => $satuanBerat,
                'tanggal_kedaluwarsa' => $tanggalKedaluwarsa,
            ];
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $this->barangMasukModel->update($id, [
            'id_donatur'      => $this->request->getPost('id_donatur'),
            'tanggal_masuk'   => $tanggalMasuk,
            'eta'             => $this->request->getPost('eta') ?: null,
            'keterangan'      => $this->request->getPost('keterangan'),
        ]);

        $barangModel = new BarangModel();

        // 1. Load existing batches for this transaction
        $existingBatches = $this->batchModel->where('id_barang_masuk', $id)->findAll();
        $existingBatchMap = [];
        foreach ($existingBatches as $eb) {
            $existingBatchMap[$eb['id']] = $eb;
        }

        // 2. Identify deleted batches (present in DB, but not in submitted form)
        $submittedIds = array_filter(array_column($cleanItems, 'id'));
        $deletedBatchIds = array_diff(array_keys($existingBatchMap), $submittedIds);

        foreach ($deletedBatchIds as $dbId) {
            $eb = $existingBatchMap[$dbId];
            if ($eb['stok_saat_ini'] < $eb['jumlah_awal']) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('errors', ['items' => "Barang '{$eb['nama_barang']}' tidak dapat dihapus karena stoknya sudah digunakan pada proses Penyaluran."]);
            }
            $this->batchModel->delete($dbId);
        }

        // 3. Bulk Load Kategori
        $kategoriList = $this->kategoriModel->findAll();
        $kategoriMap = [];
        foreach ($kategoriList as $k) {
            $kategoriMap[$k['nama_kategori']] = $k['id'];
        }

        // 4. Bulk Load existing Barang
        $namaBarangUnik = array_unique(array_column($cleanItems, 'nama_barang'));
        $namaBarangUnikLower = array_map('strtolower', $namaBarangUnik);
        
        $barangList = $barangModel->whereIn('LOWER(nama_barang)', $namaBarangUnikLower)->findAll();
        $barangMap = [];
        foreach ($barangList as $b) {
            $barangMap[strtolower($b['nama_barang'])] = $b['id'];
        }

        // 5. Prepare Batch Number Generator in-memory
        $today = date('Ymd');
        $prefix = "BT-{$today}-";
        $lastBatch = $this->batchModel->like('nomor_batch', $prefix, 'after')->orderBy('id', 'DESC')->first();
        $lastNumber = $lastBatch ? (int) substr($lastBatch['nomor_batch'], -4) : 0;

        // 6. Prepare Master Barang Number Generator in-memory
        $lastBarang = $barangModel->orderBy('id', 'DESC')->first();
        $lastBrgNumber = ($lastBarang && preg_match('/BRG-(\d+)/', $lastBarang['kode_barang'], $matches)) ? (int) $matches[1] : 0;

        foreach ($cleanItems as $item) {
            $namaBarangKey = strtolower($item['nama_barang']);
            
            // Find id_kategori
            $idKategori = $kategoriMap[$item['kategori']] ?? 1;

            // Auto Grouping / Auto Generate Master Barang
            if (isset($barangMap[$namaBarangKey])) {
                $idBarang = $barangMap[$namaBarangKey];
            } else {
                $lastBrgNumber++;
                $kodeBarang = 'BRG-' . str_pad($lastBrgNumber, 6, '0', STR_PAD_LEFT);
                
                $barangModel->insert([
                    'kode_barang'      => $kodeBarang,
                    'id_kategori'      => $idKategori,
                    'nama_barang'      => $item['nama_barang'],
                    'satuan'           => $item['satuan'],
                    'berat_per_satuan' => $item['berat_per_satuan'],
                    'satuan_berat'     => $item['satuan_berat'],
                    'minimum_stok'     => 0,
                ]);
                $idBarang = $barangModel->getInsertID();
                $barangMap[$namaBarangKey] = $idBarang; // Cache it
            }

            if (!empty($item['id']) && isset($existingBatchMap[$item['id']])) {
                // UPDATE EXISTING BATCH
                $eb = $existingBatchMap[$item['id']];

                // If the item itself changed (different id_barang)
                if ((int)$eb['id_barang'] !== (int)$idBarang) {
                    if ($eb['stok_saat_ini'] < $eb['jumlah_awal']) {
                        $db->transRollback();
                        return redirect()->back()->withInput()->with('errors', ['items' => "Barang '{$eb['nama_barang']}' tidak dapat diganti ke barang lain karena sebagian stoknya sudah digunakan."]);
                    }

                    // Reset stock to the new quantity for the new item type
                    $this->batchModel->update($eb['id'], [
                        'id_barang'           => $idBarang,
                        'nama_barang'         => $item['nama_barang'],
                        'kategori'            => $item['kategori'],
                        'tanggal_masuk'       => $tanggalMasuk,
                        'tanggal_kedaluwarsa' => $item['tanggal_kedaluwarsa'],
                        'jumlah_awal'         => $item['jumlah'],
                        'stok_saat_ini'       => $item['jumlah'],
                        'jumlah_ctn'          => $item['jumlah_ctn'],
                        'satuan'              => $item['satuan'],
                        'berat_per_satuan'    => $item['berat_per_satuan'],
                        'satuan_berat'        => $item['satuan_berat'],
                    ]);
                } else {
                    // Item is the same, adjust qty based on delta
                    $delta = $item['jumlah'] - $eb['jumlah_awal'];
                    $stokBaru = $eb['stok_saat_ini'] + $delta;

                    if ($stokBaru < 0) {
                        $db->transRollback();
                        return redirect()->back()->withInput()->with('errors', ['items' => "Jumlah barang '{$item['nama_barang']}' tidak dapat dikurangi menjadi {$item['jumlah']} karena sisa stok gudang tinggal {$eb['stok_saat_ini']}."]);
                    }

                    $this->batchModel->update($eb['id'], [
                        'nama_barang'         => $item['nama_barang'],
                        'kategori'            => $item['kategori'],
                        'tanggal_masuk'       => $tanggalMasuk,
                        'tanggal_kedaluwarsa' => $item['tanggal_kedaluwarsa'],
                        'jumlah_awal'         => $item['jumlah'],
                        'stok_saat_ini'       => $stokBaru,
                        'jumlah_ctn'          => $item['jumlah_ctn'],
                        'satuan'              => $item['satuan'],
                        'berat_per_satuan'    => $item['berat_per_satuan'],
                        'satuan_berat'        => $item['satuan_berat'],
                    ]);
                }
            } else {
                // INSERT NEW BATCH (Added during edit)
                $lastNumber++;
                $nomorBatch = $prefix . str_pad($lastNumber, 4, '0', STR_PAD_LEFT);

                $this->batchModel->insert([
                    'id_barang_masuk'     => $id,
                    'id_barang'           => $idBarang,
                    'nomor_batch'         => $nomorBatch,
                    'nama_barang'         => $item['nama_barang'],
                    'kategori'            => $item['kategori'],
                    'tanggal_masuk'       => $tanggalMasuk,
                    'tanggal_kedaluwarsa' => $item['tanggal_kedaluwarsa'],
                    'jumlah_awal'         => $item['jumlah'],
                    'stok_saat_ini'       => $item['jumlah'],
                    'jumlah_ctn'          => $item['jumlah_ctn'],
                    'satuan'              => $item['satuan'],
                    'berat_per_satuan'    => $item['berat_per_satuan'],
                    'satuan_berat'        => $item['satuan_berat'],
                    'status'              => 'Aktif',
                ]);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('errors', ['db' => 'Gagal memperbarui transaksi. Silakan coba lagi.']);
        }

        \App\Libraries\ActivityLogger::log(
            'Edit Donasi',
            'Donasi Masuk',
            "Mengubah transaksi Donasi Masuk {$barangMasuk['nomor_transaksi']}."
        );

        return redirect()->to('/transaksi/barang-masuk')->with('success', 'Transaksi Barang Masuk berhasil diperbarui.');
    }

    /**
     * Detail transaksi
     */
    public function detail($id)
    {
        $barangMasuk = $this->barangMasukModel
            ->select('barang_masuk.*, donatur.nama_donatur, users.username as petugas')
            ->join('donatur', 'donatur.id = barang_masuk.id_donatur')
            ->join('users', 'users.id = barang_masuk.id_user')
            ->find($id);

        if (!$barangMasuk) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Transaksi tidak ditemukan.');
        }

        $batches = $this->batchModel
            ->select('batch.*, COALESCE(batch.nama_barang, barang.nama_barang) AS nama_barang, COALESCE(batch.satuan, barang.satuan) AS satuan, COALESCE(batch.berat_per_satuan, barang.berat_per_satuan) AS berat_per_satuan, COALESCE(batch.satuan_berat, barang.satuan_berat) AS satuan_berat')
            ->join('barang', 'barang.id = batch.id_barang', 'left')
            ->where('batch.id_barang_masuk', $id)
            ->findAll();

        $data = [
            'title'       => 'Detail Donasi Masuk',
            'barangMasuk' => $barangMasuk,
            'batches'     => $batches,
        ];
        return view('App\Modules\Transactions\Views\barang_masuk\detail', $data);
    }

    /**
     * Hapus transaksi + batch terkait
     */
    public function delete($id)
    {
        if (!in_groups('Administrator')) {
            return view('errors/html/error_403');
        }

        $barangMasuk = $this->barangMasukModel->find($id);
        if (!$barangMasuk) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Transaksi tidak ditemukan.');
        }

        // ============================================
        // VALIDASI 3: Proteksi hapus
        // Cek apakah ada batch yang stoknya sudah terpakai
        // ============================================
        $batchTerpakai = $this->batchModel
            ->where('id_barang_masuk', $id)
            ->where('stok_saat_ini < jumlah_awal')
            ->countAllResults();

        if ($batchTerpakai > 0) {
            return redirect()->to('/transaksi/barang-masuk')->with('error', 'Transaksi Donasi Masuk tidak dapat dihapus karena sebagian atau seluruh stoknya sudah digunakan pada transaksi Barang Keluar.');
        }

        $db = \Config\Database::connect();

        // --- VERIFICATION LOGGING (BEFORE) ---
        $logData = [
            'id_transaksi' => $id,
            'waktu_delete' => date('Y-m-d H:i:s'),
            'batch_sebelum' => $this->batchModel->where('id_barang_masuk', $id)->findAll(),
            'barang_masuk_sebelum' => $this->barangMasukModel->find($id)
        ];

        $db->transStart();

        // Hapus semua batch terkait dulu
        $this->batchModel->where('id_barang_masuk', $id)->delete();
        $affectedBatch = $db->affectedRows();
        
        // Hapus header transaksi
        $this->barangMasukModel->delete($id);
        $affectedBm = $db->affectedRows();

        $db->transComplete();

        // --- VERIFICATION LOGGING (AFTER) ---
        $logData['affected_batch'] = $affectedBatch;
        $logData['affected_bm'] = $affectedBm;
        $logData['trans_status'] = $db->transStatus();
        $logData['batch_sesudah'] = $db->query("SELECT * FROM batch WHERE id_barang_masuk = {$id}")->getResultArray();
        
        // Cek apakah barang-barang dari transaksi ini masih ada di Monitoring
        $compiledQuery = $db->table('batch')
            ->select('batch.id, batch.id_barang, batch.stok_saat_ini, barang.nama_barang')
            ->join('barang', 'barang.id = batch.id_barang')
            ->where('batch.stok_saat_ini >', 0)
            ->getCompiledSelect();
            
        $monitoringData = $db->query($compiledQuery)->getResultArray();
        $logData['monitoring_sesudah'] = $monitoringData;

        file_put_contents(WRITEPATH . 'logs/delete_ui_log.json', json_encode($logData, JSON_PRETTY_PRINT));
        // --- END LOGGING ---

        if ($db->transStatus() !== false) {
            \App\Libraries\ActivityLogger::log(
                'Hapus Donasi',
                'Donasi Masuk',
                "Menghapus transaksi Donasi Masuk {$barangMasuk['nomor_transaksi']}."
            );
        }

        return redirect()->to('/transaksi/barang-masuk')->with('success', 'Transaksi berhasil dihapus.');
    }

    /**
     * Generate nomor transaksi: DM-YYYYMMDD-XXXX
     */
    private function generateNomorTransaksi(): string
    {
        $today  = date('Ymd');
        $prefix = "DM-{$today}-";

        $last = $this->barangMasukModel
            ->like('nomor_transaksi', $prefix, 'after')
            ->orderBy('id', 'DESC')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last['nomor_transaksi'], -4);
            $newNumber  = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate nomor batch: BT-YYYYMMDD-XXXX
     */
    private function generateNomorBatch(int $index = 0): string
    {
        $today  = date('Ymd');
        $prefix = "BT-{$today}-";

        $last = $this->batchModel
            ->like('nomor_batch', $prefix, 'after')
            ->orderBy('id', 'DESC')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last['nomor_batch'], -4);
            $newNumber  = $lastNumber + 1 + $index;
        } else {
            $newNumber = 1 + $index;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}



