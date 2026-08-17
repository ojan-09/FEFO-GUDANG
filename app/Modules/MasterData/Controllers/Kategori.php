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
            'title'    => 'Data Kategori'
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

        helper('format'); clear_dashboard_cache();
        return redirect()->to(site_url('masterdata/kategori'))->with('success', 'Kategori berhasil ditambahkan.');
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

        helper('format'); clear_dashboard_cache();
        return redirect()->to(site_url('masterdata/kategori'))->with('success', 'Kategori berhasil diperbarui.');
    }

    public function delete($id)
    {
        $kategori = $this->kategoriModel->find($id);
        if (!$kategori) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => false, 'message' => 'Kategori tidak ditemukan.']);
            }
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Kategori tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $isUsedBarang = $db->table('barang')->where('id_kategori', $id)->countAllResults();
        if ($isUsedBarang > 0) {
            $msg = "Kategori '" . esc($kategori['nama_kategori']) . "' tidak dapat dihapus karena masih digunakan oleh " . $isUsedBarang . " data Barang.";
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => false, 'message' => $msg]);
            }
            return redirect()->to(site_url('masterdata/kategori'))->with('error', $msg);
        }

        $this->kategoriModel->delete($id);
        helper('format'); clear_dashboard_cache();

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['status' => true, 'message' => 'Kategori berhasil dihapus.']);
        }

        return redirect()->to(site_url('masterdata/kategori'))->with('success', 'Kategori berhasil dihapus.');
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
        $list     = $this->kategoriModel->getDatatables($postData);
        $data     = [];
        $no       = $postData['start'];

        foreach ($list as $row) {
            $no++;
            $rowData = [];

            $rowData[] = '<div class="text-center">' . $no . '</div>';
            $rowData[] = '<span class="fw-semibold text-dark">' . esc($row['nama_kategori']) . '</span>';
            $rowData[] = '<span class="text-muted"><i class="fa-regular fa-calendar me-1"></i> ' . date('d M Y, H:i', strtotime($row['created_at'])) . '</span>';
            
            $aksi = '<div class="kat-actions">
                        <a href="' . site_url('masterdata/kategori/edit/' . $row['id']) . '" class="kat-action-btn kat-action-edit" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                        <button type="button" class="kat-action-btn kat-action-delete" onclick="confirmDeleteKategori(' . $row['id'] . ')" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                     </div>';
            $rowData[] = $aksi;
            $data[] = $rowData;
        }

        $output = [
            "draw"            => isset($postData['draw']) ? intval($postData['draw']) : 0,
            "recordsTotal"    => $this->kategoriModel->countAllData(),
            "recordsFiltered" => $this->kategoriModel->countFiltered($postData),
            "data"            => $data,
            csrf_token()      => csrf_hash()
        ];

        return $this->response->setJSON($output);
    }
}

