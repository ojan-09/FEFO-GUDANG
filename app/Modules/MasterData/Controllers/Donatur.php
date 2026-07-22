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
            'title'   => 'Data Donatur'
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
        return redirect()->to('masterdata/donatur')->with('success', 'Data Donatur berhasil dihapus.');
    }

    /**
     * AJAX endpoint untuk DataTables server-side
     */
    public function ajaxData()
    {
        if (!$this->request->isAJAX()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $postData = $this->request->getPost();
        $list     = $this->donaturModel->getDatatables($postData);
        $data     = [];
        $no       = $postData['start'];

        foreach ($list as $row) {
            $no++;
            $rowData = [];

            $rowData[] = '<div class="text-center text-secondary">' . $no . '</div>';
            $rowData[] = '<span class="fw-semibold" style="color:#0f172a;">' . esc($row['nama_donatur']) . '</span>';
            $rowData[] = '<span class="badge" style="background:#e0f2fe; color:#0284c7;">' . esc($row['jenis_donatur']) . '</span>';
            $rowData[] = '<span style="color:#475569;">' . esc($row['kontak']) . '</span>';
            $rowData[] = '<span style="color:#475569;">' . esc($row['email']) . '</span>';
            
            $aksi = '<div class="don-actions">
                        <a href="' . site_url('masterdata/donatur/edit/' . $row['id']) . '" class="don-action-btn don-action-edit" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                        <a href="' . site_url('masterdata/donatur/delete/' . $row['id']) . '" class="don-action-btn don-action-delete" title="Hapus" onclick="return confirm(\'Yakin ingin menghapus donatur ini?\')"><i class="fa-solid fa-trash"></i></a>
                     </div>';
            $rowData[] = $aksi;
            $data[] = $rowData;
        }

        $output = [
            "draw"            => isset($postData['draw']) ? intval($postData['draw']) : 0,
            "recordsTotal"    => $this->donaturModel->countAllData(),
            "recordsFiltered" => $this->donaturModel->countFiltered($postData),
            "data"            => $data,
            csrf_token()      => csrf_hash()
        ];

        return $this->response->setJSON($output);
    }
}
