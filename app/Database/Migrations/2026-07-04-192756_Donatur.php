<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Donatur extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama_donatur'  => ['type' => 'VARCHAR', 'constraint' => 150],
            'jenis_donatur' => ['type' => 'ENUM', 'constraint' => ['Individu', 'Perusahaan/Organisasi']],
            'kontak'        => ['type' => 'VARCHAR', 'constraint' => 50],
            'email'         => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'alamat'        => ['type' => 'TEXT', 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('donatur');
    }

    public function down()
    {
        $this->forge->dropTable('donatur');
    }
}

