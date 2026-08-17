<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDocumentWorkspaceTables extends Migration
{
    public function up()
    {
        // 1. Table document_configs
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'document_key' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'unique'     => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'number_prefix' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'number_format' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'default'    => '{nomor}/{semester}/{bulan}/{tahun}',
            ],
            'current_number' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 400,
            ],
            'reset_rule' => [
                'type'       => 'ENUM',
                'constraint' => ['none', 'monthly', 'semester', 'yearly'],
                'default'    => 'none',
            ],
            'last_reset_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'active_semester' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'default'    => 'SEM2',
            ],
            'active_month' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'default'    => '8',
            ],
            'active_year' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'default'    => '26',
            ],
            'current_version' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'meta_json' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('document_configs', true);

        // 2. Table document_provisions (Versioned Ketentuan)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'document_key' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'version' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'content' => [
                'type' => 'TEXT',
            ],
            'sort_order' => [
                'type'       => 'INT',
                'constraint' => 5,
                'default'    => 1,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['document_key', 'version']);
        $this->forge->createTable('document_provisions', true);

        // 3. Table document_history (Permanent Generated Documents)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'document_key' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'document_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'document_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'ref_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'config_version' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'provisions_snapshot' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_by' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'Terbit',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('document_key');
        $this->forge->addKey('document_number');
        $this->forge->createTable('document_history', true);

        // 4. Table document_activity_logs (Admin Configuration Change Audit Logs)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'document_key' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'action_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'field_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'before_value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'after_value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('document_activity_logs', true);
    }

    public function down()
    {
        $this->forge->dropTable('document_activity_logs', true);
        $this->forge->dropTable('document_history', true);
        $this->forge->dropTable('document_provisions', true);
        $this->forge->dropTable('document_configs', true);
    }
}
