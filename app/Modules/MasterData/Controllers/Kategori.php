<?php

namespace App\Modules\MasterData\Controllers;

use App\Controllers\BaseController;
use App\Modules\MasterData\Models\KategoriModel;

class Kategori extends BaseController
{
    protected $kategoriModel;

    public function __construct()
    {
        $this->kategoriModel = new KategoriModel();
    }

    public function index()
    {
        $data = [
            'title'    => 'Data Kategori',
            'kategori' => $this->kategoriModel->orderBy('id', 'DESC')->findAll(),
        ];
        return view('App\Modules\MasterData\Views\kategori\index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Kategori',
        ];
        return view('App\Modules\MasterData\Views\kategori\form', $data);
    }

    public function store()
    {
        $rules = [
            'nama_kategori' => 'required|min_length[3]|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->kategoriModel->insert([
            'nama_kategori' => $this->request->getPost('nama_kategori'),
        ]);

        return redirect()->to('/masterdata/kategori')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kategori = $this->kategoriModel->find($id);
        if (!$kategori) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Kategori tidak ditemukan.');
        }

        $data = [
            'title'    => 'Edit Kategori',
            'kategori' => $kategori,
        ];
        return view('App\Modules\MasterData\Views\kategori\form', $data);
    }

    public function update($id)
    {
        $rules = [
            'nama_kategori' => 'required|min_length[3]|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->kategoriModel->update($id, [
            'nama_kategori' => $this->request->getPost('nama_kategori'),
        ]);

        return redirect()->to('/masterdata/kategori')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function delete($id)
    {
        $kategori = $this->kategoriModel->find($id);
        if (!$kategori) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Kategori tidak ditemukan.');
        }

        $this->kategoriModel->delete($id);
        return redirect()->to('/masterdata/kategori')->with('success', 'Kategori berhasil dihapus.');
    }
}

