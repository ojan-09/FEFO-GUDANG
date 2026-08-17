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
        $data = [
            'title' => 'Transaksi Donasi Masuk',
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
            'barangList'      => $this->getBarangMasterList(),
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
        $kategoriValid    = array_column($this->kategoriModel->findAll(), 'nama_kategori');
        $satuanValid      = ['Karung', 'Dus', 'Box', 'Kotak', 'Pack', 'Pcs', 'Botol', 'Kaleng', 'Sak', 'Tray', 'Pouch', 'Sachet', 'Renceng', 'Kantong', 'Kardus', 'Repack'];
        $satuanBeratValid = ['Gram', 'Kg', 'ml', 'Liter'];
        $cleanItems = [];
        foreach ($items as $key => $item) {
            $namaBarang         = trim($item['nama_barang'] ?? '');
            $kategori           = trim($item['kategori'] ?? '');
            $jumlahCtn          = trim($item['jumlah_ctn'] ?? '');
            $isiPerCtn          = trim($item['isi_per_ctn'] ?? '');
            $jumlahCtnVal       = ($jumlahCtn !== '' && (int)$jumlahCtn > 0) ? (int)$jumlahCtn : null;
            $isiPerCtnVal       = ($isiPerCtn !== '' && (int)$isiPerCtn > 0) ? (int)$isiPerCtn : null;
            $menggunakanKemasan = ($jumlahCtnVal !== null && $isiPerCtnVal !== null) ? 1 : 0;

            if ($menggunakanKemasan === 1) {
                $jumlah = $jumlahCtnVal * $isiPerCtnVal;
            } else {
                $jumlah = (int) ($item['jumlah'] ?? 0);
            }

            $satuan             = trim($item['satuan'] ?? '');
            $beratPerSatuan     = (float) ($item['berat_per_satuan'] ?? 0);
            $satuanBerat        = trim($item['satuan_berat'] ?? '');
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
            if (!in_array(strtoupper($satuan), ['PCS', 'KOTAK', 'KARUNG', 'DUS', 'BOX', 'PACK', 'BOTOL', 'KALENG', 'TRAY', 'POUCH', 'SACHET', 'RENCENG', 'KANTONG', 'REPACK', 'KG'], true)) {
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
            // FIX: Repack boleh dari Karung atau sudah Repack (edit ulang)
            if ((int) ($item['bisa_dipecah'] ?? 0) === 1 && !in_array(strtolower($satuan), ['karung', 'repack'])) {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Repack (Bisa Dipecah) hanya berlaku untuk kemasan Karung."]);
            }
            $rawNilai = isset($item['nilai_satuan']) ? $item['nilai_satuan'] : '0';
            $rawNilai = str_replace(['Rp', ' ', '.'], '', $rawNilai);
            $rawNilai = str_replace(',', '.', $rawNilai);
            $nilaiSatuan = (float)$rawNilai;
            if ($nilaiSatuan < 0) {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Nilai Satuan tidak boleh negatif."]);
            }
            $cleanItems[] = [
                'nama_barang'         => $namaBarang,
                'kategori'            => $kategori,
                'menggunakan_kemasan' => $menggunakanKemasan,
                'jumlah_ctn'          => $jumlahCtnVal,
                'isi_per_ctn'         => $isiPerCtnVal,
                'jumlah'              => $jumlah,
                'satuan'              => $satuan,
                'berat_per_satuan'    => $beratPerSatuan,
                'satuan_berat'        => $satuanBerat,
                'tanggal_kedaluwarsa' => $tanggalKedaluwarsa,
                'nilai_satuan'        => $nilaiSatuan,
                'bisa_dipecah'        => (int) ($item['bisa_dipecah'] ?? 0),
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
        $kategoriList = $this->kategoriModel->findAll();
        $kategoriMap = [];
        foreach ($kategoriList as $k) {
            $kategoriMap[$k['nama_kategori']] = $k['id'];
        }
        $namaBarangUnik      = array_unique(array_column($cleanItems, 'nama_barang'));
        $namaBarangUnikLower = array_map('strtolower', $namaBarangUnik);
        $barangList = [];
        if (!empty($namaBarangUnikLower)) {
            $barangList = $barangModel->select('id, nama_barang, bisa_dipecah, satuan')
                ->whereIn('nama_barang', $namaBarangUnikLower)
                ->findAll();
        }
        $barangMap = [];
        foreach ($barangList as $b) {
            $key = strtolower($b['nama_barang']) . '_' . (int)$b['bisa_dipecah'];
            $barangMap[$key] = [
                'id'           => $b['id'],
                'bisa_dipecah' => (int) $b['bisa_dipecah']
            ];
        }
        $today      = date('Ymd');
        $prefix     = "BT-{$today}-";
        $lastBatch  = $this->batchModel->like('nomor_batch', $prefix, 'after')->orderBy('id', 'DESC')->first();
        $lastNumber = $lastBatch ? (int) substr($lastBatch['nomor_batch'], -4) : 0;
        $maxBrgRow = $db->table('barang')
            ->select('MAX(CAST(SUBSTRING(kode_barang, 5) AS UNSIGNED)) as max_num')
            ->where('kode_barang LIKE', 'BRG-%')
            ->get()->getRowArray();
        $lastBrgNumber = $maxBrgRow ? (int)$maxBrgRow['max_num'] : 0;
        $batchInsertData = [];
        foreach ($cleanItems as $item) {
            $namaBarangKey = strtolower($item['nama_barang']) . '_' . (int)$item['bisa_dipecah'];
            $idKategori    = $kategoriMap[$item['kategori']] ?? 1;
            if (isset($barangMap[$namaBarangKey])) {
                $idBarang = $barangMap[$namaBarangKey]['id'];
                $bisaDipecah = (int)$item['bisa_dipecah'];
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
                    'bisa_dipecah'     => $item['bisa_dipecah'],
                ]);
                $idBarang    = $barangModel->getInsertID();
                $bisaDipecah = $item['bisa_dipecah'];
                $barangMap[$namaBarangKey] = [
                    'id'           => $idBarang,
                    'bisa_dipecah' => $bisaDipecah
                ];
            }
            $lastNumber++;
            $nomorBatch = $prefix . str_pad($lastNumber, 4, '0', STR_PAD_LEFT);
            if ($bisaDipecah === 1) {
                $berat   = (float)$item['berat_per_satuan'];
                $satuanB = strtolower($item['satuan_berat']);
                $qty     = (float)$item['jumlah'];
                // FIX blok 1: Repack & Kg keduanya langsung pakai qty
                if (in_array(strtolower($item['satuan']), ['kg', 'repack'])) {
                    $totalKg = $qty;
                } else {
                    $totalKg = ($satuanB === 'gram') ? (($qty * $berat) / 1000) : ($qty * $berat);
                }
                $jumlahAwal  = $totalKg;
                $stokSaatIni = $totalKg;
                $jumlahCtn   = $item['jumlah_ctn'] !== null ? (int)$item['jumlah_ctn'] : null;
            } else {
                $jumlahAwal  = $item['jumlah'];
                $stokSaatIni = $item['jumlah'];
                $jumlahCtn   = $item['jumlah_ctn'] !== null ? (int)$item['jumlah_ctn'] : null;
            }
            $batchInsertData[] = [
                'id_barang_masuk'     => $idBarangMasuk,
                'id_barang'           => $idBarang,
                'nomor_batch'         => $nomorBatch,
                'nama_barang'         => $item['nama_barang'],
                'kategori'            => $item['kategori'],
                'tanggal_masuk'       => $tanggalMasuk,
                'tanggal_kedaluwarsa' => $item['tanggal_kedaluwarsa'],
                'jumlah_awal'         => $jumlahAwal,
                'stok_saat_ini'       => $stokSaatIni,
                'menggunakan_kemasan' => $item['menggunakan_kemasan'],
                'jumlah_ctn'          => $item['jumlah_ctn'],
                'isi_per_ctn'         => $item['isi_per_ctn'],
                'satuan'              => $item['satuan'],
                'berat_per_satuan'    => $item['berat_per_satuan'],
                'satuan_berat'        => $item['satuan_berat'],
                'nilai_satuan'        => $item['nilai_satuan'],
                'status'              => 'Aktif',
                'bisa_dipecah'        => $bisaDipecah,
            ];
        }
        if (!empty($batchInsertData)) {
            $this->batchModel->insertBatch($batchInsertData);
        }
        $db->transComplete();
        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('errors', ['db' => 'Gagal menyimpan transaksi. Silakan coba lagi.']);
        }
        $id_donatur = $this->request->getPost('id_donatur');
        $donaturModel = new DonaturModel();
        $donatur = $donaturModel->find($id_donatur);
        $namaDonatur = $donatur ? $donatur['nama_donatur'] : '-';
        $totalItem = array_sum(array_column($cleanItems, 'jumlah'));

        \App\Libraries\ActivityLogger::log(
            'Tambah Donasi',
            'Donasi Masuk',
            "Menambahkan Donasi Masuk\nNo. {$nomorTransaksi}\nDonatur : {$namaDonatur}\nTotal Item : {$totalItem}"
        );
        $this->clearDashboardCache();
        helper('format'); clear_dashboard_cache();
        return redirect()->to('/transaksi/barang-masuk')->with('success', 'Transaksi Barang Masuk berhasil disimpan.');
    }
    /**
     * Form edit Donasi Masuk
     */
    public function edit($id)
    {
        $barangMasuk = $this->barangMasukModel->find($id);
        if (!$barangMasuk) {
            return redirect()->to('/transaksi/barang-masuk')->with('error', 'Transaksi tidak ditemukan atau sudah dihapus.');
        }
        $batchTerpakai = $this->batchModel
            ->where('id_barang_masuk', $id)
            ->where('stok_saat_ini < jumlah_awal')
            ->countAllResults();
        if ($batchTerpakai > 0) {
            return redirect()->to('/transaksi/barang-masuk')->with('error', 'Transaksi tidak dapat diubah karena sebagian atau seluruh stok dari donasi ini sudah digunakan pada proses Penyaluran Barang atau Penyesuaian Stok.');
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
            'barangList'      => $this->getBarangMasterList(),
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
            return redirect()->to('/transaksi/barang-masuk')->with('error', 'Transaksi tidak ditemukan atau sudah dihapus.');
        }
        $batchTerpakai = $this->batchModel
            ->where('id_barang_masuk', $id)
            ->where('stok_saat_ini < jumlah_awal')
            ->countAllResults();
        if ($batchTerpakai > 0) {
            return redirect()->to('/transaksi/barang-masuk')->with('error', 'Transaksi tidak dapat diubah karena sebagian atau seluruh stok dari donasi ini sudah digunakan pada proses Penyaluran Barang atau Penyesuaian Stok.');
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
        $kategoriValid    = array_column($this->kategoriModel->findAll(), 'nama_kategori');
        $satuanValid      = ['Karung', 'Dus', 'Box', 'Kotak', 'Pack', 'Pcs', 'Botol', 'Kaleng', 'Sak', 'Tray', 'Pouch', 'Sachet', 'Renceng', 'Kantong', 'Kardus', 'Repack'];
        $satuanBeratValid = ['Gram', 'Kg', 'ml', 'Liter'];
        $cleanItems = [];
        foreach ($items as $key => $item) {
            $namaBarang         = trim($item['nama_barang'] ?? '');
            $kategori           = trim($item['kategori'] ?? '');
            $jumlahCtn          = trim($item['jumlah_ctn'] ?? '');
            $jumlah             = (int) ($item['jumlah'] ?? 0);
            $satuan             = trim($item['satuan'] ?? '');
            $beratPerSatuan     = (float) ($item['berat_per_satuan'] ?? 0);
            $satuanBerat        = trim($item['satuan_berat'] ?? '');
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
            if ((int) ($item['bisa_dipecah'] ?? 0) === 1 && !in_array(strtolower($satuan), ['karung', 'repack'])) {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Repack (Bisa Dipecah) hanya berlaku untuk kemasan Karung."]);
            }
            $rawNilai = isset($item['nilai_satuan']) ? $item['nilai_satuan'] : '0';
            $rawNilai = str_replace(['Rp', ' ', '.'], '', $rawNilai);
            $rawNilai = str_replace(',', '.', $rawNilai);
            $nilaiSatuan = (float)$rawNilai;
            if ($nilaiSatuan < 0) {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Nilai Satuan tidak boleh negatif."]);
            }
            $isiPerCtn          = trim($item['isi_per_ctn'] ?? '');
            $jumlahCtnVal       = ($jumlahCtn !== '' && is_numeric($jumlahCtn)) ? (int)$jumlahCtn : null;
            $isiPerCtnVal       = ($isiPerCtn !== '' && is_numeric($isiPerCtn)) ? (int)$isiPerCtn : null;
            $menggunakanKemasan = ($jumlahCtnVal !== null && $isiPerCtnVal !== null && $jumlahCtnVal > 0 && $isiPerCtnVal > 0) ? 1 : 0;

            if ($menggunakanKemasan === 1) {
                $jumlah = $jumlahCtnVal * $isiPerCtnVal;
            }

            $idBatch = trim($item['id'] ?? '');
            $cleanItems[] = [
                'id'                  => $idBatch === '' ? null : (int) $idBatch,
                'nama_barang'         => $namaBarang,
                'kategori'            => $kategori,
                'menggunakan_kemasan' => $menggunakanKemasan,
                'jumlah_ctn'          => $jumlahCtnVal,
                'isi_per_ctn'         => $isiPerCtnVal,
                'jumlah'              => $jumlah,
                'satuan'              => $satuan,
                'berat_per_satuan'    => $beratPerSatuan,
                'satuan_berat'        => $satuanBerat,
                'tanggal_kedaluwarsa' => $tanggalKedaluwarsa,
                'nilai_satuan'        => $nilaiSatuan,
                'bisa_dipecah'        => (int) ($item['bisa_dipecah'] ?? 0),
            ];
        }
        $db = \Config\Database::connect();
        $db->transStart();
        $this->barangMasukModel->update($id, [
            'id_donatur'    => $this->request->getPost('id_donatur'),
            'tanggal_masuk' => $tanggalMasuk,
            'eta'           => $this->request->getPost('eta') ?: null,
            'keterangan'    => $this->request->getPost('keterangan'),
        ]);
        $barangModel = new BarangModel();
        $existingBatches = $this->batchModel->where('id_barang_masuk', $id)->findAll();
        $existingBatchMap = [];
        foreach ($existingBatches as $eb) {
            $existingBatchMap[$eb['id']] = $eb;
        }
        $submittedIds   = array_filter(array_column($cleanItems, 'id'));
        $deletedBatchIds = array_diff(array_keys($existingBatchMap), $submittedIds);
        foreach ($deletedBatchIds as $dbId) {
            $eb = $existingBatchMap[$dbId];
            if ($eb['stok_saat_ini'] < $eb['jumlah_awal']) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('errors', ['items' => "Barang '{$eb['nama_barang']}' tidak dapat dihapus karena stoknya sudah digunakan pada proses Penyaluran atau Penyesuaian Stok."]);
            }
            $this->batchModel->delete($dbId);
        }
        $kategoriList = $this->kategoriModel->findAll();
        $kategoriMap  = [];
        foreach ($kategoriList as $k) {
            $kategoriMap[$k['nama_kategori']] = $k['id'];
        }
        $namaBarangUnik      = array_unique(array_column($cleanItems, 'nama_barang'));
        $namaBarangUnikLower = array_map('strtolower', $namaBarangUnik);
        $barangList = [];
        if (!empty($namaBarangUnikLower)) {
            $barangList = $barangModel->select('id, nama_barang, bisa_dipecah, satuan')
                ->whereIn('nama_barang', $namaBarangUnikLower)
                ->findAll();
        }
        $barangMap  = [];
        foreach ($barangList as $b) {
            $key = strtolower($b['nama_barang']) . '_' . (int)$b['bisa_dipecah'];
            $barangMap[$key] = [
                'id'           => $b['id'],
                'bisa_dipecah' => (int) $b['bisa_dipecah']
            ];
        }
        $today      = date('Ymd');
        $prefix     = "BT-{$today}-";
        $lastBatch  = $this->batchModel->like('nomor_batch', $prefix, 'after')->orderBy('id', 'DESC')->first();
        $lastNumber = $lastBatch ? (int) substr($lastBatch['nomor_batch'], -4) : 0;
        $maxBrgRow = $db->table('barang')
            ->select('MAX(CAST(SUBSTRING(kode_barang, 5) AS UNSIGNED)) as max_num')
            ->where('kode_barang LIKE', 'BRG-%')
            ->get()->getRowArray();
        $lastBrgNumber = $maxBrgRow ? (int)$maxBrgRow['max_num'] : 0;
        foreach ($cleanItems as $item) {
            $namaBarangKey = strtolower($item['nama_barang']) . '_' . (int)$item['bisa_dipecah'];
            $idKategori    = $kategoriMap[$item['kategori']] ?? 1;
            if (isset($barangMap[$namaBarangKey])) {
                $idBarang = $barangMap[$namaBarangKey]['id'];
                $bisaDipecah = (int)$item['bisa_dipecah'];
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
                    'bisa_dipecah'     => $item['bisa_dipecah'],
                ]);
                $idBarang    = $barangModel->getInsertID();
                $bisaDipecah = $item['bisa_dipecah'];
                $barangMap[$namaBarangKey] = [
                    'id'           => $idBarang,
                    'bisa_dipecah' => $bisaDipecah
                ];
            }
            if (!empty($item['id']) && isset($existingBatchMap[$item['id']])) {
                $eb = $existingBatchMap[$item['id']];
                if ((int)$eb['id_barang'] !== (int)$idBarang) {
                    if ($eb['stok_saat_ini'] < $eb['jumlah_awal']) {
                        $db->transRollback();
                        return redirect()->back()->withInput()->with('errors', ['items' => "Barang '{$eb['nama_barang']}' tidak dapat diganti ke barang lain karena sebagian stoknya sudah digunakan."]);
                    }
                    if ($bisaDipecah === 1) {
                        $berat   = (float)$item['berat_per_satuan'];
                        $satuanB = strtolower($item['satuan_berat']);
                        $qty     = (float)$item['jumlah'];
                        if (in_array(strtolower($item['satuan']), ['kg', 'repack'])) {
                            $newQty = $qty;
                        } else {
                            $newQty = ($satuanB === 'gram') ? (($qty * $berat) / 1000) : ($qty * $berat);
                        }
                        $jumlahCtn = $item['jumlah_ctn'] !== null ? (int)$item['jumlah_ctn'] : null;
                    } else {
                        $newQty    = $item['jumlah'];
                        $jumlahCtn = $item['jumlah_ctn'] !== null ? (int)$item['jumlah_ctn'] : null;
                    }
                    $this->batchModel->update($eb['id'], [
                        'id_barang'           => $idBarang,
                        'nama_barang'         => $item['nama_barang'],
                        'kategori'            => $item['kategori'],
                        'tanggal_masuk'       => $tanggalMasuk,
                        'tanggal_kedaluwarsa' => $item['tanggal_kedaluwarsa'],
                        'jumlah_awal'         => $newQty,
                        'stok_saat_ini'       => $newQty,
                        'menggunakan_kemasan' => $item['menggunakan_kemasan'],
                        'jumlah_ctn'          => $item['jumlah_ctn'],
                        'isi_per_ctn'         => $item['isi_per_ctn'],
                        'satuan'              => $item['satuan'],
                        'berat_per_satuan'    => $item['berat_per_satuan'],
                        'satuan_berat'        => $item['satuan_berat'],
                        'nilai_satuan'        => $item['nilai_satuan'],
                        'bisa_dipecah'        => $bisaDipecah,
                    ]);
                } else {
                    if ($bisaDipecah === 1) {
                        $berat   = (float)$item['berat_per_satuan'];
                        $satuanB = strtolower($item['satuan_berat']);
                        $qty     = (float)$item['jumlah'];
                        if (in_array(strtolower($item['satuan']), ['kg', 'repack'])) {
                            $submittedQty = $qty;
                        } else {
                            $submittedQty = ($satuanB === 'gram') ? (($qty * $berat) / 1000) : ($qty * $berat);
                        }
                    } else {
                        $submittedQty = $item['jumlah'];
                    }
                    $delta    = $submittedQty - $eb['jumlah_awal'];
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
                        'jumlah_awal'         => $submittedQty,
                        'stok_saat_ini'       => $stokBaru,
                        'menggunakan_kemasan' => $item['menggunakan_kemasan'],
                        'jumlah_ctn'          => $item['jumlah_ctn'],
                        'isi_per_ctn'         => $item['isi_per_ctn'],
                        'satuan'              => $item['satuan'],
                        'berat_per_satuan'    => $item['berat_per_satuan'],
                        'satuan_berat'        => $item['satuan_berat'],
                        'nilai_satuan'        => $item['nilai_satuan'],
                        'bisa_dipecah'        => $bisaDipecah,
                    ]);
                }
            } else {
                $lastNumber++;
                $nomorBatch = $prefix . str_pad($lastNumber, 4, '0', STR_PAD_LEFT);
                if ($bisaDipecah === 1) {
                    $berat   = (float)$item['berat_per_satuan'];
                    $satuanB = strtolower($item['satuan_berat']);
                    $qty     = (float)$item['jumlah'];
                    if (in_array(strtolower($item['satuan']), ['kg', 'repack'])) {
                        $newQty = $qty;
                    } else {
                        $newQty = ($satuanB === 'gram') ? (($qty * $berat) / 1000) : ($qty * $berat);
                    }
                } else {
                    $newQty = $item['jumlah'];
                }
                $this->batchModel->insert([
                    'id_barang_masuk'     => $id,
                    'id_barang'           => $idBarang,
                    'nomor_batch'         => $nomorBatch,
                    'nama_barang'         => $item['nama_barang'],
                    'kategori'            => $item['kategori'],
                    'tanggal_masuk'       => $tanggalMasuk,
                    'tanggal_kedaluwarsa' => $item['tanggal_kedaluwarsa'],
                    'jumlah_awal'         => $newQty,
                    'stok_saat_ini'       => $newQty,
                    'menggunakan_kemasan' => $item['menggunakan_kemasan'],
                    'jumlah_ctn'          => $item['jumlah_ctn'],
                    'isi_per_ctn'         => $item['isi_per_ctn'],
                    'satuan'              => $item['satuan'],
                    'berat_per_satuan'    => $item['berat_per_satuan'],
                    'satuan_berat'        => $item['satuan_berat'],
                    'nilai_satuan'        => $item['nilai_satuan'],
                    'status'              => 'Aktif',
                    'bisa_dipecah'        => $bisaDipecah,
                ]);
            }
        }
        $db->transComplete();
        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('errors', ['db' => 'Gagal memperbarui transaksi. Silakan coba lagi.']);
        }
        $id_donatur = $this->request->getPost('id_donatur');
        $donaturModel = new DonaturModel();
        $donatur = $donaturModel->find($id_donatur);
        $namaDonatur = $donatur ? $donatur['nama_donatur'] : '-';
        $totalItem = array_sum(array_column($cleanItems, 'jumlah'));

        \App\Libraries\ActivityLogger::log(
            'Edit Donasi',
            'Donasi Masuk',
            "Mengubah Donasi Masuk\nNo. {$barangMasuk['nomor_transaksi']}\nDonatur : {$namaDonatur}\nTotal Item : {$totalItem}"
        );
        $this->clearDashboardCache();
        helper('format'); clear_dashboard_cache();
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
            return redirect()->to('/transaksi/barang-masuk')->with('error', 'Transaksi tidak ditemukan atau sudah dihapus.');
        }
        $batches = $this->batchModel
            ->select('batch.*, COALESCE(batch.nama_barang, barang.nama_barang) AS nama_barang, COALESCE(batch.satuan, barang.satuan) AS satuan, COALESCE(batch.berat_per_satuan, barang.berat_per_satuan) AS berat_per_satuan, COALESCE(batch.satuan_berat, barang.satuan_berat) AS satuan_berat')
            ->join('barang', 'barang.id = batch.id_barang', 'left')
            ->where('batch.id_barang_masuk', $id)
            ->findAll();

        foreach ($batches as &$b) {
            if ((int)($b['bisa_dipecah'] ?? 0) === 1) {
                $b['satuan'] = $b['satuan'] . ' (Repack)';
            }
        }
        unset($b);

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
        if (!in_groups(['Administrator', 'Petugas Gudang'])) {
            return view('errors/html/error_403');
        }
        $barangMasuk = $this->barangMasukModel->find($id);
        if (!$barangMasuk) {
            return redirect()->to(site_url('transaksi/barang-masuk'))->with('error', 'Transaksi tidak ditemukan atau sudah dihapus.');
        }
        $batchTerpakai = $this->batchModel
            ->where('id_barang_masuk', $id)
            ->where('stok_saat_ini < jumlah_awal')
            ->countAllResults();
        if ($batchTerpakai > 0) {
            return redirect()->to(site_url('transaksi/barang-masuk'))->with('error', 'Transaksi Donasi Masuk tidak dapat dihapus karena sebagian atau seluruh stoknya sudah digunakan pada transaksi Barang Keluar atau Penyesuaian Stok.');
        }
        $db = \Config\Database::connect();
        $db->transStart();
        $this->batchModel->where('id_barang_masuk', $id)->delete();
        $this->barangMasukModel->delete($id);
        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to(site_url('transaksi/barang-masuk'))->with('error', 'Gagal menghapus transaksi. Silakan coba lagi.');
        }
        \App\Libraries\ActivityLogger::log(
            'Hapus Donasi',
            'Donasi Masuk',
            "Menghapus Donasi Masuk\nNo. {$barangMasuk['nomor_transaksi']}"
        );
        $this->clearDashboardCache();
        helper('format'); clear_dashboard_cache();
        return redirect()->to(site_url('transaksi/barang-masuk'))->with('success', 'Transaksi berhasil dihapus.');
    }
    private function clearDashboardCache()
    {
        cache()->delete('dashboard_total_jenis_barang');
        cache()->delete('dashboard_total_batch');
        cache()->delete('dashboard_total_berat');
        cache()->delete('dashboard_top_barang');
        cache()->delete('dashboard_kategori');
        cache()->delete('dashboard_grafik_12bln');
    }

    /**
     * Daftar barang master untuk autocomplete di form Donasi Masuk.
     */
    private function getBarangMasterList(): array
    {
        $barangModel = new BarangModel();
        return $barangModel
            ->select('barang.nama_barang, kategori.nama_kategori as kategori, barang.satuan, barang.berat_per_satuan, barang.satuan_berat, barang.bisa_dipecah')
            ->join('kategori', 'kategori.id = barang.id_kategori')
            ->where('barang.status', 'active')
            ->groupBy('barang.nama_barang')
            ->orderBy('barang.nama_barang', 'ASC')
            ->findAll();
    }
    /**
     * Generate nomor transaksi: DM-YYYYMMDD-XXXX
     */
    private function generateNomorTransaksi(): string
    {
        $today  = date('Ymd');
        $prefix = "DM-{$today}-";
        $last   = $this->barangMasukModel
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
     * AJAX endpoint untuk DataTables server-side
     */
    public function ajaxData()
    {
        if (!$this->request->isAJAX()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $postData = $this->request->getPost();
        $list     = $this->barangMasukModel->getDatatables($postData);
        $data     = [];
        $no       = $postData['start'];

        foreach ($list as $bm) {
            $no++;
            $row = [];

            // 0: No
            $row[] = '<div class="text-center text-secondary">' . $no . '</div>';
            
            // 1: Nomor Transaksi
            $row[] = '<span class="badge-notrx">' . esc($bm['nomor_transaksi']) . '</span>';
            
            // 2: Donatur
            $row[] = '<span class="fw-semibold" style="color:#0f172a;">' . esc($bm['nama_donatur']) . '</span>';
            
            // 3: Item
            $row[] = '<div class="text-center"><span class="badge-item">' . esc($bm['jumlah_item']) . ' Item</span></div>';
            
            // 4: Tanggal
            $row[] = '<span style="color:#475569;">' . date('d M Y', strtotime($bm['tanggal_masuk'])) . '</span>';
            
            // 5: Petugas
            $row[] = '<span style="color:#475569;">' . esc($bm['petugas']) . '</span>';
            
            // 6: Aksi
            $aksi = '<div class="dm-action-group">
                        <a href="' . site_url('transaksi/barang-masuk/detail/' . $bm['id']) . '" class="dm-btn-action view" title="Lihat Detail"><i class="fa-solid fa-eye"></i></a>';
            
            if ($bm['is_used']) {
                $aksi .= '<button type="button" class="dm-btn-action lock" disabled title="Sudah Digunakan"><i class="fa-solid fa-lock"></i></button>';
            } else {
                $aksi .= '<a href="' . site_url('transaksi/barang-masuk/edit/' . $bm['id']) . '" class="dm-btn-action edit" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                          <form action="' . site_url('transaksi/barang-masuk/delete/' . $bm['id']) . '" method="POST" class="d-inline form-delete-swal" data-confirm-text="Yakin ingin menghapus transaksi ini beserta semua batch-nya?">
                              ' . csrf_field() . '
                              <button type="submit" class="dm-btn-action del" title="Hapus" style="border:none; cursor:pointer;"><i class="fa-solid fa-trash"></i></button>
                          </form>';
            }
            $aksi .= '</div>';
            
            $row[] = $aksi;
            $data[] = $row;
        }

        $output = [
            "draw"            => isset($postData['draw']) ? intval($postData['draw']) : 0,
            "recordsTotal"    => $this->barangMasukModel->countAllData(),
            "recordsFiltered" => $this->barangMasukModel->countFiltered($postData),
            "data"            => $data,
            csrf_token()      => csrf_hash() // Include new CSRF token for next request
        ];

        return $this->response->setJSON($output);
    }
    
}

