<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class MigrateStockUnits extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:migrate-stock-units';
    protected $description = 'Migrates old repackable stock units from packages count to weight in Kg.';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        CLI::write("Mengambil data batch untuk verifikasi...", 'yellow');
        
        // Ambil data batch yang bisa dipecah berdasarkan master barang
        $batches = $db->table('batch')
            ->select('batch.id, batch.nomor_batch, COALESCE(batch.nama_barang, barang.nama_barang) as nama_barang, batch.jumlah_awal, batch.stok_saat_ini, batch.berat_per_satuan, batch.satuan_berat')
            ->join('barang', 'barang.id = batch.id_barang')
            ->where('barang.bisa_dipecah', 1)
            ->get()
            ->getResultArray();

        if (empty($batches)) {
            CLI::write("Tidak ada batch repackable yang ditemukan untuk migrasi.", 'green');
            return;
        }

        $errors = [];
        $validBatches = [];

        foreach ($batches as $b) {
            $berat = $b['berat_per_satuan'];
            $satuanBerat = trim($b['satuan_berat'] ?? '');
            
            // Validasi: berat_per_satuan tidak boleh null/0, satuan_berat tidak boleh kosong
            if ($berat === null || (float)$berat <= 0 || $satuanBerat === '') {
                $errors[] = $b;
            } else {
                $validBatches[] = $b;
            }
        }

        // Jika ditemukan data tidak valid, batalkan proses dan tampilkan detailnya
        if (!empty($errors)) {
            CLI::write("MIGRASI DIBATALKAN: Ditemukan data batch repackable yang tidak valid!", 'red');
            CLI::write("Silakan perbaiki data berat per satuan atau satuan berat pada batch berikut:", 'yellow');
            
            // Render table error
            $headers = ['ID Batch', 'Nomor Batch', 'Nama Barang', 'Berat/Satuan', 'Satuan Berat'];
            $rows = [];
            foreach ($errors as $err) {
                $rows[] = [
                    $err['id'],
                    $err['nomor_batch'],
                    $err['nama_barang'],
                    $err['berat_per_satuan'] ?? 'NULL',
                    $err['satuan_berat'] === '' ? 'KOSONG' : $err['satuan_berat']
                ];
            }
            CLI::table($rows, $headers);
            return;
        }

        // Jalankan migrasi dalam database transaction
        $db->transStart();

        CLI::write("Melakukan konversi unit stok ke Kg...", 'yellow');
        
        foreach ($validBatches as $b) {
            $berat = (float)$b['berat_per_satuan'];
            $satuanBerat = strtolower($b['satuan_berat']);
            
            // Hitung berat dalam Kg
            $konverter = 1.0;
            if ($satuanBerat === 'gram') {
                $konverter = 0.001;
            }
            
            $newJumlahAwal = (float)$b['jumlah_awal'] * $berat * $konverter;
            $newStokSaatIni = (float)$b['stok_saat_ini'] * $berat * $konverter;

            $db->table('batch')
                ->where('id', $b['id'])
                ->update([
                    'jumlah_awal'   => $newJumlahAwal,
                    'stok_saat_ini' => $newStokSaatIni,
                    'satuan_berat'  => 'Kg',
                    'bisa_dipecah'  => 1
                ]);
            
            CLI::write("Batch #{$b['nomor_batch']} ({$b['nama_barang']}): {$b['stok_saat_ini']} Pcs -> {$newStokSaatIni} Kg", 'green');
        }

        // Juga pastikan semua batch non-repack diset bisa_dipecah = 0
        $db->table('batch')
            ->whereNotIn('id', array_column($validBatches, 'id'))
            ->update(['bisa_dipecah' => 0]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            CLI::write("Gagal menyimpan perubahan database (Rollback).", 'red');
        } else {
            CLI::write("Migrasi stok berhasil diselesaikan!", 'green');
        }
    }
}
