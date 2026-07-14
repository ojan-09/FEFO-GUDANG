<?php

namespace App\Modules\MasterData\Controllers;

use App\Controllers\BaseController;
use App\Modules\MasterData\Models\WilayahModel;
use App\Modules\Transactions\Models\BarangKeluarModel; // To check if used

class Wilayah extends BaseController
{
    protected $wilayahModel;

    public function __construct()
    {
        $this->wilayahModel = new WilayahModel();
    }

    /**
     * Tampilkan daftar wilayah
     */
    public function index()
    {
        $data = [
            'title'   => 'Master Wilayah',
            'wilayah' => $this->wilayahModel->orderBy('nama_wilayah', 'ASC')->findAll(),
        ];
        return view('App\Modules\MasterData\Views\wilayah\index', $data);
    }

    /**
     * Form tambah wilayah
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah Wilayah',
        ];
        return view('App\Modules\MasterData\Views\wilayah\form', $data);
    }

    /**
     * Simpan data wilayah baru
     */
    public function store()
    {
        $rules = [
            'nama_wilayah'   => 'required|max_length[150]|is_unique[wilayah.nama_wilayah]',
            'status'         => 'required|in_list[Aktif,Nonaktif]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->wilayahModel->insert([
            'nama_wilayah'   => trim($this->request->getPost('nama_wilayah')),
            'status'         => $this->request->getPost('status'),
        ]);

        return redirect()->to('/masterdata/wilayah')->with('success', 'Data Wilayah berhasil ditambahkan.');
    }

    /**
     * Form edit wilayah
     */
    public function edit($id)
    {
        $wilayah = $this->wilayahModel->find($id);
        if (!$wilayah) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Wilayah tidak ditemukan.');
        }

        $data = [
            'title'   => 'Edit Wilayah',
            'wilayah' => $wilayah,
        ];
        return view('App\Modules\MasterData\Views\wilayah\form', $data);
    }

    /**
     * Update data wilayah
     */
    public function update($id)
    {
        $wilayah = $this->wilayahModel->find($id);
        if (!$wilayah) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Wilayah tidak ditemukan.');
        }

        $rules = [
            'nama_wilayah'   => "required|max_length[150]|is_unique[wilayah.nama_wilayah,id,{$id}]",
            'status'         => 'required|in_list[Aktif,Nonaktif]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->wilayahModel->update($id, [
            'nama_wilayah'   => trim($this->request->getPost('nama_wilayah')),
            'status'         => $this->request->getPost('status'),
        ]);

        return redirect()->to('/masterdata/wilayah')->with('success', 'Data Wilayah berhasil diubah.');
    }

    /**
     * Soft delete wilayah (dengan pengecekan relasi)
     */
    public function delete($id)
    {
        $wilayah = $this->wilayahModel->find($id);
        if (!$wilayah) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Wilayah tidak ditemukan.');
        }

        // Cek relasi dengan transaksi Penyaluran Barang (barang_keluar)
        $barangKeluarModel = new BarangKeluarModel();
        $isUsed = $barangKeluarModel->where('id_wilayah', $id)->countAllResults();

        if ($isUsed > 0) {
            return redirect()->to('/masterdata/wilayah')->with('error', 'Wilayah tidak dapat dihapus karena sudah digunakan pada transaksi Penyaluran Barang. Silakan ubah status menjadi Nonaktif jika sudah tidak digunakan.');
        }

        $this->wilayahModel->delete($id);

        return redirect()->to('/masterdata/wilayah')->with('success', 'Data Wilayah berhasil dihapus.');
    }

}

