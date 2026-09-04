<?php

namespace App\Modules\Dashboard\Models;

use CodeIgniter\Database\BaseConnection;

class DashboardModel
{
    protected BaseConnection $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // ── [FIX #2] Kalkulasi total berat di SQL, bukan PHP loop ──────────────
    public function getTotalBeratGudangKg(): float
    {
        // Untuk bisa_dipecah=1: stok sudah dalam Kg
        // Untuk bisa_dipecah=0: stok * berat_per_satuan, lalu konversi gram→kg jika perlu
        $sql = "
            SELECT SUM(
                CASE
                    WHEN bisa_dipecah = 1
                        THEN stok_saat_ini
                    WHEN LOWER(TRIM(satuan_berat)) IN ('gram','g','gr','ml')
                        THEN (stok_saat_ini * berat_per_satuan) / 1000
                    ELSE
                        stok_saat_ini * berat_per_satuan
                END
            ) AS total_kg
            FROM batch
            WHERE stok_saat_ini > 0
              AND status = 'Aktif'
        ";
        $row = $this->db->query($sql)->getRowArray();
        return (float)($row['total_kg'] ?? 0);
    }

    // ── [FIX #2 + #12] Top N barang dikonsolidasi di SQL dengan GROUP BY ───
    public function getTopBarang(int $limit = 5): array
    {
        $sql = "
            SELECT
                b.id,
                b.nama_barang,
                MAX(CASE WHEN bt.bisa_dipecah = 1 THEN 'Kg' ELSE bt.satuan END) AS satuan,
                SUM(bt.stok_saat_ini) AS total_stok,
                SUM(
                    CASE
                        WHEN bt.bisa_dipecah = 1
                            THEN bt.stok_saat_ini
                        WHEN LOWER(TRIM(bt.satuan_berat)) IN ('gram','g','gr','ml')
                            THEN (bt.stok_saat_ini * bt.berat_per_satuan) / 1000
                        ELSE
                            bt.stok_saat_ini * bt.berat_per_satuan
                    END
                ) AS total_berat
            FROM batch bt
            INNER JOIN barang b ON b.id = bt.id_barang
            WHERE bt.stok_saat_ini > 0
            GROUP BY b.id, b.nama_barang
            ORDER BY total_stok DESC
            LIMIT ?
        ";
        return $this->db->query($sql, [$limit])->getResultArray();
    }

    // ── [FIX #3] Grafik 12 bulan: 1 query per tabel, bukan 24 query loop ───
    public function getGrafikData(): array
    {
        // Bangun map bulan terakhir 12 bulan (Y-m => label)
        $monthMap = [];
        for ($i = 11; $i >= 0; $i--) {
            $key   = date('Y-m', strtotime("-$i months"));
            $label = date('M Y', strtotime("-$i months"));
            $monthMap[$key] = $label;
        }

        $since = date('Y-m-01', strtotime('-11 months'));

        // Query donasi masuk
        $rowsDonasi = $this->db->query("
            SELECT DATE_FORMAT(tanggal_masuk, '%Y-%m') AS ym, COUNT(*) AS total
            FROM barang_masuk
            WHERE tanggal_masuk >= ?
            GROUP BY ym
        ", [$since])->getResultArray();

        // Query barang keluar
        $rowsKeluar = $this->db->query("
            SELECT DATE_FORMAT(tanggal_keluar, '%Y-%m') AS ym, COUNT(*) AS total
            FROM barang_keluar
            WHERE tanggal_keluar >= ?
            GROUP BY ym
        ", [$since])->getResultArray();

        // Index hasil query ke map
        $donasiMap  = array_column($rowsDonasi, 'total', 'ym');
        $keluarMap  = array_column($rowsKeluar, 'total', 'ym');

        $labels      = [];
        $donasiData  = [];
        $keluarData  = [];

        foreach ($monthMap as $ym => $label) {
            $labels[]     = $label;
            $donasiData[] = (int)($donasiMap[$ym]  ?? 0);
            $keluarData[] = (int)($keluarMap[$ym]  ?? 0);
        }

        return compact('labels', 'donasiData', 'keluarData');
    }

    // ── Stok per kategori (Kg) ───────────────────────────────────────────────
    public function getKategoriStok(): array
    {
        $sql = "
            SELECT
                COALESCE(NULLIF(bt.kategori, ''), k.nama_kategori, 'Tanpa Kategori') AS label,
                SUM(
                    CASE
                        WHEN bt.bisa_dipecah = 1
                            THEN bt.stok_saat_ini
                        WHEN LOWER(TRIM(bt.satuan_berat)) IN ('gram','g','gr','ml')
                            THEN (bt.stok_saat_ini * bt.berat_per_satuan) / 1000
                        ELSE
                            bt.stok_saat_ini * bt.berat_per_satuan
                    END
                ) AS total_kg
            FROM batch bt
            LEFT JOIN barang b  ON b.id  = bt.id_barang
            LEFT JOIN kategori k ON k.id = b.id_kategori
            WHERE bt.stok_saat_ini > 0
              AND bt.status = 'Aktif'
            GROUP BY label
            ORDER BY total_kg DESC
        ";
        $rows = $this->db->query($sql)->getResultArray();

        $labels = [];
        $data   = [];
        foreach ($rows as $row) {
            $labels[] = $row['label'];
            $data[]   = round((float)$row['total_kg'], 2);
        }
        return compact('labels', 'data');
    }

    // ── Hampir expired list ──────────────────────────────────────────────────
    public function getHampirExpiredList(int $limit = 5): array
    {
        return $this->db->query("
            SELECT
                b.nama_barang,
                bt.tanggal_kedaluwarsa,
                DATEDIFF(bt.tanggal_kedaluwarsa, CURDATE()) AS sisa_hari,
                IF(
                    DATEDIFF(bt.tanggal_kedaluwarsa, CURDATE()) < 0,
                    'Expired',
                    IF(DATEDIFF(bt.tanggal_kedaluwarsa, CURDATE()) <= 30, 'Hampir Expired', 'Aman')
                ) AS status_expired
            FROM batch bt
            INNER JOIN barang b ON b.id = bt.id_barang
            WHERE bt.stok_saat_ini > 0
              AND DATEDIFF(bt.tanggal_kedaluwarsa, CURDATE()) <= 30
            ORDER BY sisa_hari ASC
            LIMIT ?
        ", [$limit])->getResultArray();
    }
}