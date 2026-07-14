<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddJumlahCtnToBatch extends Migration
{
    public function up()
    {
        $this->forge->addColumn('batch', [
            'jumlah_ctn' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'stok_saat_ini',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('batch', 'jumlah_ctn');
    }
}

