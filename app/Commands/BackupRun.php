<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Libraries\BackupManager;
use App\Libraries\ActivityLogger;

class BackupRun extends BaseCommand
{
    protected $group       = 'Backup';
    protected $name        = 'backup:run';
    protected $description = 'Run a database backup (streaming) with rotation';
    protected $usage       = 'backup:run [daily|weekly|monthly]';
    protected $arguments   = [
        'type' => 'Rotation type: daily (10), weekly (4), monthly (12)'
    ];

    public function run(array $params)
    {
        $type = $params[0] ?? 'daily';
        
        $max = 10;
        if ($type === 'weekly') $max = 4;
        if ($type === 'monthly') $max = 12;

        CLI::write("Starting Backup Engine (Native PHP Streaming)...", 'yellow');

        try {
            $mgr = new BackupManager();
            $filename = $mgr->generateBackup($type, "Auto CLI Backup - {$type}");
            
            CLI::write("Backup successfully created: {$filename}", 'green');
            
            // Log 
            ActivityLogger::log('Tambah', 'Backup Database', "Melakukan Auto Backup Database\nTipe: {$type}\nNama File: {$filename}");
            
            // Cleanup
            CLI::write("Running cleanup for {$type} (Max: {$max})...", 'cyan');
            $mgr->cleanupRotations($type, $max);
            
            CLI::write("Done.", 'green');
        } catch (\Exception $e) {
            CLI::error("Error: " . $e->getMessage());
        }
    }
}
