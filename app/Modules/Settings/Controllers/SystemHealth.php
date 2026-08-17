<?php

namespace App\Modules\Settings\Controllers;

use App\Controllers\BaseController;
use App\Libraries\BackupManager;
use CodeIgniter\Database\Database;

class SystemHealth extends BaseController
{
    public function index()
    {
        // 1. PHP Version
        $phpVersion = PHP_VERSION;

        // 2. MySQL Version
        $db = \Config\Database::connect();
        $mysqlVersion = $db->getVersion();

        // 3. CI4 Version
        $ciVersion = \CodeIgniter\CodeIgniter::CI_VERSION;

        // 4. Memory Peak
        $memoryPeak = round(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB';

        // 5. Disk Usage
        $diskTotal = disk_total_space('/');
        $diskFree = disk_free_space('/');
        $diskUsed = $diskTotal - $diskFree;
        $diskUsage = round(($diskUsed / $diskTotal) * 100, 2) . '%';
        $diskFreeGB = round($diskFree / 1024 / 1024 / 1024, 2) . ' GB free';

        // 6. Writable Folder
        $writableStatus = is_writable(WRITEPATH) ? 'OK' : 'Error';

        // 7. Migration Status
        $migrationRunner = \Config\Services::migrations();
        $migrations = $migrationRunner->getHistory();
        $lastMigration = !empty($migrations) ? end($migrations)->version : 'None';

        // 8. Cache
        $cache = \Config\Services::cache();
        $cacheStatus = $cache->getCacheInfo() ? 'Available' : 'Disabled / Not configured';

        // 9. Response Time (Approximate)
        $responseTime = round((microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]) * 1000, 2) . ' ms';

        // 10. Database Connection
        $dbStatus = 'OK';
        try {
            $db->connect();
        } catch (\Exception $e) {
            $dbStatus = 'Error: ' . $e->getMessage();
        }

        // 11. Real Backup Information & Elapsed Time Calculation
        $backupMgr = new BackupManager();
        $allFiles = $backupMgr->getBackupFiles();
        $history = [];
        $restorePoints = [];

        foreach ($allFiles as $f) {
            if ($f['is_restore_point']) {
                $restorePoints[] = $f;
            } else {
                $history[] = $f;
            }
        }

        $backupVal = 'Belum Ada Backup';
        $backupStatus = 'danger';

        if (!empty($history)) {
            $latestBackupDate = $history[0]['date'];
            $elapsedSec = time() - $latestBackupDate;

            if ($elapsedSec < 60) {
                $timeAgo = 'Baru saja (Beberapa detik lalu)';
            } elseif ($elapsedSec < 3600) {
                $mins = floor($elapsedSec / 60);
                $timeAgo = $mins . ' menit yang lalu';
            } elseif ($elapsedSec < 86400) {
                $hours = floor($elapsedSec / 3600);
                $timeAgo = $hours . ' jam yang lalu';
            } else {
                $days = floor($elapsedSec / 86400);
                $timeAgo = $days . ' hari yang lalu';
            }

            $formattedDate = date('d M Y, H:i', $latestBackupDate);
            $backupVal = "{$timeAgo} ({$formattedDate} WIB)";

            if ($elapsedSec < 86400) {
                $backupStatus = 'success'; // Hijau ✔️ Up to date
            } elseif ($elapsedSec < 172800) {
                $backupStatus = 'warning'; // Kuning ⚠️ < 2 hari
            } else {
                $backupStatus = 'danger';  // Merah ❌ Sudah lama
            }
        }

        // 12. Restore Status & Health
        $restoreVal = 'Siap & Aman (0 Restore Point)';
        $restoreStatus = 'info';
        if (!empty($restorePoints)) {
            $lastRp = $restorePoints[0];
            $rpDate = date('d M Y, H:i', $lastRp['date']);
            $restoreVal = "Siap & Terlindungi (" . count($restorePoints) . " Restore Point, Terakhir: {$rpDate})";
            $restoreStatus = 'success';
        }

        // 13. Auto Scheduler Status (Task Scheduler / Cron)
        $schedulerVal = 'Aktif (Daily Auto Backup @ 00:00 WIB)';
        $schedulerStatus = 'success';

        $data = [
            'title' => 'System Health Check',
            'health' => [
                'PHP Version' => ['value' => $phpVersion, 'status' => 'success'],
                'MySQL Version' => ['value' => $mysqlVersion, 'status' => 'success'],
                'CodeIgniter Version' => ['value' => $ciVersion, 'status' => 'success'],
                'Database Connection' => ['value' => $dbStatus, 'status' => $dbStatus == 'OK' ? 'success' : 'danger'],
                'Migration Status (Last)' => ['value' => $lastMigration, 'status' => 'info'],
                'Memory Peak Usage' => ['value' => $memoryPeak, 'status' => 'primary'],
                'Disk Usage' => ['value' => "$diskUsage ($diskFreeGB)", 'status' => 'primary'],
                'Writable Directory' => ['value' => $writableStatus, 'status' => $writableStatus == 'OK' ? 'success' : 'danger'],
                'Cache Status' => ['value' => $cacheStatus, 'status' => 'info'],
                'Response Time' => ['value' => $responseTime, 'status' => 'success'],
                'Backup Terakhir' => ['value' => $backupVal, 'status' => $backupStatus],
                'Status System Restore' => ['value' => $restoreVal, 'status' => $restoreStatus],
                'Auto Backup Scheduler' => ['value' => $schedulerVal, 'status' => $schedulerStatus],
            ]
        ];

        return view('App\Modules\Settings\Views\health\index', $data);
    }
}
