<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Libraries\BackupManager;

class BackupVerify extends BaseCommand
{
    protected $group       = 'Backup';
    protected $name        = 'backup:verify';
    protected $description = 'Verify the integrity of a backup file';
    protected $usage       = 'backup:verify [filename.sql]';

    public function run(array $params)
    {
        if (empty($params[0])) {
            CLI::error('Silakan sebutkan nama file. Contoh: php spark backup:verify daily_20260720.sql');
            return;
        }

        $filename = $params[0];
        CLI::write("Verifying {$filename}...", 'yellow');

        $mgr = new BackupManager();
        $res = $mgr->verifyFile($filename);

        if ($res['status']) {
            CLI::write("Status: VALID", 'green');
            CLI::write("Application: " . $res['meta']['application']);
            CLI::write("Version: " . $res['meta']['version']);
            CLI::write("Database: " . $res['meta']['database']);
            CLI::write("Date: " . $res['meta']['date']);
            CLI::write("Has SHA256: " . ($res['checksum_present'] ? 'Yes' : 'No'));
        } else {
            CLI::error("Status: INVALID");
            CLI::error("Reason: " . $res['message']);
        }
    }
}
