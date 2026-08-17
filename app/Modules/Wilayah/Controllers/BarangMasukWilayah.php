<?php

namespace App\Modules\Wilayah\Controllers;

use App\Controllers\BaseController;
use App\Modules\Wilayah\Models\BarangMasukWilayahModel;
use App\Modules\Wilayah\Models\MasterGudangWilayahModel;
use App\Modules\Wilayah\Models\MasterBarangWilayahModel;
use App\Modules\MasterData\Models\DonaturModel;
use App\Modules\MasterData\Models\KategoriModel;
use App\Modules\Wilayah\Services\BarangMasukWilayahService;

class BarangMasukWilayah extends BaseController
{
    protected $masukModel;
    protected $gudangModel;
    protected $masterBarangModel;
    protected $donaturModel;
    protected $kategoriModel;
    protected $masukService;

    public function __construct()
    {
        $this->masukModel        = new BarangMasukWilayahModel();
        $this->gudangModel       = new MasterGudangWilayahModel();
        $this->masterBarangModel = new MasterBarangWilayahModel();
        $this->donaturModel      = new DonaturModel();
        $this->kategoriModel     = new KategoriModel();
        $this->masukService      = new BarangMasukWilayahService();
    }

    public function index()
    {
        $isAdmin = function_exists('in_groups') ? in_groups('Administrator') : true;
        $userGudangId = (function_exists('user') && user()) ? user()->id_gudang_wilayah : null;
        $idGudang = null;
        $gudangInfo = null;

        if ($isAdmin) {
            $idGudang = $this->request->getGet('id_gudang');
            if (empty($idGudang)) {
                $data = [
                    'title'  => 'Pilih Gudang Wilayah (Barang Masuk)',
                    'action' => site_url('wilayah/masuk'),
                    'gudang' => $this->gudangModel->where('status', 'Aktif')->findAll()
                ];
                return view('App\Modules\Wilayah\Views\select_gudang', $data);
            }
            $gudangInfo = $this->gudangModel->find($idGudang);
        } else {
            $idGudang = $userGudangId;
        }

        $subtitle = 'Data riwayat barang masuk Gudang Wilayah';
        if ($isAdmin && $gudangInfo) {
            $subtitle .= ' - ' . esc($gudangInfo['nama']) . ' (' . esc($gudangInfo['kota']) . ')';
        }

        $data = [
            'title'      => 'Data Barang Masuk Wilayah',
            'subtitle'   => $subtitle,
            'idGudang'   => $idGudang,
            'isAdmin'    => $isAdmin,
            'listGudang' => $this->gudangModel->where('status', 'Aktif')->findAll(),
        ];
        
        return view('App\Modules\Wilayah\Views\masuk\index', $data);
    }

    public function ajaxData()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $postData = $this->request->getPost();
        $isAdmin = function_exists('in_groups') ? in_groups('Administrator') : true;
        $userGudangId = (function_exists('user') && user()) ? user()->id_gudang_wilayah : null;

        if (!$isAdmin) {
            $postData['id_gudang'] = $userGudangId;
        }

        $list = $this->masukModel->getDatatables($postData);
        $data = [];

        foreach ($list as $t) {
            $data[] = [
                'id'            => $t['id'],
                'nomor_dokumen' => esc($t['nomor_dokumen']),
                'tanggal'       => date('d/m/Y', strtotime($t['tanggal'])),
                'nama_gudang'   => esc($t['nama_gudang'] ?? '-'),
                'kota'          => esc($t['kota'] ?? ''),
                'nama_donatur'  => esc($t['nama_donatur'] ?? '-'),
                'total_item'    => (int)$t['total_item'],
                'total_qty'     => (float)$t['total_qty'],
                'keterangan'    => esc($t['keterangan'] ?? ''),
                'can_delete'    => (bool)$t['can_delete'],
                'is_admin'      => $isAdmin,
            ];
        }

        $output = [
            'draw'            => (int)($postData['draw'] ?? 0),
            'recordsTotal'    => $this->masukModel->countAllData($postData['id_gudang'] ?? null),
            'recordsFiltered' => $this->masukModel->countFiltered($postData),
            'data'            => $data,
            'csrf_hash'       => csrf_hash(),
        ];

