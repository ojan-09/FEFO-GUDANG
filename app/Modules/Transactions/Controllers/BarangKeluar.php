<?php

namespace App\Modules\Transactions\Controllers;

use App\Controllers\BaseController;
use App\Modules\Transactions\Models\BarangKeluarModel;
use App\Modules\Transactions\Models\DetailBarangKeluarModel;
use App\Modules\Transactions\Models\BatchModel;
use App\Modules\MasterData\Models\BarangModel;
use App\Modules\MasterData\Models\WilayahModel;
use App\Libraries\FEFO;

class BarangKeluar extends BaseController
{
    protected $barangKeluarModel;
    protected $detailModel;
    protected $batchModel;
    protected $barangModel;
    protected $wilayahModel;
    protected $fefo;

    public function __construct()
    {
        $this->barangKeluarModel = new BarangKeluarModel();
        $this->detailModel       = new DetailBarangKeluarModel();
        $this->batchModel        = new BatchModel();
        $this->barangModel       = new BarangModel();
        $this->wilayahModel      = new WilayahModel();
        $this->fefo              = new FEFO();
    }

    /**
     * Daftar transaksi Barang Keluar
     */
    public function index()
    {
        $barangKeluar = $this->barangKeluarModel
            ->select('barang_keluar.*, wilayah.nama_wilayah, users.username as petugas')
            ->join('wilayah', 'wilayah.id = barang_keluar.id_wilayah', 'left')
            ->join('users', 'users.id = barang_keluar.id_user')
            ->orderBy('barang_keluar.id', 'DESC')
            ->findAll();

        $bkIds = array_column($barangKeluar, 'id');
        $detailStats = [];

        if (!empty($bkIds)) {
            $stats = $this->detailModel
                ->select('id_barang_keluar, COUNT(id) as total_item')
                ->whereIn('id_barang_keluar', $bkIds)
                ->groupBy('id_barang_keluar')
                ->findAll();
                
            foreach ($stats as $stat) {
                $detailStats[$stat['id_barang_keluar']] = $stat['total_item'];
            }
        }

        // Hitung jumlah item per transaksi
        foreach ($barangKeluar as &$bk) {
            $bk['jumlah_item'] = $detailStats[$bk['id']] ?? 0;
        }

        $data = [
            'title'        => 'Transaksi Barang Keluar',
            'barangKeluar' => $barangKeluar,
        ];
        return view('App\Modules\Transactions\Views\barang_keluar\index', $data);
    }

    /**
     * Form tambah Barang Keluar
     */
    public function create()
    {
        // Ambil daftar barang yang benar-benar memiliki stok di gudang (Batch)
        $db = \Config\Database::connect();
        $builder = $db->table('batch');
        $builder->select('barang.id, COALESCE(MAX(batch.nama_barang), barang.nama_barang) as nama_barang, COALESCE(MAX(batch.satuan), barang.satuan) as satuan, COALESCE(MAX(batch.berat_per_satuan), barang.berat_per_satuan) as berat_per_satuan, COALESCE(MAX(batch.satuan_berat), barang.satuan_berat) as satuan_berat, SUM(batch.stok_saat_ini) as stok_tersedia');
        $builder->join('barang', 'barang.id = batch.id_barang');
        $builder->where('batch.stok_saat_ini >', 0);
        $builder->where('batch.status', 'Aktif');
        $builder->where('batch.tanggal_kedaluwarsa >=', date('Y-m-d'));
        $builder->groupBy('barang.id');
        $builder->orderBy('barang.nama_barang', 'ASC');
        $semuaBarang = $builder->get()->getResultArray();

        $data = [
            'title'           => 'Tambah Barang Keluar',
            'nomor_transaksi' => $this->generateNomorTransaksi(),
            'barang'          => $semuaBarang,
            'wilayah'         => $this->wilayahModel->where('status', 'Aktif')->orderBy('nama_wilayah', 'ASC')->findAll(),
        ];
        return view('App\Modules\Transactions\Views\barang_keluar\form', $data);
    }

