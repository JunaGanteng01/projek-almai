<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMerchandiseTable extends Migration
{
    public function up()
    {
        // Merchandise table
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 255],
            'description' => ['type' => 'TEXT', 'null' => true],
            'icon' => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => 'fa-gift'],
            'image' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'points_required' => ['type' => 'INT', 'constraint' => 11],
            'stock' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'unlimited_stock' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'status' => ['type' => 'ENUM', 'constraint' => ['active', 'inactive'], 'default' => 'active'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('merchandise');

    }

    public function down()
    {
        $this->forge->dropTable('merchandise');
    }
}
