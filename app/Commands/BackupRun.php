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
    protected $options     = [
        '--email' => 'Target Gmail address to send backup file'
    ];

    public function run(array $params)
    {
        $type = $params[0] ?? 'daily';
        $targetEmail = CLI::getOption('email');
        
        $max = 10;
        if ($type === 'weekly') $max = 4;
        if ($type === 'monthly') $max = 12;

        CLI::write("Starting Backup Engine (Native PHP Streaming)...", 'yellow');

        try {
            $mgr = new BackupManager();
            $filename = $mgr->generateBackup($type, "Auto CLI Backup - {$type}");
            
            CLI::write("Backup successfully created: {$filename}", 'green');
            
            // Send to Email if email option provided
            if ($targetEmail) {
                CLI::write("Sending backup file to Gmail ({$targetEmail})...", 'yellow');
                $filepath = WRITEPATH . 'backups/' . $filename;
                $sent = $mgr->sendBackupEmail($filepath, $targetEmail);
                if ($sent) {
                    CLI::write("Backup email sent successfully to {$targetEmail}!", 'green');
                } else {
                    CLI::write("Failed to send email. Check SMTP settings in .env / Config/Email.php", 'red');
                }
            }

            // Log 
            ActivityLogger::log('Tambah', 'Backup Database', "Melakukan Auto Backup Database\nTipe: {$type}\nNama File: {$filename}" . ($targetEmail ? "\nDikirim ke: {$targetEmail}" : ""));
            
            // Cleanup
            CLI::write("Running cleanup for {$type} (Max: {$max})...", 'cyan');
            $mgr->cleanupRotations($type, $max);
            
            CLI::write("Done.", 'green');
        } catch (\Exception $e) {
            CLI::error("Error: " . $e->getMessage());
        }
    }
}