    /**
     * Simpan transaksi + panggil Library FEFO
     */
    public function store()
    {
        $rules = [
            'tanggal_keluar'    => 'required|valid_date',
            'id_wilayah'        => 'required|integer',
            'tujuan_penyaluran' => 'required|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $items = $this->request->getPost('items');
        if (empty($items) || !is_array($items)) {
            return redirect()->back()->withInput()->with('errors', ['items' => 'Minimal harus ada 1 barang.']);
        }

        // ============================================
        // VALIDASI 0: Cegah Duplikasi Barang
        // ============================================
        $itemIds = array_column($items, 'id_barang');
        if (count($itemIds) !== count(array_unique($itemIds))) {
            return redirect()->back()->withInput()->with('errors', ['items' => 'Barang yang sama tidak boleh dipilih lebih dari satu kali dalam satu transaksi.']);
        }

        // ============================================
        // VALIDASI 1: Validasi setiap item satu per satu
        // ============================================
        foreach ($items as $key => $item) {
            if (empty($item['id_barang']) || !is_numeric($item['id_barang'])) {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Barang wajib dipilih."]);
            }
            if (empty($item['jumlah_keluar']) || (int)$item['jumlah_keluar'] <= 0) {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Jumlah keluar harus lebih besar dari 0."]);
            }
        }

        // ============================================
        // VALIDASI 2: Pencegahan barang ganda
        // Konsolidasi jumlah barang yang sama
        // ============================================
        $consolidated = [];
        foreach ($items as $item) {
            $idBrg = $item['id_barang'];
            if (isset($consolidated[$idBrg])) {
                $consolidated[$idBrg]['jumlah_keluar'] += (int)$item['jumlah_keluar'];
            } else {
                $consolidated[$idBrg] = [
                    'id_barang'     => $item['id_barang'],
                    'jumlah_keluar' => (int)$item['jumlah_keluar'],
                ];
            }
        }
        $items = array_values($consolidated);

        // Validasi stok setelah konsolidasi (Backend validation)
        $kebutuhan = [];
        foreach ($items as $item) {
            $kebutuhan[$item['id_barang']] = (int) $item['jumlah_keluar'];
        }

        $kurangStok = $this->fefo->cekKetersediaanBulk($kebutuhan);
        if (!empty($kurangStok)) {
            $errorMsgs = [];
            foreach ($kurangStok as $idBarang) {
                $brg = $this->barangModel->find($idBarang);
                $namaBarang = $brg ? $brg['nama_barang'] : 'Barang';
                $stokAda = $this->fefo->getStokBarang($idBarang);
                $errorMsgs[] = "Stok {$namaBarang} tidak mencukupi. Tersedia: {$stokAda}, diminta: {$kebutuhan[$idBarang]}.";
            }
            return redirect()->back()->withInput()->with('errors', ['stok' => implode('<br>', $errorMsgs)]);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Simpan Header Barang Keluar
        $nomorTransaksi = $this->generateNomorTransaksi();

        $this->barangKeluarModel->insert([
            'nomor_transaksi'   => $nomorTransaksi,
            'id_user'           => user()->id,
            'id_wilayah'        => $this->request->getPost('id_wilayah'),
            'tanggal_keluar'    => $this->request->getPost('tanggal_keluar'),
            'tujuan_penyaluran' => $this->request->getPost('tujuan_penyaluran'),
            'keterangan'        => $this->request->getPost('keterangan'),
        ]);

        $idBarangKeluar = $this->barangKeluarModel->getInsertID();

        // 2. Jalankan Library FEFO (Bulk mode)
        $hasilFEFO = $this->fefo->prosesBarangKeluarBulk($idBarangKeluar, $items);
        if ($hasilFEFO === false) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('errors', ['fefo' => 'Proses FEFO gagal. Stok tidak mencukupi atau terjadi kesalahan saat pemotongan batch.']);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('errors', ['db' => 'Gagal menyimpan transaksi. Silakan coba lagi.']);
        }

        \App\Libraries\ActivityLogger::log(
            'Tambah Penyaluran',
            'Penyaluran Barang',
            "Menambahkan transaksi Penyaluran {$nomorTransaksi}."
        );

        return redirect()->to('/transaksi/barang-keluar')->with('success', 'Transaksi Barang Keluar berhasil disimpan. Stok batch telah dipotong menggunakan metode FEFO.');
    }

    /**
     * Form edit Barang Keluar
     */
    public function edit($id)
    {
        $barangKeluar = $this->barangKeluarModel->find($id);
        if (!$barangKeluar) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Transaksi tidak ditemukan.');
        }

        // Ambil daftar barang yang benar-benar memiliki stok di gudang (Batch)
        $db = \Config\Database::connect();
        $builder = $db->table('batch');
        $builder->select('barang.id, COALESCE(MAX(batch.nama_barang), barang.nama_barang) as nama_barang, COALESCE(MAX(batch.satuan), barang.satuan) as satuan, COALESCE(MAX(batch.berat_per_satuan), barang.berat_per_satuan) as berat_per_satuan, COALESCE(MAX(batch.satuan_berat), barang.satuan_berat) as satuan_berat, SUM(batch.stok_saat_ini) as stok_tersedia');
        $builder->join('barang', 'barang.id = batch.id_barang');
        $builder->where('batch.stok_saat_ini >', 0);
        $builder->where('batch.status', 'Aktif');
        $builder->where('batch.tanggal_kedaluwarsa >=', date('Y-m-d'));
        $builder->groupBy('barang.id');
        $builder->orderBy('barang.nama_barang', 'ASC');
        $semuaBarang = $builder->get()->getResultArray();

        // Ambil items lama
        $details = $this->detailModel->where('id_barang_keluar', $id)->findAll();
        // Konsolidasi items lama untuk form edit (karena 1 barang bisa jadi >1 detail jika dipotong FEFO ke multi batch)
        $consolidatedDetails = [];
        $addedStok = [];

        foreach ($details as $d) {
            $batchInfo = $this->batchModel->find($d['id_batch']);
            if ($batchInfo) {
                $idBrg = $batchInfo['id_barang'];
                if (isset($consolidatedDetails[$idBrg])) {
                    $consolidatedDetails[$idBrg]['jumlah_keluar'] += $d['jumlah_keluar'];
                } else {
                    $consolidatedDetails[$idBrg] = [
                        'id_barang' => $idBrg,
                        'jumlah_keluar' => $d['jumlah_keluar'],
                        'satuan' => $batchInfo['satuan'] ?: 'Pcs',
                        'berat_per_satuan' => $batchInfo['berat_per_satuan'],
                        'satuan_berat' => $batchInfo['satuan_berat'],
                        'nama_barang' => $batchInfo['nama_barang']
                    ];
                }
                
                if (!isset($addedStok[$idBrg])) {
                    $addedStok[$idBrg] = 0;
                }
                $addedStok[$idBrg] += $d['jumlah_keluar'];
            }
        }

        // Kembalikan stok sementara ke $semuaBarang agar field form menampilkan stok asli saat transaksi
        $existingBarangIds = [];
        foreach ($semuaBarang as &$b) {
            $existingBarangIds[] = $b['id'];
            if (isset($addedStok[$b['id']])) {
                $b['stok_tersedia'] += $addedStok[$b['id']];
            }
        }

        // Jika ada barang yang stok_saat_ini-nya 0 (sehingga tidak masuk di query $semuaBarang), tambahkan secara manual
        foreach ($consolidatedDetails as $idBrg => $det) {
            if (!in_array($idBrg, $existingBarangIds)) {
                $semuaBarang[] = [
                    'id' => $idBrg,
                    'nama_barang' => $det['nama_barang'],
                    'satuan' => $det['satuan'],
                    'berat_per_satuan' => $det['berat_per_satuan'],
                    'satuan_berat' => $det['satuan_berat'],
                    'stok_tersedia' => $addedStok[$idBrg]
                ];
            }
        }

        $data = [
            'title'           => 'Edit Barang Keluar',
            'barangKeluar'    => $barangKeluar,
            'nomor_transaksi' => $barangKeluar['nomor_transaksi'],
            'details'         => array_values($consolidatedDetails),
            'barang'          => $semuaBarang,
            'wilayah'         => $this->wilayahModel->where('status', 'Aktif')->orderBy('nama_wilayah', 'ASC')->findAll(),
        ];
        return view('App\Modules\Transactions\Views\barang_keluar\form', $data);
    }

