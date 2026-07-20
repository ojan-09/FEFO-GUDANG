<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Libraries\BackupManager;

class BackupList extends BaseCommand
{
    protected $group       = 'Backup';
    protected $name        = 'backup:list';
    protected $description = 'List all backup files';
    protected $usage       = 'backup:list [--restore-point]';
    protected $options     = [
        '--restore-point' => 'Include auto-generated restore points'
    ];

    public function run(array $params)
    {
        $showRestorePoints = array_key_exists('restore-point', $params);

        $mgr = new BackupManager();
        $files = $mgr->getBackupFiles();
        
        $table = [];
        $totalSize = 0;

        foreach ($files as $f) {
            if (!$showRestorePoints && $f['is_restore_point']) {
                continue;
            }
            
            $size = round($f['size'] / 1024 / 1024, 2) . ' MB';
            $totalSize += $f['size'];
            $date = date('Y-m-d H:i:s', $f['date']);
            
            $table[] = [
                $f['filename'],
                $size,
                $date,
                $f['metadata']['application'] ?: '-'
            ];
        }

        if (empty($table)) {
            CLI::write('Tidak ada file backup.', 'yellow');
            return;
        }

        CLI::table($table, ['Filename', 'Size', 'Date', 'Application']);
        $totalMb = round($totalSize / 1024 / 1024, 2);
        CLI::write("Total Files: " . count($table) . " | Total Size: {$totalMb} MB", 'cyan');
    }
}
