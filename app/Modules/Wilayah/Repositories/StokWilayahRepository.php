<?php

namespace App\Modules\Wilayah\Repositories;

use CodeIgniter\Database\ConnectionInterface;

class StokWilayahRepository
{
    protected $db;

    public function __construct(ConnectionInterface $db = null)
    {
        $this->db = $db ?? \Config\Database::connect();
    }

    /**
     * Mengunci row stok gudang wilayah untuk mencegah race condition.
     * Harus dipanggil di dalam transaction.
     */
    public function lockStok($idGudang, $idBarang)
    {
        $sql = "SELECT * FROM stok_gudang_wilayah WHERE id_gudang = ? AND id_barang = ? FOR UPDATE";
        return $this->db->query($sql, [$idGudang, $idBarang])->getRowArray();
    }

    /**
     * Mengambil daftar barang yang memiliki stok > 0 untuk gudang tertentu.
     */
    public function getBarangAvailable($idGudang)
    {
        $sql = "SELECT b.id, b.kode_barang, b.nama_barang, b.satuan, s.jumlah, s.total_berat, s.berat_per_satuan, s.satuan_berat 
                FROM stok_gudang_wilayah s 
                JOIN master_barang_wilayah b ON b.id = s.id_barang 
                WHERE s.id_gudang = ? AND s.jumlah > 0 
                ORDER BY b.nama_barang ASC";
        return $this->db->query($sql, [$idGudang])->getResultArray();
    }

    /**
     * Memperbarui atau menambahkan stok baru beserta akumulasi total_berat.
     */
    public function addStok($idGudang, $idBarang, $jumlah, $userId, $ip, $beratPerSatuan = 0, $satuanBerat = 'Kg')
    {
        $stok = $this->lockStok($idGudang, $idBarang);
        $jml  = (float) $jumlah;
        $bSat = (float) $beratPerSatuan;
        
        // Konversi ke Kg untuk akumulasi total_berat
        $masukKg = (strcasecmp($satuanBerat, 'Gram') === 0) ? ($jml * $bSat / 1000) : ($jml * $bSat);

        if ($stok) {
            $newJumlah     = (float) $stok['jumlah'] + $jml;
            $newTotalBerat = (float) ($stok['total_berat'] ?? 0) + $masukKg;

            $updateData = [
                'jumlah'           => $newJumlah,
                'total_berat'      => $newTotalBerat,
                'updated_at'       => date('Y-m-d H:i:s'),
                'updated_by'       => $userId,
                'updated_ip'       => $ip
            ];

            if ($bSat > 0) {
                $updateData['berat_per_satuan'] = $bSat;
                $updateData['satuan_berat']     = $satuanBerat ?: 'Kg';
            }

            $this->db->table('stok_gudang_wilayah')
                ->where('id', $stok['id'])
                ->update($updateData);
        } else {
            $this->db->table('stok_gudang_wilayah')->insert([
                'id_gudang'        => $idGudang,
                'id_barang'        => $idBarang,
                'jumlah'           => $jml,
                'total_berat'      => $masukKg,
                'berat_per_satuan' => $bSat > 0 ? $bSat : null,
                'satuan_berat'     => $satuanBerat ?: 'Kg',
                'updated_at'       => date('Y-m-d H:i:s'),
                'updated_by'       => $userId,
                'created_ip'       => $ip,
                'updated_ip'       => $ip
            ]);
        }
    }

    /**
     * Mengurangi stok dengan validasi sisa dan pengurangan total_berat secara proporsional.
     */
    public function subtractStok($idGudang, $idBarang, $jumlah, $userId, $ip)
    {
        $stok = $this->lockStok($idGudang, $idBarang);

        if (!$stok) {
            throw new \RuntimeException("Data stok tidak ditemukan.");
        }

        $stokSaat       = (float) $stok['jumlah'];
        $totalBeratSaat = (float) ($stok['total_berat'] ?? 0);
        $jumlahKeluar   = (float) $jumlah;

        if ($stokSaat < $jumlahKeluar) {
            throw new \RuntimeException("Stok gudang tidak mencukupi.");
        }

        $newJumlah = $stokSaat - $jumlahKeluar;

        // Pengurangan proporsional total_berat
        if ($stokSaat > 0) {
            $rasioKeluar    = $jumlahKeluar / $stokSaat;
            $beratKeluar    = $totalBeratSaat * $rasioKeluar;
            $newTotalBerat  = max(0, $totalBeratSaat - $beratKeluar);
        } else {
            $newTotalBerat  = 0;
        }

        $this->db->table('stok_gudang_wilayah')
            ->where('id', $stok['id'])
            ->update([
                'jumlah'      => $newJumlah,
                'total_berat' => $newTotalBerat,
                'updated_at'  => date('Y-m-d H:i:s'),
                'updated_by'  => $userId,
                'updated_ip'  => $ip
            ]);
    }
}
