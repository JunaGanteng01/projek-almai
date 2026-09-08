<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLayananCompletionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
            ],
            'layanan_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'layanan_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'layanan', // 'layanan' or 'event'
            ],
            'transaksi_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'null' => true,
            ],
            'completed_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'certificate_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'notes' => [
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
        $this->forge->addKey(['user_id', 'layanan_id', 'layanan_type']);
        $this->forge->createTable('layanan_completions');
    }

    public function down()
    {
        $this->forge->dropTable('layanan_completions');
    }
}
