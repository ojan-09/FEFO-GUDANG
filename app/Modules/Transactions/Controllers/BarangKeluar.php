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

    public function index()
    {
        $data = [
            'title' => 'Transaksi Penyaluran Barang'
        ];
        return view('App\Modules\Transactions\Views\barang_keluar\index', $data);
    }

    public function ajaxData()
    {
        if (!$this->request->isAJAX()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $postData = $this->request->getPost();
        $list     = $this->barangKeluarModel->getDatatables($postData);
        $data     = [];
        $no       = $postData['start'];

        foreach ($list as $bk) {
            $no++;
            $row = [];

            $jenisBadge = ($bk['jenis_penyaluran'] === 'Penyaluran Internal')
                ? '<span class="badge bg-purple-subtle text-purple border border-purple-subtle rounded-pill px-2 py-1 ms-1 small" style="background:#F3E8FF; color:#7E22CE; font-size:11px;">Internal</span>'
                : '<span class="badge bg-blue-subtle text-blue border border-blue-subtle rounded-pill px-2 py-1 ms-1 small" style="background:#EFF6FF; color:#1D4ED8; font-size:11px;">Relawan</span>';

            $row[] = '<div class="text-center text-secondary">' . $no . '</div>';
            $row[] = '<div><span class="badge-notrx">' . esc($bk['nomor_transaksi']) . '</span> ' . $jenisBadge . '</div>';
            $row[] = '<span class="fw-semibold" style="color:#0f172a;">' . esc($bk['tujuan_penyaluran']) . '</span>';
            $row[] = '<span style="color:#475569;">' . esc($bk['nama_wilayah'] ?? '-') . '</span>';
            $row[] = '<span style="color:#475569;">' . date('d M Y', strtotime($bk['tanggal_keluar'])) . '</span>';
            $row[] = '<div class="text-center"><span class="badge-item">' . esc($bk['jumlah_item']) . ' Item</span></div>';
            $row[] = '<span style="color:#475569;">' . esc($bk['petugas']) . '</span>';

            $aksi = '<div class="dm-action-group">
                        <a href="' . site_url('transaksi/barang-keluar/berita-acara/' . $bk['id']) . '" class="dm-btn-action pdf" title="Cetak Berita Acara (PDF)" target="_blank"><i class="fa-solid fa-file-pdf"></i></a>
                        <a href="' . site_url('transaksi/barang-keluar/berita-acara-word/' . $bk['id']) . '" class="dm-btn-action word" title="Download Berita Acara (Word)"><i class="fa-solid fa-file-word"></i></a>
                        <a href="' . site_url('transaksi/barang-keluar/detail/' . $bk['id']) . '" class="dm-btn-action view" title="Lihat Detail"><i class="fa-solid fa-eye"></i></a>
                        <a href="' . site_url('transaksi/barang-keluar/edit/' . $bk['id']) . '" class="dm-btn-action edit" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form action="' . site_url('transaksi/barang-keluar/delete/' . $bk['id']) . '" method="POST" class="d-inline form-delete-swal" data-confirm-text="Yakin ingin menghapus transaksi penyaluran ini? Stok akan dikembalikan ke batch awal.">
                            ' . csrf_field() . '
                            <button type="submit" class="dm-btn-action del" title="Hapus" style="border:none; cursor:pointer;"><i class="fa-solid fa-trash"></i></button>
                        </form>
                     </div>';
            $row[] = $aksi;
            $data[] = $row;
        }

        $output = [
            "draw"            => isset($postData['draw']) ? intval($postData['draw']) : 0,
            "recordsTotal"    => $this->barangKeluarModel->countAllData(),
            "recordsFiltered" => $this->barangKeluarModel->countFiltered($postData),
            "data"            => $data,
            csrf_token()      => csrf_hash()
        ];

        return $this->response->setJSON($output);
    }

    public function create()
    {
        $db = \Config\Database::connect();

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
                GROUP BY id_barang
            ) stock_summary ON stock_summary.id_barang = barang.id
            WHERE barang.status = 'active'
            ORDER BY barang.nama_barang ASC
        ";

        $semuaBarang = $db->query($sql)->getResultArray();

        $data = [
            'title'           => 'Tambah Barang Keluar',
            'nomor_transaksi' => $this->generateNomorTransaksi(),
            'barang'          => $semuaBarang,
            'wilayah'         => $this->wilayahModel->where('status', 'Aktif')->orderBy('nama_wilayah', 'ASC')->findAll(),
        ];
        return view('App\Modules\Transactions\Views\barang_keluar\form', $data);
    }

    public function validateExpired()
    {
        $items = $this->request->getPost('items');
        if (empty($items) || !is_array($items)) {
            return $this->response->setJSON(['expired' => false, 'data' => []]);
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

        $expiredBatches = $this->fefo->hasExpiredBatch($items);

        return $this->response->setJSON([
            'expired' => !empty($expiredBatches),
            'data'    => $expiredBatches,
            'csrf'    => csrf_hash()
        ]);
    }

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

        // [FIX #7] Konsolidasi DULU sebelum validasi lainnya.
        // Validasi duplikat redundan dihapus — konsolidasi sudah menggabungkan
        // item dengan id_barang yang sama, sehingga tidak mungkin ada duplikat setelahnya.
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

        $itemIds = array_column($items, 'id_barang');
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

            $idBrg       = $item['id_barang'];
            $bisaDipecah = $barangBisaDipecahMap[$idBrg] ?? 0;
            $qty         = (float)$item['jumlah_keluar'];

            if ($bisaDipecah === 0 && floor($qty) != $qty) {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Jumlah keluar untuk barang utuh tidak boleh desimal."]);
            }
        }

        $kebutuhan = [];
        foreach ($items as $item) {
            $kebutuhan[$item['id_barang']] = (float)$item['jumlah_keluar'];
        }

        // Cek stok & expired SEBELUM buka transaksi (read-only)
        $kurangStok = $this->fefo->cekKetersediaanBulk($kebutuhan);
        if (!empty($kurangStok)) {
            $errorMsgs = [];
            foreach ($kurangStok as $idBarang) {
                $brg        = $this->barangModel->find($idBarang);
                $namaBarang = $brg ? $brg['nama_barang'] : 'Barang';
                $stokAda    = $this->fefo->getStokBarang($idBarang);
                $errorMsgs[] = "Stok {$namaBarang} tidak mencukupi. Tersedia: {$stokAda}, diminta: {$kebutuhan[$idBarang]}.";
            }
            return redirect()->back()->withInput()->with('errors', ['stok' => implode('<br>', $errorMsgs)]);
        }

        $expiredBatches = $this->fefo->hasExpiredBatch($items);
        if (!empty($expiredBatches) && $this->request->getPost('force_expired') !== 'true') {
            return redirect()->back()->withInput()->with('errors', ['expired' => 'Terdapat barang yang sudah melewati tanggal kedaluwarsa. Mohon gunakan tombol yang benar pada peringatan.']);
        }

        // Semua write operation dalam satu transaksi
        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $docService     = new \App\Libraries\DocumentNumberService();
            $nomorTransaksi = $docService->getNextDocumentNumber(
                'barang_keluar',
                null,
                user()->username ?? 'Petugas'
            );

            $jenisPenyaluran = $this->request->getPost('jenis_penyaluran') ?? 'Penyaluran Relawan';

            $this->barangKeluarModel->insert([
                'nomor_transaksi'   => $nomorTransaksi,
                'jenis_penyaluran'  => $jenisPenyaluran,
                'penerima_relawan'  => $this->request->getPost('penerima_relawan'),
                'unit_internal'     => $this->request->getPost('unit_internal'),
                'id_user'           => user()->id,
                'divisi_petugas'    => user()->divisi ?? null,
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

            if ($db->transStatus() === false) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('errors', ['db' => 'Gagal menyimpan transaksi. Silakan coba lagi.']);
            }

            $db->transCommit();

        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', '[BarangKeluar::store] Exception: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('errors', ['db' => 'Terjadi kesalahan tidak terduga. Transaksi dibatalkan.']);
        }

        $tujuan    = $this->request->getPost('tujuan_penyaluran') ?: '-';
        $totalItem = array_sum(array_column($items, 'jumlah_keluar'));

        $logMsg = "Menambahkan Penyaluran\nNo. {$nomorTransaksi}\nTujuan : {$tujuan}\nTotal Item : {$totalItem}";
        if (!empty($expiredBatches)) {
            $logMsg = "Penyaluran menggunakan batch expired\nNo. {$nomorTransaksi}\nTujuan : {$tujuan}\nJumlah Batch Expired : " . count($expiredBatches);
        }

        \App\Libraries\ActivityLogger::log('Tambah Penyaluran', 'Penyaluran Barang', $logMsg);

        helper('format');
        clear_dashboard_cache();
        return redirect()->to('/transaksi/barang-keluar')->with('success', 'Transaksi Barang Keluar berhasil disimpan. Stok batch telah dipotong menggunakan metode FEFO.');
    }

    public function edit($id)
    {
        $barangKeluar = $this->barangKeluarModel->find($id);
        if (!$barangKeluar) {
            return redirect()->to('/transaksi/barang-keluar')->with('error', 'Transaksi tidak ditemukan atau sudah dihapus.');
        }

        $db = \Config\Database::connect();

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
                GROUP BY id_barang
            ) stock_summary ON stock_summary.id_barang = barang.id
            WHERE barang.status = 'active'
            ORDER BY barang.nama_barang ASC
        ";

        $semuaBarang = $db->query($sql, [$id, $id, $id])->getResultArray();

        $details = $this->detailModel->where('id_barang_keluar', $id)->findAll();
        $consolidatedDetails = [];
        $addedStok = [];

        $batchIds = array_column($details, 'id_batch');
        $batchList = !empty($batchIds) ? $this->batchModel->whereIn('id', $batchIds)->findAll() : [];
        $batchMap  = [];
        foreach ($batchList as $b) {
            $batchMap[$b['id']] = $b;
        }

        foreach ($details as $d) {
            $batchInfo = $batchMap[$d['id_batch']] ?? null;
            if ($batchInfo) {
                $idBrg = $batchInfo['id_barang'];
                if (isset($consolidatedDetails[$idBrg])) {
                    $consolidatedDetails[$idBrg]['jumlah_keluar'] += $d['jumlah_keluar'];
                } else {
                    $consolidatedDetails[$idBrg] = [
                        'id_barang'        => $idBrg,
                        'jumlah_keluar'    => $d['jumlah_keluar'],
                        'satuan'           => $batchInfo['satuan'] ?: 'Pcs',
                        'berat_per_satuan' => $batchInfo['berat_per_satuan'],
                        'satuan_berat'     => $batchInfo['satuan_berat'],
                        'nama_barang'      => $batchInfo['nama_barang'],
                        'bisa_dipecah'     => (int)($batchInfo['bisa_dipecah'] ?? 0),
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
                $brgInfo     = $this->barangModel->find($idBrg);
                $bisaDipecah = $brgInfo ? (int)$brgInfo['bisa_dipecah'] : 0;
                $semuaBarang[] = [
                    'id'                  => $idBrg,
                    'satuan_kemasan'      => ($bisaDipecah === 1) ? 'Karung' : $det['satuan'],
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

    public function update($id)
    {
        $barangKeluar = $this->barangKeluarModel->find($id);
        if (!$barangKeluar) {
            return redirect()->to('/transaksi/barang-keluar')->with('error', 'Transaksi tidak ditemukan atau sudah dihapus.');
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

        // [FIX #7] Konsolidasi DULU, validasi per-item setelahnya.
        // Konsisten dengan store() — duplikat digabung, bukan ditolak.
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

        $itemIds = array_column($items, 'id_barang');
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

            $idBrg       = $item['id_barang'];
            $bisaDipecah = $barangBisaDipecahMap[$idBrg] ?? 0;
            $qty         = (float)$item['jumlah_keluar'];

            if ($bisaDipecah === 0 && floor($qty) != $qty) {
                return redirect()->back()->withInput()->with('errors', ['items' => "Item baris ke-{$key}: Jumlah keluar untuk barang utuh tidak boleh desimal."]);
            }
        }

        // Validasi expired SEBELUM buka transaksi (read-only)
        $expiredBatches = $this->fefo->hasExpiredBatch($items);
        if (!empty($expiredBatches) && $this->request->getPost('force_expired') !== 'true') {
            return redirect()->back()->withInput()->with('errors', ['expired' => 'Terdapat barang yang sudah melewati tanggal kedaluwarsa. Mohon gunakan tombol yang benar pada peringatan.']);
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        // [FIX #4] rollbackBarangKeluar() dan seluruh write operation masuk try-catch.
        // Sebelumnya rollback ada di luar try-catch — jika exception di sini,
        // transaksi tidak di-rollback dan stok bisa inkonsisten.
        try {
            $this->fefo->rollbackBarangKeluar($id);

            // Cek stok SETELAH rollback (stok sudah dikembalikan, angka akurat)
            $kebutuhan = [];
            foreach ($items as $item) {
                $kebutuhan[$item['id_barang']] = (float)$item['jumlah_keluar'];
            }

            $kurangStok = $this->fefo->cekKetersediaanBulk($kebutuhan);
            if (!empty($kurangStok)) {
                $errorMsgs = [];
                foreach ($kurangStok as $idBarang) {
                    $brg        = $this->barangModel->find($idBarang);
                    $namaBarang = $brg ? $brg['nama_barang'] : 'Barang';
                    $stokAda    = $this->fefo->getStokBarang($idBarang);
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

            if ($db->transStatus() === false) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('errors', ['db' => 'Gagal memperbarui transaksi. Silakan coba lagi.']);
            }

            $db->transCommit();

        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', '[BarangKeluar::update] Exception: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('errors', ['db' => 'Terjadi kesalahan tidak terduga. Transaksi dibatalkan.']);
        }

        $tujuan    = $this->request->getPost('tujuan_penyaluran') ?: '-';
        $totalItem = array_sum(array_column($items, 'jumlah_keluar'));

        $logMsg = "Mengubah Penyaluran\nNo. {$barangKeluar['nomor_transaksi']}\nTujuan : {$tujuan}\nTotal Item : {$totalItem}";
        if (!empty($expiredBatches)) {
            $logMsg = "Edit Penyaluran menggunakan batch expired\nNo. {$barangKeluar['nomor_transaksi']}\nTujuan : {$tujuan}\nJumlah Batch Expired : " . count($expiredBatches);
        }

        \App\Libraries\ActivityLogger::log('Edit Penyaluran', 'Penyaluran Barang', $logMsg);

        helper('format');
        clear_dashboard_cache();
        return redirect()->to('/transaksi/barang-keluar')->with('success', 'Transaksi Barang Keluar berhasil diperbarui. Stok batch telah dihitung ulang menggunakan metode FEFO.');
    }

    public function detail($id)
    {
        $barangKeluar = $this->barangKeluarModel
            ->select('barang_keluar.*, wilayah.nama_wilayah, users.username as petugas')
            ->join('wilayah', 'wilayah.id = barang_keluar.id_wilayah', 'left')
            ->join('users', 'users.id = barang_keluar.id_user')
            ->find($id);

        if (!$barangKeluar) {
            return redirect()->to('/transaksi/barang-keluar')->with('error', 'Transaksi tidak ditemukan atau sudah dihapus.');
        }

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

    public function delete($id)
    {
        if (!in_groups(['Administrator', 'Petugas Gudang'])) {
            return view('errors/html/error_403');
        }

        $barangKeluar = $this->barangKeluarModel->find($id);
        if (!$barangKeluar) {
            return redirect()->to('/transaksi/barang-keluar')->with('error', 'Transaksi tidak ditemukan atau sudah dihapus.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $this->fefo->rollbackBarangKeluar($id);
        $this->barangKeluarModel->delete($id);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to(site_url('transaksi/barang-keluar'))->with('error', 'Gagal menghapus transaksi Barang Keluar. Rollback dibatalkan.');
        }

        \App\Libraries\ActivityLogger::log(
            'Hapus Penyaluran',
            'Penyaluran Barang',
            "Menghapus Penyaluran\nNo. {$barangKeluar['nomor_transaksi']}"
        );

        helper('format');
        clear_dashboard_cache();
        return redirect()->to(site_url('transaksi/barang-keluar'))->with('success', 'Transaksi berhasil dihapus. Stok batch telah dikembalikan.');
    }

    public function getStok($idBarang)
    {
        if (!$this->request->isAJAX()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $stok = $this->fefo->getStokBarang((int) $idBarang);
        return $this->response->setJSON(['stok' => $stok]);
    }

    private function generateNomorTransaksi(): string
    {
        $docService = new \App\Libraries\DocumentNumberService();
        return $docService->getFormattedNumber();
    }

    public function downloadBeritaAcara($id)
    {
        $barangKeluar = $this->barangKeluarModel
            ->select('barang_keluar.*, wilayah.nama_wilayah, users.username as petugas, users.divisi as divisi_petugas')
            ->join('wilayah', 'wilayah.id = barang_keluar.id_wilayah', 'left')
            ->join('users', 'users.id = barang_keluar.id_user')
            ->find($id);

        if (!$barangKeluar) {
            return redirect()->to('/transaksi/barang-keluar')->with('error', 'Transaksi tidak ditemukan atau sudah dihapus.');
        }

        $details = $this->detailModel
            ->select('detail_barang_keluar.*, batch.nomor_batch, batch.tanggal_kedaluwarsa, batch.jumlah_awal, batch.stok_saat_ini, COALESCE(barang.nama_barang, batch.nama_barang) as nama_barang, batch.satuan, batch.berat_per_satuan, batch.satuan_berat, batch.bisa_dipecah')
            ->join('batch', 'batch.id = detail_barang_keluar.id_batch')
            ->join('barang', 'barang.id = batch.id_barang', 'left')
            ->where('detail_barang_keluar.id_barang_keluar', $id)
            ->findAll();

        $docService = new \App\Libraries\DocumentNumberService();
        $provisions = $docService->getActiveProvisions();

        $data = [
            'title'           => 'Berita Acara Pendistribusian Donasi',
            'barangKeluar'    => $barangKeluar,
            'details'         => $details,
            'document_number' => $barangKeluar['nomor_transaksi'],
            'provisions'      => array_column($provisions, 'content'),
        ];

        $dompdf  = new \Dompdf\Dompdf();
        $options = $dompdf->getOptions();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $dompdf->setOptions($options);

        $isInternal   = ($barangKeluar['jenis_penyaluran'] === 'Penyaluran Internal');
        $viewTemplate = $isInternal ? 'laporan/permintaan_barang_keluar_internal' : 'laporan/berita_acara_penyaluran';

        $html = view($viewTemplate, $data);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $prefixName = $isInternal ? 'PBK_Internal_' : 'BAST_Donasi_';
        $filename   = $prefixName . str_replace('/', '_', $barangKeluar['nomor_transaksi']) . '.pdf';
        $dompdf->stream($filename, ['Attachment' => 0]);
        exit;
    }

    public function downloadBeritaAcaraWord($id)
    {
        $barangKeluar = $this->barangKeluarModel
            ->select('barang_keluar.*, wilayah.nama_wilayah, users.username as petugas, users.divisi as divisi_petugas')
            ->join('wilayah', 'wilayah.id = barang_keluar.id_wilayah', 'left')
            ->join('users', 'users.id = barang_keluar.id_user')
            ->find($id);

        if (!$barangKeluar) {
            return redirect()->to('/transaksi/barang-keluar')->with('error', 'Transaksi tidak ditemukan atau sudah dihapus.');
        }

        $details = $this->detailModel
            ->select('detail_barang_keluar.*, batch.nomor_batch, batch.tanggal_kedaluwarsa, batch.jumlah_awal, batch.stok_saat_ini, COALESCE(barang.nama_barang, batch.nama_barang) as nama_barang, batch.satuan, batch.berat_per_satuan, batch.satuan_berat, batch.bisa_dipecah')
            ->join('batch', 'batch.id = detail_barang_keluar.id_batch')
            ->join('barang', 'barang.id = batch.id_barang', 'left')
            ->where('detail_barang_keluar.id_barang_keluar', $id)
            ->findAll();

        $data = [
            'title'           => 'Berita Acara Pendistribusian Donasi',
            'barangKeluar'    => $barangKeluar,
            'details'         => $details,
            'document_number' => $barangKeluar['nomor_transaksi'],
            'isWord'          => true,
        ];

        $html     = view('laporan/berita_acara_penyaluran', $data);
        $filename = 'BAST_Donasi_' . str_replace('/', '_', $barangKeluar['nomor_transaksi']) . '.doc';

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.ms-word; charset=utf-8')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setHeader('Cache-Control', 'private, max-age=0, must-revalidate')
            ->setHeader('Pragma', 'public')
            ->setBody($html);
    }
}