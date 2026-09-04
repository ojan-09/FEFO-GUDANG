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
            'title'   => 'Master Wilayah'
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
            'nama_wilayah'   => 'required|max_length[150]',
            'status'         => 'required|in_list[Aktif,Nonaktif]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $nama_wilayah = trim($this->request->getPost('nama_wilayah'));
        $existing = $this->wilayahModel->where('nama_wilayah', $nama_wilayah)->first();
        if ($existing) {
            return redirect()->back()->withInput()->with('errors', ['nama_wilayah' => 'Nama Wilayah sudah terdaftar.']);
        }

        $this->wilayahModel->insert([
            'nama_wilayah'   => trim($this->request->getPost('nama_wilayah')),
            'status'         => $this->request->getPost('status'),
        ]);

        helper('format'); clear_dashboard_cache();
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
            'nama_wilayah'   => "required|max_length[150]",
            'status'         => 'required|in_list[Aktif,Nonaktif]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $nama_wilayah = trim($this->request->getPost('nama_wilayah'));
        $existing = $this->wilayahModel->where('nama_wilayah', $nama_wilayah)->where('id !=', $id)->first();
        if ($existing) {
            return redirect()->back()->withInput()->with('errors', ['nama_wilayah' => 'Nama Wilayah sudah terdaftar.']);
        }

        $this->wilayahModel->update($id, [
            'nama_wilayah'   => trim($this->request->getPost('nama_wilayah')),
            'status'         => $this->request->getPost('status'),
        ]);

        helper('format'); clear_dashboard_cache();
        return redirect()->to('/masterdata/wilayah')->with('success', 'Data Wilayah berhasil diubah.');
    }

    /**
     * Soft delete wilayah (dengan pengecekan relasi)
     */
    public function delete($id)
    {
        $wilayah = $this->wilayahModel->find($id);
        if (!$wilayah) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => false, 'message' => 'Wilayah tidak ditemukan.']);
            }
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Wilayah tidak ditemukan.');
        }

        // Cek relasi dengan transaksi Penyaluran Barang (barang_keluar)
        $barangKeluarModel = new BarangKeluarModel();
        $isUsed = $barangKeluarModel->where('id_wilayah', $id)->countAllResults();

        if ($isUsed > 0) {
            $msg = 'Wilayah tidak dapat dihapus karena sudah digunakan pada transaksi Penyaluran Barang. Silakan ubah status menjadi Nonaktif jika sudah tidak digunakan.';
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => false, 'message' => $msg]);
            }
            return redirect()->to(site_url('masterdata/wilayah'))->with('error', $msg);
        }

        $this->wilayahModel->delete($id);

        helper('format'); clear_dashboard_cache();

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['status' => true, 'message' => 'Data Wilayah berhasil dihapus.']);
        }

        return redirect()->to(site_url('masterdata/wilayah'))->with('success', 'Data Wilayah berhasil dihapus.');
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
        $list     = $this->wilayahModel->getDatatables($postData);
        $data     = [];
        $no       = $postData['start'];

        foreach ($list as $row) {
            $no++;
            $rowData = [];

            $rowData[] = '<div class="text-center wil-cell-no">' . $no . '</div>';
            $rowData[] = '<span class="wil-cell-name">' . esc($row['nama_wilayah']) . '</span>';

            $statusBadge = $row['status'] == 'Aktif'
                ? '<span class="wil-badge wil-badge-aktif"><i class="fa-solid fa-check me-1"></i>Aktif</span>'
                : '<span class="wil-badge wil-badge-nonaktif"><i class="fa-solid fa-xmark me-1"></i>Nonaktif</span>';

            $rowData[] = $statusBadge;

            $aksi = '<div class="wil-actions">
                        <a href="' . site_url('masterdata/wilayah/edit/' . $row['id']) . '" class="wil-action-btn wil-action-edit" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                        <a href="' . site_url('masterdata/wilayah/delete/' . $row['id']) . '" class="wil-action-btn wil-action-delete btn-delete-swal" title="Hapus" data-confirm-text="Yakin ingin menghapus wilayah ini?"><i class="fa-solid fa-trash"></i></a>
                    </div>';
            $rowData[] = $aksi;
            $data[] = $rowData;
        }

        $output = [
            "draw"            => isset($postData['draw']) ? intval($postData['draw']) : 0,
            "recordsTotal"    => $this->wilayahModel->countAllData(),
            "recordsFiltered" => $this->wilayahModel->countFiltered($postData),
            "data"            => $data,
            csrf_token()      => csrf_hash()
        ];

        return $this->response->setJSON($output);
    }
}

