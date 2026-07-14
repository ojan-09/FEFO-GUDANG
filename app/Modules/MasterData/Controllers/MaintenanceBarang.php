<?php

namespace App\Modules\MasterData\Controllers;

use App\Controllers\BaseController;
use App\Modules\MasterData\Models\BarangModel;
use App\Modules\Transactions\Models\BatchModel;

class MaintenanceBarang extends BaseController
{
    protected $barangModel;
    protected $batchModel;

    public function __construct()
    {
        $this->barangModel = new BarangModel();
        $this->batchModel = new BatchModel();
    }

    public function index()
    {
        $barang = $this->barangModel->orderBy('nama_barang', 'ASC')->findAll();
        
        // Count batches for each barang
        foreach ($barang as &$b) {
            $b['jumlah_batch'] = $this->batchModel->where('id_barang', $b['id'])->countAllResults();
        }

        $data = [
            'title' => 'Maintenance Master Barang',
            'barang' => $barang,
        ];

        return view('App\Modules\MasterData\Views\maintenance_barang\index', $data);
    }

    public function rename($id)
    {
        $barang = $this->barangModel->find($id);
        if (!$barang) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $namaBaru = trim($this->request->getPost('nama_barang') ?? '');
        if (empty($namaBaru)) {
            return redirect()->back()->with('error', 'Nama barang tidak boleh kosong.');
        }

        $this->barangModel->update($id, ['nama_barang' => $namaBaru]);

        return redirect()->back()->with('success', 'Nama barang berhasil diperbarui.');
    }

    public function merge()
    {
        $targetId = $this->request->getPost('target_id');
        $sourceIds = $this->request->getPost('source_ids'); // array of ids to merge into target

        if (empty($targetId) || empty($sourceIds)) {
            return redirect()->back()->with('error', 'Pilih minimal satu barang untuk digabungkan ke barang target.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        foreach ($sourceIds as $sId) {
            if ($sId == $targetId) continue;
            
            // Move batches to target
            $this->batchModel->where('id_barang', $sId)->set(['id_barang' => $targetId])->update();
            
            // Delete source barang
            $this->barangModel->delete($sId);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menggabungkan barang.');
        }

        return redirect()->back()->with('success', 'Barang berhasil digabungkan.');
    }
}

