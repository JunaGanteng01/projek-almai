<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLayananUlasanTable extends Migration
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
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'layanan_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'layanan_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'rating' => [
                'type'       => 'DECIMAL',
                'constraint' => '2,1',
            ],
            'ulasan' => [
                'type' => 'TEXT',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected'],
                'default'    => 'approved',
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
        $this->forge->createTable('layanan_ulasan');
    }

    public function down()
    {
        $this->forge->dropTable('layanan_ulasan');
    }
}
