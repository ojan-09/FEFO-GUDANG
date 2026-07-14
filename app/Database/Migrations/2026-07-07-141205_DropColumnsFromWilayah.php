<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropColumnsFromWilayah extends Migration
{
    public function up()
    {
        // Check if columns exist before dropping (prevent errors if rolling back/re-running)
        $this->forge->dropColumn('wilayah', ['kode_wilayah', 'kota_kabupaten', 'provinsi']);
    }

    public function down()
    {
        $fields = [
            'kode_wilayah' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'unique'     => true,
            ],
            'kota_kabupaten' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'provinsi' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
        ];
        $this->forge->addColumn('wilayah', $fields);
    }
}

