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
            ->select('barang_keluar.id, barang_keluar.nomor_transaksi, barang_keluar.tujuan_penyaluran, barang_keluar.tanggal_keluar, wilayah.nama_wilayah, users.username as petugas')
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

        foreach ($barangKeluar as &$bk) {
            $bk['jumlah_item'] = $detailStats[$bk['id']] ?? 0;
        }

        $data = [
            'title'        => 'Transaksi Barang Keluar',
            'barangKeluar' => $barangKeluar,
            'detailStats'  => $detailStats
        ];
        return view('App\Modules\Transactions\Views\barang_keluar\index', $data);
    }

    /**
     * Form tambah Barang Keluar
     */
    public function create()
    {
        $db = \Config\Database::connect();
        $today = date('Y-m-d');
        
        $sql = "
            SELECT 
                barang.id,
                CASE WHEN fefo_batch.bisa_dipecah = 1 
                    THEN 'Karung' 
                    ELSE barang.satuan 
                END as satuan_kemasan,
                fefo_batch.bisa_dipecah,
                fefo_batch.tanggal_kedaluwarsa,
                COALESCE(fefo_batch.nama_barang, barang.nama_barang) as nama_barang,
                CASE WHEN fefo_batch.bisa_dipecah = 1 
                    THEN barang.satuan 
                    ELSE COALESCE(fefo_batch.satuan, barang.satuan) 
                END as satuan,
                COALESCE(fefo_batch.berat_per_satuan, barang.berat_per_satuan) as berat_per_satuan,
                COALESCE(fefo_batch.satuan_berat, barang.satuan_berat) as satuan_berat,
                stock_summary.stok_tersedia
            FROM barang
            JOIN (
                SELECT b1.*
                FROM batch b1
                JOIN (
                    SELECT id_barang, MIN(tanggal_kedaluwarsa) as min_exp
                    FROM batch
                    WHERE stok_saat_ini > 0
                      AND status = 'Aktif'
                      AND tanggal_kedaluwarsa >= ?
                    GROUP BY id_barang
                ) b2 ON b1.id_barang = b2.id_barang AND b1.tanggal_kedaluwarsa = b2.min_exp
                WHERE b1.id = (
                    SELECT MIN(id) 
                    FROM batch 
                    WHERE id_barang = b1.id_barang 
                      AND tanggal_kedaluwarsa = b1.tanggal_kedaluwarsa 
                      AND stok_saat_ini > 0 
                      AND status = 'Aktif'
                )
            ) fefo_batch ON fefo_batch.id_barang = barang.id
            JOIN (
                SELECT id_barang, SUM(stok_saat_ini) as stok_tersedia
                FROM batch
                WHERE stok_saat_ini > 0
                  AND status = 'Aktif'
                  AND tanggal_kedaluwarsa >= ?
                GROUP BY id_barang
            ) stock_summary ON stock_summary.id_barang = barang.id
            ORDER BY barang.nama_barang ASC
        ";
        
        $semuaBarang = $db->query($sql, [$today, $today])->getResultArray();

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

        $itemIds = array_column($items, 'id_barang');
        if (count($itemIds) !== count(array_unique($itemIds))) {
            return redirect()->back()->withInput()->with('errors', ['items' => 'Barang yang sama tidak boleh dipilih lebih dari satu kali dalam satu transaksi.']);
        }

        $barangList = $this->barangModel->whereIn('id', $itemIds)->findAll();
        $barangBisaDipecahMap = [];
        foreach ($barangList as $b) {
            $barangBisaDipecahMap[$b['id']] = (int)$b['bisa_dipecah'];
        }

        foreach ($items as $key => $item) {
            if (empty($item['id_barang']) || !is_numeric($item['id_barang'])) {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Barang wajib dipilih."]);
            }
            if (empty($item['jumlah_keluar']) || (float)$item['jumlah_keluar'] <= 0) {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Jumlah keluar harus lebih besar dari 0."]);
            }

            $idBrg = $item['id_barang'];
            $bisaDipecah = $barangBisaDipecahMap[$idBrg] ?? 0;
            $qty = (float)$item['jumlah_keluar'];

            if ($bisaDipecah === 0 && floor($qty) != $qty) {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Jumlah keluar untuk barang utuh tidak boleh desimal."]);
            }
        }

        $consolidated = [];
        foreach ($items as $item) {
            $idBrg = $item['id_barang'];
            if (isset($consolidated[$idBrg])) {
                $consolidated[$idBrg]['jumlah_keluar'] += (float)$item['jumlah_keluar'];
            } else {
                $consolidated[$idBrg] = [
                    'id_barang'     => $item['id_barang'],
                    'jumlah_keluar' => (float)$item['jumlah_keluar'],
                ];
            }
        }
        $items = array_values($consolidated);

        $kebutuhan = [];
        foreach ($items as $item) {
            $kebutuhan[$item['id_barang']] = (float) $item['jumlah_keluar'];
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

        $db = \Config\Database::connect();
        $today = date('Y-m-d');
        
        $sql = "
            SELECT 
                barang.id,
                CASE WHEN fefo_batch.bisa_dipecah = 1 
                    THEN 'Karung' 
                    ELSE barang.satuan 
                END as satuan_kemasan,
                fefo_batch.bisa_dipecah,
                fefo_batch.tanggal_kedaluwarsa,
                COALESCE(fefo_batch.nama_barang, barang.nama_barang) as nama_barang,
                CASE WHEN fefo_batch.bisa_dipecah = 1 
                    THEN barang.satuan 
                    ELSE COALESCE(fefo_batch.satuan, barang.satuan) 
                END as satuan,
                COALESCE(fefo_batch.berat_per_satuan, barang.berat_per_satuan) as berat_per_satuan,
                COALESCE(fefo_batch.satuan_berat, barang.satuan_berat) as satuan_berat,
                stock_summary.stok_tersedia
            FROM barang
            JOIN (
                SELECT b1.*
                FROM batch b1
                JOIN (
                    SELECT id_barang, MIN(tanggal_kedaluwarsa) as min_exp
                    FROM batch
                    WHERE (stok_saat_ini > 0 OR id IN (SELECT id_batch FROM detail_barang_keluar WHERE id_barang_keluar = ?))
                      AND status = 'Aktif'
                      AND tanggal_kedaluwarsa >= ?
                    GROUP BY id_barang
                ) b2 ON b1.id_barang = b2.id_barang AND b1.tanggal_kedaluwarsa = b2.min_exp
                WHERE b1.id = (
                    SELECT MIN(id) 
                    FROM batch 
                    WHERE id_barang = b1.id_barang 
                      AND tanggal_kedaluwarsa = b1.tanggal_kedaluwarsa 
                      AND (stok_saat_ini > 0 OR id IN (SELECT id_batch FROM detail_barang_keluar WHERE id_barang_keluar = ?))
                      AND status = 'Aktif'
                )
            ) fefo_batch ON fefo_batch.id_barang = barang.id
            JOIN (
                SELECT id_barang, SUM(stok_saat_ini) as stok_tersedia
                FROM batch
                WHERE (stok_saat_ini > 0 OR id IN (SELECT id_batch FROM detail_barang_keluar WHERE id_barang_keluar = ?))
                  AND status = 'Aktif'
                  AND tanggal_kedaluwarsa >= ?
                GROUP BY id_barang
            ) stock_summary ON stock_summary.id_barang = barang.id
            ORDER BY barang.nama_barang ASC
        ";
        
        $semuaBarang = $db->query($sql, [$id, $today, $id, $id, $today])->getResultArray();

        $details = $this->detailModel->where('id_barang_keluar', $id)->findAll();
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
                        'id_barang'     => $idBrg,
                        'jumlah_keluar' => $d['jumlah_keluar'],
                        'satuan'        => $batchInfo['satuan'] ?: 'Pcs',
                        'berat_per_satuan' => $batchInfo['berat_per_satuan'],
                        'satuan_berat'  => $batchInfo['satuan_berat'],
                        'nama_barang'   => $batchInfo['nama_barang'],
                        'bisa_dipecah'  => (int)($batchInfo['bisa_dipecah'] ?? 0),
                    ];
                }
                
                if (!isset($addedStok[$idBrg])) {
                    $addedStok[$idBrg] = 0;
                }
                $addedStok[$idBrg] += $d['jumlah_keluar'];
            }
        }

        $existingBarangIds = [];
        foreach ($semuaBarang as &$b) {
            $existingBarangIds[] = $b['id'];
            if (isset($addedStok[$b['id']])) {
                $b['stok_tersedia'] += $addedStok[$b['id']];
            }
        }

        foreach ($consolidatedDetails as $idBrg => $det) {
            if (!in_array($idBrg, $existingBarangIds)) {
                $brgInfo = $this->barangModel->find($idBrg);
                $bisaDipecah = $brgInfo ? (int)$brgInfo['bisa_dipecah'] : 0;
                $semuaBarang[] = [
                    'id'                  => $idBrg,
                    'bisa_dipecah'        => $bisaDipecah,
                    'tanggal_kedaluwarsa' => date('Y-m-d'),
                    'nama_barang'         => $det['nama_barang'],
                    'satuan'              => $det['satuan'],
                    'berat_per_satuan'    => $det['berat_per_satuan'],
                    'satuan_berat'        => $det['satuan_berat'],
                    'stok_tersedia'       => $addedStok[$idBrg],
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

        $itemIds = array_column($items, 'id_barang');
        if (count($itemIds) !== count(array_unique($itemIds))) {
            return redirect()->back()->withInput()->with('errors', ['items' => 'Barang yang sama tidak boleh dipilih lebih dari satu kali dalam satu transaksi.']);
        }

        $barangList = $this->barangModel->whereIn('id', $itemIds)->findAll();
        $barangBisaDipecahMap = [];
        foreach ($barangList as $b) {
            $barangBisaDipecahMap[$b['id']] = (int)$b['bisa_dipecah'];
        }

        foreach ($items as $key => $item) {
            if (empty($item['id_barang']) || !is_numeric($item['id_barang'])) {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Barang wajib dipilih."]);
            }
            if (empty($item['jumlah_keluar']) || (float)$item['jumlah_keluar'] <= 0) {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Jumlah keluar harus lebih besar dari 0."]);
            }

            $idBrg = $item['id_barang'];
            $bisaDipecah = $barangBisaDipecahMap[$idBrg] ?? 0;
            $qty = (float)$item['jumlah_keluar'];

            if ($bisaDipecah === 0 && floor($qty) != $qty) {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Jumlah keluar untuk barang utuh tidak boleh desimal."]);
            }
        }

        $consolidated = [];
        foreach ($items as $item) {
            $idBrg = $item['id_barang'];
            if (isset($consolidated[$idBrg])) {
                $consolidated[$idBrg]['jumlah_keluar'] += (float)$item['jumlah_keluar'];
            } else {
                $consolidated[$idBrg] = [
                    'id_barang'     => $item['id_barang'],
                    'jumlah_keluar' => (float)$item['jumlah_keluar'],
                ];
            }
        }
        $items = array_values($consolidated);

        $db = \Config\Database::connect();
        $db->transStart();

        $this->fefo->rollbackBarangKeluar($id);

        $kebutuhan = [];
        foreach ($items as $item) {
            $kebutuhan[$item['id_barang']] = (float) $item['jumlah_keluar'];
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

        $this->barangKeluarModel->update($id, [
            'id_wilayah'        => $this->request->getPost('id_wilayah'),
            'tanggal_keluar'    => $this->request->getPost('tanggal_keluar'),
            'tujuan_penyaluran' => $this->request->getPost('tujuan_penyaluran'),
            'keterangan'        => $this->request->getPost('keterangan'),
        ]);

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

        // FIX: tambahkan batch.bisa_dipecah agar view bisa membedakan
        // barang repack (jumlah sudah dalam Kg, jangan dikalikan lagi)
        // vs barang utuh (perlu dikalikan berat_per_satuan untuk dapat berat total).
        $details = $this->detailModel
            ->select('detail_barang_keluar.*, batch.nomor_batch, batch.tanggal_kedaluwarsa, batch.jumlah_awal, batch.stok_saat_ini, COALESCE(barang.nama_barang, batch.nama_barang) as nama_barang, batch.satuan, batch.berat_per_satuan, batch.satuan_berat, batch.bisa_dipecah')
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

        $this->fefo->rollbackBarangKeluar($id);
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

        // FIX: sama seperti detail(), tambahkan batch.bisa_dipecah
        // agar template PDF Berita Acara juga bisa hitung berat dengan benar.
        $details = $this->detailModel
            ->select('detail_barang_keluar.*, batch.nomor_batch, batch.tanggal_kedaluwarsa, batch.jumlah_awal, batch.stok_saat_ini, COALESCE(barang.nama_barang, batch.nama_barang) as nama_barang, batch.satuan, batch.berat_per_satuan, batch.satuan_berat, batch.bisa_dipecah')
            ->join('batch', 'batch.id = detail_barang_keluar.id_batch')
            ->join('barang', 'barang.id = batch.id_barang', 'left')
            ->where('detail_barang_keluar.id_barang_keluar', $id)
            ->findAll();

        $data = [
            'title'        => 'Berita Acara Pendistribusian Donasi',
            'barangKeluar' => $barangKeluar,
            'details'      => $details,
        ];

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
        $dompdf->stream($filename, ['Attachment' => 0]);
        exit;
    }
}