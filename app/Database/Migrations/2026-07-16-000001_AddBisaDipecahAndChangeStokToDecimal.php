<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBisaDipecahAndChangeStokToDecimal extends Migration
{
    public function up()
    {
        // 1. Tambah kolom bisa_dipecah ke tabel barang
        $this->forge->addColumn('barang', [
            'bisa_dipecah' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => false,
                'after'      => 'minimum_stok'
            ]
        ]);

        // 2. Tambah kolom bisa_dipecah ke tabel batch
        $this->forge->addColumn('batch', [
            'bisa_dipecah' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => false,
                'after'      => 'status'
            ]
        ]);

        // 3. Ubah tipe data kolom stok di tabel batch menjadi DECIMAL(10,2)
        $this->forge->modifyColumn('batch', [
            'jumlah_awal' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => false,
            ],
            'stok_saat_ini' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => false,
            ]
        ]);

        // 4. Ubah tipe data kolom jumlah_keluar di detail_barang_keluar menjadi DECIMAL(10,2)
        $this->forge->modifyColumn('detail_barang_keluar', [
            'jumlah_keluar' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => false,
            ]
        ]);
    }

    public function down()
    {
        // 1. Kembalikan tipe data detail_barang_keluar.jumlah_keluar menjadi INT
        $this->forge->modifyColumn('detail_barang_keluar', [
            'jumlah_keluar' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ]
        ]);

        // 2. Kembalikan tipe data batch.jumlah_awal dan stok_saat_ini menjadi INT
        $this->forge->modifyColumn('batch', [
            'jumlah_awal' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            'stok_saat_ini' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ]
        ]);

        // 3. Hapus kolom bisa_dipecah dari tabel batch
        $this->forge->dropColumn('batch', 'bisa_dipecah');

        // 4. Hapus kolom bisa_dipecah dari tabel barang
        $this->forge->dropColumn('barang', 'bisa_dipecah');
    }
}
