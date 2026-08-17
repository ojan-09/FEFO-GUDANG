<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCtnPackagingToBatch extends Migration
{
    public function up()
    {
        $fields = [];

        if (!$this->db->fieldExists('menggunakan_kemasan', 'batch')) {
            $fields['menggunakan_kemasan'] = [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => false,
                'after'      => 'stok_saat_ini',
            ];
        }

        if (!$this->db->fieldExists('jumlah_ctn', 'batch')) {
            $fields['jumlah_ctn'] = [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'menggunakan_kemasan',
            ];
        } else {
            // Modify column to ensure it is INT UNSIGNED NULL
            $this->forge->modifyColumn('batch', [
                'jumlah_ctn' => [
                    'name'       => 'jumlah_ctn',
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                ]
            ]);
        }

        if (!$this->db->fieldExists('isi_per_ctn', 'batch')) {
            $fields['isi_per_ctn'] = [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'jumlah_ctn',
            ];
        } else {
            $this->forge->modifyColumn('batch', [
                'isi_per_ctn' => [
                    'name'       => 'isi_per_ctn',
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                ]
            ]);
        }

        if (!empty($fields)) {
            $this->forge->addColumn('batch', $fields);
        }
    }

    public function down()
    {
        $columnsToDrop = [];
        if ($this->db->fieldExists('menggunakan_kemasan', 'batch')) {
            $columnsToDrop[] = 'menggunakan_kemasan';
        }
        if ($this->db->fieldExists('isi_per_ctn', 'batch')) {
            $columnsToDrop[] = 'isi_per_ctn';
        }

        if (!empty($columnsToDrop)) {
            $this->forge->dropColumn('batch', $columnsToDrop);
        }
    }
}
