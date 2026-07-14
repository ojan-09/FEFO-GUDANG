<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropIpUserAgentFromActivityLogs extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('activity_logs', ['ip_address', 'user_agent']);
    }

    public function down()
    {
        $this->forge->addColumn('activity_logs', [
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => '45',
                'null'       => true,
            ],
            'user_agent' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
        ]);
    }
}

