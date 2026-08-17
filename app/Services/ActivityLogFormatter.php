<?php

namespace App\Services;

class ActivityLogFormatter
{
    /**
     * Format raw activity log into human-readable representation
     *
     * @param array $log Database row from activity_logs
     * @param array $gudangMap Map of [id_gudang => nama_gudang]
     * @return array Formatted log object for UI and Drawer
     */
    public static function format(array $log, array $gudangMap = []): array
    {
        $modul     = $log['modul'] ?? 'Umum';
        $aktivitas = $log['aktivitas'] ?? 'Aktivitas';
        $rawDesc   = trim($log['deskripsi'] ?? '');
        
        $summary = '';
        $details = [];
        $badgeClass = 'bg-login';
        $colorClass = 'mod-login';
        $icon = 'fa-circle-dot';
        
        $parsedJson = null;
        $isJson = false;

        // Extract JSON if description contains JSON or JSON prefix
        if (!empty($rawDesc)) {
            // Check if deskripsi contains JSON substring
            $jsonStart = strpos($rawDesc, '{');
            if ($jsonStart !== false) {
                $possibleJson = substr($rawDesc, $jsonStart);
                $decoded = json_decode($possibleJson, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $parsedJson = $decoded;
                    $isJson = true;
                }
            }
        }

        $modulLower = strtolower($modul);
        $aktivitasLower = strtolower($aktivitas);

        // 1. EXPORT ACTIVITIES (PDF / Excel)
        if (strpos($aktivitasLower, 'export') !== false || strpos($rawDesc, 'Export') !== false) {
            $isPdf   = strpos($aktivitasLower, 'pdf') !== false || strpos($rawDesc, 'pdf') !== false || strpos($rawDesc, 'PDF') !== false;
            $isExcel = strpos($aktivitasLower, 'excel') !== false || strpos($rawDesc, 'excel') !== false || strpos($rawDesc, 'Excel') !== false;
            
            $docType = $isPdf ? 'PDF' : ($isExcel ? 'Excel' : 'Dokumen');
            
            if ($isPdf) {
                $badgeClass = 'bg-export-pdf';
                $colorClass = 'mod-export-pdf';
                $icon = 'fa-file-pdf';
            } else {
                $badgeClass = 'bg-export-excel';
                $colorClass = 'mod-export-excel';
                $icon = 'fa-file-excel';
            }

            // Extract jenis report from JSON or raw text
            $jenisReport = 'Laporan';
            if (isset($parsedJson['jenis'])) {
                if ($parsedJson['jenis'] === 'masuk') $jenisReport = 'Barang Masuk';
                elseif ($parsedJson['jenis'] === 'keluar') $jenisReport = 'Barang Keluar';
                elseif ($parsedJson['jenis'] === 'stok') $jenisReport = 'Stok Barang';
            } elseif (strpos($rawDesc, 'masuk') !== false) {
                $jenisReport = 'Barang Masuk';
            } elseif (strpos($rawDesc, 'keluar') !== false) {
                $jenisReport = 'Barang Keluar';
            }

            // Resolve Gudang Name
            $namaGudang = 'Semua Gudang';
            if (isset($parsedJson['id_gudang']) && !empty($parsedJson['id_gudang'])) {
                $gId = (int)$parsedJson['id_gudang'];
                $namaGudang = $gudangMap[$gId] ?? ("Gudang #" . $gId);
            }

            // Resolve Periode
            $periode = 'Semua Periode';
            $startDate = $parsedJson['start_date'] ?? null;
            $endDate   = $parsedJson['end_date'] ?? null;

            if (!empty($startDate) && !empty($endDate)) {
                $periode = date('d M Y', strtotime($startDate)) . ' - ' . date('d M Y', strtotime($endDate));
            } elseif (!empty($startDate)) {
                $periode = 'Mulai ' . date('d M Y', strtotime($startDate));
            } elseif (!empty($endDate)) {
                $periode = 'Sampai ' . date('d M Y', strtotime($endDate));
            }

            $summary = "Mengunduh Laporan {$jenisReport} {$modul} ({$docType}).";
            $details = [
                'Jenis Laporan' => "{$jenisReport} ({$docType})",
                'Gudang'        => $namaGudang,
                'Periode'       => $periode,
            ];

        // 2. AUTHENTICATION (Login / Logout)
        } elseif (strpos($modulLower, 'auth') !== false || strpos($aktivitasLower, 'login') !== false || strpos($aktivitasLower, 'logout') !== false) {
            $isLogin = strpos($aktivitasLower, 'login') !== false || strpos($rawDesc, 'Login') !== false;
            
            if ($isLogin) {
                $summary = 'Berhasil masuk ke sistem.';
                $badgeClass = 'bg-login';
                $colorClass = 'mod-login';
                $icon = 'fa-right-to-bracket';
            } else {
                $summary = 'Keluar dari sistem.';
                $badgeClass = 'bg-logout';
                $colorClass = 'mod-logout';
                $icon = 'fa-right-from-bracket';
            }
            $details = [
                'Aktivitas' => $summary,
                'Waktu'     => date('d M Y - H:i:s', strtotime($log['created_at'] ?? 'now'))
            ];

        // 3. BACKUP & RESTORE
        } elseif (strpos($aktivitasLower, 'backup') !== false || strpos($modulLower, 'backup') !== false) {
            $badgeClass = 'bg-backup';
            $colorClass = 'mod-backup';
            $icon = 'fa-database';

            $filename = '-';
            if (preg_match('/Nama File:\s*([^\s\n]+)/i', $rawDesc, $matches)) {
                $filename = trim($matches[1]);
            } elseif ($isJson && isset($parsedJson['filename'])) {
                $filename = $parsedJson['filename'];
            }

            $summary = 'Melakukan Backup Database.';
            $details = [
                'Aktivitas' => 'Backup Database',
                'Nama File' => $filename
            ];

        } elseif (strpos($aktivitasLower, 'restore') !== false || strpos($modulLower, 'restore') !== false) {
            $badgeClass = 'bg-restore';
            $colorClass = 'mod-restore';
            $icon = 'fa-rotate-left';

            $filename = '-';
            if (preg_match('/File Sumber:\s*([^\s\n]+)/i', $rawDesc, $matches)) {
                $filename = trim($matches[1]);
            } elseif ($isJson && isset($parsedJson['filename'])) {
                $filename = $parsedJson['filename'];
            }

            $summary = 'Melakukan Restore Database.';
            $details = [
                'Aktivitas'    => 'Restore Database',
                'Sumber Backup' => $filename
            ];

        // 4. TRANSAKSI (BARANG MASUK / KELUAR)
        } elseif (strpos($modulLower, 'masuk') !== false || strpos($modulLower, 'donasi') !== false) {
            $badgeClass = 'bg-donasi';
            $colorClass = 'mod-donasi';
            $icon = 'fa-hand-holding-heart';

            $summary = $rawDesc;
            if (empty($summary)) {
                $summary = 'Menambahkan transaksi Barang Masuk.';
            }

            $details = [
                'Modul'     => $modul,
                'Aktivitas' => $aktivitas,
                'Deskripsi' => $summary
            ];

        } elseif (strpos($modulLower, 'keluar') !== false || strpos($modulLower, 'penyaluran') !== false) {
            $badgeClass = 'bg-penyaluran-danger';
            $colorClass = 'mod-penyaluran-danger';
            $icon = 'fa-truck-fast';

            $summary = $rawDesc;
            if (empty($summary)) {
                $summary = 'Menambahkan transaksi Barang Keluar.';
            }

            $details = [
                'Modul'     => $modul,
                'Aktivitas' => $aktivitas,
                'Deskripsi' => $summary
            ];

        // 5. MONITORING & STOK
        } elseif (strpos($modulLower, 'monitoring') !== false || strpos($modulLower, 'stok') !== false) {
            $badgeClass = 'bg-penyesuaian';
            $colorClass = 'mod-penyesuaian';
            $icon = 'fa-chart-line';

            $summary = $rawDesc ?: "Aktivitas pada modul {$modul}.";
            $details = [
                'Modul'     => $modul,
                'Aktivitas' => $aktivitas,
                'Deskripsi' => $summary
            ];

        // 6. DEFAULT / GENERAL FALLBACK
        } else {
            $badgeClass = 'bg-login';
            $colorClass = 'mod-login';
            $icon = 'fa-circle-info';

            $summary = $rawDesc ?: "{$aktivitas} pada {$modul}.";
            $details = [
                'Modul'     => $modul,
                'Aktivitas' => $aktivitas,
                'Deskripsi' => $summary
            ];
        }

        return [
            'summary'     => $summary,
            'details'     => $details,
            'badge_class' => $badgeClass,
            'color_class' => $colorClass,
            'icon'        => $icon,
            'raw_json'    => $isJson ? json_encode($parsedJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : $rawDesc
        ];
    }
}
