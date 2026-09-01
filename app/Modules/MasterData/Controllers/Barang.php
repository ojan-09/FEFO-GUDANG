<?php

namespace App\Modules\MasterData\Controllers;

use App\Controllers\BaseController;
use App\Modules\MasterData\Models\BarangModel;
use App\Modules\MasterData\Models\KategoriModel;

class Barang extends BaseController
{
    protected $barangModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->barangModel   = new BarangModel();
        $this->kategoriModel = new KategoriModel();
    }

    // =============================================
    // INDEX — Daftar Barang (Administrator + Petugas Gudang)
    // =============================================
    public function index()
    {
        $data = [
            'title'         => 'Informasi Master Barang',
            'active_barang' => $this->barangModel
                ->where('status', 'active')
                ->orderBy('nama_barang', 'ASC')
                ->findAll(),
        ];
        return view('App\Modules\MasterData\Views\barang\index', $data);
    }

    // =============================================
    // DETAIL — Detail Barang + Daftar Batch
    // =============================================
    public function detail($id)
    {
        $barang = $this->barangModel
            ->select('barang.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.id = barang.id_kategori', 'left')
            ->find($id);

        if (!$barang) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Barang tidak ditemukan.');
        }

        $db = \Config\Database::connect();

        // Batch urut FEFO: expired terdekat duluan
        $batches = $db->table('batch')
            ->where('id_barang', $id)
            ->orderBy('tanggal_kedaluwarsa', 'ASC')
            ->get()->getResultArray();

        $totalStok   = 0;
        $activeBatch = 0;
        foreach ($batches as $b) {
            $totalStok += $b['stok_saat_ini'];
            if ($b['status'] === 'Aktif' && $b['stok_saat_ini'] > 0) {
                $activeBatch++;
            }
        }

        $data = [
            'title'       => 'Detail Barang — ' . $barang['nama_barang'],
            'barang'      => $barang,
            'batches'     => $batches,
            'totalStok'   => $totalStok,
            'activeBatch' => $activeBatch,
        ];
        return view('App\Modules\MasterData\Views\barang\detail', $data);
    }

    // =============================================
    // AJAX DataTables
    // =============================================
    public function ajaxData()
    {
        if (!$this->request->isAJAX()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $postData = $this->request->getPost();
        $list     = $this->barangModel->getDatatables($postData);
        $data     = [];
        $no       = $postData['start'];

        foreach ($list as $row) {
            $no++;

            $nama_barang_html = esc($row['nama_barang']);
            if (isset($row['status']) && $row['status'] === 'merged') {
                $targetName = !empty($row['target_nama_barang']) ? esc($row['target_nama_barang']) : 'Unknown';
                $nama_barang_html .= ' <span class="badge bg-secondary ms-1" style="font-size:0.75em;"><i class="fa-solid fa-code-merge me-1"></i>Merged &rarr; ' . $targetName . '</span>';
            }

            $statusHtml = $row['status'] === 'active'
                ? '<span class="badge bg-success">Aktif</span>'
                : '<span class="badge bg-secondary">Merged</span>';

            $stokFormatted = number_format($row['total_stok'] ?? 0, 0, ',', '.') . ' ' . esc($row['satuan']);
            $batchBadge    = '<span class="badge bg-info text-dark">' . ($row['jumlah_batch'] ?? 0) . ' Batch</span>';

            $aksi = '<div class="d-flex justify-content-center">
                        <a href="' . site_url('masterdata/barang/detail/' . $row['id']) . '"
                           class="btn btn-sm btn-outline-primary rounded-pill px-3">
                            <i class="fa-solid fa-eye me-1"></i>Detail
                        </a>
                     </div>';

            $data[] = [
                '<div class="text-center text-secondary">' . $no . '</div>',
                '<span class="fw-bold" style="color:#2563eb;">' . esc($row['kode_barang']) . '</span>',
                '<span class="fw-semibold">' . $nama_barang_html . '</span>',
                '<span class="badge bg-light text-dark border">' . esc($row['nama_kategori']) . '</span>',
                esc($row['satuan']),
                '<span class="fw-bold">' . $stokFormatted . '</span>',
                $batchBadge,
                '<div class="text-center">' . $statusHtml . '</div>',
                $aksi,
            ];
        }

        $output = [
            'draw'            => isset($postData['draw']) ? intval($postData['draw']) : 0,
            'recordsTotal'    => $this->barangModel->countAllData(),
            'recordsFiltered' => $this->barangModel->countFiltered($postData),
            'data'            => $data,
            csrf_token()      => csrf_hash(),
        ];

        file_put_contents(WRITEPATH . 'debug_ajax.json', json_encode($output));
        return $this->response->setJSON($output);
    }

    // =============================================
    // MERGE PREVIEW — tampilkan info kedua barang sebelum konfirmasi
    // Hanya Administrator (route sudah difilter RBAC)
    // =============================================
    public function mergePreview()
    {
        // Double-check RBAC backend
        if (!in_groups('Administrator')) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Akses ditolak.']);
        }

        $id_target = $this->request->getPost('id_target');
        $id_sumber = $this->request->getPost('id_sumber');

        if (empty($id_target) || empty($id_sumber)) {
            return redirect()->to('/masterdata/barang')->with('error', 'Barang target dan sumber harus dipilih.');
        }

        if (!is_array($id_sumber)) {
            $id_sumber = [$id_sumber];
        }

        if (in_array($id_target, $id_sumber)) {
            return redirect()->to('/masterdata/barang')->with('error', 'Barang target tidak boleh sama dengan barang sumber.');
        }

        $db = \Config\Database::connect();

        $target = $this->barangModel
            ->select('barang.*, kategori.nama_kategori,
                      COALESCE(SUM(batch.stok_saat_ini), 0) as total_stok,
                      COUNT(batch.id) as jumlah_batch')
            ->join('kategori', 'kategori.id = barang.id_kategori', 'left')
            ->join('batch', 'batch.id_barang = barang.id AND batch.stok_saat_ini > 0', 'left')
            ->where('barang.id', $id_target)
            ->groupBy('barang.id')
            ->first();

        if (!$target || $target['status'] !== 'active') {
            return redirect()->to('/masterdata/barang')->with('error', 'Barang target tidak valid atau sudah digabungkan.');
        }

        $sumberList = [];
        foreach ($id_sumber as $sid) {
            $s = $this->barangModel
                ->select('barang.*, kategori.nama_kategori,
                          COALESCE(SUM(batch.stok_saat_ini), 0) as total_stok,
                          COUNT(batch.id) as jumlah_batch')
                ->join('kategori', 'kategori.id = barang.id_kategori', 'left')
                ->join('batch', 'batch.id_barang = barang.id AND batch.stok_saat_ini > 0', 'left')
                ->where('barang.id', $sid)
                ->groupBy('barang.id')
                ->first();

            if (!$s) {
                return redirect()->to('/masterdata/barang')->with('error', 'Barang sumber tidak ditemukan (ID: ' . $sid . ').');
            }
            if ($s['status'] !== 'active') {
                return redirect()->to('/masterdata/barang')->with('error', "Barang {$s['nama_barang']} sudah berstatus merged.");
            }
            $sumberList[] = $s;
        }

        $data = [
            'title'      => 'Konfirmasi Merge Barang',
            'target'     => $target,
            'sumberList' => $sumberList,
            'id_target'  => $id_target,
            'id_sumber'  => $id_sumber,
            'active_barang' => $this->barangModel->where('status', 'active')->orderBy('nama_barang', 'ASC')->findAll(),
        ];
        return view('App\Modules\MasterData\Views\barang\merge_confirm', $data);
    }

    // =============================================
    // MERGE — proses merge (Administrator only)
    // =============================================
    public function merge()
    {
        // Double-check RBAC backend
        if (!in_groups('Administrator')) {
            return redirect()->to('/masterdata/barang')->with('error', 'Akses ditolak. Hanya Administrator yang dapat menggabungkan barang.');
        }

        $id_target = $this->request->getPost('id_target');
        $id_sumber = $this->request->getPost('id_sumber');

        if (empty($id_target) || empty($id_sumber)) {
            return redirect()->to('/masterdata/barang')->with('error', 'Barang target dan sumber harus dipilih.');
        }

        if (!is_array($id_sumber)) {
            $id_sumber = [$id_sumber];
        }

        if (in_array($id_target, $id_sumber)) {
            return redirect()->to('/masterdata/barang')->with('error', 'Barang target tidak boleh sama dengan barang sumber.');
        }

        $db = \Config\Database::connect();

        // Validasi target
        $target = $this->barangModel->find($id_target);
        if (!$target || $target['status'] !== 'active') {
            return redirect()->to('/masterdata/barang')->with('error', 'Barang target tidak valid atau sudah digabungkan.');
        }

        // Validasi semua sumber
        $sumberList = $this->barangModel->whereIn('id', $id_sumber)->findAll();
        if (count($sumberList) !== count($id_sumber)) {
            return redirect()->to('/masterdata/barang')->with('error', 'Beberapa barang sumber tidak ditemukan.');
        }

        $namaSumber    = [];
        $totalBatchSumber = 0;
        $totalStokSumber  = 0;

        foreach ($sumberList as $s) {
            if ($s['status'] !== 'active') {
                return redirect()->to('/masterdata/barang')->with('error', "Barang {$s['nama_barang']} sudah berstatus merged.");
            }
            $namaSumber[] = $s['nama_barang'] . ' (ID: ' . $s['id'] . ')';

            // Hitung stok + batch untuk log
            $stokData = $db->table('batch')
                ->selectSum('stok_saat_ini', 'total_stok')
                ->selectCount('id', 'jumlah_batch')
                ->where('id_barang', $s['id'])
                ->get()->getRowArray();
            $totalBatchSumber += (int)($stokData['jumlah_batch'] ?? 0);
            $totalStokSumber  += (float)($stokData['total_stok'] ?? 0);
        }

        // Stok target sebelum merge (untuk log)
        $stokTargetBefore = $db->table('batch')
            ->selectSum('stok_saat_ini', 'total_stok')
            ->selectCount('id', 'jumlah_batch')
            ->where('id_barang', $id_target)
            ->get()->getRowArray();

        // ---- TRANSACTION ----
        $db->transStart();

        foreach ($id_sumber as $sid) {
            $this->barangModel->update($sid, [
                'status'    => 'merged',
                'merged_to' => $id_target,
            ]);
        }

        // Pindahkan semua batch dari sumber ke target
        $db->table('batch')
            ->whereIn('id_barang', $id_sumber)
            ->update(['id_barang' => $id_target]);

        // ---- LOG AUDIT ----
        $user_id      = user_id();
        $namaSumberStr = implode('; ', $namaSumber);

        $stokTargetAfter = $db->table('batch')
            ->selectSum('stok_saat_ini', 'total_stok')
            ->selectCount('id', 'jumlah_batch')
            ->where('id_barang', $id_target)
            ->get()->getRowArray();

        $deskripsi = "Merge Master Barang\n"
            . "---\n"
            . "Target: {$target['nama_barang']} (ID: {$id_target})\n"
            . "Sumber: {$namaSumberStr}\n"
            . "---\n"
            . "Sebelum merge:\n"
            . "  Target - Batch: " . ($stokTargetBefore['jumlah_batch'] ?? 0) . ", Stok: " . number_format($stokTargetBefore['total_stok'] ?? 0, 2) . "\n"
            . "  Sumber - Batch: {$totalBatchSumber}, Stok: " . number_format($totalStokSumber, 2) . "\n"
            . "Sesudah merge:\n"
            . "  Target - Batch: " . ($stokTargetAfter['jumlah_batch'] ?? 0) . ", Stok: " . number_format($stokTargetAfter['total_stok'] ?? 0, 2) . "\n"
            . "Status: Berhasil";

        $db->table('activity_logs')->insert([
            'id_user'    => $user_id,
            'aksi'       => 'Merge Barang',
            'modul'      => 'Master Data Barang',
            'deskripsi'  => $deskripsi,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/masterdata/barang')->with('error', 'Terjadi kesalahan saat menggabungkan barang. Proses dibatalkan (rollback).');
        }

        // Clear relevant caches
        cache()->delete('dashboard_total_jenis_barang');
        cache()->delete('dashboard_total_batch');
        cache()->delete('dashboard_total_berat');
        cache()->delete('dashboard_top_barang');
        cache()->delete('dashboard_kategori');

        return redirect()->to('/masterdata/barang')->with('success', 'Barang berhasil digabungkan. Batch, stok, dan histori transaksi telah dialihkan ke barang target.');
    }

    // =============================================
    // Endpoint lama — dinonaktifkan
    // =============================================
    public function create()  { throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(); }
    public function store()   { throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(); }
    public function edit($id) { throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(); }
    public function update($id) { throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(); }
    public function delete($id) { throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(); }
}