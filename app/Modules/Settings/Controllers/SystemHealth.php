<?php

namespace App\Modules\Settings\Controllers;

use App\Controllers\BaseController;
use Config\Services;
use Config\Database;

class SystemHealth extends BaseController
{
    public function index()
    {
        $db = Database::connect();
        $cache = Services::cache();

        // Server Information
        $serverInfo = [
            'app_version' => env('app.version', '1.0.0'),
            'ci_version'  => \CodeIgniter\CodeIgniter::CI_VERSION,
            'php_version' => phpversion(),
            'server'      => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'os'          => php_uname('s') . ' ' . php_uname('r'),
            'timezone'    => date_default_timezone_get(),
            'environment' => ENVIRONMENT,
            'build'       => '2026.07.20',
            'developer'   => 'Foodbank'
        ];

        // DB Version
        try {
            $dbVersion = $db->getVersion();
        } catch (\Exception $e) {
            $dbVersion = 'Unknown';
        }

        // DB Health (Cached 10 mins)
        if (!$dbHealth = $cache->get('db_health_info')) {
            try {
                $dbName = $db->getDatabase();
                $query = $db->query("SELECT COUNT(*) AS table_count, 
                                     SUM(table_rows) AS row_count,
                                     SUM(data_length + index_length) / 1024 / 1024 AS size_mb 
                                     FROM information_schema.TABLES 
                                     WHERE table_schema = '{$dbName}'");
                $row = $query->getRow();
                $dbHealth = [
                    'status' => 'Connected',
                    'name' => $dbName,
                    'tables' => $row->table_count ?? 0,
                    'records' => $row->row_count ?? 0,
                    'size_mb' => round($row->size_mb ?? 0, 2),
                    'version' => $dbVersion
                ];
            } catch (\Exception $e) {
                $dbHealth = [
                    'status' => 'Failed: ' . $e->getMessage(),
                    'name' => '-',
                    'tables' => 0,
                    'records' => 0,
                    'size_mb' => 0,
                    'version' => '-'
                ];
            }
            if ($dbHealth['status'] === 'Connected') {
                $cache->save('db_health_info', $dbHealth, 600); // 10 minutes
            }
        }

        // PHP Limits & Disk
        function formatBytes($bytes) {
            if ($bytes == 0) return "0.00 B";
            $s = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
            $e = floor(log($bytes, 1024));
            return round($bytes/pow(1024, $e), 2) . ' ' . $s[$e];
        }

        $freeSpace = disk_free_space(WRITEPATH);
        $totalSpace = disk_total_space(WRITEPATH);
        $usedSpace = $totalSpace - $freeSpace;

        $limitsInfo = [
            'memory_limit' => ini_get('memory_limit'),
            'upload_max' => ini_get('upload_max_filesize'),
            'post_max' => ini_get('post_max_size'),
            'execution_time' => ini_get('max_execution_time') . ' sec',
            'disk_used' => formatBytes($usedSpace),
            'disk_free' => formatBytes($freeSpace),
            'disk_total' => formatBytes($totalSpace)
        ];

        // Folder Writable Status
        $folders = ['writable', 'writable/cache', 'writable/session', 'writable/uploads', 'writable/backups', 'writable/logs'];
        $folderStatus = [];
        foreach ($folders as $folder) {
            $path = ROOTPATH . $folder;
            $folderStatus[$folder] = is_dir($path) && is_writable($path);
        }

        // Cache Info
        $cacheInfo = [
            'driver' => config('Cache')->handler,
            'items_cached' => '-', // CI4 doesn't have an easy get_cache_info cross-driver
            'ttl_default' => config('Cache')->ttl . ' sec'
        ];
        
        if (config('Cache')->handler === 'file') {
            // Count files in cache dir roughly
            $cacheFiles = glob(WRITEPATH . 'cache/*');
            $cacheInfo['items_cached'] = count($cacheFiles) > 0 ? count($cacheFiles) - 1 : 0; // minus index.html
        }

        // Backup Info
        $latestBackup = 'None';
        $latestBackupSize = '0 MB';
        $latestBackupHash = '-';
        $backupPath = WRITEPATH . 'backups/';
        $bFiles = glob($backupPath . '*.sql');
        if (!empty($bFiles)) {
            usort($bFiles, function($a, $b) {
                return filemtime($b) <=> filemtime($a);
            });
            $lBackup = basename($bFiles[0]);
            $latestBackup = $lBackup;
            $latestBackupSize = round(filesize($bFiles[0]) / 1024 / 1024, 2) . ' MB';
            
            // Checksha256
            $shaFile = str_replace('.sql', '.sha256', $bFiles[0]);
            if (file_exists($shaFile)) {
                $expected = trim(file_get_contents($shaFile));
                $actual = hash_file('sha256', $bFiles[0]);
                $latestBackupHash = ($expected === $actual) ? 'Valid' : 'Invalid';
            } else {
                $latestBackupHash = 'No Checksum';
            }
        }

        $backupInfo = [
            'latest' => $latestBackup,
            'size' => $latestBackupSize,
            'checksum' => $latestBackupHash
        ];

        // System Performance
        $timeEnd = microtime(true);
        $timeStart = $_SERVER["REQUEST_TIME_FLOAT"] ?? $timeEnd;
        $responseTime = round(($timeEnd - $timeStart) * 1000) . ' ms';
        $memoryUsage = round(memory_get_usage() / 1024 / 1024, 2) . ' MB';
        $peakMemory = round(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB';

        $performanceInfo = [
            'response_time' => $responseTime,
            'memory_usage' => $memoryUsage,
            'peak_memory' => $peakMemory
        ];

        $data = [
            'title' => 'System Health Check',
            'serverInfo' => $serverInfo,
            'dbHealth' => $dbHealth,
            'limitsInfo' => $limitsInfo,
            'folderStatus' => $folderStatus,
            'cacheInfo' => $cacheInfo,
            'backupInfo' => $backupInfo,
            'performanceInfo' => $performanceInfo
        ];

        return view('App\Modules\Settings\Views\health\index', $data);
    }
}
