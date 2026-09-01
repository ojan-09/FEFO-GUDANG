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
        $this->batchModel  = new BatchModel();
    }

    public function index()
    {
        $barang = $this->barangModel->orderBy('nama_barang', 'ASC')->findAll();

        foreach ($barang as &$b) {
            $b['jumlah_batch'] = $this->batchModel->where('id_barang', $b['id'])->countAllResults();
        }

        return view('App\Modules\MasterData\Views\maintenance_barang\index', [
            'title'  => 'Maintenance Master Barang',
            'barang' => $barang,
        ]);
    }

    public function rename($id)
    {
        // Cast & validasi ID dari URL param
        $id = (int) $id;
        if ($id <= 0) {
            return redirect()->back()->with('error', 'ID tidak valid.');
        }

        $barang = $this->barangModel->find($id);
        if (!$barang) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $namaBaru = trim($this->request->getPost('nama_barang') ?? '');
        if (empty($namaBaru)) {
            return redirect()->back()->with('error', 'Nama barang tidak boleh kosong.');
        }

        // Batasi panjang nama
        if (mb_strlen($namaBaru) > 255) {
            return redirect()->back()->with('error', 'Nama barang terlalu panjang (maks 255 karakter).');
        }

        $this->barangModel->update($id, ['nama_barang' => $namaBaru]);

        return redirect()->back()->with('success', 'Nama barang berhasil diperbarui.');
    }

    public function merge()
    {
        // 1. Cast & validasi target_id
        $targetId  = (int) $this->request->getPost('target_id');
        $sourceRaw = $this->request->getPost('source_ids');

        if ($targetId <= 0) {
            return redirect()->back()->with('error', 'Target barang tidak valid.');
        }

        // 2. Pastikan source_ids array, cast semua ke int, filter <= 0
        $sourceIds = array_filter(
            array_map('intval', (array) $sourceRaw),
            fn($id) => $id > 0
        );
        $sourceIds = array_values(array_unique($sourceIds));

        if (empty($sourceIds)) {
            return redirect()->back()->with('error', 'Pilih minimal satu barang sumber yang valid.');
        }

        // 3. Batasi jumlah merge sekaligus (hindari overload)
        if (count($sourceIds) > 50) {
            return redirect()->back()->with('error', 'Maksimal 50 barang sumber dalam satu kali merge.');
        }

        // 4. Pastikan target ada di DB
        if (!$this->barangModel->find($targetId)) {
            return redirect()->back()->with('error', 'Barang target tidak ditemukan.');
        }

        // 5. Hapus target dari source (cegah self-merge)
        $sourceIds = array_values(array_filter($sourceIds, fn($id) => $id !== $targetId));

        if (empty($sourceIds)) {
            return redirect()->back()->with('error', 'Barang sumber tidak boleh sama dengan barang target.');
        }

        // 6. Validasi semua source ID benar-benar ada di DB
        $existingCount = $this->barangModel
            ->whereIn('id', $sourceIds)
            ->countAllResults();

        if ($existingCount !== count($sourceIds)) {
            return redirect()->back()->with('error', 'Satu atau lebih barang sumber tidak ditemukan.');
        }

        // 7. Proses merge dalam transaksi
        $db = \Config\Database::connect();
        $db->transStart();

        foreach ($sourceIds as $sId) {
            $this->batchModel
                ->where('id_barang', $sId)
                ->set(['id_barang' => $targetId])
                ->update();

            $this->barangModel->delete($sId);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menggabungkan barang.');
        }

        return redirect()->back()->with('success', 'Barang berhasil digabungkan.');
    }
}