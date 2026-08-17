<?php

namespace App\Modules\Wilayah\Controllers;

use App\Controllers\BaseController;
use App\Modules\Wilayah\Models\MasterBarangWilayahModel;
use App\Modules\MasterData\Models\KategoriModel;

class MasterBarangWilayah extends BaseController
{
    protected $masterBarangModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->masterBarangModel = new MasterBarangWilayahModel();
        $this->kategoriModel = new KategoriModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Master Barang Wilayah'
        ];
        return view('App\Modules\Wilayah\Views\master_barang\index', $data);
    }

    public function ajaxList()
    {
        if ($this->request->isAJAX()) {
            $postData = $this->request->getPost();
            $data = $this->masterBarangModel->getDatatables($postData);
            
            $output = [
                "draw" => $postData['draw'],
                "recordsTotal" => $this->masterBarangModel->countAllData(),
                "recordsFiltered" => $this->masterBarangModel->countFiltered($postData),
                "data" => $data,
                csrf_token() => csrf_hash()
            ];

            return $this->response->setJSON($output);
        }
    }

    public function create()
    {
        $data = [
            'title'    => 'Tambah Master Barang Wilayah',
            'kategori' => $this->kategoriModel->orderBy('nama_kategori', 'ASC')->findAll(),
        ];
        return view('App\Modules\Wilayah\Views\master_barang\form', $data);
    }

    public function store()
    {
        $rules = [
            'kode_barang'      => 'required|is_unique[master_barang_wilayah.kode_barang]',
            'nama_barang'      => 'required',
            'id_kategori'      => 'required|integer',
            'satuan'           => 'required',
            'berat_per_satuan' => 'permit_empty|numeric',
            'satuan_berat'     => 'permit_empty|in_list[Gram,Kg]',
            'status'           => 'required|in_list[Aktif,Nonaktif]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->masterBarangModel->insert([
            'kode_barang'      => $this->request->getPost('kode_barang'),
            'nama_barang'      => $this->request->getPost('nama_barang'),
            'id_kategori'      => $this->request->getPost('id_kategori'),
            'satuan'           => $this->request->getPost('satuan'),
            'berat_per_satuan' => $this->request->getPost('berat_per_satuan') ? $this->request->getPost('berat_per_satuan') : null,
            'satuan_berat'     => $this->request->getPost('satuan_berat'),
            'status'           => $this->request->getPost('status'),
            'keterangan'       => $this->request->getPost('keterangan')
        ]);

        helper('format'); clear_dashboard_cache();
        return redirect()->to('wilayah/master-barang')->with('success', 'Master Barang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $barang = $this->masterBarangModel->find($id);
        if (!$barang) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title'    => 'Edit Master Barang Wilayah',
            'barang'   => $barang,
            'kategori' => $this->kategoriModel->orderBy('nama_kategori', 'ASC')->findAll(),
        ];
        return view('App\Modules\Wilayah\Views\master_barang\form', $data);
    }

    public function update($id)
    {
        $barang = $this->masterBarangModel->find($id);
        if (!$barang) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $rules = [
            'nama_barang'      => 'required',
            'id_kategori'      => 'required|integer',
            'satuan'           => 'required',
            'berat_per_satuan' => 'permit_empty|numeric',
            'satuan_berat'     => 'permit_empty|in_list[Gram,Kg]',
            'status'           => 'required|in_list[Aktif,Nonaktif]'
        ];

        // Check if kode_barang changed
        $kode_barang = $this->request->getPost('kode_barang');
        if ($kode_barang !== $barang['kode_barang']) {
            $rules['kode_barang'] = 'required|is_unique[master_barang_wilayah.kode_barang]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->masterBarangModel->update($id, [
            'kode_barang'      => $this->request->getPost('kode_barang'),
            'nama_barang'      => $this->request->getPost('nama_barang'),
            'id_kategori'      => $this->request->getPost('id_kategori'),
            'satuan'           => $this->request->getPost('satuan'),
            'berat_per_satuan' => $this->request->getPost('berat_per_satuan') ? $this->request->getPost('berat_per_satuan') : null,
            'satuan_berat'     => $this->request->getPost('satuan_berat'),
            'status'           => $this->request->getPost('status'),
            'keterangan'       => $this->request->getPost('keterangan')
        ]);

        helper('format'); clear_dashboard_cache();
        return redirect()->to('wilayah/master-barang')->with('success', 'Master Barang berhasil diupdate.');
    }

    public function delete($id)
    {
        $barang = $this->masterBarangModel->find($id);
        if (!$barang) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Barang tidak ditemukan']);
        }

        $db = \Config\Database::connect();

        // 1. Cek Ketersediaan Stok Aktif
        $stokCount = $db->table('stok_gudang_wilayah')
            ->where('id_barang', $id)
            ->where('jumlah >', 0)
            ->countAllResults();

        if ($stokCount > 0) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Barang tidak dapat dihapus karena masih memiliki stok aktif di Gudang Wilayah. Silakan kosongkan atau salurkan stok terlebih dahulu.'
            ]);
        }

        // 2. Cek Riwayat Detail Transaksi Masuk / Keluar
        $bmCount = $db->table('detail_barang_masuk_wilayah')->where('id_barang', $id)->countAllResults();
        $bkCount = $db->table('detail_barang_keluar_wilayah')->where('id_barang', $id)->countAllResults();

        if ($bmCount > 0 || $bkCount > 0) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Barang tidak dapat dihapus karena memiliki riwayat transaksi (' . ($bmCount + $bkCount) . ' record). Barang yang pernah digunakan transaksi tidak boleh dihapus untuk integritas data.'
            ]);
        }

        $this->masterBarangModel->delete($id);
        return $this->response->setJSON(['status' => 'success', 'message' => 'Barang berhasil dihapus']);
    }
}
