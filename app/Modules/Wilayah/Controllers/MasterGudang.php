<?php

namespace App\Modules\Wilayah\Controllers;

use App\Controllers\BaseController;
use App\Modules\Wilayah\Models\MasterGudangWilayahModel;
use Myth\Auth\Models\UserModel;

class MasterGudang extends BaseController
{
    protected $gudangModel;
    protected $userModel;

    public function __construct()
    {
        $this->gudangModel = new MasterGudangWilayahModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Master Gudang Wilayah',
            'gudang' => $this->gudangModel->findAll()
        ];
        return view('App\Modules\Wilayah\Views\master\index', $data);
    }

    public function store()
    {
        $rules = [
            'nama' => 'required',
            'provinsi' => 'required',
            'kota' => 'required',
            'alamat' => 'required',
            'pic' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->gudangModel->save([
            'nama' => $this->request->getPost('nama'),
            'provinsi' => $this->request->getPost('provinsi'),
            'kota' => $this->request->getPost('kota'),
            'alamat' => $this->request->getPost('alamat'),
            'pic' => $this->request->getPost('pic'),
            'telepon' => $this->request->getPost('telepon'),
            'status' => $this->request->getPost('status') ?? 'Aktif',
        ]);

        // Invalidate Gudang Wilayah list cache
        cache()->delete('gudang_wilayah_list_all');

        helper('format'); clear_dashboard_cache();
        return redirect()->to('wilayah/master')->with('success', 'Gudang berhasil ditambahkan.');
    }

    public function update($id)
    {
        $rules = [
            'nama' => 'required',
            'provinsi' => 'required',
            'kota' => 'required',
            'alamat' => 'required',
            'pic' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->gudangModel->update($id, [
            'nama' => $this->request->getPost('nama'),
            'provinsi' => $this->request->getPost('provinsi'),
            'kota' => $this->request->getPost('kota'),
            'alamat' => $this->request->getPost('alamat'),
            'pic' => $this->request->getPost('pic'),
            'telepon' => $this->request->getPost('telepon'),
            'status' => $this->request->getPost('status'),
        ]);

        // Invalidate Gudang Wilayah list cache
        cache()->delete('gudang_wilayah_list_all');

        helper('format'); clear_dashboard_cache();
        return redirect()->to('wilayah/master')->with('success', 'Data Gudang berhasil diperbarui.');
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();

        // 1. Cek Ketersediaan Stok Aktif
        $stokCount = $db->table('stok_gudang_wilayah')
            ->where('id_gudang', $id)
            ->where('jumlah >', 0)
            ->countAllResults();

        if ($stokCount > 0) {
            return redirect()->to('wilayah/master')->with('error', 'Gudang tidak dapat dihapus karena masih memiliki stok barang aktif (' . $stokCount . ' item barang). Silakan kosongkan atau salurkan stok terlebih dahulu.');
        }

        // 2. Cek Riwayat Transaksi Barang Masuk
        $bmCount = $db->table('barang_masuk_wilayah')
            ->where('id_gudang', $id)
            ->where('deleted_at', null)
            ->countAllResults();

        // 3. Cek Riwayat Transaksi Barang Keluar
        $bkCount = $db->table('barang_keluar_wilayah')
            ->where('id_gudang', $id)
            ->where('deleted_at', null)
            ->countAllResults();

        if ($bmCount > 0 || $bkCount > 0) {
            $totalTx = $bmCount + $bkCount;
            return redirect()->to('wilayah/master')->with('error', 'Gudang tidak dapat dihapus karena memiliki riwayat transaksi aktif (' . $totalTx . ' transaksi). Gudang yang pernah beroperasi tidak boleh dihapus untuk menjaga integritas data.');
        }

        $this->gudangModel->delete($id);
        
        // Invalidate Gudang Wilayah list cache
        cache()->delete('gudang_wilayah_list_all');
        
        helper('format'); clear_dashboard_cache();
        return redirect()->to('wilayah/master')->with('success', 'Gudang berhasil dihapus.');
    }
}
