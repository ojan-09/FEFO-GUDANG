<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNilaiToBatch extends Migration
{
    public function up()
    {
        $this->forge->addColumn('batch', [
            'nilai_satuan' => [
                'type' => 'DECIMAL',
                'constraint' => '18,2',
                'null' => false,
                'default' => 0
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('batch', 'nilai_satuan');
    }
}
