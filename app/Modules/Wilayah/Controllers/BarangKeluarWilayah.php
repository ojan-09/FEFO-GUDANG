<?php

namespace App\Modules\Wilayah\Controllers;

use App\Controllers\BaseController;
use App\Modules\Wilayah\Models\BarangKeluarWilayahModel;
use App\Modules\Wilayah\Models\StokGudangWilayahModel;
use App\Modules\Wilayah\Models\MasterGudangWilayahModel;
use App\Modules\Wilayah\Models\MasterBarangWilayahModel;
use App\Modules\Wilayah\Services\BarangKeluarWilayahService;

class BarangKeluarWilayah extends BaseController
{
    protected $keluarModel;
    protected $stokModel;
    protected $gudangModel;
    protected $barangModel;
    protected $keluarService;

    public function __construct()
    {
        $this->keluarModel = new BarangKeluarWilayahModel();
        $this->stokModel   = new StokGudangWilayahModel();
        $this->gudangModel = new MasterGudangWilayahModel();
        $this->keluarService= new BarangKeluarWilayahService();
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
                    'title'  => 'Pilih Gudang Wilayah (Barang Keluar)',
                    'action' => site_url('wilayah/keluar'),
                    'gudang' => $this->gudangModel->where('status', 'Aktif')->findAll()
                ];
                return view('App\Modules\Wilayah\Views\select_gudang', $data);
            }
            $gudangInfo = $this->gudangModel->find($idGudang);
        } else {
            $idGudang = $userGudangId;
        }

        $subtitle = 'Data riwayat barang keluar Gudang Wilayah';
        if ($isAdmin && $gudangInfo) {
            $subtitle .= ' - ' . esc($gudangInfo['nama']) . ' (' . esc($gudangInfo['kota']) . ')';
        }

        $data = [
            'title'      => 'Data Barang Keluar Wilayah',
            'subtitle'   => $subtitle,
            'idGudang'   => $idGudang,
            'isAdmin'    => $isAdmin,
            'listGudang' => $this->gudangModel->where('status', 'Aktif')->findAll(),
        ];
        
        return view('App\Modules\Wilayah\Views\keluar\index', $data);
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

        $list = $this->keluarModel->getDatatables($postData);
        $data = [];

        foreach ($list as $t) {
            $data[] = [
                'id'            => $t['id'],
                'nomor_dokumen' => esc($t['nomor_dokumen']),
                'tanggal'       => date('d/m/Y', strtotime($t['tanggal'])),
                'nama_gudang'   => esc($t['nama_gudang'] ?? '-'),
                'kota'          => esc($t['kota'] ?? ''),
                'tujuan'        => esc($t['tujuan'] ?? '-'),
                'total_item'    => (int)$t['total_item'],
                'total_qty'     => (float)$t['total_qty'],
                'keterangan'    => esc($t['keterangan'] ?? ''),
                'is_admin'      => $isAdmin,
            ];
        }

        $output = [
            'draw'            => (int)($postData['draw'] ?? 0),
            'recordsTotal'    => $this->keluarModel->countAllData($postData['id_gudang'] ?? null),
            'recordsFiltered' => $this->keluarModel->countFiltered($postData),
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
            $selectedGudangId = $this->request->getGet('id_gudang');
            if (!$selectedGudangId) {
                $data = [
                    'title'  => 'Pilih Gudang Wilayah',
                    'gudang' => $allGudang,
                    'action' => site_url('wilayah/keluar/create')
                ];
                return view('App\Modules\Wilayah\Views\select_gudang', $data);
            }
            $userGudangId = $selectedGudangId;
        }

        $gudang = $this->gudangModel->where('id', $userGudangId)->findAll();
        if (empty($gudang)) {
            return redirect()->to('wilayah/keluar')->with('error', 'Gudang tidak valid.');
        }

            $kodeGudang = strtoupper(substr(str_replace('Gudang ', '', $gudang[0]['nama']), 0, 3)) . $gudang[0]['id'];
            $autoDoc = $kodeGudang . '-OUT-' . date('ymd-Hi');

            $data = [
                'title'           => 'Input Barang Keluar Wilayah',
                'gudang'          => $gudang,
                'allGudang'       => $allGudang,
                'isAdmin'         => $isAdmin,
                'userGudangId'    => $userGudangId,
                'oldItems'        => old('items') ?: [],
                'barang_tersedia' => $this->keluarService->getBarangAvailable($userGudangId),
                'autoDoc'         => $autoDoc
            ];
            return view('App\Modules\Wilayah\Views\keluar\form', $data);
        }

    public function ajaxGetBarangByGudang()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }
        $idGudang = $this->request->getPost('id_gudang');
        if (!$idGudang) {
            return $this->response->setJSON([]);
        }
        $barang = $this->keluarService->getBarangAvailable($idGudang);
        return $this->response->setJSON($barang);
    }

    public function store()
    {
        $rules = [
            'id_gudang'       => 'required|integer',
            'tujuan'          => 'required',
            'tanggal'         => 'required|valid_date',
            'items.*.id_barang' => 'required|integer',
            'items.*.jumlah'    => 'required|numeric|greater_than[0]'
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
            'tujuan'        => $this->request->getPost('tujuan'),
            'tanggal'       => $this->request->getPost('tanggal'),
            'keterangan'    => $this->request->getPost('keterangan'),
        ];

        $user = user();
        $isAdmin = in_groups('Administrator');
        if (in_groups('Petugas Gudang')) {
            $headerData['id_gudang'] = $user->id_gudang_wilayah;
        }

        try {
            $this->keluarService->processBarangKeluar($headerData, $items, $user->id, $this->request->getIPAddress());
            $redirectUrl = 'wilayah/keluar';
            if ($isAdmin && !empty($headerData['id_gudang'])) {
                $redirectUrl .= '?id_gudang=' . $headerData['id_gudang'];
            }
            helper('format'); clear_dashboard_cache();
        return redirect()->to($redirectUrl)->with('success', 'Barang keluar berhasil dicatat.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function detail($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $header = $this->keluarModel
            ->select('barang_keluar_wilayah.*, master_gudang_wilayah.nama as nama_gudang, users.username as operator')
            ->join('master_gudang_wilayah', 'master_gudang_wilayah.id = barang_keluar_wilayah.id_gudang')
            ->join('users', 'users.id = barang_keluar_wilayah.created_by', 'left')
            ->find($id);

        if (!$header) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Transaksi tidak ditemukan']);
        }

        $detailModel = new \App\Modules\Wilayah\Models\DetailBarangKeluarWilayahModel();
        $details = $detailModel
            ->select('detail_barang_keluar_wilayah.*, master_barang_wilayah.kode_barang, master_barang_wilayah.nama_barang, kategori.nama_kategori')
            ->join('master_barang_wilayah', 'master_barang_wilayah.id = detail_barang_keluar_wilayah.id_barang')
            ->join('kategori', 'kategori.id = master_barang_wilayah.id_kategori', 'left')
            ->where('id_keluar', $id)
            ->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'header' => $header,
            'details' => $details
        ]);
    }

    public function delete($id)
    {
        $user = user();
        $header = $this->keluarModel->find($id);

        $isAdmin = function_exists('in_groups') ? in_groups('Administrator') : true;
        $redirectUrl = 'wilayah/keluar';
        if ($isAdmin && !empty($header['id_gudang'])) {
            $redirectUrl .= '?id_gudang=' . $header['id_gudang'];
        }

        if (!$header) {
            return redirect()->to($redirectUrl)->with('error', 'Transaksi tidak ditemukan.');
        }

        // Validasi Gudang Hak Akses
        if (!in_groups('Administrator') && $header['id_gudang'] != $user->id_gudang_wilayah) {
            return redirect()->to($redirectUrl)->with('error', 'Anda tidak berhak menghapus transaksi gudang lain.');
        }

        try {
            $this->keluarService->deleteTransaksi($id, $user->id, $this->request->getIPAddress());

            \App\Libraries\ActivityLogger::log(
                'Hapus Barang Keluar Wilayah',
                'Barang Keluar Wilayah',
                "Menghapus Transaksi Barang Keluar Wilayah\nNo. {$header['nomor_dokumen']} (Gudang ID: {$header['id_gudang']})"
            );

            helper('format'); clear_dashboard_cache();
        return redirect()->to($redirectUrl)->with('success', 'Transaksi berhasil dihapus dan stok dikembalikan.');
        } catch (\RuntimeException $e) {
            return redirect()->to($redirectUrl)->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            return redirect()->to($redirectUrl)->with('error', 'Terjadi kesalahan sistem saat menghapus transaksi.');
        }
    }
}
