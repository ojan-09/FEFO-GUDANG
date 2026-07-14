<?php

namespace App\Modules\MasterData\Controllers;

use App\Controllers\BaseController;
use App\Modules\MasterData\Models\DonaturModel;

class Donatur extends BaseController
{
    protected $donaturModel;

    public function __construct()
    {
        $this->donaturModel = new DonaturModel();
    }

    public function index()
    {
        $data = [
            'title'   => 'Data Donatur',
            'donatur' => $this->donaturModel->orderBy('id', 'DESC')->findAll(),
        ];
        return view('App\Modules\MasterData\Views\donatur\index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Donatur',
        ];
        return view('App\Modules\MasterData\Views\donatur\form', $data);
    }

    public function store()
    {
        $rules = [
            'nama_donatur'  => 'required|min_length[3]|max_length[150]',
            'jenis_donatur' => 'required|in_list[Individu,Perusahaan/Organisasi]',
            'kontak'        => 'required|max_length[50]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->donaturModel->insert([
            'nama_donatur'  => $this->request->getPost('nama_donatur'),
            'jenis_donatur' => $this->request->getPost('jenis_donatur'),
            'kontak'        => $this->request->getPost('kontak'),
            'email'         => $this->request->getPost('email'),
            'alamat'        => $this->request->getPost('alamat'),
        ]);

        return redirect()->to('/masterdata/donatur')->with('success', 'Donatur berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $donatur = $this->donaturModel->find($id);
        if (!$donatur) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Donatur tidak ditemukan.');
        }

        $data = [
            'title'   => 'Edit Donatur',
            'donatur' => $donatur,
        ];
        return view('App\Modules\MasterData\Views\donatur\form', $data);
    }

    public function update($id)
    {
        $rules = [
            'nama_donatur'  => 'required|min_length[3]|max_length[150]',
            'jenis_donatur' => 'required|in_list[Individu,Perusahaan/Organisasi]',
            'kontak'        => 'required|max_length[50]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->donaturModel->update($id, [
            'nama_donatur'  => $this->request->getPost('nama_donatur'),
            'jenis_donatur' => $this->request->getPost('jenis_donatur'),
            'kontak'        => $this->request->getPost('kontak'),
            'email'         => $this->request->getPost('email'),
            'alamat'        => $this->request->getPost('alamat'),
        ]);

        return redirect()->to('/masterdata/donatur')->with('success', 'Donatur berhasil diperbarui.');
    }

    public function delete($id)
    {
        $donatur = $this->donaturModel->find($id);
        if (!$donatur) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Donatur tidak ditemukan.');
        }

        $this->donaturModel->delete($id);
        return redirect()->to('/masterdata/donatur')->with('success', 'Donatur berhasil dihapus.');
    }
}

