<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\FEFO;
use App\Modules\Transactions\Models\BatchModel;
use App\Modules\Transactions\Models\BarangMasukModel;
use App\Modules\MasterData\Models\BarangModel;

class TestFefo extends BaseController
{
    public function run()
    {
        echo "Menjalankan FEFO Test...\n";

        $fefo = new FEFO();
        $batchModel = new BatchModel();

        // 1. Ambil salah satu barang yang punya stok
        $barangAktif = $batchModel->where('status', 'Aktif')->where('stok_saat_ini >', 0)->first();
        if (!$barangAktif) {
            echo "Tidak ada barang aktif untuk ditest.\n";
            return;
        }

        $idBarang = (int) $barangAktif['id_barang'];
        
        $stokAwal = $fefo->getStokBarang($idBarang);
        echo "Stok awal barang {$idBarang} adalah: {$stokAwal}\n";

        // Coba bulk cek ketersediaan
        $kebutuhan = [
            $idBarang => 1
        ];

        $kurang = $fefo->cekKetersediaanBulk($kebutuhan);
        if (!empty($kurang)) {
            echo "Gagal: Stok tidak cukup untuk test.\n";
            return;
        }

        echo "Cek Ketersediaan Bulk OK.\n";

        // Coba proses FEFO Bulk
        $db = \Config\Database::connect();
        $db->transStart();

        $items = [
            ['id_barang' => $idBarang, 'jumlah_keluar' => 1]
        ];

        // Buat dummy barang keluar ID
        $dummyIdBarangKeluar = 999999;
        
        $fefoResult = $fefo->prosesBarangKeluarBulk($dummyIdBarangKeluar, $items);
        if ($fefoResult) {
            echo "Proses FEFO Bulk OK.\n";
        } else {
            echo "Gagal: Proses FEFO Bulk mengembalikan false.\n";
        }

        $stokAkhir = $fefo->getStokBarang($idBarang);
        echo "Stok setelah dipotong 1 adalah: {$stokAkhir} (Seharusnya " . ($stokAwal - 1) . ")\n";

        // Rollback supaya tidak mengotori data
        $db->transRollback();
        echo "Rollback OK.\n";

        $stokSetelahRollback = $fefo->getStokBarang($idBarang);
        echo "Stok setelah rollback: {$stokSetelahRollback} (Seharusnya {$stokAwal})\n";
    }
}