    /**
     * Proses update transaksi + panggil ulang Library FEFO
     */
    public function update($id)
    {
        $barangKeluar = $this->barangKeluarModel->find($id);
        if (!$barangKeluar) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Transaksi tidak ditemukan.');
        }

        $rules = [
            'tanggal_keluar'    => 'required|valid_date',
            'id_wilayah'        => 'required|integer',
            'tujuan_penyaluran' => 'required|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $items = $this->request->getPost('items');
        if (empty($items) || !is_array($items)) {
            return redirect()->back()->withInput()->with('errors', ['items' => 'Minimal harus ada 1 barang.']);
        }

        // ============================================
        // VALIDASI 0: Cegah Duplikasi Barang
        // ============================================
        $itemIds = array_column($items, 'id_barang');
        if (count($itemIds) !== count(array_unique($itemIds))) {
            return redirect()->back()->withInput()->with('errors', ['items' => 'Barang yang sama tidak boleh dipilih lebih dari satu kali dalam satu transaksi.']);
        }

        foreach ($items as $key => $item) {
            if (empty($item['id_barang']) || !is_numeric($item['id_barang'])) {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Barang wajib dipilih."]);
            }
            if (empty($item['jumlah_keluar']) || (int)$item['jumlah_keluar'] <= 0) {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Jumlah keluar harus lebih besar dari 0."]);
            }
        }

        $consolidated = [];
        foreach ($items as $item) {
            $idBrg = $item['id_barang'];
            if (isset($consolidated[$idBrg])) {
                $consolidated[$idBrg]['jumlah_keluar'] += (int)$item['jumlah_keluar'];
            } else {
                $consolidated[$idBrg] = [
                    'id_barang'     => $item['id_barang'],
                    'jumlah_keluar' => (int)$item['jumlah_keluar'],
                ];
            }
        }
        $items = array_values($consolidated);

        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Rollback seluruh histori FEFO untuk transaksi ini
        $this->fefo->rollbackBarangKeluar($id);

        // 2. Validasi ulang ketersediaan stok setelah rollback
        $kebutuhan = [];
        foreach ($items as $item) {
            $kebutuhan[$item['id_barang']] = (int) $item['jumlah_keluar'];
        }

        $kurangStok = $this->fefo->cekKetersediaanBulk($kebutuhan);
        if (!empty($kurangStok)) {
            $errorMsgs = [];
            foreach ($kurangStok as $idBarang) {
                $brg = $this->barangModel->find($idBarang);
                $namaBarang = $brg ? $brg['nama_barang'] : 'Barang';
                $stokAda = $this->fefo->getStokBarang($idBarang);
                $errorMsgs[] = "Stok {$namaBarang} tidak mencukupi (Tersedia: {$stokAda}, diminta: {$kebutuhan[$idBarang]}). Stok telah berubah, silakan muat ulang transaksi.";
            }
            $db->transRollback();
            return redirect()->back()->withInput()->with('errors', ['stok' => implode('<br>', $errorMsgs)]);
        }

        // 3. Simpan Header
        $this->barangKeluarModel->update($id, [
            'id_wilayah'        => $this->request->getPost('id_wilayah'),
            'tanggal_keluar'    => $this->request->getPost('tanggal_keluar'),
            'tujuan_penyaluran' => $this->request->getPost('tujuan_penyaluran'),
            'keterangan'        => $this->request->getPost('keterangan'),
        ]);

        // 4. Jalankan ulang Library FEFO (Bulk mode)
        $hasilFEFO = $this->fefo->prosesBarangKeluarBulk($id, $items);
        if ($hasilFEFO === false) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('errors', ['fefo' => 'Proses FEFO gagal. Stok tidak mencukupi atau terjadi kesalahan pemotongan batch.']);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('errors', ['db' => 'Gagal memperbarui transaksi. Silakan coba lagi.']);
        }

