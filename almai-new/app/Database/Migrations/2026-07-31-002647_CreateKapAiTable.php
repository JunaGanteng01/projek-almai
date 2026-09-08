<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKapAiTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tanggal' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'nama_item' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'akun_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'posisi' => [
                'type'       => 'ENUM',
                'constraint' => ['debit', 'kredit'],
                'default'    => 'debit',
            ],
            'nominal' => [
                'type'       => 'DECIMAL',
                'constraint' => '20,2',
                'default'    => 0.00,
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
        $this->forge->createTable('kap_ai');
    }

    public function down()
    {
        $this->forge->dropTable('kap_ai');
    }
}
