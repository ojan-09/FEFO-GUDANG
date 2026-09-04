<?php

namespace App\Modules\Transactions\Controllers;

use App\Controllers\BaseController;
use App\Modules\Transactions\Models\BarangMasukModel;
use App\Modules\Transactions\Models\BatchModel;
use App\Modules\MasterData\Models\DonaturModel;
use App\Modules\MasterData\Models\KategoriModel;
use App\Modules\Transactions\Services\BarangMasukService;

class BarangMasuk extends BaseController
{
    protected BarangMasukModel   $barangMasukModel;
    protected BatchModel         $batchModel;
    protected DonaturModel       $donaturModel;
    protected KategoriModel      $kategoriModel;
    protected BarangMasukService $service;

    public function __construct()
    {
        $this->barangMasukModel = new BarangMasukModel();
        $this->batchModel       = new BatchModel();
        $this->donaturModel     = new DonaturModel();
        $this->kategoriModel    = new KategoriModel();
        $this->service          = new BarangMasukService();
    }

    // =========================================================
    // Read
    // =========================================================

    public function index()
    {
        return view('App\Modules\Transactions\Views\barang_masuk\index', [
            'title' => 'Transaksi Donasi Masuk',
        ]);
    }

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

        $batches = $this->fetchBatchesForTransaction($id);
        foreach ($batches as &$b) {
            if ((int) ($b['bisa_dipecah'] ?? 0) === 1) {
                $b['satuan'] .= ' (Repack)';
            }
        }
        unset($b);