        return $this->response->setJSON($output);
    }

    public function create()
    {
        $isAdmin = in_groups('Administrator');
        $userGudangId = user()->id_gudang_wilayah;
        $allGudang = [];

        if ($isAdmin) {
            $allGudang = $this->gudangModel->where('status', 'Aktif')->findAll();
            $selectedGudangId = $this->request->getGet('id_gudang') ?: old('id_gudang');
            if (!$selectedGudangId) {
                $data = [
                    'title'  => 'Pilih Gudang Wilayah',
                    'gudang' => $allGudang,
                    'action' => site_url('wilayah/masuk/create')
                ];
                return view('App\Modules\Wilayah\Views\select_gudang', $data);
            }
            $userGudangId = $selectedGudangId;
        }

        $gudang = $this->gudangModel->where('id', $userGudangId)->findAll();
        if (empty($gudang)) {
            return redirect()->to('wilayah/masuk')->with('error', 'Gudang tidak valid.');
        }

        $kodeGudang = strtoupper(substr(str_replace('Gudang ', '', $gudang[0]['nama']), 0, 3)) . $gudang[0]['id'];
        $autoDoc = $kodeGudang . '-IN-' . date('ymd-Hi');

        $data = [
            'title'        => 'Input Barang Masuk Wilayah',
            'gudang'       => $gudang,
            'allGudang'    => $allGudang,
            'donatur'      => $this->donaturModel->orderBy('nama_donatur', 'ASC')->findAll(),
            'kategori'     => $this->kategoriModel->orderBy('nama_kategori', 'ASC')->findAll(),
            'barangList'   => $this->masterBarangModel->findAll(),
            'isAdmin'      => $isAdmin,
            'userGudangId' => $userGudangId,
            'oldItems'     => old('items') ?: [],
            'autoDoc'      => $autoDoc
        ];
        return view('App\Modules\Wilayah\Views\masuk\form', $data);
    }

    public function store()
    {
        $rules = [
            'id_gudang'       => 'required|integer',
            'id_donatur'      => 'required|integer',
            'tanggal'         => 'required|valid_date',
            'items.*.nama_barang' => 'required',
            'items.*.id_kategori' => 'required|integer',
            'items.*.jumlah'      => 'required|numeric|greater_than[0]',
            'items.*.satuan'      => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $items = $this->request->getPost('items') ?? [];
        if (empty($items)) {
            return redirect()->back()->withInput()->with('error', 'Minimal harus ada 1 barang dalam transaksi.');
        }

        $headerData = [
            'nomor_dokumen' => $this->request->getPost('nomor_dokumen') ?: '-',
            'id_gudang'     => $this->request->getPost('id_gudang'),
            'id_donatur'    => $this->request->getPost('id_donatur'),
            'tanggal'       => $this->request->getPost('tanggal'),
            'keterangan'    => $this->request->getPost('keterangan'),
        ];

        $user = user();
        $isAdmin = in_groups('Administrator');
        if (in_groups('Petugas Gudang')) {
            $headerData['id_gudang'] = $user->id_gudang_wilayah;
        }

        try {
            $this->masukService->processBarangMasuk($headerData, $items, $user->id, $this->request->getIPAddress());
            $redirectUrl = 'wilayah/masuk';
            if ($isAdmin && !empty($headerData['id_gudang'])) {
                $redirectUrl .= '?id_gudang=' . $headerData['id_gudang'];
            }
            helper('format'); clear_dashboard_cache();
        return redirect()->to($redirectUrl)->with('success', 'Barang masuk berhasil dicatat.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function detail($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $header = $this->masukModel
            ->select('barang_masuk_wilayah.*, master_gudang_wilayah.nama as nama_gudang, donatur.nama_donatur, users.username as operator')
            ->join('master_gudang_wilayah', 'master_gudang_wilayah.id = barang_masuk_wilayah.id_gudang')
            ->join('donatur', 'donatur.id = barang_masuk_wilayah.id_donatur', 'left')
            ->join('users', 'users.id = barang_masuk_wilayah.created_by', 'left')
            ->find($id);

        if (!$header) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Transaksi tidak ditemukan']);
        }

        $header['created_at_fmt'] = !empty($header['created_at']) ? date('d/m/Y H:i', strtotime($header['created_at'])) : '-';
        $header['updated_at_fmt'] = !empty($header['updated_at']) ? date('d/m/Y H:i', strtotime($header['updated_at'])) : $header['created_at_fmt'];

        $db = \Config\Database::connect();
        
        // Single JOIN Query to prevent N+1 queries
        $details = $db->table('detail_barang_masuk_wilayah dbm')
            ->select('dbm.*, b.kode_barang, b.nama_barang, k.nama_kategori, COALESCE(s.jumlah, 0) as stok_saat_ini')
            ->join('master_barang_wilayah b', 'b.id = dbm.id_barang')
            ->join('kategori k', 'k.id = b.id_kategori', 'left')
            ->join('stok_gudang_wilayah s', 's.id_gudang = ' . $db->escape($header['id_gudang']) . ' AND s.id_barang = dbm.id_barang', 'left')
            ->where('dbm.id_masuk', $id)
            ->get()->getResultArray();

        $totalItem = count($details);
        $totalMasuk = 0;
        $totalSisaStok = 0;
        $totalNilaiBarang = 0;
        $firstSatuan = !empty($details[0]['satuan']) ? $details[0]['satuan'] : 'Unit';

        foreach ($details as &$d) {
            $totalMasuk += (float) $d['jumlah'];
            $totalSisaStok += (float) $d['stok_saat_ini'];
            $subtotal = (float) ($d['subtotal_nilai'] ?? ($d['jumlah'] * ($d['harga_satuan'] ?? 0)));
            $d['subtotal_nilai_calc'] = $subtotal;
            $totalNilaiBarang += $subtotal;
        }
        unset($d);

        // Single Aggregate Query to check outgoing status
        $itemIds = array_column($details, 'id_barang');
        $hasKeluar = false;
        $totalTxKeluar = 0;

        if (!empty($itemIds)) {
            $keluarRow = $db->table('barang_keluar_wilayah bk')
                ->select('COUNT(DISTINCT bk.id) as total_tx')
                ->join('detail_barang_keluar_wilayah dbk', 'dbk.id_keluar = bk.id')
                ->where('bk.id_gudang', $header['id_gudang'])
                ->whereIn('dbk.id_barang', $itemIds)
                ->where('bk.deleted_at IS NULL')
                ->get()->getRowArray();

            $totalTxKeluar = $keluarRow ? (int) $keluarRow['total_tx'] : 0;
            $hasKeluar = ($totalTxKeluar > 0);
        }

        $firstItemId     = !empty($details[0]['id_barang']) ? $details[0]['id_barang'] : null;
        $firstKodeBarang = !empty($details[0]['kode_barang']) ? $details[0]['kode_barang'] : null;

        return $this->response->setJSON([
            'status' => 'success',
            'header' => $header,
            'details' => $details,
            'summary' => [
                'total_item'        => $totalItem . ' Barang',
                'total_masuk'       => number_format($totalMasuk, 0, ',', '.') . ' ' . $firstSatuan,
                'total_sisa_stok'   => number_format($totalSisaStok, 0, ',', '.') . ' ' . $firstSatuan,
                'total_nilai_barang'=> 'Rp ' . number_format($totalNilaiBarang, 0, ',', '.'),
                'has_keluar'        => $hasKeluar,
                'total_tx_keluar'   => $totalTxKeluar,
                'first_item_id'     => $firstItemId,
                'first_kode_barang' => $firstKodeBarang
            ]
        ]);
    }

    public function delete($id)
    {
        $header = $this->masukModel->find($id);

        if (!$header) {
            return redirect()->to('wilayah/masuk')->with('error', 'Transaksi tidak ditemukan.');
        }

        $isAdmin = in_groups('Administrator');
        $redirectUrl = 'wilayah/masuk';
        if ($isAdmin && !empty($header['id_gudang'])) {
            $redirectUrl .= '?id_gudang=' . $header['id_gudang'];
        }

        // Cek stok sebelum membatalkan Barang Masuk
        $db = \Config\Database::connect();
        $details = $db->table('detail_barang_masuk_wilayah')->where('id_masuk', $id)->get()->getResultArray();
        foreach ($details as $d) {
            $stok = $db->table('stok_gudang_wilayah')
                ->where('id_gudang', $header['id_gudang'])
                ->where('id_barang', $d['id_barang'])
                ->get()->getRowArray();
            
            if (!$stok || (float)$stok['jumlah'] < (float)$d['jumlah']) {
                return redirect()->to($redirectUrl)->with('error', 'Stok Gudang Wilayah tidak mencukupi untuk membatalkan transaksi ini! Barang sudah terpakai/keluar.');
            }
        }

        try {
            $user = user();
            $this->masukService->processDelete($id, $user->id, $this->request->getIPAddress());
            helper('format'); clear_dashboard_cache();
        return redirect()->to($redirectUrl)->with('success', 'Transaksi berhasil dibatalkan.');
        } catch (\Throwable $e) {
            return redirect()->to($redirectUrl)->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }
}
