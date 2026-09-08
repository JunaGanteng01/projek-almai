<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyTransaksiTable extends Migration
{
    public function up()
    {
        // Rename kelas_id to layanan_id
        $this->forge->modifyColumn('transaksi', [
            'kelas_id' => [
                'name' => 'layanan_id',
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
        ]);

        // Change product_type to VARCHAR to support new values
        $this->forge->modifyColumn('transaksi', [
            'product_type' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        // Revert layanan_id to kelas_id
        $this->forge->modifyColumn('transaksi', [
            'layanan_id' => [
                'name' => 'kelas_id',
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
        ]);

        // Revert product_type to ENUM
        $this->forge->modifyColumn('transaksi', [
            'product_type' => [
                'type' => 'ENUM',
                'constraint' => ['kelas', 'tools', 'other', 'poin'], // Added poin as it appeared in other files
                'default' => 'kelas',
            ],
        ]);
    }
}