        return view('App\Modules\Transactions\Views\barang_masuk\detail', [
            'title'       => 'Detail Donasi Masuk',
            'barangMasuk' => $barangMasuk,
            'batches'     => $batches,
        ]);
    }

    // =========================================================
    // Create
    // =========================================================

    public function create()
    {
        return view('App\Modules\Transactions\Views\barang_masuk\form', [
            'title'           => 'Tambah Donasi Masuk',
            'nomor_transaksi' => $this->barangMasukModel->previewNomorTransaksi(),
            'donatur'         => $this->donaturModel->orderBy('nama_donatur', 'ASC')->findAll(),
            'kategori'        => $this->kategoriModel->orderBy('nama_kategori', 'ASC')->findAll(),
            'barangList'      => $this->getBarangMasterList(),
        ]);
    }

    public function store()
    {
        if (!$this->validate(['tanggal_masuk' => 'required|valid_date', 'id_donatur' => 'required|integer'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $items = $this->request->getPost('items');
        if (empty($items) || !is_array($items)) {
            return redirect()->back()->withInput()->with('errors', ['items' => 'Minimal harus ada 1 barang.']);
        }

        $tanggalMasuk = $this->request->getPost('tanggal_masuk');
        $validated    = $this->service->validateAndCleanItems($items, $tanggalMasuk);
        if (!$validated['ok']) {
            return redirect()->back()->withInput()->with('errors', $validated['errors']);
        }

        $result = $this->service->store(
            [
                'id_donatur'    => $this->request->getPost('id_donatur'),
                'id_user'       => user()->id,
                'tanggal_masuk' => $tanggalMasuk,
                'eta'           => $this->request->getPost('eta'),
                'keterangan'    => $this->request->getPost('keterangan'),
            ],
            $validated['items']
        );

        if (!$result['ok']) {
            return redirect()->back()->withInput()->with('errors', $result['errors']);
        }

        $namaDonatur = $this->donaturName($this->request->getPost('id_donatur'));
        \App\Libraries\ActivityLogger::log(
            'Tambah Donasi', 'Donasi Masuk',
            "Menambahkan Donasi Masuk\nNo. {$result['nomor_transaksi']}\nDonatur : {$namaDonatur}\nTotal Item : {$result['total_item']}"
        );

        $this->bustDashboardCache();

        return redirect()->to('/transaksi/barang-masuk')->with('success', 'Transaksi Barang Masuk berhasil disimpan.');
    }

    // =========================================================
    // Update
    // =========================================================

    public function edit($id)
    {
        $barangMasuk = $this->barangMasukModel->find($id);
        if (!$barangMasuk) {
            return redirect()->to('/transaksi/barang-masuk')->with('error', 'Transaksi tidak ditemukan atau sudah dihapus.');
        }

        if ($this->hasPenggunaanStok($id)) {
            return redirect()->to('/transaksi/barang-masuk')->with('error', 'Transaksi tidak dapat diubah karena sebagian atau seluruh stok dari donasi ini sudah digunakan pada proses Penyaluran Barang atau Penyesuaian Stok.');
        }

        return view('App\Modules\Transactions\Views\barang_masuk\form', [
            'title'           => 'Edit Donasi Masuk',
            'barangMasuk'     => $barangMasuk,
            'nomor_transaksi' => $barangMasuk['nomor_transaksi'],
            'batches'         => $this->fetchBatchesForTransaction($id),
            'donatur'         => $this->donaturModel->orderBy('nama_donatur', 'ASC')->findAll(),
            'kategori'        => $this->kategoriModel->orderBy('nama_kategori', 'ASC')->findAll(),
            'barangList'      => $this->getBarangMasterList(),
        ]);
    }

    public function update($id)
    {
        $barangMasuk = $this->barangMasukModel->find($id);
        if (!$barangMasuk) {
            return redirect()->to('/transaksi/barang-masuk')->with('error', 'Transaksi tidak ditemukan atau sudah dihapus.');
        }

        if ($this->hasPenggunaanStok($id)) {
            return redirect()->to('/transaksi/barang-masuk')->with('error', 'Transaksi tidak dapat diubah karena sebagian atau seluruh stok dari donasi ini sudah digunakan pada proses Penyaluran Barang atau Penyesuaian Stok.');
        }

        if (!$this->validate(['tanggal_masuk' => 'required|valid_date', 'id_donatur' => 'required|integer'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $items = $this->request->getPost('items');
        if (empty($items) || !is_array($items)) {
            return redirect()->back()->withInput()->with('errors', ['items' => 'Minimal harus ada 1 barang.']);
        }

        $tanggalMasuk = $this->request->getPost('tanggal_masuk');
        $validated    = $this->service->validateAndCleanItems($items, $tanggalMasuk);
        if (!$validated['ok']) {
            return redirect()->back()->withInput()->with('errors', $validated['errors']);
        }

        $result = $this->service->update(
            (int) $id,
            [
                'id_donatur'    => $this->request->getPost('id_donatur'),
                'tanggal_masuk' => $tanggalMasuk,
                'eta'           => $this->request->getPost('eta'),
                'keterangan'    => $this->request->getPost('keterangan'),
            ],
            $validated['items']
        );

        if (!$result['ok']) {
            return redirect()->back()->withInput()->with('errors', $result['errors']);
        }

        $namaDonatur = $this->donaturName($this->request->getPost('id_donatur'));
        \App\Libraries\ActivityLogger::log(
            'Edit Donasi', 'Donasi Masuk',
            "Mengubah Donasi Masuk\nNo. {$barangMasuk['nomor_transaksi']}\nDonatur : {$namaDonatur}\nTotal Item : {$result['total_item']}"
        );

        $this->bustDashboardCache();

        return redirect()->to('/transaksi/barang-masuk')->with('success', 'Transaksi Barang Masuk berhasil diperbarui.');
    }

    // =========================================================
    // Delete
    // =========================================================

    public function delete($id)
    {
        // [FIX #10] Hapus cek in_groups() manual di sini — redundan karena route
        // sudah dilindungi filter 'rbac:Administrator,Petugas Gudang'.
        // Dua sumber kebenaran untuk aturan yang sama berisiko tidak sinkron
        // jika route filter diubah tapi controller lupa diupdate.

        $barangMasuk = $this->barangMasukModel->find($id);
        if (!$barangMasuk) {
            return redirect()->to(site_url('transaksi/barang-masuk'))->with('error', 'Transaksi tidak ditemukan atau sudah dihapus.');
        }

        if ($this->hasPenggunaanStok($id)) {
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
            'Hapus Donasi', 'Donasi Masuk',
            "Menghapus Donasi Masuk\nNo. {$barangMasuk['nomor_transaksi']}"
        );

        $this->bustDashboardCache();

        return redirect()->to(site_url('transaksi/barang-masuk'))->with('success', 'Transaksi berhasil dihapus.');
    }

    // =========================================================
    // AJAX
    // =========================================================

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
            $aksi = $this->buildActionButtons($bm);
            $data[] = [
                '<div class="text-center text-secondary">' . $no . '</div>',
                '<span class="badge-notrx">' . esc($bm['nomor_transaksi']) . '</span>',
                '<span class="fw-semibold" style="color:#0f172a;">' . esc($bm['nama_donatur']) . '</span>',
                '<div class="text-center"><span class="badge-item">' . esc($bm['jumlah_item']) . ' Item</span></div>',
                '<span style="color:#475569;">' . date('d M Y', strtotime($bm['tanggal_masuk'])) . '</span>',
                '<span style="color:#475569;">' . esc($bm['petugas']) . '</span>',
                $aksi,
            ];
        }

        return $this->response->setJSON([
            'draw'            => isset($postData['draw']) ? intval($postData['draw']) : 0,
            'recordsTotal'    => $this->barangMasukModel->countAllData(),
            'recordsFiltered' => $this->barangMasukModel->countFiltered($postData),
            'data'            => $data,
            csrf_token()      => csrf_hash(),
        ]);
    }

    // =========================================================
    // Private helpers
    // =========================================================

    private function hasPenggunaanStok(int $id): bool
    {
        return $this->batchModel
            ->where('id_barang_masuk', $id)
            ->where('stok_saat_ini < jumlah_awal')
            ->countAllResults() > 0;
    }

    private function fetchBatchesForTransaction(int $id): array
    {
        return $this->batchModel
            ->select('batch.*, COALESCE(batch.nama_barang, barang.nama_barang) AS nama_barang,
                      COALESCE(batch.satuan, barang.satuan) AS satuan,
                      COALESCE(batch.berat_per_satuan, barang.berat_per_satuan) AS berat_per_satuan,
                      COALESCE(batch.satuan_berat, barang.satuan_berat) AS satuan_berat')
            ->join('barang', 'barang.id = batch.id_barang', 'left')
            ->where('batch.id_barang_masuk', $id)
            ->findAll();
    }

    private function donaturName(int $idDonatur): string
    {
        $donatur = $this->donaturModel->find($idDonatur);
        return $donatur ? $donatur['nama_donatur'] : '-';
    }

    private function bustDashboardCache(): void
    {
        helper('format');
        clear_dashboard_cache();
    }

    private function getBarangMasterList(): array
    {
        $barangModel = new \App\Modules\MasterData\Models\BarangModel();

        // [FIX #15] Ganti groupBy('barang.nama_barang') dengan subquery MIN(id)
        // agar tidak melanggar only_full_group_by di MySQL strict mode.
        // Sebelumnya kolom satuan, berat_per_satuan, dll tidak diagregasi
        // padahal tidak ada di GROUP BY — ini error di MySQL strict mode.
        return $barangModel
            ->select('barang.nama_barang, kategori.nama_kategori as kategori, barang.satuan,
                      barang.berat_per_satuan, barang.satuan_berat, barang.bisa_dipecah')
            ->join('kategori', 'kategori.id = barang.id_kategori')
            ->where('barang.status', 'active')
            ->where('barang.id IN (SELECT MIN(id) FROM barang WHERE status = "active" GROUP BY nama_barang)', null, false)
            ->orderBy('barang.nama_barang', 'ASC')
            ->findAll();
    }

    private function buildActionButtons(array $bm): string
    {
        $detailUrl = site_url('transaksi/barang-masuk/detail/' . $bm['id']);
        $editUrl   = site_url('transaksi/barang-masuk/edit/' . $bm['id']);
        $deleteUrl = site_url('transaksi/barang-masuk/delete/' . $bm['id']);

        $buttons  = '<div class="dm-action-group">';
        $buttons .= '<a href="' . $detailUrl . '" class="dm-btn-action view" title="Lihat Detail"><i class="fa-solid fa-eye"></i></a>';

        if ($bm['is_used']) {
            $buttons .= '<button type="button" class="dm-btn-action lock" disabled title="Sudah Digunakan"><i class="fa-solid fa-lock"></i></button>';
        } else {
            $buttons .= '<a href="' . $editUrl . '" class="dm-btn-action edit" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>';
            $buttons .= '<button type="button" class="dm-btn-action del btn-delete-dm" title="Hapus"'
                      . ' data-id="' . $bm['id'] . '" data-url="' . $deleteUrl . '">'
                      . '<i class="fa-solid fa-trash"></i></button>';
        }

        $buttons .= '</div>';
        return $buttons;
    }
}