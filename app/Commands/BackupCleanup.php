<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Libraries\BackupManager;

class BackupCleanup extends BaseCommand
{
    protected $group       = 'Backup';
    protected $name        = 'backup:cleanup';
    protected $description = 'Force cleanup of old backup rotations';
    protected $usage       = 'backup:cleanup [daily|weekly|monthly] [max_files]';

    public function run(array $params)
    {
        $type = $params[0] ?? 'daily';
        $max = isset($params[1]) ? (int)$params[1] : 10;

        CLI::write("Cleaning up {$type} backups, keeping max {$max} files...", 'yellow');

        $mgr = new BackupManager();
        $mgr->cleanupRotations($type, $max);

        CLI::write("Done.", 'green');
    }
}
