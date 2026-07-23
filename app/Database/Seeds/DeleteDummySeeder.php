<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DeleteDummySeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        echo "Menghapus data dummy...\n";
        
        $db->query('SET FOREIGN_KEY_CHECKS = 0');
        
        $db->transStart();
        
        // Delete Barang Keluar
        $db->table('barang_keluar')->like('nomor_transaksi', 'BK-DUMMY-')->delete();
        $db->table('barang_keluar')->like('nomor_transaksi', 'TRX-OUT-')->delete();
        
        // Delete Penyesuaian
        $db->table('penyesuaian_stok')->like('nomor_penyesuaian', 'ADJ-DUMMY-')->delete();
        $db->table('penyesuaian_stok')->like('nomor_penyesuaian', 'ADJ-')->delete();
        
        // Delete Barang Masuk
        $db->table('barang_masuk')->like('nomor_transaksi', 'BM-DUMMY-')->delete();
        $db->table('barang_masuk')->like('nomor_transaksi', 'TRX-IN-')->delete();
        
        // Delete Barang (yang otomatis delete batch karena cascade)
        $db->table('barang')->like('nama_barang', 'Barang Dummy')->delete();
        
        // Bersihkan detail yg yatim piatu atau batch yatim piatu jika ada
        $db->query('DELETE FROM batch WHERE id_barang_masuk NOT IN (SELECT id FROM barang_masuk)');
        $db->query('DELETE FROM detail_barang_keluar WHERE id_barang_keluar NOT IN (SELECT id FROM barang_keluar)');
        $db->query('DELETE FROM detail_penyesuaian_stok WHERE id_penyesuaian NOT IN (SELECT id FROM penyesuaian_stok)');
        
        $db->transComplete();
        
        $db->query('SET FOREIGN_KEY_CHECKS = 1');
        
        if ($db->transStatus() === FALSE) {
            echo "Gagal menghapus data dummy.\n";
        } else {
            echo "Data dummy berhasil dihapus!\n";
        }
    }
}
