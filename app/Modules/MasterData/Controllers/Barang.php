<?php

namespace App\Modules\MasterData\Controllers;

use App\Controllers\BaseController;
use App\Modules\MasterData\Models\BarangModel;
use App\Modules\MasterData\Models\KategoriModel;

class Barang extends BaseController
{
    protected $barangModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->barangModel   = new BarangModel();
        $this->kategoriModel = new KategoriModel();
    }

    public function index()
    {
        // Join dengan tabel kategori untuk menampilkan nama_kategori
        $barang = $this->barangModel
            ->select('barang.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.id = barang.id_kategori')
            ->orderBy('barang.id', 'DESC')
            ->findAll();

        $data = [
            'title'  => 'Data Barang',
            'barang' => $barang,
        ];
        return view('App\Modules\MasterData\Views\barang\index', $data);
    }

    public function create()
    {
        $data = [
            'title'       => 'Tambah Barang',
            'kode_barang' => $this->generateKodeBarang(),
            'kategori'    => $this->kategoriModel->orderBy('nama_kategori', 'ASC')->findAll(),
        ];
        return view('App\Modules\MasterData\Views\barang\form', $data);
    }

    public function store()
    {
        $rules = [
            'id_kategori'      => 'required|integer',
            'nama_barang'      => 'required|min_length[3]|max_length[100]|is_unique[barang.nama_barang]',
            'satuan'           => 'required|in_list[Karung,Dus,Box,Pack,Pcs,Botol,Kaleng,Sak,Tray,Pouch]',
            'berat_per_satuan' => 'required|decimal|greater_than[0]',
            'satuan_berat'     => 'required|in_list[Gram,Kg]',
            'minimum_stok'     => 'required|integer|greater_than_equal_to[0]',
        ];

        $errors = [
            'nama_barang' => [
                'required'   => 'Nama Barang wajib diisi.',
                'min_length' => 'Nama Barang minimal 3 karakter.',
                'max_length' => 'Nama Barang maksimal 100 karakter.',
                'is_unique'  => 'Nama Barang sudah digunakan.',
            ],
            'id_kategori' => [
                'required' => 'Kategori wajib dipilih.',
            ],
            'satuan' => [
                'required' => 'Satuan wajib dipilih.',
                'in_list'  => 'Satuan tidak valid.',
            ],
            'berat_per_satuan' => [
                'required'     => 'Berat per satuan wajib diisi.',
                'decimal'      => 'Berat harus berupa angka desimal.',
                'greater_than' => 'Berat harus lebih besar dari 0.',
            ],
            'satuan_berat' => [
                'required' => 'Satuan berat wajib dipilih.',
                'in_list'  => 'Satuan berat tidak valid.',
            ],
            'minimum_stok' => [
                'required'               => 'Minimum stok wajib diisi.',
                'integer'                => 'Minimum stok harus berupa bilangan bulat.',
                'greater_than_equal_to' => 'Minimum stok tidak boleh negatif.',
            ],
        ];

        // Trim nama_barang
        $namaBarang = trim($this->request->getPost('nama_barang') ?? '');
        $_POST['nama_barang'] = $namaBarang;

        if (!$this->validate($rules, $errors)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $kodeBarang = $this->generateKodeBarang();

        $this->barangModel->insert([
            'kode_barang'      => $kodeBarang,
            'id_kategori'      => $this->request->getPost('id_kategori'),
            'nama_barang'      => $namaBarang,
            'satuan'           => $this->request->getPost('satuan'),
            'berat_per_satuan' => $this->request->getPost('berat_per_satuan'),
            'satuan_berat'     => $this->request->getPost('satuan_berat'),
            'minimum_stok'     => $this->request->getPost('minimum_stok'),
        ]);

        return redirect()->to(site_url('masterdata/barang'))->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $barang = $this->barangModel->find($id);
        if (!$barang) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Barang tidak ditemukan.');
        }

        $data = [
            'title'    => 'Edit Barang',
            'barang'   => $barang,
            'kategori' => $this->kategoriModel->orderBy('nama_kategori', 'ASC')->findAll(),
        ];
        return view('App\Modules\MasterData\Views\barang\form', $data);
    }

    public function update($id)
    {
        $rules = [
            'id_kategori'      => 'required|integer',
            'nama_barang'      => "required|min_length[3]|max_length[100]|is_unique[barang.nama_barang,id,{$id}]",
            'satuan'           => 'required|in_list[Karung,Dus,Box,Pack,Pcs,Botol,Kaleng,Sak,Tray,Pouch]',
            'berat_per_satuan' => 'required|decimal|greater_than[0]',
            'satuan_berat'     => 'required|in_list[Gram,Kg]',
            'minimum_stok'     => 'required|integer|greater_than_equal_to[0]',
        ];

        $errors = [
            'nama_barang' => [
                'required'   => 'Nama Barang wajib diisi.',
                'min_length' => 'Nama Barang minimal 3 karakter.',
                'max_length' => 'Nama Barang maksimal 100 karakter.',
                'is_unique'  => 'Nama Barang sudah digunakan.',
            ],
            'id_kategori' => [
                'required' => 'Kategori wajib dipilih.',
            ],
            'satuan' => [
                'required' => 'Satuan wajib dipilih.',
                'in_list'  => 'Satuan tidak valid.',
            ],
            'berat_per_satuan' => [
                'required'     => 'Berat per satuan wajib diisi.',
                'decimal'      => 'Berat harus berupa angka desimal.',
                'greater_than' => 'Berat harus lebih besar dari 0.',
            ],
            'satuan_berat' => [
                'required' => 'Satuan berat wajib dipilih.',
                'in_list'  => 'Satuan berat tidak valid.',
            ],
            'minimum_stok' => [
                'required'               => 'Minimum stok wajib diisi.',
                'integer'                => 'Minimum stok harus berupa bilangan bulat.',
                'greater_than_equal_to' => 'Minimum stok tidak boleh negatif.',
            ],
        ];

        $namaBarang = trim($this->request->getPost('nama_barang') ?? '');
        $_POST['nama_barang'] = $namaBarang;

        if (!$this->validate($rules, $errors)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $barang = $this->barangModel->find($id);
        if (!$barang) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Barang tidak ditemukan.');
        }

        $this->barangModel->update($id, [
            'id_kategori'      => $this->request->getPost('id_kategori'),
            'nama_barang'      => $namaBarang,
            'satuan'           => $this->request->getPost('satuan'),
            'berat_per_satuan' => $this->request->getPost('berat_per_satuan'),
            'satuan_berat'     => $this->request->getPost('satuan_berat'),
            'minimum_stok'     => $this->request->getPost('minimum_stok'),
        ]);

        return redirect()->to(site_url('masterdata/barang'))->with('success', 'Barang berhasil diperbarui.');
    }

    public function delete($id)
    {
        $barang = $this->barangModel->find($id);
        if (!$barang) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Barang tidak ditemukan.');
        }

        $this->barangModel->delete($id);
        return redirect()->to(site_url('masterdata/barang'))->with('success', 'Barang berhasil dihapus.');
    }

    private function generateKodeBarang(): string
    {
        $last = $this->barangModel->orderBy('id', 'DESC')->first();
        if ($last && preg_match('/BRG-(\d+)/', $last['kode_barang'], $matches)) {
            $lastNumber = (int) $matches[1];
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        return 'BRG-' . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }
}