        \App\Libraries\ActivityLogger::log(
            'Edit Penyaluran',
            'Penyaluran Barang',
            "Mengubah transaksi Penyaluran {$barangKeluar['nomor_transaksi']}."
        );

        return redirect()->to('/transaksi/barang-keluar')->with('success', 'Transaksi Barang Keluar berhasil diperbarui. Stok batch telah dihitung ulang menggunakan metode FEFO.');
    }



    /**
     * Detail transaksi + batch yang dipotong
     */
    public function detail($id)
    {
        $barangKeluar = $this->barangKeluarModel
            ->select('barang_keluar.*, wilayah.nama_wilayah, users.username as petugas')
            ->join('wilayah', 'wilayah.id = barang_keluar.id_wilayah', 'left')
            ->join('users', 'users.id = barang_keluar.id_user')
            ->find($id);

        if (!$barangKeluar) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Transaksi tidak ditemukan.');
        }

        // Ambil detail batch yang dipotong
        $details = $this->detailModel
            ->select('detail_barang_keluar.*, batch.nomor_batch, batch.tanggal_kedaluwarsa, batch.jumlah_awal, batch.stok_saat_ini, COALESCE(barang.nama_barang, batch.nama_barang) as nama_barang, batch.satuan, batch.berat_per_satuan, batch.satuan_berat')
            ->join('batch', 'batch.id = detail_barang_keluar.id_batch')
            ->join('barang', 'barang.id = batch.id_barang', 'left')
            ->where('detail_barang_keluar.id_barang_keluar', $id)
            ->findAll();

        $data = [
            'title'        => 'Detail Barang Keluar',
            'barangKeluar' => $barangKeluar,
            'details'      => $details,
        ];
        return view('App\Modules\Transactions\Views\barang_keluar\detail', $data);
    }

    /**
     * Hapus transaksi + kembalikan stok batch
     */
    public function delete($id)
    {
        if (!in_groups('Administrator')) {
            return view('errors/html/error_403');
        }

        $barangKeluar = $this->barangKeluarModel->find($id);
        if (!$barangKeluar) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Transaksi tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // Rollback penuh (kembalikan stok & hapus histori detail) menggunakan FEFO
        $this->fefo->rollbackBarangKeluar($id);

        // Hapus header
        $this->barangKeluarModel->delete($id);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/transaksi/barang-keluar')->with('error', 'Gagal menghapus transaksi Barang Keluar. Rollback dibatalkan.');
        }

        \App\Libraries\ActivityLogger::log(
            'Hapus Penyaluran',
            'Penyaluran Barang',
            "Menghapus transaksi Penyaluran {$barangKeluar['nomor_transaksi']}."
        );

        return redirect()->to('/transaksi/barang-keluar')->with('success', 'Transaksi berhasil dihapus. Stok batch telah dikembalikan.');
    }

    /**
     * API: Ambil stok barang (untuk JavaScript)
     */
    public function getStok($idBarang)
    {
        $stok = $this->fefo->getStokBarang((int) $idBarang);
        return $this->response->setJSON(['stok' => $stok]);
    }

    /**
     * Generate nomor transaksi: BK-YYYYMMDD-XXXX
     */
    private function generateNomorTransaksi(): string
    {
        $today  = date('Ymd');
        $prefix = "BK-{$today}-";

        $last = $this->barangKeluarModel
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

    public function downloadBeritaAcara($id)
    {
        $barangKeluar = $this->barangKeluarModel
            ->select('barang_keluar.*, wilayah.nama_wilayah, users.username as petugas')
            ->join('wilayah', 'wilayah.id = barang_keluar.id_wilayah', 'left')
            ->join('users', 'users.id = barang_keluar.id_user')
            ->find($id);

        if (!$barangKeluar) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Transaksi tidak ditemukan.');
        }

        // Ambil detail batch yang dipotong
        $details = $this->detailModel
            ->select('detail_barang_keluar.*, batch.nomor_batch, batch.tanggal_kedaluwarsa, batch.jumlah_awal, batch.stok_saat_ini, COALESCE(barang.nama_barang, batch.nama_barang) as nama_barang, batch.satuan, batch.berat_per_satuan, batch.satuan_berat')
            ->join('batch', 'batch.id = detail_barang_keluar.id_batch')
            ->join('barang', 'barang.id = batch.id_barang', 'left')
            ->where('detail_barang_keluar.id_barang_keluar', $id)
            ->findAll();

        $data = [
            'title'        => 'Berita Acara Pendistribusian Donasi',
            'barangKeluar' => $barangKeluar,
            'details'      => $details,
        ];

        // Load DOMPDF
        $dompdf = new \Dompdf\Dompdf();
        $options = $dompdf->getOptions();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $dompdf->setOptions($options);

        $html = view('laporan/berita_acara_penyaluran', $data);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'BAST_Donasi_' . $barangKeluar['nomor_transaksi'] . '.pdf';
        
        // Output PDF to browser instead of auto download (Attachment => 0)
        $dompdf->stream($filename, ['Attachment' => 0]);
        exit;
    }
}




