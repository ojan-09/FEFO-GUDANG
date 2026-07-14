<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterWilayahTable extends Migration
{
    public function up()
    {
        $fields = [
            'kode_wilayah' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'unique'     => true,
                'after'      => 'id',
            ],
            'kota_kabupaten' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'after'      => 'nama_wilayah',
            ],
            'provinsi' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'after'      => 'kota_kabupaten',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Aktif', 'Nonaktif'],
                'default'    => 'Aktif',
                'after'      => 'provinsi',
            ],
            'deleted_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
                'after'      => 'updated_at',
            ],
        ];

        // Ensure database table `wilayah` has its data wiped or handled before altering to avoid UNIQUE constraint violation if there are existing records without code.
        // Or we can just add the columns if table is empty.
        $this->forge->addColumn('wilayah', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('wilayah', ['kode_wilayah', 'kota_kabupaten', 'provinsi', 'status', 'deleted_at']);
    }
}

